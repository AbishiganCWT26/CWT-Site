<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$projectId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($projectId <= 0) {
	header('Location: ' . ADMIN_URL . '/solution-projects.php');
	exit;
}

$project = getProjectById($pdo, $projectId);
if (empty($project)) {
	header('Location: ' . ADMIN_URL . '/solution-projects.php');
	exit;
}

$category = getSolutionCategory($pdo, (int)$project['category_id']);
$details  = getProjectDetails($pdo, $projectId);
$mockups  = getProjectMockups($pdo, $projectId);

$msg = '';
$msgType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

	/* ─── 1. Handle mockup delete (bypasses content requirement) ────────── */
	$deleteMockupId = (int)($_POST['delete_mockup_id'] ?? 0);
	if ($deleteMockupId > 0) {
		if (deleteProjectMockup($pdo, $deleteMockupId, $projectId)) {
			$_SESSION['flash_success'] = 'Mockup image removed.';
		} else {
			$_SESSION['flash_error'] = 'Could not remove mockup image.';
		}
		header('Location: project-edit.php?id=' . $projectId);
		exit;
	}

	/* ─── 2. Handle new mockup uploads ──────────────────────────────────── */
	$mockupUploadedCount = 0;
	$mockupFailedCount   = 0;
	if (!empty($_FILES['mockup_files']['name'][0])) {
		$count = count($_FILES['mockup_files']['name']);
		for ($i = 0; $i < $count; $i++) {
			if ($_FILES['mockup_files']['error'][$i] !== UPLOAD_ERR_OK) {
				$mockupFailedCount++;
				continue;
			}
			$file = [
				'name'     => $_FILES['mockup_files']['name'][$i],
				'type'     => $_FILES['mockup_files']['type'][$i],
				'tmp_name' => $_FILES['mockup_files']['tmp_name'][$i],
				'error'    => $_FILES['mockup_files']['error'][$i],
				'size'     => $_FILES['mockup_files']['size'][$i],
			];
			$uploaded = uploadFile($file, 'mockup');
			if ($uploaded) {
				addProjectMockup($pdo, $projectId, $uploaded);
				$mockupUploadedCount++;
			} else {
				$mockupFailedCount++;
			}
		}
	}

	/* ─── 3. Handle content + banner save ───────────────────────────────── */
	$content = $_POST['content'] ?? '';

	// Legacy fields preserved from DB (no longer editable here)
	$client_name  = $details['client_name']  ?? '';
	$industry     = $details['industry']     ?? '';
	$duration     = $details['duration']     ?? '';
	$technologies = $details['technologies'] ?? '';

	$bannerImage = $details['banner_image'] ?? '';
	if (isset($_POST['remove_banner']) && $_POST['remove_banner'] === '1') {
		$bannerImage = '';
	}
	if (!empty($_FILES['banner_image']['name'])) {
		$uploaded = uploadFile($_FILES['banner_image'], 'projects');
		if ($uploaded) $bannerImage = $uploaded;
	}

	$stripContent = trim(strip_tags($content));
	if ($stripContent === '') {
		// Only warn if there was actually a mockup upload happening — otherwise just skip content save
		if ($mockupUploadedCount > 0) {
			$_SESSION['flash_success'] = $mockupUploadedCount . ' mockup image(s) uploaded.';
			if ($mockupFailedCount > 0) {
				$_SESSION['flash_error'] = $mockupFailedCount . ' file(s) failed to upload.';
			}
			header('Location: project-edit.php?id=' . $projectId);
			exit;
		}
		$msg = 'Please add some content before saving.';
		$msgType = 'error';
	} else {
		saveProjectDetails($pdo, $projectId, [
			'banner_image' => $bannerImage,
			'client_name'  => $client_name,
			'industry'     => $industry,
			'duration'     => $duration,
			'technologies' => $technologies,
			'content'      => $content,
		]);

		$flash = 'Project content saved successfully.';
		if ($mockupUploadedCount > 0) {
			$flash .= ' ' . $mockupUploadedCount . ' mockup image(s) uploaded.';
		}
		if ($mockupFailedCount > 0) {
			$msgType = 'error';
			$flash  .= ' ' . $mockupFailedCount . ' file(s) failed to upload.';
		}

		// Refresh in-memory state
		$details = getProjectDetails($pdo, $projectId);

		// Use session flash so a browser refresh won't re-submit
		$_SESSION['flash_success'] = $flash;
		header('Location: project-edit.php?id=' . $projectId);
		exit;
	}
}

// Pick up flash messages
if (!empty($_SESSION['flash_success'])) {
	$msg = $_SESSION['flash_success'];
	$msgType = 'success';
	unset($_SESSION['flash_success']);
} elseif (!empty($_SESSION['flash_error'])) {
	$msg = $_SESSION['flash_error'];
	$msgType = 'error';
	unset($_SESSION['flash_error']);
}

// Always refresh mockups after any action
$mockups = getProjectMockups($pdo, $projectId);

$pageTitle = 'Edit Project Content — ' . $project['project_name'];
include __DIR__ . '/layout_top.php';
?>
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-project-edit.css">

<div class="pe-page-header">
	<div class="pe-page-header-left">
		<a href="solution-projects.php<?= $category ? '?category='.$category['id'] : '' ?>" class="pe-back-link">
			<i class="fa-solid fa-arrow-left"></i> Back to Projects
		</a>
		<div class="pe-title-block">
			<span class="pe-cat-pill" style="background:<?= e($category['color'] ?? '#3b82f6') ?>;">
				<i class="fa-solid <?= e($category['icon'] ?? 'fa-folder') ?>"></i>
				<?= e($category['name'] ?? 'Uncategorized') ?>
			</span>
			<h1><?= e($project['project_name']) ?></h1>
			<p><?= e($project['short_description'] ?? '') ?></p>
		</div>
	</div>
	<div class="pe-page-header-right">
		<a href="<?= SITE_URL ?>/project-detail.php?slug=<?= urlencode($project['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">
			<i class="fa-solid fa-external-link-alt"></i> Preview
		</a>
	</div>
