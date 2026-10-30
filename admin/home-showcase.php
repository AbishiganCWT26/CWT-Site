<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

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

// Handle POST actions (Add, Edit, Delete, Toggle)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? 'save';

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0) {
            if (deleteShowcaseRecordWithFiles($pdo, $id)) {
                setFlash('success', 'Showcase item deleted successfully.');
            } else {
                setFlash('error', 'Could not delete showcase item.');
            }
        }
        header('Location: ' . ADMIN_URL . '/home-showcase.php');
        exit;
    }

    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $section_name = trim($_POST['section_name'] ?? '');
    $project_slug = trim($_POST['project_slug'] ?? '');
    $is_active = isset($_POST['is_active']) ? 1 : 0;
    $existing_image = $_POST['existing_image'] ?? '';
    $image_path = $existing_image;
    $newImageUploaded = false;

    // Sort order: auto-assign when blank, otherwise use admin-supplied value.
    $sort_order_raw = $_POST['sort_order'] ?? '';
    if ($sort_order_raw === '' || !is_numeric($sort_order_raw)) {
        if ($id > 0) {
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
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK && !empty($_FILES['image']['name'])) {
        $uploaded = uploadFile($_FILES['image'], 'showcase');
        if ($uploaded !== false) {
            $image_path = $uploaded;
            $newImageUploaded = true;
        } else {
            setFlash('error', 'Failed to upload image. Please ensure it is a valid image under 5MB.');
            header('Location: ' . ADMIN_URL . '/home-showcase.php' . ($id > 0 ? '?edit=' . $id : ''));
            exit;
        }
    }

    if (empty($image_path) && $id === 0) {
        setFlash('warning', 'An image is required for new showcase items.');
        header('Location: ' . ADMIN_URL . '/home-showcase.php');
        exit;
    }

    try {
        if ($id > 0) {
            $stmt = $pdo->prepare("UPDATE home_showcase SET section_name = ?, project_slug = ?, sort_order = ?, is_active = ?, image_path = ? WHERE id = ?");
            $ok = $stmt->execute([$section_name, $project_slug, $sort_order, $is_active, $image_path, $id]);
            if ($ok) {
                if ($newImageUploaded && !empty($existing_image) && $existing_image !== $image_path) {
                    deleteUploadedFile($pdo, $existing_image, 'showcase');
                }
                setFlash('success', 'Showcase item updated successfully.');
            } else {
                if ($newImageUploaded && !empty($image_path)) {
                    deleteUploadedFile($pdo, $image_path, 'showcase');
                }
                setFlash('error', 'Database error updating showcase item.');
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO home_showcase (section_name, project_slug, sort_order, is_active, image_path) VALUES (?, ?, ?, ?, ?)");
            $ok = $stmt->execute([$section_name, $project_slug, $sort_order, $is_active, $image_path]);
            if ($ok) {
                setFlash('success', 'Showcase item added successfully.');
            } else {
                if ($newImageUploaded && !empty($image_path)) {
                    deleteUploadedFile($pdo, $image_path, 'showcase');
                }
                setFlash('error', 'Database error saving showcase item.');
            }
        }
    } catch (Throwable $e) {
        error_log('[home-showcase.php] ' . $e->getMessage());
        setFlash('error', 'An error occurred while saving the item.');
    }

    header('Location: ' . ADMIN_URL . '/home-showcase.php');
    exit;
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

<div class="admin-content-layout">
    <div class="admin-form-section">
        <h2><?= $editItem ? 'Edit Item' : 'Add New Item' ?></h2>
        <form method="POST" action="home-showcase.php" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save">
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
                                    <div style="display:flex;gap:6px;align-items:center;">
                                        <a href="?edit=<?= $item['id'] ?>" class="btn-sm btn-secondary">Edit</a>
                                        <form method="POST" action="home-showcase.php" style="margin:0;display:inline-block;">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                            <button type="submit" class="btn-sm btn-danger" data-confirm="Delete showcase item? This will permanently delete its image.">Delete</button>
                                        </form>
                                    </div>
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
