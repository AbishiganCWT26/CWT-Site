<?php
/**
 * Admin — Manage Client Logos (for Marquee)
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

// Handle actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'add') {
        $name  = trim($_POST['company_name'] ?? '');
        $order = (int)($_POST['sort_order'] ?? 0);
        if ($name) {
            $logoPath = '';
            if (!empty($_FILES['logo']['name'])) {
                $logoPath = uploadFile($_FILES['logo'], 'clients') ?: '';
            }
            $pdo->prepare("INSERT INTO clients (company_name,logo_path,sort_order,is_active) VALUES(?,?,?,1)")
                ->execute([$name, $logoPath, $order]);
            $msg = 'added';
        }
    } elseif ($action === 'toggle') {
        $id     = (int)$_POST['id'];
        $active = (int)$_POST['is_active'];
        $pdo->prepare("UPDATE clients SET is_active=? WHERE id=?")->execute([$active, $id]);
        $msg = 'updated';
    } elseif ($action === 'delete') {
        $id = (int)$_POST['id'];
        $pdo->prepare("DELETE FROM clients WHERE id=?")->execute([$id]);
        $msg = 'deleted';
    }
}

$clients   = $pdo->query("SELECT * FROM clients ORDER BY sort_order ASC, id ASC")->fetchAll();
$pageTitle = 'Client Logos';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
  <div>
    <h1>Client Logos</h1>
    <p>Manage client/company logos shown in the marquee sections.</p>
  </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-success" data-auto-dismiss="3000">
  ✅ Client <?= $msg === 'added' ? 'added' : ($msg === 'deleted' ? 'deleted' : 'updated') ?> successfully!
</div>
<?php endif; ?>

<!-- Add Client Form -->
<div class="admin-card" style="margin-bottom:24px;">
  <div class="admin-card-header"><h2>Add New Client</h2></div>
  <div class="admin-card-body">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="action" value="add">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Company Name <span class="req">*</span></label>
          <input type="text" name="company_name" class="form-control" placeholder="e.g. TechCorp Ltd" required>
        </div>
        <div class="form-group">
          <label class="form-label">Sort Order</label>
          <input type="number" name="sort_order" class="form-control" value="0" min="0">
        </div>
      </div>
      <div class="form-group">
        <label class="form-label">Logo Image</label>
        <input type="file" name="logo" id="logoInput" class="form-control-file" accept="image/*" data-preview="logoPreview">
        <img id="logoPreview" class="img-preview" style="display:none;">
        <p class="form-hint">Accepted: JPG, PNG, WebP, SVG. Max 5MB. Recommended: transparent background PNG.</p>
      </div>
      <button type="submit" class="btn btn-primary" id="addClientBtn">➕ Add Client</button>
    </form>
  </div>
</div>

<!-- Clients Table -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>All Clients (<?= count($clients) ?>)</h2>
  </div>
  <div class="admin-card-body">
    <?php if (empty($clients)): ?>
      <p style="color:var(--admin-muted);text-align:center;padding:40px;">No clients added yet.</p>
    <?php else: ?>
    <div class="admin-table-wrap">
      <table class="admin-table">
        <thead>
          <tr>
            <th>Logo</th>
            <th>Company Name</th>
            <th>Sort Order</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($clients as $client): ?>
          <tr>
            <td>
              <?php $lu = imgUrl($client['logo_path']); ?>
              <?php if ($lu): ?>
                <img src="<?= e($lu) ?>" alt="<?= e($client['company_name']) ?>">
              <?php else: ?>
                <span style="background:#f1f5f9;padding:8px 12px;border-radius:6px;font-size:0.8rem;color:#64748b;">No logo</span>
              <?php endif; ?>
            </td>
            <td><strong><?= e($client['company_name']) ?></strong></td>
            <td><?= (int)$client['sort_order'] ?></td>
            <td>
              <span class="badge <?= $client['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                <?= $client['is_active'] ? 'Active' : 'Inactive' ?>
              </span>
            </td>
            <td style="display:flex;gap:8px;align-items:center;">
              <!-- Toggle active -->
              <form method="POST" style="margin:0;">
                <input type="hidden" name="action" value="toggle">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                <input type="hidden" name="is_active" value="<?= $client['is_active'] ? 0 : 1 ?>">
                <button type="submit" class="btn btn-<?= $client['is_active'] ? 'warning' : 'success' ?> btn-xs">
                  <?= $client['is_active'] ? 'Deactivate' : 'Activate' ?>
                </button>
              </form>
              <!-- Delete -->
              <form method="POST" style="margin:0;">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= $client['id'] ?>">
                <button type="submit" class="btn btn-danger btn-xs"
                        data-confirm="Delete '<?= e($client['company_name']) ?>'? This cannot be undone.">
                  Delete
                </button>
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
