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
		$id          = (int)($_POST['id'] ?? 0);
		$name        = trim($_POST['name'] ?? '');
		$description = trim($_POST['description'] ?? '');
		$color       = trim($_POST['color'] ?? '#3b82f6');
		$icon        = trim($_POST['icon'] ?? 'fa-folder');
		$area_slug   = trim($_POST['area_slug'] ?? '');
		$sort_order  = (int)($_POST['sort_order'] ?? 0);
		$is_active   = isset($_POST['is_active']) ? 1 : 0;

		if ($name === '') {
			$msg = 'error_required';
		} else {
			if ($action === 'add') {
				$pdo->prepare("INSERT INTO solution_category (name, description, color, icon, area_slug, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
					->execute([$name, $description, $color, $icon, $area_slug, $sort_order, $is_active]);
				$msg = 'added';
			} else {
				$pdo->prepare("UPDATE solution_category SET name=?, description=?, color=?, icon=?, area_slug=?, sort_order=?, is_active=? WHERE id=?")
					->execute([$name, $description, $color, $icon, $area_slug, $sort_order, $is_active, $id]);
				$msg = 'updated';
			}
		}
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM solution_category WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	} elseif ($action === 'toggle') {
		$id = (int)$_POST['id'];
		$active = (int)$_POST['is_active'];
		$pdo->prepare("UPDATE solution_category SET is_active=? WHERE id=?")->execute([$active, $id]);
		$msg = 'updated';
	}

	header('Location: ' . ADMIN_URL . '/solution-category.php?msg=' . $msg);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$editCat = null;
if (isset($_GET['edit'])) {
	$editCat = getSolutionCategory($pdo, (int)$_GET['edit']);
}

$categories = $pdo->query("SELECT c.*, (SELECT COUNT(*) FROM projects p WHERE p.category_id = c.id) AS project_count FROM solution_category c ORDER BY c.sort_order ASC, c.id ASC")->fetchAll();
$nextOrder = 1;
if (!empty($categories)) {
	$max = 0;
	foreach ($categories as $c) { if ((int)$c['sort_order'] > $max) $max = (int)$c['sort_order']; }
	$nextOrder = $max + 1;
}

$pageTitle = 'Solution Categories';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-solution-category.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="page-header">
	<div>
		<h1>Solution Categories</h1>
		<p>Manage categories that group your solution projects on the public portfolio page.</p>
	</div>
	<?php if ($editCat): ?>
	<a href="solution-category.php" class="btn btn-secondary btn-sm">+ New Category</a>
	<?php endif; ?>
</div>

