<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	try {
		if ($action === 'add' || $action === 'edit') {
			$id     = (int)($_POST['id'] ?? 0);
			$name   = trim($_POST['client_name'] ?? '');
			$text   = trim($_POST['feedback'] ?? '');
			$date   = trim($_POST['feedback_date'] ?? '');
			$active = isset($_POST['is_active']) ? 1 : 0;

			$oldPhoto = '';
			if ($action === 'edit' && $id > 0) {
				$existing = $pdo->prepare("SELECT photo_path FROM client_feedback WHERE id=?");
				$existing->execute([$id]);
				$oldPhoto = $existing->fetchColumn() ?: '';
			}

			$photoPath = $oldPhoto;
			$newPhotoUploaded = false;

			if (!empty($_FILES['photo']['name'])) {
				$newPhoto = uploadFile($_FILES['photo'], 'feedback');
				if ($newPhoto) {
					$photoPath = $newPhoto;
					$newPhotoUploaded = true;
				} else {
					setFlash('error', 'Client photo could not be uploaded. Please ensure it is a valid image under 5MB.');
					header('Location: ' . ADMIN_URL . '/feedback.php' . ($action === 'edit' ? '?edit=' . $id : ''));
					exit;
				}
			}

			if ($name === '' || $text === '' || $date === '' || $photoPath === '') {
				if ($newPhotoUploaded && $photoPath !== '') {
					deleteUploadedFile($pdo, $photoPath, 'feedback');
				}
				setFlash('warning', 'Client Name, Feedback Text, Feedback Date and Client Photo are required.');
				header('Location: ' . ADMIN_URL . '/feedback.php' . ($action === 'edit' ? '?edit=' . $id : ''));
				exit;
			}

			if ($action === 'add') {
				$stmt = $pdo->prepare("INSERT INTO client_feedback (client_name, feedback, feedback_date, photo_path, is_active) VALUES (?,?,?,?,?)");
				$ok = $stmt->execute([$name, $text, $date, $photoPath, $active]);
				if ($ok) {
					setFlash('success', 'Testimonial added successfully.');
				} else {
					deleteUploadedFile($pdo, $photoPath, 'feedback');
					setFlash('error', 'Failed to save testimonial to database.');
				}
			} else {
				$stmt = $pdo->prepare("UPDATE client_feedback SET client_name=?, feedback=?, feedback_date=?, photo_path=?, is_active=? WHERE id=?");
				$ok = $stmt->execute([$name, $text, $date, $photoPath, $active, $id]);
				if ($ok) {
					if ($newPhotoUploaded && $oldPhoto !== '' && $oldPhoto !== $photoPath) {
						deleteUploadedFile($pdo, $oldPhoto, 'feedback');
					}
					setFlash('success', 'Testimonial updated successfully.');
				} else {
					if ($newPhotoUploaded && $photoPath !== '') {
						deleteUploadedFile($pdo, $photoPath, 'feedback');
					}
					setFlash('error', 'Failed to update testimonial.');
				}
			}
		} elseif ($action === 'toggle') {
			$id = (int)$_POST['id'];
			$active = (int)$_POST['is_active'];
			$pdo->prepare("UPDATE client_feedback SET is_active=? WHERE id=?")->execute([$active, $id]);
			setFlash('success', $active ? 'Testimonial activated.' : 'Testimonial deactivated.');
		} elseif ($action === 'delete') {
			$id = (int)$_POST['id'];
			if (deleteFeedbackRecordWithFiles($pdo, $id)) {
				setFlash('success', 'Testimonial deleted successfully.');
			} else {
				setFlash('error', 'Could not delete testimonial.');
			}
		}
	} catch (Throwable $e) {
		error_log('[feedback.php] ' . $e->getMessage());
		setFlash('error', 'An error occurred while processing the request.');
	}

	header('Location: ' . ADMIN_URL . '/feedback.php');
	exit;
}

$editFeedback = null;
if (isset($_GET['edit'])) {
	$id = (int)$_GET['edit'];
	$stmt = $pdo->prepare("SELECT * FROM client_feedback WHERE id=?");
	$stmt->execute([$id]);
	$editFeedback = $stmt->fetch();
}

