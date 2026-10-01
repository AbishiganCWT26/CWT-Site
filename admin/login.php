<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');

if (isLoggedIn()) {
	header('Location: ' . ADMIN_URL . '/dashboard.php');
	exit;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$password = $_POST['password'] ?? '';
	if (empty($username) || empty($password)) {
		$error = 'Please enter both username and password.';
	} else {
		$stmt = $pdo->prepare("SELECT id, username, password_hash FROM admin_users WHERE username = ? LIMIT 1");
		$stmt->execute([$username]);
		$user = $stmt->fetch();
		if ($user && password_verify($password, $user['password_hash'])) {
			loginAdmin($user['username']);
			header('Location: ' . ADMIN_URL . '/dashboard.php');
			exit;
		} else {
			$error = 'Invalid username or password.';
		}
	}
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Admin Login — Creative Web Technologies</title>
	<meta name="robots" content="noindex, nofollow">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-login.css">
</head>
<body class="admin-login-page">
<div class="login-wrapper">
	<div class="login-box">
		<div class="login-logo">
			<div class="login-logo-icon">CWT</div>
		</div>
		<h1 class="login-title">Admin Portal</h1>
		<p class="login-sub">Creative Web Technologies — Secure Access</p>
		<?php if ($error): ?>
		<div class="login-alert" role="alert">⚠️ <?= htmlspecialchars($error) ?></div>
		<?php endif; ?>
		<form method="POST" action="" class="login-form" novalidate>
			<div class="form-group">
				<label for="username" class="form-label">Username</label>
				<input type="text" id="username" name="username" class="form-control" placeholder="Enter your username" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autocomplete="username" autofocus>
			</div>
			<div class="form-group">
				<label for="password" class="form-label">Password</label>
				<input type="password" id="password" name="password" class="form-control" placeholder="Enter your password" required autocomplete="current-password">
			</div>
			<button type="submit" class="btn btn-primary btn-block">Sign In to Admin</button>
		</form>
		<p class="login-footer-note"><a href="<?= SITE_URL ?>/index.php">← Back to Website</a></p>
	</div>
</div>
</body>
</html>