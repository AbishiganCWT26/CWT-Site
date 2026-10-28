<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();


$message = '';
$error = '';

/**
 * Compute the next available sort_order within a given section.
 * Scoped per section so orders stay tidy inside "Our Customers" / "Our Products".
 */
function getNextShowcaseSortOrder(PDO $pdo, string $sectionName, int $excludeId = 0): int
{
    if ($excludeId > 0) {
        $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM home_showcase WHERE section_name = ? AND id != ?");
        $stmt->execute([$sectionName, $excludeId]);
    } else {
        $stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM home_showcase WHERE section_name = ?");
        $stmt->execute([$sectionName]);
    }
    return (int)$stmt->fetchColumn();
}

/**
 * Hard-delete an image file from the uploads folder.
 * Uses realpath() to block path-traversal attempts.
 */
function deleteShowcaseImage(string $imagePath): void
{
    if ($imagePath === '') return;

    $uploadsBase = realpath(__DIR__ . '/../uploads/');
    if ($uploadsBase === false) return;
    $uploadsBase = rtrim($uploadsBase, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

    $fullPath = realpath($uploadsBase . $imagePath);
    if ($fullPath === false) {
        // File already missing — nothing to delete.
        return;
    }
    if (!str_starts_with($fullPath, $uploadsBase)) {
        // Path escapes uploads directory — refuse.
        return;
    }
    if (is_file($fullPath)) {
        @unlink($fullPath);
    }
}

// Handle Delete — hard delete DB row + image file
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("SELECT image_path FROM home_showcase WHERE id = ?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    if ($item && !empty($item['image_path'])) {
        deleteShowcaseImage($item['image_path']);
    }

    $stmt = $pdo->prepare("DELETE FROM home_showcase WHERE id = ?");
    $stmt->execute([$id]);
    $message = 'Item deleted successfully.';
}

// Handle Add/Edit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $section_name = trim($_POST['section_name']);
    $project_slug = trim($_POST['project_slug'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $image_path = $_POST['existing_image'] ?? '';

    // Sort order: auto-assign when blank, otherwise use admin-supplied value.
    $sort_order_raw = $_POST['sort_order'] ?? '';
    if ($sort_order_raw === '' || !is_numeric($sort_order_raw)) {
        if ($id > 0) {
            // Editing — keep existing value rather than reshuffling.
            $stmt = $pdo->prepare("SELECT sort_order FROM home_showcase WHERE id = ?");
            $stmt->execute([$id]);
            $sort_order = (int)$stmt->fetchColumn();
        } else {
            $sort_order = getNextShowcaseSortOrder($pdo, $section_name);
        }
    } else {
        $sort_order = (int)$sort_order_raw;
    }

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/../uploads/showcase/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $fileInfo = pathinfo($_FILES['image']['name']);
        $ext = strtolower($fileInfo['extension']);
        $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

        if (in_array($ext, $allowed)) {
            $newName = 'showcase_' . uniqid() . '.' . $ext;
            $dest = $uploadDir . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                // Hard-delete the previous image only after successful replacement.
                if (!empty($image_path)) {
                    deleteShowcaseImage($image_path);
                }
                $image_path = 'showcase/' . $newName;
            } else {
                $error = 'Failed to upload image.';
            }
        } else {
            $error = 'Invalid image format.';
        }
    }

    if (empty($image_path) && $id === 0) {
        $error = 'Image is required.';
    }

    if (empty($error)) {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE home_showcase SET section_name = ?, project_slug = ?, sort_order = ?, is_active = ?, image_path = ? WHERE id = ?");
            $stmt->execute([$section_name, $project_slug, $sort_order, $is_active, $image_path, $id]);
            $message = 'Item updated successfully.';
        } else {
            $stmt = $pdo->prepare("INSERT INTO home_showcase (section_name, project_slug, sort_order, is_active, image_path) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$section_name, $project_slug, $sort_order, $is_active, $image_path]);
            $message = 'Item added successfully.';
        }
    }
}

// Fetch edit item
$editItem = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM home_showcase WHERE id = ?");
    $stmt->execute([(int)$_GET['edit']]);
    $editItem = $stmt->fetch();
}

// Fetch all items
$items = $pdo->query("SELECT * FROM home_showcase ORDER BY section_name ASC, sort_order ASC, id DESC")->fetchAll();
// Fetch active projects for dropdown
$projectsList = getActiveProjects($pdo);

