<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';
	$id = (int)($_POST['id'] ?? 0);

	if ($action === 'toggle_read' && $id > 0) {
		$pdo->prepare("UPDATE client_meetings SET is_read = IF(is_read = 1, 0, 1) WHERE id = ?")->execute([$id]);
		header('Location: ' . ADMIN_URL . '/client-meeting.php');
		exit;
	}

	if ($action === 'mark_all_read') {
		$pdo->exec("UPDATE client_meetings SET is_read = 1 WHERE is_read = 0");
		header('Location: ' . ADMIN_URL . '/client-meeting.php');
		exit;
	}

	if ($action === 'delete' && $id > 0) {
		$pdo->prepare("DELETE FROM client_meetings WHERE id = ?")->execute([$id]);
		header('Location: ' . ADMIN_URL . '/client-meeting.php');
		exit;
	}
}

$meetings = $pdo->query("SELECT * FROM client_meetings ORDER BY is_read ASC, created_at DESC")->fetchAll();

$totalMeetings = count($meetings);
$unreadMeetings = 0;
foreach ($meetings as $m) {
	if ((int)$m['is_read'] === 0) $unreadMeetings++;
}

$pageTitle = 'Client Meetings';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-client-meeting.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="cm-page-header">
	<div class="cm-page-header-left">
		<h1 class="cm-page-title">
			<span class="cm-page-title-ico"><i class="fa-solid fa-handshake"></i></span>
			Client Meetings
		</h1>
		<p class="cm-page-sub">Contact requests submitted through the public contact form.</p>
	</div>
	<div class="cm-page-header-right">
		<div class="cm-stat">
			<span class="cm-stat-value"><?= $totalMeetings ?></span>
			<span class="cm-stat-label">Total</span>
		</div>
		<div class="cm-stat cm-stat-accent">
			<span class="cm-stat-value"><?= $unreadMeetings ?></span>
			<span class="cm-stat-label">Unread</span>
		</div>
		<?php if ($unreadMeetings > 0): ?>
		<form method="post" class="cm-inline-form" onsubmit="return confirm('Mark all as read?');">
			<input type="hidden" name="action" value="mark_all_read">
			<button type="submit" class="cm-btn cm-btn-ghost">
				<i class="fa-solid fa-check-double"></i>
				<span>Mark All Read</span>
			</button>
		</form>
		<?php endif; ?>
	</div>
</div>

<?php if (empty($meetings)): ?>
<div class="cm-empty">
	<span class="cm-empty-icon"><i class="fa-solid fa-inbox"></i></span>
	<h3 class="cm-empty-title">No meetings yet</h3>
	<p class="cm-empty-text">Client submissions will appear here once they come in.</p>
</div>
<?php else: ?>
<div class="cm-list">

	<?php foreach ($meetings as $m): ?>
	<?php
		$isRead = (int)$m['is_read'] === 1;
		$initial = mb_strtoupper(mb_substr($m['client_name'], 0, 1));
		$date = !empty($m['created_at']) ? date('M j, Y · g:i A', strtotime($m['created_at'])) : '';
		$websiteUrl = $m['website'] ?? '';
		if (!empty($websiteUrl) && !preg_match('/^https?:\/\//i', $websiteUrl)) {
			$websiteUrl = 'https://' . $websiteUrl;
		}
	?>
	<article class="cm-card <?= $isRead ? 'is-read' : 'is-unread' ?>" data-id="<?= (int)$m['id'] ?>">
		<span class="cm-card-status" aria-hidden="true"></span>
		<span class="cm-card-glow" aria-hidden="true"></span>

		<div class="cm-card-avatar">
			<span class="cm-card-avatar-letter"><?= e($initial) ?></span>
			<span class="cm-card-avatar-ring" aria-hidden="true"></span>
		</div>

		<div class="cm-card-body">
			<header class="cm-card-head">
				<div class="cm-card-head-left">
					<h2 class="cm-card-name"><?= e($m['client_name']) ?></h2>
					<?php if (!$isRead): ?>
					<span class="cm-badge cm-badge-new">
						<i class="fa-solid fa-circle"></i>
						New
					</span>
					<?php else: ?>
					<span class="cm-badge cm-badge-read">
						<i class="fa-solid fa-circle-check"></i>
						Read
					</span>
					<?php endif; ?>
				</div>
				<?php if ($date !== ''): ?>
				<span class="cm-card-date">
					<i class="fa-regular fa-clock"></i>
					<?= e($date) ?>
				</span>
				<?php endif; ?>
			</header>

			<div class="cm-card-meta">
				<a href="mailto:<?= e($m['email']) ?>" class="cm-chip cm-chip-mail">
					<i class="fa-solid fa-at"></i>
					<span><?= e($m['email']) ?></span>
				</a>
				<?php if (!empty($m['phone'])): ?>
				<a href="tel:<?= e(preg_replace('/\s+/', '', $m['phone'])) ?>" class="cm-chip cm-chip-phone">
					<i class="fa-solid fa-phone"></i>
					<span><?= e($m['phone']) ?></span>
				</a>
				<?php endif; ?>
				<?php if (!empty($m['website'])): ?>
				<a href="<?= e($websiteUrl) ?>" target="_blank" rel="noopener noreferrer" class="cm-chip cm-chip-web">
					<i class="fa-solid fa-globe"></i>
					<span><?= e($m['website']) ?></span>
				</a>
				<?php endif; ?>
			</div>

			<div class="cm-card-message">
				<span class="cm-card-message-label">
					<i class="fa-solid fa-comment-dots"></i>
					Message
				</span>
				<p class="cm-card-message-text"><?= nl2br(e($m['message'])) ?></p>
			</div>
		</div>

		<div class="cm-card-actions">
			<a href="mailto:<?= e($m['email']) ?>?subject=Re: Your enquiry&body=Hi <?= e($m['client_name']) ?>," class="cm-btn cm-btn-primary" title="Reply by email">
				<i class="fa-solid fa-reply"></i>
				<span>Reply</span>
			</a>

			<form method="post" class="cm-inline-form">
				<input type="hidden" name="action" value="toggle_read">
				<input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
				<button type="submit" class="cm-btn cm-btn-soft" title="<?= $isRead ? 'Mark as unread' : 'Mark as read' ?>">
					<i class="fa-solid <?= $isRead ? 'fa-envelope' : 'fa-envelope-open' ?>"></i>
					<span><?= $isRead ? 'Unread' : 'Read' ?></span>
				</button>
			</form>

			<form method="post" class="cm-inline-form cm-inline-form-delete" data-confirm="Delete this meeting request?">
				<input type="hidden" name="action" value="delete">
				<input type="hidden" name="id" value="<?= (int)$m['id'] ?>">
				<button type="submit" class="cm-btn cm-btn-danger" title="Delete">
					<i class="fa-solid fa-trash"></i>
					<span>Delete</span>
				</button>
			</form>
		</div>
	</article>
	<?php endforeach; ?>

</div>
<?php endif; ?>

<script src="<?= SITE_URL ?>/assets/js/admin-client-meeting.js"></script>

<?php include __DIR__ . '/layout_bottom.php'; ?>