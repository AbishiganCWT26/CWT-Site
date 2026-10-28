<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$pageTitle = 'Crew Management';

$flash = '';
$flashType = 'success';
$activeTab = ($_GET['tab'] ?? 'expert') === 'team' ? 'team' : 'expert';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verifyCsrf();
	$action = $_POST['action'] ?? '';
	$activeTab = ($_POST['tab'] ?? 'expert') === 'team' ? 'team' : 'expert';

	try {
		if ($action === 'save') {
			$id          = (int)($_POST['id'] ?? 0);
			$name        = trim($_POST['name'] ?? '');
			$position    = trim($_POST['position'] ?? '');
			$linkedin    = trim($_POST['linkedin_url'] ?? '');
			$crew_type   = (($_POST['crew_type'] ?? 'Team') === 'Expert') ? 'Expert' : 'Team';
		$activeTab = $crew_type === 'Team' ? 'team' : 'expert';
			$intern      = isset($_POST['intern']) ? 1 : 0;
			$sort_order  = (int)($_POST['sort_order'] ?? 0);
			$removePhoto = isset($_POST['remove_photo']) ? (int)$_POST['remove_photo'] : 0;

			$edu_quals = [];
			if (isset($_POST['educational_qualification']) && is_array($_POST['educational_qualification'])) {
				foreach ($_POST['educational_qualification'] as $eq) {
					if (!is_string($eq)) continue;
					$eq = trim($eq);
					if ($eq !== '') $edu_quals[] = $eq;
				}
			}
			$educational_qualification = !empty($edu_quals) ? json_encode($edu_quals) : null;
			$domestic_experience       = trim($_POST['domestic_experience'] ?? '');
			$international_experience  = trim($_POST['international_experience'] ?? '');
			if ($domestic_experience === '') $domestic_experience = null;
			if ($international_experience === '') $international_experience = null;

			if ($crew_type === 'Team') {
				$educational_qualification = null;
				$domestic_experience = null;
				$international_experience = null;
			}

			if ($crew_type === 'Expert') $intern = 0;

			$photo        = null;
			$uploadFailed = false;

			if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK && !empty($_FILES['profile_photo']['name'])) {
				$uploaded = uploadFile($_FILES['profile_photo'], 'Crew');
				if ($uploaded !== false) {
					$photo = $uploaded;
				} else {
					$uploadFailed = true;
				}
			}

			if ($uploadFailed) {
				$flash = 'Profile photo upload failed. Please try a different file.';
				$flashType = 'error';
			} elseif ($name === '' || $position === '' || $sort_order < 1) {
				$flash = 'Name, Position and Sort Order are required.';
				$flashType = 'error';
			} else {
				if ($id > 0) {
					if ($photo !== null) {
						$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, profile_photo=?, intern=?, linkedin_url=?, crew_type=?, sort_order=?, educational_qualification=?, domestic_experience=?, international_experience=? WHERE id=?");
						$stmt->execute([$name, $position, $photo, $intern, $linkedin, $crew_type, $sort_order, $educational_qualification, $domestic_experience, $international_experience, $id]);
					} elseif ($removePhoto === 1) {
						$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, profile_photo=NULL, intern=?, linkedin_url=?, crew_type=?, sort_order=?, educational_qualification=?, domestic_experience=?, international_experience=? WHERE id=?");
						$stmt->execute([$name, $position, $intern, $linkedin, $crew_type, $sort_order, $educational_qualification, $domestic_experience, $international_experience, $id]);
					} else {
						$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, intern=?, linkedin_url=?, crew_type=?, sort_order=?, educational_qualification=?, domestic_experience=?, international_experience=? WHERE id=?");
						$stmt->execute([$name, $position, $intern, $linkedin, $crew_type, $sort_order, $educational_qualification, $domestic_experience, $international_experience, $id]);
					}
					$flash = 'Crew member updated successfully.';
				} else {
					$stmt = $pdo->prepare("INSERT INTO crew (name, position, profile_photo, intern, linkedin_url, crew_type, sort_order, educational_qualification, domestic_experience, international_experience) VALUES (?,?,?,?,?,?,?,?,?,?)");
					$stmt->execute([$name, $position, $photo, $intern, $linkedin, $crew_type, $sort_order, $educational_qualification, $domestic_experience, $international_experience]);
					$flash = 'Crew member added successfully.';
				}
			}
		} elseif ($action === 'delete') {
			$id = (int)($_POST['id'] ?? 0);
			if ($id > 0) {
				$pdo->prepare("DELETE FROM crew WHERE id=?")->execute([$id]);
				$flash = 'Crew member deleted.';
			}
		}
	} catch (Throwable $ex) {
		$flash = 'Something went wrong while saving. Please try again.';
		$flashType = 'error';
	}

	header('Location: ' . ADMIN_URL . '/crew.php?tab=' . $activeTab . '&msg=' . urlencode($flash) . '&type=' . urlencode($flashType));
	exit;
}