<?php if ($msg === 'added' || $msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Category <?= $msg ?> successfully.</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3000">Category name is required.</div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editCat ? 'Edit Category' : 'Add New Category' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="solution-category.php" id="catForm">
			<input type="hidden" name="action" value="<?= $editCat ? 'edit' : 'add' ?>">
			<?php if ($editCat): ?><input type="hidden" name="id" value="<?= $editCat['id'] ?>"><?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Category Name <span class="req">*</span></label>
					<input type="text" name="name" class="form-control" value="<?= e($editCat['name'] ?? '') ?>" placeholder="e.g. Banking & Financial Services" required>
				</div>
				<div class="form-group">
					<label class="form-label">Area Slug (CSS class / identifier)</label>
					<input type="text" name="area_slug" class="form-control" value="<?= e($editCat['area_slug'] ?? '') ?>" placeholder="e.g. banking">
					<div class="form-hint">Lowercase, no spaces. Used for CSS targeting.</div>
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Short Description (max 12 words)</label>
				<input type="text" name="description" class="form-control" value="<?= e($editCat['description'] ?? '') ?>" maxlength="140" placeholder="Data-Driven Banking. Smarter Operations.">
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Category Color</label>
					<div class="color-input-wrap">
						<input type="color" name="color" id="colorPicker" class="color-picker" value="<?= e($editCat['color'] ?? '#3b82f6') ?>">
						<input type="text" id="colorHex" class="form-control color-hex" value="<?= e($editCat['color'] ?? '#3b82f6') ?>" maxlength="7" readonly>
					</div>
				</div>
				<div class="form-group">
					<label class="form-label">Icon (Font Awesome class)</label>
					<div class="icon-input-wrap">
						<span class="icon-preview"><i id="iconPreview" class="fa-solid <?= e($editCat['icon'] ?? 'fa-folder') ?>"></i></span>
						<input type="text" name="icon" id="iconInput" class="form-control" value="<?= e($editCat['icon'] ?? 'fa-folder') ?>" placeholder="fa-folder">
					</div>
					<div class="icon-suggestions">
						<button type="button" class="icon-chip" data-icon="fa-folder"><i class="fa-solid fa-folder"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-ship"><i class="fa-solid fa-ship"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-university"><i class="fa-solid fa-university"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-shield-alt"><i class="fa-solid fa-shield-alt"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-leaf"><i class="fa-solid fa-leaf"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-building"><i class="fa-solid fa-building"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-database"><i class="fa-solid fa-database"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-heartbeat"><i class="fa-solid fa-heartbeat"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-users"><i class="fa-solid fa-users"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-cloud"><i class="fa-solid fa-cloud"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-briefcase"><i class="fa-solid fa-briefcase"></i></button>
						<button type="button" class="icon-chip" data-icon="fa-laptop-code"><i class="fa-solid fa-laptop-code"></i></button>
					</div>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Sort Order</label>
					<input type="number" name="sort_order" class="form-control" value="<?= $editCat['sort_order'] ?? $nextOrder ?>" min="1">
				</div>
				<div class="form-group" style="padding-top:24px;">
					<label class="form-check">
						<input type="checkbox" name="is_active" value="1" <?= (!isset($editCat) || $editCat['is_active']) ? 'checked' : '' ?>>
						<span>Active (show on public site)</span>
					</label>
				</div>
			</div>

			<button type="submit" class="btn btn-primary"><?= $editCat ? '💾 Update Category' : '➕ Add Category' ?></button>
			<?php if ($editCat): ?>
			<a href="solution-category.php" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Categories (<?= count($categories) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($categories)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No categories added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead><tr><th>Icon</th><th>Name</th><th>Description</th><th>Color</th><th>Projects</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
				<tbody>
					<?php foreach ($categories as $cat): ?>
					<tr>
						<td>
							<span class="cat-icon-cell" style="background:<?= e($cat['color']) ?>;">
								<i class="fa-solid <?= e($cat['icon']) ?>"></i>
							</span>
						</td>
						<td><strong><?= e($cat['name']) ?></strong></td>
						<td style="max-width:220px;"><span class="muted-text"><?= e($cat['description'] ?? '—') ?></span></td>
						<td><span class="color-swatch" style="background:<?= e($cat['color']) ?>;"></span> <code style="font-size:11px;"><?= e($cat['color']) ?></code></td>
						<td><span class="badge badge-info"><?= (int)$cat['project_count'] ?></span></td>
						<td><?= (int)$cat['sort_order'] ?></td>
						<td>
							<form method="POST" style="margin:0;">
								<input type="hidden" name="action" value="toggle">
								<input type="hidden" name="id" value="<?= $cat['id'] ?>">
								<input type="hidden" name="is_active" value="<?= $cat['is_active'] ? 0 : 1 ?>">
								<button type="submit" class="badge <?= $cat['is_active'] ? 'badge-success' : 'badge-danger' ?>" style="border:none;cursor:pointer;">
									<?= $cat['is_active'] ? 'Active' : 'Inactive' ?>
								</button>
							</form>
						</td>
						<td>
							<div style="display:flex;gap:6px;flex-wrap:wrap;">
								<a href="solution-projects.php?category=<?= $cat['id'] ?>" class="btn btn-secondary btn-xs">Projects</a>
								<a href="solution-category.php?edit=<?= $cat['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $cat['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($cat['name']) ?>'? This will also delete all its projects.">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-solution-category.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>