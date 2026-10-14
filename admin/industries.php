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

	if ($action === 'add' || $action === 'edit') {
		$id    = (int)($_POST['id'] ?? 0);
		$name  = trim($_POST['name'] ?? '');
		$order = (int)($_POST['sort_order'] ?? 0);

		$imagePath = '';
		if ($action === 'edit') {
			$existing = $pdo->prepare("SELECT image_path FROM industries WHERE id=?");
			$existing->execute([$id]);
			$imagePath = $existing->fetchColumn() ?: '';
		}

		if (!empty($_FILES['image']['name'])) {
			$newImg = uploadFile($_FILES['image'], 'industries');
			if ($newImg) $imagePath = $newImg;
		}

		if ($name === '' || $imagePath === '' || $order < 1) {
			$msg = 'error_required';
		} else {
			if ($action === 'add') {
				$pdo->prepare("INSERT INTO industries (name, image_path, sort_order) VALUES (?,?,?)")
					->execute([$name, $imagePath, $order]);
				$msg = 'added';
			} else {
				$pdo->prepare("UPDATE industries SET name=?, image_path=?, sort_order=? WHERE id=?")
					->execute([$name, $imagePath, $order, $id]);
				$msg = 'updated';
			}
		}
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM industries WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	}

	header('Location: ' . ADMIN_URL . '/industries.php?msg=' . $msg);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$editInd = null;
if (isset($_GET['edit'])) {
	$id = (int)$_GET['edit'];
	$stmt = $pdo->prepare("SELECT * FROM industries WHERE id=?");
	$stmt->execute([$id]);
	$editInd = $stmt->fetch();
}

$industries = $pdo->query("SELECT * FROM industries ORDER BY sort_order ASC, id ASC")->fetchAll();
$nextOrder = 1;
if (!empty($industries)) {
	$maxOrd = 0;
	foreach ($industries as $i) { if ((int)$i['sort_order'] > $maxOrd) $maxOrd = (int)$i['sort_order']; }
	$nextOrder = $maxOrd + 1;
}
$pageTitle = 'Industries & Verticals';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Industries & Verticals</h1>
		<p>Manage the industry sectors displayed on the Services page.</p>
	</div>
	<?php if ($editInd): ?>
	<a href="industries.php" class="btn btn-secondary btn-sm">+ Add Industry</a>
	<?php endif; ?>
</div>

<?php if ($msg === 'added' || $msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Industry <?= $msg ?> successfully.</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3000">Name, Industry Icon/Image and Sort Order are required.</div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editInd ? 'Edit Industry' : 'Add New Industry' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="industries.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?= $editInd ? 'edit' : 'add' ?>">
			<?php if ($editInd): ?><input type="hidden" name="id" value="<?= $editInd['id'] ?>"><?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Industry Name <span class="req">*</span></label>
					<input type="text" name="name" class="form-control" value="<?= e($editInd['name'] ?? '') ?>" placeholder="e.g. Healthcare, Finance..." required>
				</div>
				<div class="form-group">
					<label class="form-label">Sort Order <span class="req">*</span></label>
					<input type="number" name="sort_order" class="form-control" value="<?= $editInd['sort_order'] ?? $nextOrder ?>" min="1" required>
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Industry Icon/Image <?= $editInd ? '' : '<span class="req">*</span>' ?></label>
				<input type="file" name="image" class="form-control-file" accept="image/*" data-preview="indImg" <?= $editInd ? '' : 'required' ?>>
				<img id="indImg" src="<?= imgUrl($editInd['image_path'] ?? '') ?>" class="img-preview" style="<?= empty($editInd['image_path']) ? 'display:none;' : '' ?>">
			</div>

			<button type="submit" class="btn btn-primary"><?= $editInd ? '💾 Update Industry' : '➕ Add Industry' ?></button>
			<?php if ($editInd): ?>
			<a href="industries.php" class="btn btn-secondary" style="margin-left:8px;">Cancel Edit</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Industries (<?= count($industries) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($industries)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No industries added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead><tr><th>Image/Icon</th><th>Industry Name</th><th>Order</th><th>Actions</th></tr></thead>
				<tbody>
					<?php foreach ($industries as $ind): ?>
					<tr>
						<td>
							<?php $iu = imgUrl($ind['image_path']); ?>
							<?php if ($iu): ?>
								<img src="<?= e($iu) ?>" alt="Icon">
							<?php else: ?>
								<span class="badge badge-grey">No image</span>
							<?php endif; ?>
						</td>
						<td><strong><?= e($ind['name']) ?></strong></td>
						<td><?= $ind['sort_order'] ?></td>
						<td>
							<div style="display:flex;gap:6px;flex-wrap:wrap;">
								<a href="industries.php?edit=<?= $ind['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" action="industries.php" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $ind['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete industry '<?= e($ind['name']) ?>'?">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-industries.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>