</div>

<?php if ($msg !== ''): ?>
<div class="alert alert-<?= $msgType === 'error' ? 'danger' : 'success' ?>" data-auto-dismiss="2800"><?= e($msg) ?></div>
<?php endif; ?>

<form id="projectDetailsForm" method="POST" action="project-edit.php?id=<?= $projectId ?>" enctype="multipart/form-data" class="pe-layout">

	<!-- Hidden field: set by JS when a mockup delete is requested -->
	<input type="hidden" name="delete_mockup_id" id="deleteMockupId" value="">

	<div class="pe-main">
		<div class="admin-card">
			<div class="admin-card-header">
				<h2>Project Content</h2>
			</div>
			<div class="admin-card-body">
				<label class="form-label">Detailed Content <span class="req">*</span></label>
				<div id="quillToolbar">
					<span class="ql-formats">
						<select class="ql-header">
							<option value="1">Heading 1</option>
							<option value="2">Heading 2</option>
							<option value="3">Heading 3</option>
							<option selected>Normal</option>
						</select>
					</span>
					<span class="ql-formats">
						<button class="ql-bold"></button>
						<button class="ql-italic"></button>
						<button class="ql-underline"></button>
						<button class="ql-strike"></button>
					</span>
					<span class="ql-formats">
						<button class="ql-list" value="ordered"></button>
						<button class="ql-list" value="bullet"></button>
						<button class="ql-blockquote"></button>
					</span>
					<span class="ql-formats">
						<button class="ql-link"></button>
						<button class="ql-image"></button>
						<button class="ql-code-block"></button>
					</span>
					<span class="ql-formats">
						<button class="ql-clean"></button>
					</span>
				</div>
				<div id="quillEditor"><?= $details['content'] ?? '' ?></div>
				<input type="hidden" name="content" id="quillContent" required>
				<p class="form-hint" style="margin-top:10px;">Use headings, lists, quotes, code blocks and images to build the project detail page.</p>
			</div>
		</div>
	</div>

	<div class="pe-side">
		<!-- ─── Banner image ─────────────────────────────────────────────── -->
		<div class="admin-card">
			<div class="admin-card-header"><h2>Banner Image</h2></div>
			<div class="admin-card-body">
				<div class="pe-banner-wrap">
					<img id="bannerPreview" src="<?= imgUrl($details['banner_image'] ?? '') ?>" alt="Banner Preview" style="<?= empty($details['banner_image']) ? 'display:none;' : '' ?>">
					<div class="pe-banner-empty" id="bannerEmpty" style="<?= empty($details['banner_image']) ? '' : 'display:none;' ?>">
						<i class="fa-solid fa-image"></i>
						<span>No banner yet</span>
					</div>
					<label for="bannerInput" class="pe-banner-overlay">
						<i class="fa-solid fa-cloud-arrow-up"></i>
						<span>Upload Banner</span>
					</label>
					<input type="file" name="banner_image" id="bannerInput" accept="image/*" hidden>
				</div>
				<input type="hidden" name="remove_banner" id="removeBannerFlag" value="0">
				<button type="button" class="pe-remove-banner" id="removeBannerBtn">Remove banner</button>
				<p class="form-hint">Recommended: 1600×900 JPG/PNG. Max 5MB.</p>
			</div>
		</div>

		<!-- ─── Project Mockups ──────────────────────────────────────────── -->
		<div class="admin-card">
			<div class="admin-card-header"><h2>Project Mockups</h2></div>
			<div class="admin-card-body">
				<div class="pe-mockup-dropzone" id="mockupDropzone">
					<input type="file" name="mockup_files[]" id="mockupInput" accept="image/*" multiple hidden>
					<div class="pe-mockup-dropzone-inner">
						<i class="fa-solid fa-laptop-code"></i>
						<strong>Upload mockup images</strong>
						<span>Click or drag &amp; drop. PNG / JPG / WEBP. Max 5MB each.</span>
						<span class="pe-mockup-recommend">Recommended size: 1600 × 1000 (16:10)</span>
					</div>
				</div>

				<div class="pe-mockup-grid" id="mockupGrid" <?= empty($mockups) ? 'hidden' : '' ?>>
					<?php foreach ($mockups as $m): ?>
						<div class="pe-mockup-item" data-id="<?= (int)$m['id'] ?>">
							<img src="<?= e(imgUrl($m['image_path'])) ?>" alt="Mockup">
							<button type="button" class="pe-mockup-delete" data-id="<?= (int)$m['id'] ?>" title="Remove">
								<i class="fa-solid fa-xmark"></i>
							</button>
						</div>
					<?php endforeach; ?>
				</div>

				<p class="form-hint pe-mockup-hint">
					<?php if (empty($mockups)): ?>
						No mockups yet. The laptop showcase will be hidden on the public page until you upload at least one image.
					<?php else: ?>
						<?= count($mockups) ?> image(s) will appear in the laptop showcase on the public page.
					<?php endif; ?>
				</p>
			</div>
		</div>

		<!-- ─── Save ─────────────────────────────────────────────────────── -->
		<div class="pe-actions">
			<button type="submit" class="btn btn-primary btn-lg pe-save-btn">
				<i class="fa-solid fa-floppy-disk"></i> Save Project Content
			</button>
		</div>
	</div>

</form>

<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script src="<?= SITE_URL ?>/assets/js/admin-project-edit.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>