<?php
/**
 * Admin — Manage Tech Stack
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

$categories = [
    'software_engineering' => 'Software Engineering',
    'ai_engineering'       => 'AI Engineering',
    'data_engineering'     => 'Data Engineering',
    'devops_cloud'         => 'DevOps & Cloud',
    'project_delivery'     => 'Project Delivery & PMO',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add' || $action === 'edit') {
        $id       = (int)($_POST['id'] ?? 0);
        $name     = trim($_POST['name'] ?? '');
        $category = $_POST['category'] ?? '';
        
        $logoPath = '';
        if ($action === 'edit') {
            $existing = $pdo->prepare("SELECT logo_path FROM tech_stack WHERE id=?");
            $existing->execute([$id]);
            $logoPath = $existing->fetchColumn() ?: '';
        }

        if (!empty($_FILES['logo']['name'])) {
            $newLogo = uploadFile($_FILES['logo'], 'tech');
            if ($newLogo) $logoPath = $newLogo;
        }

        if ($name && array_key_exists($category, $categories)) {
            if ($action === 'add') {
                $pdo->prepare("INSERT INTO tech_stack (name, category, logo_path) VALUES (?,?,?)")
                    ->execute([$name, $category, $logoPath]);
                $msg = 'added';
            } else {
                $pdo->prepare("UPDATE tech_stack SET name=?, category=?, logo_path=? WHERE id=?")
                    ->execute([$name, $category, $logoPath, $id]);
                $msg = 'updated';
            }
        }
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $pdo->prepare("DELETE FROM tech_stack WHERE id=?")->execute([$id]);
        $msg = 'deleted';
    }
}

// Edit mode
$editTech = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM tech_stack WHERE id=?");
    $stmt->execute([$id]);
    $editTech = $stmt->fetch();
}

$techStack = $pdo->query("SELECT * FROM tech_stack ORDER BY category ASC, name ASC")->fetchAll();
$pageTitle = 'Tech Stack';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
  <div>
    <h1>Tech Stack</h1>
    <p>Manage the technology stack logos displayed on the Services page.</p>
  </div>
  <?php if ($editTech): ?>
  <a href="tech-stack.php" class="btn btn-secondary btn-sm">➕ Add Tech</a>
  <?php endif; ?>
</div>

<?php if ($msg): ?>
<div class="alert alert-success" data-auto-dismiss="3000">
  ✅ Technology <?= $msg === 'added' ? 'added' : ($msg === 'deleted' ? 'deleted' : 'updated') ?> successfully!
</div>
<?php endif; ?>

<!-- Form -->
<div class="admin-card" style="margin-bottom:24px;">
  <div class="admin-card-header">
    <h2><?= $editTech ? 'Edit Technology' : 'Add New Technology' ?></h2>
  </div>
  <div class="admin-card-body">
    <form method="POST" action="tech-stack.php" enctype="multipart/form-data">
      <input type="hidden" name="action" value="<?= $editTech ? 'edit' : 'add' ?>">
      <?php if ($editTech): ?>
      <input type="hidden" name="id" value="<?= $editTech['id'] ?>">
      <?php endif; ?>

      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Technology Name <span class="req">*</span></label>
          <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($editTech['name'] ?? '') ?>" placeholder="e.g. React, Docker, Python..." required>
        </div>
        <div class="form-group">
          <label class="form-label">Category <span class="req">*</span></label>
          <select name="category" class="form-control" required>
            <option value="">-- Select Category --</option>
            <?php foreach ($categories as $k => $v): ?>
            <option value="<?= $k ?>" <?= (isset($editTech) && $editTech['category'] === $k) ? 'selected' : '' ?>><?= e($v) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Technology Logo</label>
        <input type="file" name="logo" class="form-control-file" accept="image/*" data-preview="techLogo">
        <img id="techLogo" src="<?= imgUrl($editTech['logo_path'] ?? '') ?>" class="img-preview" style="<?= empty($editTech['logo_path']) ? 'display:none;' : '' ?>">
      </div>

      <button type="submit" class="btn btn-primary"><?= $editTech ? '💾 Update Tech' : '➕ Add Tech' ?></button>
      <?php if ($editTech): ?>
      <a href="tech-stack.php" class="btn btn-secondary" style="margin-left:8px;">Cancel Edit</a>
      <?php endif; ?>
    </form>
  </div>
</div>

<!-- Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>All Technologies (<?= count($techStack) ?>)</h2>
  </div>
  <div class="admin-card-body">
    <?php if (empty($techStack)): ?>
      <p style="color:var(--admin-muted);text-align:center;padding:40px;">No technologies added yet.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Logo</th>
            <th>Name</th>
            <th>Category</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($techStack as $tech): ?>
          <tr>
            <td>
              <?php $tu = imgUrl($tech['logo_path']); ?>
              <?php if ($tu): ?>
                <img src="<?= e($tu) ?>" alt="Logo" style="max-height:40px;">
              <?php else: ?>
                <span style="background:#f1f5f9;padding:8px 12px;border-radius:6px;font-size:0.8rem;color:#64748b;">No logo</span>
              <?php endif; ?>
            </td>
            <td><strong><?= e($tech['name']) ?></strong></td>
            <td>
              <span class="badge badge-grey"><?= e($categories[$tech['category']] ?? $tech['category']) ?></span>
            </td>
            <td style="display:flex;gap:8px;align-items:center;">
              <a href="tech-stack.php?edit=<?= $tech['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
              <form method="POST" action="tech-stack.php" style="margin:0;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $tech['id'] ?>">
                <button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($tech['name']) ?>'?">Delete</button>
              </form>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<?php include __DIR__ . '/layout_bottom.php'; ?>
