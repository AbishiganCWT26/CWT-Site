<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
		setFlash('error', 'The uploaded file is too large. Please upload an image under 5MB.');
		header('Location: ' . ADMIN_URL . '/award.php');
		exit;
	}

	$section = $_POST['section'] ?? $_POST['action'] ?? '';

	try {
		if ($section === 'award_add' || $section === 'add') {
			verifyCsrf();
			$title = trim($_POST['award_title'] ?? '');
			$para  = trim($_POST['award_para'] ?? '');

			if ($title === '' || $para === '' || empty($_FILES['award_image']['name'])) {
				setFlash('error', 'Title, paragraph, and image are all required.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$imgName = handle_award_image_upload($_FILES['award_image']);
			if ($imgName === false) {
				setFlash('error', 'Award image could not be uploaded. Use jpg, jpeg, png, webp, or gif under 5MB.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			create_award($pdo, $imgName, $title, $para);
			setFlash('success', 'Award added successfully.');
			header('Location: ' . ADMIN_URL . '/award.php');
			exit;

		} elseif ($section === 'award_edit' || $section === 'edit') {
			verifyCsrf();
			$id    = (int)($_POST['award_id'] ?? $_POST['id'] ?? 0);
			$title = trim($_POST['award_title'] ?? '');
			$para  = trim($_POST['award_para'] ?? '');

			if ($id < 1 || $title === '' || $para === '') {
				setFlash('error', 'Title and paragraph are required.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$existing = get_award_by_id($pdo, $id);
			if (!$existing) {
				setFlash('error', 'Award not found.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$imgName = $existing['image'];
			if (!empty($_FILES['award_image']['name'])) {
				$newImg = handle_award_image_upload($_FILES['award_image']);
				if ($newImg === false) {
					setFlash('error', 'Award image could not be uploaded. Use jpg, jpeg, png, webp, or gif under 5MB.');
					header('Location: ' . ADMIN_URL . '/award.php?edit_award=' . $id);
					exit;
				}
				$oldImgName = $existing['image'];
				$imgName = $newImg;
				update_award($pdo, $id, $imgName, $title, $para);
				if ($oldImgName !== '') {
					deleteUploadedFile($pdo, 'award/' . $oldImgName, 'award');
				}
			} else {
				update_award($pdo, $id, $imgName, $title, $para);
			}

			setFlash('success', 'Award updated successfully.');
			header('Location: ' . ADMIN_URL . '/award.php');
			exit;

		} elseif ($section === 'award_delete' || $section === 'delete') {
			verifyCsrf();
			$id = (int)($_POST['award_id'] ?? $_POST['id'] ?? 0);
			if ($id < 1) {
				setFlash('error', 'Invalid award.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$existing = get_award_by_id($pdo, $id);
			if ($existing) {
				delete_award($pdo, $id);
				setFlash('success', 'Award deleted successfully.');
			} else {
				setFlash('error', 'Award not found.');
			}
			header('Location: ' . ADMIN_URL . '/award.php');
			exit;
		}
	} catch (Throwable $e) {
		error_log('[award.php] ' . $e->getMessage());
		setFlash('error', 'Could not save changes. Database or file error occurred.');
		header('Location: ' . ADMIN_URL . '/award.php');
		exit;
	}
}

$awards = get_awards($pdo);
$editAward = null;
if (isset($_GET['edit_award'])) {
	$editAward = get_award_by_id($pdo, (int)$_GET['edit_award']);
}

$pageTitle = 'Awards';
include __DIR__ . '/layout_top.php';
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-award.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-award.css') ?>">

<div class="page-header">
	<div>
		<h1>Awards &amp; Recognition</h1>
		<p>Manage company awards, honors, and certificates.</p>
	</div>
</div>

<div class="admin-card" id="award-form-section">
	<div class="admin-card-header">
		<h2><?= $editAward ? 'Edit Award' : 'Add New Award' ?></h2>
		<?php if ($editAward): ?>
		<a href="award.php" class="btn btn-secondary btn-sm">+ Add Award</a>
		<?php endif; ?>
	</div>
	<div class="admin-card-body">
		<form method="POST" action="award.php" enctype="multipart/form-data" id="awardForm">
			<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
			<input type="hidden" name="section" value="<?= $editAward ? 'award_edit' : 'award_add' ?>">
			<?php if ($editAward): ?>
			<input type="hidden" name="award_id" value="<?= (int)$editAward['id'] ?>">
			<?php endif; ?>
			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Award Title <span class="req">*</span></label>
					<input type="text" name="award_title" class="form-control" value="<?= e($editAward['title'] ?? '') ?>" placeholder="e.g. Best Tech Company 2024" required>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label">Award Paragraph <span class="req">*</span></label>
				<textarea name="award_para" class="form-control" rows="4" placeholder="Describe the award..." required><?= e($editAward['para'] ?? '') ?></textarea>
			</div>
			<div class="form-group">
				<label class="form-label">Award Image <?= $editAward ? '(leave blank to keep current)' : '<span class="req">*</span>' ?></label>
				<input type="file" name="award_image" class="form-control-file" accept="image/*" data-preview="awardImgPreview" <?= $editAward ? '' : 'required' ?>>
				<div class="form-hint">Accepted formats: JPG, JPEG, PNG, WebP, GIF under 5MB.</div>
				<?php if ($editAward && !empty($editAward['image'])): ?>
				<div class="aw-current-img">
					<img id="awardImgPreview" src="<?= e(SITE_URL . '/uploads/award/' . $editAward['image']) ?>" alt="Current award image">
				</div>
				<?php else: ?>
				<img id="awardImgPreview" class="img-preview" style="display:none;" src="" alt="">
				<?php endif; ?>
			</div>
			<div style="display:flex;gap:8px;flex-wrap:wrap;">
				<button type="submit" class="btn btn-primary"><?= $editAward ? '💾 Update Award' : '➕ Add Award' ?></button>
				<?php if ($editAward): ?>
				<a href="award.php" class="btn btn-secondary">Cancel Edit</a>
				<?php endif; ?>
			</div>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Awards (<?= count($awards) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($awards)): ?>
		<p class="empty-state">No awards added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead>
					<tr>
						<th style="width:90px;">Image</th>
						<th>Title</th>
						<th>Paragraph</th>
						<th style="width:140px;">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($awards as $aw): ?>
				<tr>
					<td>
						<?php if (!empty($aw['image'])): ?>
						<img src="<?= e(SITE_URL . '/uploads/award/' . $aw['image']) ?>" alt="<?= e($aw['title']) ?>" class="aw-thumb">
						<?php else: ?>
						<span class="badge badge-grey">No image</span>
						<?php endif; ?>
					</td>
					<td><strong><?= e($aw['title']) ?></strong></td>
					<td class="aw-para-cell"><?= e(mb_substr($aw['para'], 0, 100)) ?><?= mb_strlen($aw['para']) > 100 ? '…' : '' ?></td>
					<td>
						<div style="display:flex;gap:6px;flex-wrap:wrap;">
							<a href="award.php?edit_award=<?= (int)$aw['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
							<form method="POST" action="award.php" style="margin:0;">
								<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
								<input type="hidden" name="section" value="award_delete">
								<input type="hidden" name="award_id" value="<?= (int)$aw['id'] ?>">
								<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete award '<?= e($aw['title']) ?>'? This will permanently remove the image.">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-award.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-award.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>