if (isset($_GET['msg'])) {
	$flash = $_GET['msg'];
	$flashType = ($_GET['type'] ?? 'success') === 'error' ? 'error' : 'success';
}

$expertCrew = getCrewByType($pdo, 'Expert');
$teamCrew   = getCrewByType($pdo, 'Team');

$nextExpertSort = count($expertCrew) + 1;
$nextTeamSort   = count($teamCrew) + 1;

$placeholder = 'https://cdn-icons-png.flaticon.com/512/3237/3237447.png';

include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-crew.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-crew.css') ?>">

<div class="crew-admin">
	<div class="crew-header">
		<div class="crew-header-left">
			<h1><span class="crew-emoji">👥</span> Crew Management</h1>
			<p>Manage Expert Crew and Team Crew members shown on the public site.</p>
		</div>
		<div class="crew-header-right">
			<a href="<?= SITE_URL ?>/our-crew.php" target="_blank" rel="noopener" class="crew-btn crew-btn-ghost"><span>View Page ↗</span></a>
			<button type="button" class="crew-btn crew-btn-primary" data-open-modal data-crew-type="Expert" data-next-sort="<?= $nextExpertSort ?>"><span>+ Add Expert</span></button>
			<button type="button" class="crew-btn crew-btn-primary crew-btn-team" data-open-modal data-crew-type="Team" data-next-sort="<?= $nextTeamSort ?>"><span>+ Add Team</span></button>
		</div>
	</div>

	<?php if ($flash !== ''): ?>
		<div id="crewFlash" data-msg="<?= e($flash) ?>" data-type="<?= e($flashType) ?>" hidden></div>
	<?php endif; ?>

	<div class="crew-tabs" role="tablist">
		<button type="button" class="crew-tab <?= $activeTab === 'expert' ? 'active' : '' ?>" data-tab="expert" role="tab" aria-selected="<?= $activeTab === 'expert' ? 'true' : 'false' ?>">
			<span class="crew-tab-ico">🏆</span><span>Expert Crew</span><span class="crew-tab-count"><?= count($expertCrew) ?></span>
		</button>
		<button type="button" class="crew-tab <?= $activeTab === 'team' ? 'active' : '' ?>" data-tab="team" role="tab" aria-selected="<?= $activeTab === 'team' ? 'true' : 'false' ?>">
			<span class="crew-tab-ico">🤝</span><span>Team Crew</span><span class="crew-tab-count"><?= count($teamCrew) ?></span>
		</button>
	</div>

	<section class="crew-panel <?= $activeTab === 'expert' ? 'active' : '' ?>" data-panel="expert">
		<?php if (empty($expertCrew)): ?>
			<div class="crew-empty"><span class="crew-empty-ico">🏆</span><h3>No Expert Crew members yet</h3><p>Add your first Expert Crew member to get started.</p></div>
		<?php else: ?>
			<div class="crew-grid">
				<?php foreach ($expertCrew as $member): ?>
					<?php $photoUrl = !empty($member['profile_photo']) ? imgUrl($member['profile_photo'], $placeholder) : $placeholder; ?>
					<article class="crew-card" data-id="<?= (int)$member['id'] ?>">
						<div class="crew-card-media">
							<img src="<?= e($photoUrl) ?>" alt="<?= e($member['name']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
							<span class="crew-card-type crew-card-type-expert">Expert</span>
						</div>
						<div class="crew-card-body">
							<h3 class="crew-card-name"><?= e($member['name']) ?></h3>
							<p class="crew-card-pos"><?= e($member['position']) ?></p>
							<?php if (!empty($member['linkedin_url'])): ?>
								<a class="crew-card-linkedin" href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener">in</a>
							<?php endif; ?>
						</div>
						<div class="crew-card-actions">
							<button type="button" class="crew-icon-btn crew-edit"
								data-edit
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								data-education="<?= e($member['educational_qualification'] ?? '[]') ?>"
								data-domestic="<?= e($member['domestic_experience'] ?? '') ?>"
								data-international="<?= e($member['international_experience'] ?? '') ?>"
								data-position="<?= e($member['position']) ?>"
								data-linkedin="<?= e($member['linkedin_url'] ?? '') ?>"
								data-intern="<?= (int)$member['intern'] ?>"
								data-crew-type="<?= e($member['crew_type']) ?>"
								data-sort="<?= (int)$member['sort_order'] ?>"
								data-photo="<?= e($photoUrl) ?>" aria-label="Edit">✎</button>
							<button type="button" class="crew-icon-btn crew-delete"
								data-delete
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>" aria-label="Delete">🗑</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<section class="crew-panel <?= $activeTab === 'team' ? 'active' : '' ?>" data-panel="team">
		<?php if (empty($teamCrew)): ?>
			<div class="crew-empty"><span class="crew-empty-ico">🤝</span><h3>No Team Crew members yet</h3><p>Add your first Team Crew member to get started.</p></div>
		<?php else: ?>
			<div class="crew-grid">
				<?php foreach ($teamCrew as $member): ?>
					<?php $photoUrl = !empty($member['profile_photo']) ? imgUrl($member['profile_photo'], $placeholder) : $placeholder; ?>
					<article class="crew-card" data-id="<?= (int)$member['id'] ?>">
						<div class="crew-card-media">
							<img src="<?= e($photoUrl) ?>" alt="<?= e($member['name']) ?>" loading="lazy" onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
							<span class="crew-card-type crew-card-type-team">Team</span>
							<?php if ((int)$member['intern'] === 1): ?><span class="crew-card-intern">Intern</span><?php endif; ?>
						</div>
						<div class="crew-card-body">
							<h3 class="crew-card-name"><?= e($member['name']) ?></h3>
							<p class="crew-card-pos"><?= e($member['position']) ?></p>
							<?php if (!empty($member['linkedin_url'])): ?>
								<a class="crew-card-linkedin" href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener">in</a>
							<?php endif; ?>
						</div>
						<div class="crew-card-actions">
							<button type="button" class="crew-icon-btn crew-edit"
								data-edit
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								data-position="<?= e($member['position']) ?>"
								data-linkedin="<?= e($member['linkedin_url'] ?? '') ?>"
								data-intern="<?= (int)$member['intern'] ?>"
								data-crew-type="<?= e($member['crew_type']) ?>"
								data-sort="<?= (int)$member['sort_order'] ?>"
								data-photo="<?= e($photoUrl) ?>" aria-label="Edit">✎</button>
							<button type="button" class="crew-icon-btn crew-delete"
								data-delete
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>" aria-label="Delete">🗑</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>
</div>

