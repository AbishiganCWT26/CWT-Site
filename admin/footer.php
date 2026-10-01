<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';
$footer = getFooter($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$fields = ['para','linkedin_url','facebook_url','youtube_url','instagram_url','email','phone','address'];
	$data = [];
	foreach ($fields as $f) $data[$f] = trim($_POST[$f] ?? '');
	if (!empty($footer)) {
		$sql = "UPDATE footer SET para=:para, linkedin_url=:linkedin_url, facebook_url=:facebook_url, youtube_url=:youtube_url, instagram_url=:instagram_url, email=:email, phone=:phone, address=:address WHERE id=:id";
		$data['id'] = $footer['id'];
	} else {
		$sql = "INSERT INTO footer (para,linkedin_url,facebook_url,youtube_url,instagram_url,email,phone,address) VALUES (:para,:linkedin_url,:facebook_url,:youtube_url,:instagram_url,:email,:phone,:address)";
	}
	$pdo->prepare($sql)->execute($data);
	$msg = 'success';
	$footer = getFooter($pdo);
}

$fp = array_map('htmlspecialchars', array_map('strval', $footer));
$pageTitle = 'Footer Settings';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Footer Settings</h1>
		<p>Manage footer content shown on all public pages.</p>
	</div>
</div>

<?php if ($msg === 'success'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Footer settings updated successfully.</div>
<?php endif; ?>

<div class="admin-card">
	<div class="admin-card-header"><h2>Edit Footer</h2></div>
	<div class="admin-card-body">
		<form method="POST" action="">
			<div class="form-group">
				<label class="form-label">Footer Paragraph <span class="req">*</span></label>
				<textarea name="para" class="form-control" rows="4" placeholder="Company description..."><?= $fp['para'] ?? '' ?></textarea>
			</div>
			<div class="form-row">
				<div class="form-group"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control" value="<?= $fp['linkedin_url'] ?? '' ?>"></div>
				<div class="form-group"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="<?= $fp['facebook_url'] ?? '' ?>"></div>
				<div class="form-group"><label class="form-label">YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="<?= $fp['youtube_url'] ?? '' ?>"></div>
				<div class="form-group"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="<?= $fp['instagram_url'] ?? '' ?>"></div>
			</div>
			<div class="form-row">
				<div class="form-group"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="<?= $fp['email'] ?? '' ?>"></div>
				<div class="form-group"><label class="form-label">Phone Number</label><input type="text" name="phone" class="form-control" value="<?= $fp['phone'] ?? '' ?>"></div>
			</div>
			<div class="form-group">
				<label class="form-label">Address</label>
				<textarea name="address" class="form-control" rows="2"><?= $fp['address'] ?? '' ?></textarea>
			</div>
			<button type="submit" class="btn btn-primary">💾 Save Footer Settings</button>
		</form>
	</div>
</div>

<script src="<?= SITE_URL ?>/assets/js/admin-footer.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>