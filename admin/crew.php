<?php
/**
 * Admin — Crew Management (Expert & Team Crew)
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$pageTitle = 'Crew Management';

$flash     = '';
$flashType = 'success';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verifyCsrf();
	$action = $_POST['action'] ?? '';

	if ($action === 'save') {
		$id          = (int)($_POST['id'] ?? 0);
		$name        = trim($_POST['name'] ?? '');
		$position    = trim($_POST['position'] ?? '');
		$linkedin    = trim($_POST['linkedin_url'] ?? '');
		$crew_type   = (($_POST['crew_type'] ?? 'Team') === 'Expert') ? 'Expert' : 'Team';
		$intern      = isset($_POST['intern']) ? 1 : 0;
		$sort_order  = (int)($_POST['sort_order'] ?? 0);
		$removePhoto = isset($_POST['remove_photo']) ? 1 : 0;

		if ($crew_type === 'Expert') $intern = 0;

		$photo        = null;
		$uploadFailed = false;

		if (isset($_FILES['profile_photo']) && $_FILES['profile_photo']['error'] === UPLOAD_ERR_OK) {
			$uploaded = uploadFile($_FILES['profile_photo'], 'Crew');
			if ($uploaded !== false) {
				$photo = $uploaded;
			} else {
				$uploadFailed = true;
			}
		}

		if ($uploadFailed) {
			$flash     = 'Profile photo upload failed. Please try a different file.';
			$flashType = 'error';
		} elseif ($name === '' || $position === '') {
			$flash     = 'Name and Position are required.';
			$flashType = 'error';
		} else {
			if ($id > 0) {
				if ($photo !== null) {
					$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, profile_photo=?, intern=?, linkedin_url=?, crew_type=?, sort_order=? WHERE id=?");
					$stmt->execute([$name, $position, $photo, $intern, $linkedin, $crew_type, $sort_order, $id]);
				} elseif ($removePhoto) {
					$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, profile_photo=NULL, intern=?, linkedin_url=?, crew_type=?, sort_order=? WHERE id=?");
					$stmt->execute([$name, $position, $intern, $linkedin, $crew_type, $sort_order, $id]);
				} else {
					$stmt = $pdo->prepare("UPDATE crew SET name=?, position=?, intern=?, linkedin_url=?, crew_type=?, sort_order=? WHERE id=?");
					$stmt->execute([$name, $position, $intern, $linkedin, $crew_type, $sort_order, $id]);
				}
				$flash = 'Crew member updated successfully.';
			} else {
				$stmt = $pdo->prepare("INSERT INTO crew (name, position, profile_photo, intern, linkedin_url, crew_type, sort_order) VALUES (?,?,?,?,?,?,?)");
				$stmt->execute([$name, $position, $photo, $intern, $linkedin, $crew_type, $sort_order]);
				$flash = 'Crew member added successfully.';
			}
		}
	} elseif ($action === 'delete') {
		$id = (int)($_POST['id'] ?? 0);
		if ($id > 0) {
			$stmt = $pdo->prepare("DELETE FROM crew WHERE id=?");
			$stmt->execute([$id]);
			$flash = 'Crew member deleted.';
		}
	}

	header('Location: ' . ADMIN_URL . '/crew.php?msg=' . urlencode($flash) . '&type=' . urlencode($flashType));
	exit;
}

if (isset($_GET['msg'])) {
	$flash     = $_GET['msg'];
	$flashType = ($_GET['type'] ?? 'success') === 'error' ? 'error' : 'success';
}

$expertCrew = getCrewByType($pdo, 'Expert');
$teamCrew   = getCrewByType($pdo, 'Team');

$placeholder = 'https://cdn-icons-png.flaticon.com/512/3237/3237447.png';

include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-crew.css">

<div class="crew-admin">

	<div class="crew-header">
		<div class="crew-header-left">
			<h1><span class="crew-emoji">👥</span> Crew Management</h1>
			<p>Manage Expert Crew and Team Crew members shown on the public site.</p>
		</div>
		<div class="crew-header-right">
			<a href="<?= SITE_URL ?>/CEO.php" target="_blank" class="crew-btn crew-btn-ghost">
				<span>View Page ↗</span>
			</a>
			<button type="button" class="crew-btn crew-btn-primary" data-open-modal data-crew-type="Expert">
				<span>+ Add Expert</span>
			</button>
			<button type="button" class="crew-btn crew-btn-primary crew-btn-team" data-open-modal data-crew-type="Team">
				<span>+ Add Team</span>
			</button>
		</div>
	</div>

	<?php if ($flash !== ''): ?>
		<div class="crew-alert <?= $flashType === 'error' ? 'crew-alert-error' : 'crew-alert-success' ?>">
			<span class="crew-alert-ico"><?= $flashType === 'error' ? '⚠️' : '✅' ?></span>
			<span><?= e($flash) ?></span>
		</div>
	<?php endif; ?>

	<div class="crew-tabs" role="tablist">
		<button type="button" class="crew-tab active" data-tab="expert" role="tab">
			<span class="crew-tab-ico">🏆</span>
			<span>Expert Crew</span>
			<span class="crew-tab-count"><?= count($expertCrew) ?></span>
		</button>
		<button type="button" class="crew-tab" data-tab="team" role="tab">
			<span class="crew-tab-ico">🤝</span>
			<span>Team Crew</span>
			<span class="crew-tab-count"><?= count($teamCrew) ?></span>
		</button>
		<span class="crew-tab-indicator"></span>
	</div>

	<section class="crew-panel active" data-panel="expert">
		<div class="crew-panel-head">
			<h2>Expert Crew <span class="crew-hint">(Ideal total: 9 members)</span></h2>
		</div>

		<?php if (empty($expertCrew)): ?>
			<div class="crew-empty">
				<span class="crew-empty-ico">🏆</span>
				<h3>No Expert Crew members yet</h3>
				<p>Add your first Expert Crew member to get started.</p>
			</div>
		<?php else: ?>
			<div class="crew-grid">
				<?php foreach ($expertCrew as $member): ?>
					<?php
						$photoUrl = !empty($member['profile_photo'])
							? imgUrl($member['profile_photo'], $placeholder)
							: $placeholder;
					?>
					<article class="crew-card" data-id="<?= (int)$member['id'] ?>">
						<div class="crew-card-media">
							<img src="<?= e($photoUrl) ?>" alt="<?= e($member['name']) ?>" loading="lazy"
								onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
							<span class="crew-card-type crew-card-type-expert">Expert</span>
						</div>
						<div class="crew-card-body">
							<h3 class="crew-card-name"><?= e($member['name']) ?></h3>
							<p class="crew-card-pos"><?= e($member['position']) ?></p>
							<?php if (!empty($member['linkedin_url'])): ?>
								<a class="crew-card-linkedin" href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn profile">
									<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg>
								</a>
							<?php endif; ?>
						</div>
						<div class="crew-card-actions">
							<button type="button" class="crew-icon-btn crew-edit"
								data-edit
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								data-position="<?= e($member['position']) ?>"
								data-linkedin="<?= e($member['linkedin_url']) ?>"
								data-intern="<?= (int)$member['intern'] ?>"
								data-crew-type="<?= e($member['crew_type']) ?>"
								data-sort="<?= (int)$member['sort_order'] ?>"
								data-photo="<?= e($photoUrl) ?>"
								aria-label="Edit">
								<svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
							</button>
							<button type="button" class="crew-icon-btn crew-delete"
								data-delete
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								aria-label="Delete">
								<svg viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
							</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

	<section class="crew-panel" data-panel="team">
		<div class="crew-panel-head">
			<h2>Team Crew <span class="crew-hint">(Unlimited members)</span></h2>
		</div>

		<?php if (empty($teamCrew)): ?>
			<div class="crew-empty">
				<span class="crew-empty-ico">🤝</span>
				<h3>No Team Crew members yet</h3>
				<p>Add your first Team Crew member to get started.</p>
			</div>
		<?php else: ?>
			<div class="crew-grid">
				<?php foreach ($teamCrew as $member): ?>
					<?php
						$photoUrl = !empty($member['profile_photo'])
							? imgUrl($member['profile_photo'], $placeholder)
							: $placeholder;
					?>
					<article class="crew-card" data-id="<?= (int)$member['id'] ?>">
						<div class="crew-card-media">
							<img src="<?= e($photoUrl) ?>" alt="<?= e($member['name']) ?>" loading="lazy"
								onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
							<span class="crew-card-type crew-card-type-team">Team</span>
							<?php if ((int)$member['intern'] === 1): ?>
								<span class="crew-card-intern">Intern</span>
							<?php endif; ?>
						</div>
						<div class="crew-card-body">
							<h3 class="crew-card-name"><?= e($member['name']) ?></h3>
							<p class="crew-card-pos"><?= e($member['position']) ?></p>
							<?php if (!empty($member['linkedin_url'])): ?>
								<a class="crew-card-linkedin" href="<?= e($member['linkedin_url']) ?>" target="_blank" rel="noopener" aria-label="LinkedIn profile">
									<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.13 1.45-2.13 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg>
								</a>
							<?php endif; ?>
						</div>
						<div class="crew-card-actions">
							<button type="button" class="crew-icon-btn crew-edit"
								data-edit
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								data-position="<?= e($member['position']) ?>"
								data-linkedin="<?= e($member['linkedin_url']) ?>"
								data-intern="<?= (int)$member['intern'] ?>"
								data-crew-type="<?= e($member['crew_type']) ?>"
								data-sort="<?= (int)$member['sort_order'] ?>"
								data-photo="<?= e($photoUrl) ?>"
								aria-label="Edit">
								<svg viewBox="0 0 24 24"><path d="M3 17.25V21h3.75L17.81 9.94l-3.75-3.75L3 17.25zM20.71 7.04a1 1 0 0 0 0-1.41l-2.34-2.34a1 1 0 0 0-1.41 0l-1.83 1.83 3.75 3.75 1.83-1.83z"/></svg>
							</button>
							<button type="button" class="crew-icon-btn crew-delete"
								data-delete
								data-id="<?= (int)$member['id'] ?>"
								data-name="<?= e($member['name']) ?>"
								aria-label="Delete">
								<svg viewBox="0 0 24 24"><path d="M6 19a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V7H6v12zM19 4h-3.5l-1-1h-5l-1 1H5v2h14V4z"/></svg>
							</button>
						</div>
					</article>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</section>

</div>

<div class="crew-modal" id="crewModal" aria-hidden="true">
	<div class="crew-modal-backdrop" data-close-modal></div>
	<div class="crew-modal-dialog" role="dialog" aria-modal="true" aria-labelledby="crewModalTitle">
		<div class="crew-modal-head">
			<h2 id="crewModalTitle">Add Crew Member</h2>
			<button type="button" class="crew-modal-close" data-close-modal aria-label="Close">&times;</button>
		</div>
		<form method="post" enctype="multipart/form-data" id="crewForm" class="crew-form">
			<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
			<input type="hidden" name="action" value="save">
			<input type="hidden" name="id" id="crewId" value="0">
			<input type="hidden" name="crew_type" id="crewType" value="Expert">
			<input type="hidden" name="remove_photo" id="crewRemovePhoto" value="0">

			<div class="crew-form-grid">
				<div class="crew-form-field crew-form-photo">
					<label>Profile Photo</label>
					<div class="crew-photo-wrap">
						<img id="crewPhotoPreview" src="<?= e($placeholder) ?>" alt="Preview">
						<label for="crewPhotoInput" class="crew-photo-overlay">
							<svg viewBox="0 0 24 24"><path d="M9 16h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H5v-2z"/></svg>
							<span>Upload</span>
						</label>
						<input type="file" name="profile_photo" id="crewPhotoInput" accept="image/*" hidden>
					</div>
					<button type="button" class="crew-photo-remove" id="crewPhotoRemove">Remove photo</button>
				</div>

				<div class="crew-form-fields">
					<div class="crew-form-field">
						<label for="crewName">Name *</label>
						<input type="text" id="crewName" name="name" required maxlength="255" autocomplete="off">
					</div>
					<div class="crew-form-field">
						<label for="crewPosition">Position *</label>
						<input type="text" id="crewPosition" name="position" required maxlength="255" autocomplete="off">
					</div>
					<div class="crew-form-field">
						<label for="crewLinkedin">LinkedIn Link</label>
						<input type="url" id="crewLinkedin" name="linkedin_url" placeholder="https://www.linkedin.com/in/..." autocomplete="off">
					</div>
					<div class="crew-form-row">
						<div class="crew-form-field">
							<label for="crewSort">Sort Order</label>
							<input type="number" id="crewSort" name="sort_order" value="0">
						</div>
						<div class="crew-form-field crew-form-check" id="crewInternWrap">
							<label class="crew-checkbox">
								<input type="checkbox" id="crewIntern" name="intern" value="1">
								<span class="crew-checkmark"></span>
								<span>Intern</span>
							</label>
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
			<div class="crew-form-actions">
				<button type="button" class="crew-btn crew-btn-ghost" data-close-confirm>Cancel</button>
				<button type="submit" class="crew-btn crew-btn-danger">Delete</button>
			</div>
		</form>
	</div>
</div>

<script>
	window.CREW_PLACEHOLDER = <?= json_encode($placeholder) ?>;
</script>
<script src="<?= SITE_URL ?>/assets/js/admin-crew.js"></script>

<?php include __DIR__ . '/layout_bottom.php'; ?>