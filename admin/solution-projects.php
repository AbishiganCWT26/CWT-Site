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
		$category_id = (int)($_POST['category_id'] ?? 0);
		$name        = trim($_POST['project_name'] ?? '');
		$desc        = trim($_POST['short_description'] ?? '');
		$sort_order  = (int)($_POST['sort_order'] ?? 0);
		$is_active   = isset($_POST['is_active']) ? 1 : 0;

		$imagePath = '';
		if ($action === 'edit') {
			$existing = $pdo->prepare("SELECT image_path, slug FROM projects WHERE id=?");
			$existing->execute([$id]);
			$row = $existing->fetch();
			$imagePath = $row['image_path'] ?? '';
		}

		if (!empty($_FILES['image']['name'])) {
			$uploaded = uploadFile($_FILES['image'], 'projects');
			if ($uploaded) $imagePath = $uploaded;
		}

		$slug = projectSlug($name);

		if ($name === '' || $category_id <= 0) {
			$msg = 'error_required';
		} else {
			$dupStmt = $pdo->prepare("SELECT id FROM projects WHERE slug = ? AND id != ?");
			$dupStmt->execute([$slug, $id]);
			if ($dupStmt->fetch()) {
				$slug .= '-' . time();
			}

			if ($action === 'add') {
				$pdo->prepare("INSERT INTO projects (category_id, project_name, short_description, image_path, slug, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
					->execute([$category_id, $name, $desc, $imagePath, $slug, $sort_order, $is_active]);
				$msg = 'added';
			} else {
				$pdo->prepare("UPDATE projects SET category_id=?, project_name=?, short_description=?, image_path=?, slug=?, sort_order=?, is_active=? WHERE id=?")
					->execute([$category_id, $name, $desc, $imagePath, $slug, $sort_order, $is_active, $id]);
				$msg = 'updated';
			}
		}
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM projects WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	} elseif ($action === 'toggle') {
		$id = (int)$_POST['id'];
		$active = (int)$_POST['is_active'];
		$pdo->prepare("UPDATE projects SET is_active=? WHERE id=?")->execute([$active, $id]);
		$msg = 'updated';
	}

	$redir = ADMIN_URL . '/solution-projects.php?msg=' . $msg;
	if (!empty($_POST['return_category'])) $redir .= '&category=' . (int)$_POST['return_category'];
	header('Location: ' . $redir);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$categories = getSolutionCategories($pdo, false);
$filterCat = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$editProj = null;
if (isset($_GET['edit'])) {
	$editProj = getProjectById($pdo, (int)$_GET['edit']);
	$filterCat = (int)($editProj['category_id'] ?? $filterCat);
}

$sql = "SELECT p.*, c.name AS category_name, c.color AS category_color FROM projects p LEFT JOIN solution_category c ON p.category_id = c.id";
if ($filterCat > 0) $sql .= " WHERE p.category_id = " . $filterCat;
$sql .= " ORDER BY c.sort_order ASC, p.sort_order ASC, p.id ASC";
$projects = $pdo->query($sql)->fetchAll();

$nextSort = 1;
if ($filterCat > 0) {
	$maxStmt = $pdo->prepare("SELECT MAX(sort_order) FROM projects WHERE category_id = ?");
	$maxStmt->execute([$filterCat]);
	$nextSort = (int)$maxStmt->fetchColumn() + 1;
} else {
	$maxStmt = $pdo->query("SELECT MAX(sort_order) FROM projects");
	$nextSort = (int)$maxStmt->fetchColumn() + 1;
}

$pageTitle = 'Solution Projects';
include __DIR__ . '/layout_top.php';

$projectsWithContent = [];
$contentStmt = $pdo->query("SELECT project_id FROM project_details WHERE content IS NOT NULL AND content != ''");
while ($row = $contentStmt->fetch()) {
	$projectsWithContent[(int)$row['project_id']] = true;
}
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-solution-projects.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="page-header">
	<div>
		<h1>Solution Projects</h1>
		<p>Manage individual projects shown under each solution category.</p>
	</div>
	<?php if ($editProj): ?>
	<a href="solution-projects.php<?= $filterCat ? '?category='.$filterCat : '' ?>" class="btn btn-secondary btn-sm">+ New Project</a>
	<?php endif; ?>
</div>

