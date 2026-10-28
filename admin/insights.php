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
	if ($action === 'toggle') {
		$id = (int)$_POST['id'];
		$active = (int)$_POST['is_published'];
		$pdo->prepare("UPDATE blog_details SET is_published=? WHERE id=?")->execute([$active, $id]);
		$msg = 'updated';
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM blog_details WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	}
	header('Location: ' . ADMIN_URL . '/insights.php?msg=' . $msg);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$blogs = $pdo->query("SELECT * FROM blog_details ORDER BY publish_date DESC, id DESC")->fetchAll();
$pageTitle = 'Insights & Blog';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Insights & Blog Posts</h1>
		<p>Manage thought leadership and news articles.</p>
	</div>
	<a href="insight-edit.php" class="btn btn-primary">➕ Write New Post</a>
</div>

<?php if ($msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Post <?= $msg ?> successfully.</div>
<?php endif; ?>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Posts (<?= count($blogs) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($blogs)): ?>
			<div style="text-align:center;padding:40px;color:var(--text-muted);">
				<p>No blog posts published yet.</p>
				<a href="insight-edit.php" class="btn btn-primary btn-sm" style="margin-top:14px;">Write Your First Post</a>
			</div>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead><tr><th>Thumbnail</th><th>Topic / Title</th><th>Publish Date</th><th>Status</th><th>Actions</th></tr></thead>
				<tbody>
					<?php foreach ($blogs as $blog): ?>
					<tr>
						<td>
							<?php $bu = imgUrl($blog['featured_image']); ?>
							<?php if ($bu): ?>
								<img src="<?= e($bu) ?>" alt="Thumb" style="width:56px;height:38px;object-fit:cover;border-radius:6px;">
							<?php else: ?>
								<span class="badge badge-grey">No img</span>
							<?php endif; ?>
						</td>
						<td>
							<strong><?= e($blog['topic']) ?></strong><br>
							<a href="<?= SITE_URL ?>/insight-detail.php?slug=<?= urlencode($blog['slug']) ?>" target="_blank" style="font-size:11.5px;color:var(--primary);font-weight:600;">View post ↗</a>
						</td>
						<td><?= $blog['publish_date'] ? date('M j, Y', strtotime($blog['publish_date'])) : '—' ?></td>
						<td>
							<form method="POST" style="margin:0;">
								<input type="hidden" name="action" value="toggle">
								<input type="hidden" name="id" value="<?= $blog['id'] ?>">
								<input type="hidden" name="is_published" value="<?= $blog['is_published'] ? 0 : 1 ?>">
								<button type="submit" class="badge <?= $blog['is_published'] ? 'badge-success' : 'badge-warning' ?>" style="border:none;cursor:pointer;">
									<?= $blog['is_published'] ? 'Published' : 'Draft' ?>
								</button>
							</form>
						</td>
						<td>
							<div style="display:flex;gap:6px;flex-wrap:wrap;">
								<a href="insight-edit.php?id=<?= $blog['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" action="insights.php" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $blog['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete post '<?= e($blog['topic']) ?>'?">Delete</button>
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

<?php include __DIR__ . '/layout_bottom.php'; ?>