<div class="crew-modal" id="crewModal" aria-hidden="true">
	<div class="crew-modal-backdrop" data-close-modal></div>
	<div class="crew-modal-dialog" role="dialog" aria-modal="true">
		<div class="crew-modal-head">
			<h2 id="crewModalTitle">Add Crew Member</h2>
			<button type="button" class="crew-modal-close" data-close-modal aria-label="Close">&times;</button>
		</div>
		<form method="post" enctype="multipart/form-data" id="crewForm" class="crew-form">
			<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="id" id="crewId" value="0">
			<input type="hidden" name="crew_type" id="crewType" value="Expert">
			<input type="hidden" name="remove_photo" id="crewRemovePhotoFlag" value="0">

			<div class="crew-modal-body">
				<div class="crew-form-grid">
					<div class="crew-form-field crew-form-photo">
					<label>Profile Photo</label>
					<div class="crew-photo-wrap">
						<img id="crewPhotoPreview" src="<?= e($placeholder) ?>" alt="Preview">
						<input type="file" name="profile_photo" id="crewPhotoInput" accept="image/*" hidden>
					</div>
					<div class="crew-photo-actions">
						<button type="button" id="crewPhotoUploadBtn" class="crew-photo-upload"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 16V4m-5 5 5-5 5 5M4 16v4h16v-4"/></svg>Upload photo</button>
						<button type="button" class="crew-photo-action" id="crewPhotoCropBtn" hidden>Crop photo</button>
						<button type="button" class="crew-photo-action crew-photo-delete" id="crewPhotoRemoveBtn" hidden>Remove</button>
					</div>
					<p class="crew-photo-hint">Choose a photo, then crop it to frame the face.</p>
					<p id="crewPhotoStatus" class="crew-photo-status" role="status" aria-live="polite"></p>
				</div>
				<div class="crew-form-fields">
						<div class="crew-form-field">
							<label for="crewName">Name *</label>
							<input type="text" id="crewName" name="name" required maxlength="255" placeholder="e.g. John Doe">
						</div>
						<div class="crew-form-field">
							<label for="crewPosition">Position *</label>
							<input type="text" id="crewPosition" name="position" required maxlength="255" placeholder="e.g. Senior Architect">
						</div>
						<div class="crew-form-field">
							<label for="crewLinkedin">LinkedIn Link</label>
							<input type="url" id="crewLinkedin" name="linkedin_url" placeholder="https://www.linkedin.com/in/...">
						</div>

						<div id="expertFieldsWrap" class="crew-expert-fields">
							<div class="crew-form-field">
								<label>Educational Qualifications</label>
								<div id="crewEduList" class="crew-edu-list">
									<div class="crew-edu-item">
										<input type="text" name="educational_qualification[]" maxlength="255" placeholder="e.g. B.Sc. in Computer Science">
										<button type="button" class="crew-btn-icon-add" id="addEduBtn" title="Add Qualification">+</button>
									</div>
								</div>
							</div>
							<div class="crew-form-row">
								<div class="crew-form-field">
									<label for="crewDomestic">Domestic Experience</label>
									<input type="text" id="crewDomestic" name="domestic_experience" maxlength="255" placeholder="e.g. 5 Years">
								</div>
								<div class="crew-form-field">
									<label for="crewIntl">International Experience</label>
									<input type="text" id="crewIntl" name="international_experience" maxlength="255" placeholder="e.g. 3 Years">
								</div>
							</div>
						</div>

						<div class="crew-form-row crew-sort-row">
							<div class="crew-form-field crew-sort-field">
								<label for="crewSort">Sort Order *</label>
								<input type="number" id="crewSort" name="sort_order" value="1" min="1" required>
							</div>
							<div class="crew-form-field crew-form-check" id="crewInternWrap">
								<label class="crew-checkbox">
									<input type="checkbox" id="crewIntern" name="intern" value="1">
									<span>Intern Member</span>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="crew-form-actions">
				<button type="button" class="crew-btn crew-btn-ghost" data-close-modal>Cancel</button>
				<button type="submit" class="crew-btn crew-btn-primary">Save Member</button>
			</div>
		</form>
	</div>
