<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';
$blogId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$blog = [];

if ($blogId) {
	$stmt = $pdo->prepare("SELECT * FROM blog_details WHERE id = ?");
	$stmt->execute([$blogId]);
	$blog = $stmt->fetch() ?: [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$topic     = trim($_POST['topic'] ?? '');
	$slug      = trim($_POST['slug'] ?? '');
	$content   = $_POST['content'] ?? '';
	$hashtags  = trim($_POST['hashtags'] ?? '');
	$published = isset($_POST['is_published']) ? 1 : 0;

	if (empty($slug)) $slug = slugify($topic);

	$featuredImage = $blog['featured_image'] ?? '';
	if (!empty($_FILES['featured_image']['name'])) {
		$newImg = uploadFile($_FILES['featured_image'], 'insights');
		if ($newImg) $featuredImage = $newImg;
	}

	$tagsArray = array_filter(array_map('trim', explode(',', $hashtags)));

	if ($topic === '' || $featuredImage === '' || count($tagsArray) < 2) {
		$msg = 'error_required';
	} else {
		if ($blogId && !empty($blog)) {
			$stmt = $pdo->prepare("UPDATE blog_details SET topic=?, slug=?, content=?, hashtags=?, featured_image=?, is_published=? WHERE id=?");
			$stmt->execute([$topic, $slug, $content, $hashtags, $featuredImage, $published, $blogId]);
			$msg = 'updated';
		} else {
			$stmt = $pdo->prepare("INSERT INTO blog_details (topic, slug, content, hashtags, featured_image, is_published, publish_date) VALUES (?,?,?,?,?,?,?)");
			$stmt->execute([$topic, $slug, $content, $hashtags, $featuredImage, $published, date('Y-m-d')]);
			$blogId = (int)$pdo->lastInsertId();
			$msg = 'added';
		}

		$stmt = $pdo->prepare("SELECT * FROM blog_details WHERE id = ?");
		$stmt->execute([$blogId]);
		$blog = $stmt->fetch();
	}
}

$pageTitle = $blogId ? 'Edit Post' : 'New Post';
include __DIR__ . '/layout_top.php';
?>

<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-insight-edit.css">

<div class="page-header">
	<div>
		<h1><?= $blogId ? 'Edit Post' : 'Write New Post' ?></h1>
		<p>Use the rich text editor below to format your insight.</p>
	</div>
	<a href="insights.php" class="btn btn-secondary btn-sm">← Back to Posts</a>
</div>

<?php if ($msg === 'added' || $msg === 'updated'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Post <?= $msg === 'added' ? 'saved' : 'updated' ?> successfully.</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3400">Title, Featured Image and at least 2 Hashtags are required.</div>
<?php endif; ?>

<div class="admin-card">
	<div class="admin-card-body">
		<form id="postForm" method="POST" action="insight-edit.php<?= $blogId ? '?id='.$blogId : '' ?>" enctype="multipart/form-data">

			<div class="form-row">
				<div class="form-group" style="flex:2;">
					<label class="form-label">Post Title (Topic) <span class="req">*</span></label>
					<input type="text" name="topic" id="insight_topic" class="form-control" value="<?= e($blog['topic'] ?? '') ?>" required>
				</div>
				<div class="form-group" style="flex:1;">
					<label class="form-label">URL Slug</label>
					<input type="text" name="slug" id="insight_slug" class="form-control" value="<?= e($blog['slug'] ?? '') ?>" placeholder="auto-generated">
				</div>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Featured Image <?= $blogId ? '' : '<span class="req">*</span>' ?></label>
					<input type="file" name="featured_image" class="form-control-file" accept="image/*" data-preview="postImg" <?= $blogId ? '' : 'required' ?>>
					<img id="postImg" src="<?= imgUrl($blog['featured_image'] ?? '') ?>" class="img-preview" style="<?= empty($blog['featured_image']) ? 'display:none;' : '' ?>">
				</div>
				<div class="form-group">
					<label class="form-label">Hashtags (Tags) <span class="req">*</span></label>
					<div id="tag_container"></div>
					<input type="text" id="hashtag_input" class="form-control" placeholder="Type a tag and press Enter...">
					<input type="hidden" name="hashtags" id="hashtags_hidden" value="<?= e($blog['hashtags'] ?? '') ?>">
					<div class="form-hint">Minimum 2 hashtags required. Press Enter or comma to add.</div>
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Content <span class="req">*</span></label>
				<div id="quillToolbar">
					<span class="ql-formats">
						<select class="ql-header"><option value="1">Heading 1</option><option value="2">Heading 2</option><option value="3">Heading 3</option><option selected>Normal</option></select>
					</span>
					<span class="ql-formats"><button class="ql-bold"></button><button class="ql-italic"></button><button class="ql-underline"></button></span>
					<span class="ql-formats"><button class="ql-list" value="ordered"></button><button class="ql-list" value="bullet"></button></span>
					<span class="ql-formats"><button class="ql-link"></button><button class="ql-image"></button><button class="ql-code-block"></button></span>
					<span class="ql-formats"><button class="ql-clean"></button></span>
				</div>
				<div id="quillEditor"><?= $blog['content'] ?? '' ?></div>
				<input type="hidden" name="content" id="quillContent" required>
			</div>

			<div class="form-group" style="padding-top:14px;border-top:1px solid var(--border);">
				<label class="form-check">
					<input type="checkbox" name="is_published" value="1" <?= (!isset($blog['is_published']) || $blog['is_published']) ? 'checked' : '' ?>>
					<span>Publish Immediately</span>
				</label>
			</div>

			<button type="submit" class="btn btn-primary btn-lg" style="width:100%;justify-content:center;margin-top:10px;">💾 Save Post</button>
		</form>
	</div>
</div>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>window.INITIAL_HASHTAGS = <?= json_encode($blog['hashtags'] ?? '') ?>;</script>
<script src="<?= SITE_URL ?>/assets/js/admin-insight-edit.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>