// Section Name dropdown options
$sectionOptions = ['Our Customers', 'Our Products'];
$currentSection  = $editItem['section_name'] ?? 'Our Customers';
if (!in_array($currentSection, $sectionOptions, true)) {
    $sectionOptions[] = $currentSection;
}

// Sort order default: existing value when editing, auto-computed for new items.
$defaultSortOrder = $editItem
    ? (int)$editItem['sort_order']
    : getNextShowcaseSortOrder($pdo, $currentSection);

$pageTitle = 'Home Showcase Management';
require_once __DIR__ . '/layout_top.php';
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-home-showcase.css">
<script src="<?= SITE_URL ?>/assets/js/admin-home-showcase.js" defer></script>

<div class="admin-page-header">
    <h1>Home Showcase Management</h1>
    <p>Manage the customer and product cards displayed on the homepage.</p>
</div>

<?php if ($message): ?>
    <div class="alert alert-success"><?= e($message) ?></div>
<?php endif; ?>
<?php if ($error): ?>
    <div class="alert alert-danger"><?= e($error) ?></div>
<?php endif; ?>

<div class="admin-content-layout">
    <div class="admin-form-section">
        <h2><?= $editItem ? 'Edit Item' : 'Add New Item' ?></h2>
        <form method="POST" enctype="multipart/form-data">
            <?php if ($editItem): ?>
                <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
                <input type="hidden" name="existing_image" value="<?= e($editItem['image_path']) ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Section Name</label>
                <select name="section_name" required style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 14px;">
                    <?php foreach ($sectionOptions as $opt): ?>
                        <option value="<?= e($opt) ?>" <?= $currentSection === $opt ? 'selected' : '' ?>>
                            <?= e($opt) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>e.g., "Our Customers" or "Our Products"</small>
            </div>

            <div class="form-group">
                <label>Image <?= $editItem ? '(Leave blank to keep current)' : '' ?></label>
                <input type="file" name="image" accept="image/*" <?= $editItem ? '' : 'required' ?>>
                <?php if ($editItem && $editItem['image_path']): ?>
                    <div class="current-image">
                        <img src="<?= e(imgUrl($editItem['image_path'])) ?>" alt="Current Image">
                    </div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label>Linked Project (Slug)</label>
                <select name="project_slug" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e0; border-radius: 6px; font-size: 14px;">
                    <option value="">-- No Link (Display only) --</option>
                    <?php foreach ($projectsList as $proj): ?>
                        <option value="<?= e($proj['slug']) ?>" <?= ($editItem['project_slug'] ?? '') === $proj['slug'] ? 'selected' : '' ?>>
                            <?= e($proj['slug']) ?> — <?= e($proj['short_description'] ?? '') ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small>Select a project to link the image to its detail page.</small>
            </div>

            <div class="form-group">
                <label>Sort Order</label>
                <input type="number" name="sort_order" value="<?= e($defaultSortOrder) ?>" min="0">
                <small>Auto-assigned within the selected section. Edit to override.</small>
            </div>

            <div class="form-group">
                <label>
                    <input type="checkbox" name="is_active" value="1" <?= ($editItem && $editItem['is_active']) || !$editItem ? 'checked' : '' ?>>
                    Active
                </label>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-primary"><?= $editItem ? 'Update' : 'Add' ?> Item</button>
                <?php if ($editItem): ?>
                    <a href="home-showcase.php" class="btn-secondary">Cancel</a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div class="admin-list-section">
        <h2>Existing Items</h2>
        <div class="table-responsive">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Section</th>
                        <th>Linked Project (Slug)</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($items)): ?>
                        <tr><td colspan="6" class="text-center">No items found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($items as $item): ?>
                            <tr>
                                <td>
                                    <img src="<?= e(imgUrl($item['image_path'])) ?>" alt="Thumb" class="admin-thumb">
                                </td>
                                <td><?= e($item['section_name']) ?></td>
                                <td>
                                    <?php if ($item['project_slug']): ?>
                                        <a href="<?= SITE_URL ?>/project-detail?slug=<?= e($item['project_slug']) ?>" target="_blank"><?= e($item['project_slug']) ?></a>
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?= e($item['sort_order']) ?></td>
                                <td>
                                    <span class="badge <?= $item['is_active'] ? 'badge-success' : 'badge-secondary' ?>">
                                        <?= $item['is_active'] ? 'Active' : 'Inactive' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-secondary">Edit</a>
                                    <a href="?delete=<?= $item['id'] ?>" class="btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/layout_bottom.php'; ?>