<?php if ($msg === 'added' || $msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Project <?= $msg ?> successfully.</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3000">Project name and category are required.</div>
<?php endif; ?>

<div class="cat-filter-bar">
	<a href="solution-projects.php" class="cat-filter-chip <?= $filterCat === 0 ? 'active' : '' ?>">
		<i class="fa-solid fa-layer-group"></i><span>All</span>
		<em><?= count(getAllProjects($pdo, false)) ?></em>
	</a>
	<?php foreach ($categories as $c): ?>
	<a href="solution-projects.php?category=<?= $c['id'] ?>" class="cat-filter-chip <?= $filterCat === (int)$c['id'] ? 'active' : '' ?>" style="<?= $filterCat === (int)$c['id'] ? 'background:'.$c['color'].';border-color:'.$c['color'].';' : '' ?>">
		<i class="fa-solid <?= e($c['icon']) ?>"></i>
		<span><?= e($c['name']) ?></span>
		<?php
			$cntStmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE category_id=?");
			$cntStmt->execute([$c['id']]);
		?>
		<em><?= (int)$cntStmt->fetchColumn() ?></em>
	</a>
	<?php endforeach; ?>
</div>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editProj ? 'Edit Project' : 'Add New Project' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="solution-projects.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?= $editProj ? 'edit' : 'add' ?>">
			<input type="hidden" name="return_category" value="<?= $filterCat ?>">
			<?php if ($editProj): ?><input type="hidden" name="id" value="<?= $editProj['id'] ?>"><?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Category <span class="req">*</span></label>
					<select name="category_id" class="form-control" required>
						<option value="">-- Select Category --</option>
						<?php foreach ($categories as $c): ?>
						<option value="<?= $c['id'] ?>" <?= ((int)($editProj['category_id'] ?? $filterCat) === (int)$c['id']) ? 'selected' : '' ?>>
							<?= e($c['name']) ?>
						</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="form-group">
					<label class="form-label">Project Name <span class="req">*</span></label>
					<input type="text" name="project_name" id="projName" class="form-control" value="<?= e($editProj['project_name'] ?? '') ?>" placeholder="e.g. Allianz - AIR" required>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Short Description (max 6 words)</label>
					<input type="text" name="short_description" class="form-control" value="<?= e($editProj['short_description'] ?? '') ?>" maxlength="100" placeholder="Insight Reporting Platform">
					<div class="form-hint">Keep it short — appears below the project name.</div>
				</div>
				<div class="form-group">
					<label class="form-label">URL Slug (auto-generated)</label>
					<input type="text" id="slugPreview" class="form-control" value="<?= e($editProj['slug'] ?? '') ?>" readonly placeholder="Allianz-AIR">
					<div class="form-hint">Public link: /project.php?slug=<span id="slugInline"><?= e($editProj['slug'] ?? 'Allianz-AIR') ?></span></div>
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Project Image</label>
					<input type="file" name="image" class="form-control-file" accept="image/*" data-preview="projImg">
					<img id="projImg" src="<?= imgUrl($editProj['image_path'] ?? '') ?>" class="img-preview" style="<?= empty($editProj['image_path']) ? 'display:none;' : '' ?>">
					<div class="form-hint">Recommended: 1200×600 JPG/PNG. Max 5MB.</div>
				</div>
				<div>
					<div class="form-group">
						<label class="form-label">Sort Order</label>
						<input type="number" name="sort_order" class="form-control" value="<?= $editProj['sort_order'] ?? $nextSort ?>" min="1">
					</div>
					<div class="form-group">
						<label class="form-check">
							<input type="checkbox" name="is_active" value="1" <?= (!isset($editProj) || $editProj['is_active']) ? 'checked' : '' ?>>
							<span>Active (show on public site)</span>
						</label>
					</div>
				</div>
			</div>

			<button type="submit" class="btn btn-primary"><?= $editProj ? '💾 Update Project' : '➕ Add Project' ?></button>
			<?php if ($editProj): ?>
			<a href="solution-projects.php<?= $filterCat ? '?category='.$filterCat : '' ?>" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2><?= $filterCat ? 'Projects in Category' : 'All Projects' ?> (<?= count($projects) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($projects)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No projects added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead><tr><th>Image</th><th>Project Name</th><th>Description</th><th>Category</th><th>Slug</th><th>Order</th><th>Status</th><th>Actions</th></tr></thead>
				<tbody>
					<?php foreach ($projects as $p): ?>
					<tr>
						<td>
							<?php $pu = imgUrl($p['image_path']); ?>
							<?php if ($pu): ?>
								<img src="<?= e($pu) ?>" alt="Thumb" style="width:60px;height:40px;object-fit:cover;border-radius:6px;">
							<?php else: ?>
								<span class="badge badge-grey">No img</span>
							<?php endif; ?>
						</td>
						<td>
                            <strong><?= e($p['project_name']) ?></strong>
                            <?php if (isset($projectsWithContent[(int)$p['id']])): ?>
                                <span class="badge badge-success" style="margin-left:6px;font-size:9px;">✓ Content</span>
                            <?php else: ?>
                                <span class="badge badge-warning" style="margin-left:6px;font-size:9px;">No Content</span>
                            <?php endif; ?>
                        </td>
						<td><span class="muted-text"><?= e($p['short_description'] ?? '—') ?></span></td>
						<td>
							<span class="badge" style="background:<?= e($p['category_color'] ?? '#eef1f7') ?>1a;color:<?= e($p['category_color'] ?? '#64748b') ?>;">
								<?= e($p['category_name'] ?? '—') ?>
							</span>
						</td>
						<td><code class="slug-code"><?= e($p['slug']) ?></code></td>
						<td><?= (int)$p['sort_order'] ?></td>
						<td>
							<form method="POST" style="margin:0;">
								<input type="hidden" name="action" value="toggle">
								<input type="hidden" name="id" value="<?= $p['id'] ?>">
								<input type="hidden" name="is_active" value="<?= $p['is_active'] ? 0 : 1 ?>">
								<input type="hidden" name="return_category" value="<?= $filterCat ?>">
								<button type="submit" class="badge <?= $p['is_active'] ? 'badge-success' : 'badge-danger' ?>" style="border:none;cursor:pointer;">
									<?= $p['is_active'] ? 'Active' : 'Inactive' ?>
								</button>
							</form>
						</td>
						<td>
                            <div style="display:flex;gap:6px;flex-wrap:wrap;">
                                <a href="project-edit.php?id=<?= $p['id'] ?>" class="btn btn-primary btn-xs">✎ Content</a>
                                <a href="solution-projects.php?edit=<?= $p['id'] ?><?= $filterCat ? '&category='.$filterCat : '' ?>" class="btn btn-secondary btn-xs">Edit</a>
                                <a href="<?= SITE_URL ?>/project.php?slug=<?= urlencode($p['slug']) ?>" target="_blank" class="btn btn-secondary btn-xs">View ↗</a>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $p['id'] ?>">
                                    <input type="hidden" name="return_category" value="<?= $filterCat ?>">
                                    <button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($p['project_name']) ?>'?">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-solution-projects.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>