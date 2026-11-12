<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
		setFlash('error', 'The uploaded file is too large. Please upload an image under 10MB and a 3D file under 10MB.');
		header('Location: ' . ADMIN_URL . '/award.php');
		exit;
	}

	$section = $_POST['section'] ?? $_POST['action'] ?? '';

	try {
		if ($section === 'award_add' || $section === 'add') {
			verifyCsrf();
			$category = (string)($_POST['award_category'] ?? '');
			$title    = trim($_POST['award_title'] ?? '');
			$para     = trim($_POST['award_para'] ?? '');

			if (!isset(award_categories()[$category]) || $title === '' || $para === '' || empty($_FILES['award_image']['name'])) {
				setFlash('error', 'Category, title, paragraph, and image are all required.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$imgName = handle_award_image_upload($_FILES['award_image']);
			if ($imgName === false) {
				setFlash('error', 'Image could not be uploaded. Use jpg, jpeg, png, webp, or gif under 10MB.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$file3dName = null;
			if ($category === 'award' && !empty($_FILES['award_3d_file']['name'])) {
				$file3dName = handle_award_3d_upload($_FILES['award_3d_file']);
				if ($file3dName === false) {
					deleteUploadedFile($pdo, 'award/' . $imgName, 'award');
					setFlash('error', '3D file could not be uploaded. Use an html file under 10MB.');
					header('Location: ' . ADMIN_URL . '/award.php');
					exit;
				}
			}

			create_award($pdo, $imgName, $title, $para, $file3dName, $category);
			setFlash('success', award_categories()[$category] . ' added successfully.');
			header('Location: ' . ADMIN_URL . '/award.php');
			exit;

		} elseif ($section === 'award_edit' || $section === 'edit') {
			verifyCsrf();
			$id       = (int)($_POST['award_id'] ?? $_POST['id'] ?? 0);
			$category = (string)($_POST['award_category'] ?? '');
			$title    = trim($_POST['award_title'] ?? '');
			$para     = trim($_POST['award_para'] ?? '');

			if ($id < 1 || !isset(award_categories()[$category]) || $title === '' || $para === '') {
				setFlash('error', 'Category, title, and paragraph are required.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$existing = get_award_by_id($pdo, $id);
			if (!$existing) {
				setFlash('error', 'Record not found.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$imgName    = $existing['image'];
			$oldImgName = '';
			if (!empty($_FILES['award_image']['name'])) {
				$newImg = handle_award_image_upload($_FILES['award_image']);
				if ($newImg === false) {
					setFlash('error', 'Image could not be uploaded. Use jpg, jpeg, png, webp, or gif under 10MB.');
					header('Location: ' . ADMIN_URL . '/award.php?edit_award=' . $id);
					exit;
				}
				$oldImgName = (string)$existing['image'];
				$imgName    = $newImg;
			}

			$file3dName    = $existing['3d_file'] ?? null;
			$oldFile3dName = '';
			if ($category !== 'award') {
				$oldFile3dName = (string)($existing['3d_file'] ?? '');
				$file3dName    = null;
			} elseif (!empty($_FILES['award_3d_file']['name'])) {
				$newFile3d = handle_award_3d_upload($_FILES['award_3d_file']);
				if ($newFile3d === false) {
					if ($oldImgName !== '') {
						deleteUploadedFile($pdo, 'award/' . $imgName, 'award');
					}
					setFlash('error', '3D file could not be uploaded. Use an html file under 10MB.');
					header('Location: ' . ADMIN_URL . '/award.php?edit_award=' . $id);
					exit;
				}
				$oldFile3dName = (string)($existing['3d_file'] ?? '');
				$file3dName    = $newFile3d;
			} elseif (!empty($_POST['remove_3d_file'])) {
				$oldFile3dName = (string)($existing['3d_file'] ?? '');
				$file3dName    = null;
			}

			update_award($pdo, $id, $imgName, $title, $para, $file3dName, $category);

			if ($oldImgName !== '') {
				deleteUploadedFile($pdo, 'award/' . $oldImgName, 'award');
			}
			if ($oldFile3dName !== '') {
				delete_award_3d_file($pdo, $oldFile3dName);
			}

			setFlash('success', award_categories()[$category] . ' updated successfully.');
			header('Location: ' . ADMIN_URL . '/award.php');
			exit;

		} elseif ($section === 'award_delete' || $section === 'delete') {
			verifyCsrf();
			$id = (int)($_POST['award_id'] ?? $_POST['id'] ?? 0);
			if ($id < 1) {
				setFlash('error', 'Invalid record.');
				header('Location: ' . ADMIN_URL . '/award.php');
				exit;
			}

			$existing = get_award_by_id($pdo, $id);
			if ($existing) {
				delete_award($pdo, $id);
				setFlash('success', 'Record deleted successfully.');
			} else {
				setFlash('error', 'Record not found.');
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
$categories = award_categories();
$editAward = null;
if (isset($_GET['edit_award'])) {
	$editAward = get_award_by_id($pdo, (int)$_GET['edit_award']);
}
$selectedCategory = (string)($editAward['category'] ?? 'award');
if (!isset($categories[$selectedCategory])) $selectedCategory = 'award';
$is3dAllowed = $selectedCategory === 'award';

$pageTitle = 'Awards';
include __DIR__ . '/layout_top.php';
?>

<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-award.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-award.css') ?>">

<div class="page-header">
	<div>
		<h1>Awards &amp; Recognition</h1>
		<p>Manage company awards, certifications, and client appreciations.</p>
	</div>
</div>

<div class="admin-card" id="award-form-section">
	<div class="admin-card-header">
		<h2><?= $editAward ? 'Edit Recognition' : 'Add New Recognition' ?></h2>
		<?php if ($editAward): ?>
		<a href="award.php" class="btn btn-secondary btn-sm">+ Add Recognition</a>
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
					<label class="form-label" for="awardCategory">Recognition Category <span class="req">*</span></label>
					<select name="award_category" id="awardCategory" class="form-control" required>
						<?php foreach ($categories as $catKey => $catLabel): ?>
						<option value="<?= e($catKey) ?>"<?= $selectedCategory === $catKey ? ' selected' : '' ?>><?= e($catLabel) ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>
			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Title <span class="req">*</span></label>
					<input type="text" name="award_title" class="form-control" value="<?= e($editAward['title'] ?? '') ?>" placeholder="e.g. Best Tech Company 2024" required>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label">Paragraph <span class="req">*</span></label>
				<textarea name="award_para" class="form-control" rows="4" placeholder="Describe the recognition..." required><?= e($editAward['para'] ?? '') ?></textarea>
			</div>
			<div class="form-group">
				<label class="form-label">Image <?= $editAward ? '(leave blank to keep current)' : '<span class="req">*</span>' ?></label>
				<input type="file" name="award_image" class="form-control-file" accept=".jpg,.jpeg,.png,.webp,.gif,image/jpeg,image/png,image/webp,image/gif" data-preview="awardImgPreview" <?= $editAward ? '' : 'required' ?>>
				<div class="form-hint">Accepted formats: JPG, JPEG, PNG, WebP, GIF under 10MB.</div>
				<?php if ($editAward && !empty($editAward['image'])): ?>
				<div class="aw-current-img">
					<img id="awardImgPreview" src="<?= e(SITE_URL . '/uploads/award/' . $editAward['image']) ?>" alt="Current image">
				</div>
				<?php else: ?>
				<img id="awardImgPreview" class="img-preview" style="display:none;" src="" alt="">
				<?php endif; ?>
			</div>
			<div class="form-group" id="award3dGroup"<?= $is3dAllowed ? '' : ' hidden' ?>>
				<label class="form-label">3D Model File <?= $editAward ? '(optional, leave blank to keep current)' : '(optional)' ?></label>
				<input type="file" name="award_3d_file" id="award3dFile" class="form-control-file" accept=".html,.htm,text/html"<?= $is3dAllowed ? '' : ' disabled' ?>>
				<div class="form-hint">HTML file containing the three.js 3D award model. Max 10MB. When present it is shown on the Recognition page instead of the image. Available for Awards only.</div>
				<div class="aw-3d-info" id="award3dInfo"<?= ($editAward && !empty($editAward['3d_file'])) ? '' : ' style="display:none;"' ?>>
					<span class="aw-3d-badge"><i class="fa-solid fa-cube" aria-hidden="true"></i> 3D</span>
					<span class="aw-3d-name" id="award3dName"><?= e($editAward['3d_file'] ?? '') ?></span>
					<?php if ($editAward && !empty($editAward['3d_file'])): ?>
					<a href="<?= e(SITE_URL . '/uploads/award/3D/' . rawurlencode($editAward['3d_file'])) ?>" target="_blank" rel="noopener" class="aw-3d-link">Preview</a>
					<?php endif; ?>
				</div>
				<?php if ($editAward && !empty($editAward['3d_file'])): ?>
				<label class="aw-3d-remove" for="award3dRemove">
					<input type="checkbox" name="remove_3d_file" id="award3dRemove" value="1"<?= $is3dAllowed ? '' : ' disabled' ?>>
					<span>Remove current 3D model</span>
				</label>
				<?php endif; ?>
			</div>
			<div class="aw-form-actions" style="display:flex;gap:8px;flex-wrap:wrap;">
				<button type="submit" class="btn btn-primary"><?= $editAward ? '💾 Update Recognition' : '➕ Add Recognition' ?></button>
				<?php if ($editAward): ?>
				<a href="award.php" class="btn btn-secondary">Cancel Edit</a>
				<?php endif; ?>
			</div>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Recognitions (<?= count($awards) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($awards)): ?>
		<p class="empty-state">No records added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead>
					<tr>
						<th style="width:90px;">Image</th>
						<th style="width:160px;">Category</th>
						<th>Title</th>
						<th>Paragraph</th>
						<th style="width:90px;">3D</th>
						<th style="width:140px;">Actions</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($awards as $aw):
					$rowCategory = (string)($aw['category'] ?? 'award');
					if (!isset($categories[$rowCategory])) $rowCategory = 'award';
				?>
				<tr>
					<td>
						<?php if (!empty($aw['image'])): ?>
						<img src="<?= e(SITE_URL . '/uploads/award/' . $aw['image']) ?>" alt="<?= e($aw['title']) ?>" class="aw-thumb">
						<?php else: ?>
						<span class="badge badge-grey">No image</span>
						<?php endif; ?>
					</td>
					<td><span class="aw-cat aw-cat-<?= e($rowCategory) ?>"><?= e($categories[$rowCategory]) ?></span></td>
					<td><strong><?= e($aw['title']) ?></strong></td>
					<td class="aw-para-cell"><?= e(mb_substr($aw['para'], 0, 100)) ?><?= mb_strlen($aw['para']) > 100 ? '…' : '' ?></td>
					<td>
						<?php if ($rowCategory !== 'award'): ?>
						<span class="badge badge-grey">N/A</span>
						<?php elseif (!empty($aw['3d_file'])): ?>
						<a href="<?= e(SITE_URL . '/uploads/award/3D/' . rawurlencode($aw['3d_file'])) ?>" target="_blank" rel="noopener" class="aw-3d-badge">3D</a>
						<?php else: ?>
						<span class="badge badge-grey">None</span>
						<?php endif; ?>
					</td>
					<td>
						<div style="display:flex;gap:6px;flex-wrap:wrap;">
							<a href="award.php?edit_award=<?= (int)$aw['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
							<form method="POST" action="award.php" style="margin:0;">
								<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
								<input type="hidden" name="section" value="award_delete">
								<input type="hidden" name="award_id" value="<?= (int)$aw['id'] ?>">
								<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($aw['title']) ?>'? This will permanently remove the image and any 3D file.">Delete</button>
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