$feedbacks = $pdo->query("SELECT * FROM client_feedback ORDER BY feedback_date DESC, id DESC")->fetchAll();
$pageTitle = 'Client Feedback';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Client Feedback</h1>
		<p>Manage customer stories shown on the Clients Say page.</p>
	</div>
	<?php if ($editFeedback): ?>
	<a href="feedback.php" class="btn btn-secondary btn-sm">+ New Testimonial</a>
	<?php endif; ?>
</div>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editFeedback ? 'Edit Testimonial' : 'Add New Testimonial' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="feedback.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?= $editFeedback ? 'edit' : 'add' ?>">
			<?php if ($editFeedback): ?><input type="hidden" name="id" value="<?= $editFeedback['id'] ?>"><?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Client Name <span class="req">*</span></label>
					<input type="text" name="client_name" class="form-control" value="<?= e($editFeedback['client_name'] ?? '') ?>" required>
				</div>
				<div class="form-group">
					<label class="form-label">Feedback Date <span class="req">*</span></label>
					<input type="date" name="feedback_date" class="form-control" value="<?= e($editFeedback['feedback_date'] ?? date('Y-m-d')) ?>" required>
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Feedback Text <span class="req">*</span></label>
				<textarea name="feedback" class="form-control" rows="4" required><?= e($editFeedback['feedback'] ?? '') ?></textarea>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Client Photo <?= $editFeedback ? '' : '<span class="req">*</span>' ?></label>
					<input type="file" name="photo" class="form-control-file" accept="image/*" data-preview="fbPhoto" <?= $editFeedback ? '' : 'required' ?>>
					<img id="fbPhoto" src="<?= imgUrl($editFeedback['photo_path'] ?? '') ?>" class="img-preview" style="<?= empty($editFeedback['photo_path']) ? 'display:none;border-radius:50%;' : 'border-radius:50%;' ?>">
				</div>
				<div class="form-group" style="margin-top:28px;">
					<label class="form-check">
						<input type="checkbox" name="is_active" value="1" <?= (!isset($editFeedback) || $editFeedback['is_active']) ? 'checked' : '' ?>>
						<span>Active (Show on website)</span>
					</label>
				</div>
			</div>

			<button type="submit" class="btn btn-primary"><?= $editFeedback ? '💾 Update Testimonial' : '➕ Add Testimonial' ?></button>
			<?php if ($editFeedback): ?>
			<a href="feedback.php" class="btn btn-secondary" style="margin-left:8px;">Cancel Edit</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Testimonials (<?= count($feedbacks) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($feedbacks)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No testimonials added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead>
					<tr>
						<th>Photo</th>
						<th>Client Name</th>
						<th>Feedback Excerpt</th>
						<th>Date</th>
						<th>Status</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($feedbacks as $fb): ?>
					<tr>
						<td>
							<?php $pu = imgUrl($fb['photo_path']); ?>
							<?php if ($pu): ?>
								<img src="<?= e($pu) ?>" alt="Photo" style="border-radius:50%;width:40px;height:40px;object-fit:cover;">
							<?php else: ?>
								<div style="width:40px;height:40px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:700;color:#64748b;font-size:12px;">
									<?= mb_strtoupper(mb_substr($fb['client_name'], 0, 1)) ?>
								</div>
							<?php endif; ?>
						</td>
						<td><strong><?= e($fb['client_name']) ?></strong></td>
						<td style="max-width:250px;">
							<div style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:12px;color:var(--text-muted);line-height:1.45;">"<?= e($fb['feedback']) ?>"</div>
						</td>
						<td><?= $fb['feedback_date'] ? date('M j, Y', strtotime($fb['feedback_date'])) : '—' ?></td>
						<td>
							<form method="POST" style="margin:0;">
								<input type="hidden" name="action" value="toggle">
								<input type="hidden" name="id" value="<?= $fb['id'] ?>">
								<input type="hidden" name="is_active" value="<?= $fb['is_active'] ? 0 : 1 ?>">
								<button type="submit" class="badge <?= $fb['is_active'] ? 'badge-success' : 'badge-warning' ?>" style="border:none;cursor:pointer;">
									<?= $fb['is_active'] ? 'Active' : 'Inactive' ?>
								</button>
							</form>
						</td>
						<td>
							<div style="display:flex;gap:6px;flex-wrap:wrap;">
								<a href="feedback.php?edit=<?= $fb['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" action="feedback.php" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $fb['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete testimonial from <?= e($fb['client_name']) ?>? This will permanently delete the client's photo.">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-feedback.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-feedback.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>
