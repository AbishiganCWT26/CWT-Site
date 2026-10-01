<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	if ($action === 'add') {
		$name = trim($_POST['company_name'] ?? '');
		$order = (int)($_POST['sort_order'] ?? 0);
		$logoPath = '';
		if (!empty($_FILES['logo']['name'])) {
			$logoPath = uploadFile($_FILES['logo'], 'clients') ?: '';
		}
		if ($name === '' || $logoPath === '') {
			$msg = 'error_required';
		} else {
			$pdo->prepare("INSERT INTO clients (company_name,logo_path,sort_order,is_active) VALUES(?,?,?,1)")
				->execute([$name, $logoPath, $order]);
			$msg = 'added';
		}
	} elseif ($action === 'edit') {
		$id = (int)$_POST['id'];
		$name = trim($_POST['company_name'] ?? '');
		$order = (int)($_POST['sort_order'] ?? 0);
		$stmt = $pdo->prepare("SELECT logo_path FROM clients WHERE id=?");
		$stmt->execute([$id]);
		$logoPath = $stmt->fetchColumn() ?: '';
		if (!empty($_FILES['logo']['name'])) {
			$newLogo = uploadFile($_FILES['logo'], 'clients');
			if ($newLogo) $logoPath = $newLogo;
		}
		if ($name !== '') {
			$pdo->prepare("UPDATE clients SET company_name=?, logo_path=?, sort_order=? WHERE id=?")
				->execute([$name, $logoPath, $order, $id]);
			$msg = 'updated';
		}
	} elseif ($action === 'toggle') {
		$id = (int)$_POST['id'];
		$active = (int)$_POST['is_active'];
		$pdo->prepare("UPDATE clients SET is_active=? WHERE id=?")->execute([$active, $id]);
		$msg = 'updated';
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM clients WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	}

	header('Location: ' . ADMIN_URL . '/clients.php?msg=' . $msg);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$clients = $pdo->query("SELECT * FROM clients ORDER BY sort_order ASC, id ASC")->fetchAll();
$nextOrder = 1;
if (!empty($clients)) {
	$maxOrd = 0;
	foreach ($clients as $c) { if ((int)$c['sort_order'] > $maxOrd) $maxOrd = (int)$c['sort_order']; }
	$nextOrder = $maxOrd + 1;
}

$editClient = null;
if (isset($_GET['edit'])) {
	$id = (int)$_GET['edit'];
	$stmt = $pdo->prepare("SELECT * FROM clients WHERE id=?");
	$stmt->execute([$id]);
	$editClient = $stmt->fetch();
}

$pageTitle = 'Client Logos';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Client Logos</h1>
		<p>Manage client/company logos shown in the marquee sections.</p>
	</div>
	<?php if ($editClient): ?>
	<a href="clients.php" class="btn btn-secondary btn-sm">+ New Client</a>
	<?php endif; ?>
</div>

<?php if ($msg === 'added' || $msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">
	Client <?= $msg === 'added' ? 'added' : ($msg === 'deleted' ? 'deleted' : 'updated') ?> successfully.
</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3000">Logo Image and Company Name are required.</div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editClient ? 'Edit Client' : 'Add New Client' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="clients.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?= $editClient ? 'edit' : 'add' ?>">
			<?php if ($editClient): ?><input type="hidden" name="id" value="<?= $editClient['id'] ?>"><?php endif; ?>
			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Company Name <span class="req">*</span></label>
					<input type="text" name="company_name" class="form-control" value="<?= e($editClient['company_name'] ?? '') ?>" required>
				</div>
				<div class="form-group">
					<label class="form-label">Sort Order <span class="req">*</span></label>
					<input type="number" name="sort_order" class="form-control" value="<?= $editClient['sort_order'] ?? $nextOrder ?>" min="1" required>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label">Logo Image <?= $editClient ? '' : '<span class="req">*</span>' ?></label>
				<input type="file" name="logo" class="form-control-file" accept="image/*" data-preview="logoPreview" <?= $editClient ? '' : 'required' ?>>
				<img id="logoPreview" src="<?= imgUrl($editClient['logo_path'] ?? '') ?>" class="img-preview" style="<?= empty($editClient['logo_path']) ? 'display:none;' : '' ?>">
				<p class="form-hint">JPG, PNG, WebP, SVG. Max 5MB.</p>
			</div>
			<button type="submit" class="btn btn-primary"><?= $editClient ? '💾 Update Client' : '➕ Add Client' ?></button>
			<?php if ($editClient): ?>
			<a href="clients.php" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Clients (<?= count($clients) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($clients)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No clients added yet.</p>
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
								<span class="badge badge-grey">No logo</span>
							<?php endif; ?>
						</td>
						<td><strong><?= e($client['company_name']) ?></strong></td>
						<td><?= (int)$client['sort_order'] ?></td>
						<td>
							<span class="badge <?= $client['is_active'] ? 'badge-success' : 'badge-danger' ?>">
								<?= $client['is_active'] ? 'Active' : 'Inactive' ?>
							</span>
						</td>
						<td>
							<div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
								<a href="clients.php?edit=<?= $client['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" style="margin:0;">
									<input type="hidden" name="action" value="toggle">
									<input type="hidden" name="id" value="<?= $client['id'] ?>">
									<input type="hidden" name="is_active" value="<?= $client['is_active'] ? 0 : 1 ?>">
									<button type="submit" class="btn btn-<?= $client['is_active'] ? 'warning' : 'success' ?> btn-xs">
										<?= $client['is_active'] ? 'Deactivate' : 'Activate' ?>
									</button>
								</form>
								<form method="POST" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $client['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($client['company_name']) ?>'?">Delete</button>
								</form>
							</div>
						</td>
					</tr>
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
		<?php endif; ?>
	</div>
</div>

<script src="<?= SITE_URL ?>/assets/js/admin-clients.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>