</div>

<div class="crew-confirm" id="crewConfirm" aria-hidden="true">
	<div class="crew-modal-backdrop" data-close-confirm></div>
	<div class="crew-confirm-dialog" role="dialog" aria-modal="true">
		<div class="crew-confirm-ico">⚠️</div>
		<h3>Delete crew member?</h3>
		<p>You're about to permanently delete <strong id="crewConfirmName">this member</strong>. This action cannot be undone.</p>
		<form method="post" id="crewDeleteForm">
			<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
			<input type="hidden" name="action" value="delete">
			<input type="hidden" name="id" id="crewDeleteId" value="0">
			<input type="hidden" name="tab" id="crewDeleteTab" value="<?= e($activeTab) ?>">
			<div class="crew-form-actions">
				<button type="button" class="crew-btn crew-btn-ghost" data-close-confirm>Cancel</button>
				<button type="submit" class="crew-btn crew-btn-danger">Delete</button>
			</div>
		</form>
	</div>
</div>

<dialog id="crewPhotoCropDialog" class="crew-crop-dialog" aria-labelledby="crewCropTitle" aria-describedby="crewCropHelp">
 <div class="crew-crop-header"><h2 id="crewCropTitle">Crop profile photo</h2><button type="button" class="crew-modal-close" data-cancel-crop aria-label="Cancel crop">&times;</button></div>
 <p id="crewCropHelp">Drag the photo to reposition it. Use the zoom slider to frame the face.</p>
 <canvas id="crewCropCanvas" width="600" height="600" tabindex="0" aria-label="Square photo crop preview. Use arrow keys to reposition the photo."></canvas>
 <label class="crew-crop-zoom" for="crewCropZoom"><span>Zoom</span><input id="crewCropZoom" type="range" min="1" max="3" step="0.01" value="1"><output id="crewCropZoomValue" for="crewCropZoom">100%</output></label>
 <p id="crewCropStatus" class="crew-photo-status" role="status" aria-live="polite"></p>
 <div class="crew-crop-tools"><button type="button" class="crew-btn crew-btn-ghost" id="crewCropReset">Reset position</button><button type="button" class="crew-btn crew-btn-ghost" id="crewCropOriginal">Use original</button></div>
 <div class="crew-crop-footer"><button type="button" class="crew-btn crew-btn-ghost" data-cancel-crop>Cancel</button><button type="button" class="crew-btn crew-btn-primary" id="crewCropApply">Use cropped photo</button></div>
</dialog>
<script src="<?= SITE_URL ?>/assets/js/crew-photo-crop.js?v=<?= filemtime(__DIR__ . '/../assets/js/crew-photo-crop.js') ?>"></script>
<script>window.CREW_PLACEHOLDER = <?= json_encode($placeholder) ?>;</script>
<script src="<?= SITE_URL ?>/assets/js/admin-crew.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-crew.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>
