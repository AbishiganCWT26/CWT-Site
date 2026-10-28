<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$errors  = [];
$success = false;
$formUsername = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verifyCsrf();

	$username         = trim($_POST['username'] ?? '');
	$password         = $_POST['password'] ?? '';
	$confirmPassword  = $_POST['confirm_password'] ?? '';
	$formUsername     = $username;

	// Validate username
	if ($username === '') {
		$errors['username'] = 'Username is required.';
	} elseif (!validateAdminUsername($username)) {
		$errors['username'] = 'Username must be 3–100 characters and contain only letters, numbers, dots, underscores, or hyphens.';
	}

	// Validate password
	if ($password === '') {
		$errors['password'] = 'Password is required.';
	} elseif (strlen($password) < 8) {
		$errors['password'] = 'Password must be at least 8 characters.';
	}

	// Validate confirm password
	if ($confirmPassword === '') {
		$errors['confirm_password'] = 'Please confirm the password.';
	} elseif ($password !== $confirmPassword) {
		$errors['confirm_password'] = 'Passwords do not match.';
	}

	// Check uniqueness only if username format is valid
	if (empty($errors['username']) && getAdminUserByUsername($pdo, $username)) {
		$errors['username'] = 'That username is already taken. Please choose another.';
	}

	if (empty($errors)) {
		$newId = createAdminUser($pdo, $username, $password);
		if ($newId !== false) {
			$_SESSION['flash_success'] = 'Admin user "' . $username . '" created successfully.';
			header('Location: ' . ADMIN_URL . '/add-admin.php');
			exit;
		} else {
			$errors['username'] = 'That username is already taken. Please choose another.';
		}
	}
}

// Read and clear flash messages
$flashSuccess = '';
$flashError   = '';
if (!empty($_SESSION['flash_success'])) {
	$flashSuccess = $_SESSION['flash_success'];
	unset($_SESSION['flash_success']);
}
if (!empty($_SESSION['flash_error'])) {
	$flashError = $_SESSION['flash_error'];
	unset($_SESSION['flash_error']);
}

$pageTitle = 'Add Admin';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-add_admin.css">

<div class="page-header">
	<div>
		<h1>➕ Add Admin User</h1>
		<p>Create a new administrator account for the CWT admin panel.</p>
	</div>
	<a href="<?= ADMIN_URL ?>/dashboard.php" class="btn btn-secondary btn-sm">← Back to Dashboard</a>
</div>

<div class="add-admin-wrap">
	<?php if (!empty($errors)): ?>
	<div class="form-alert form-alert-error" role="alert" aria-live="polite">
		<strong>Please fix the following errors:</strong>
		<ul>
			<?php foreach ($errors as $err): ?>
			<li><?= e($err) ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<?php endif; ?>

	<div class="admin-card add-admin-card">
		<div class="admin-card-header">
			<h2>New Administrator Account</h2>
		</div>
		<div class="admin-card-body">
			<form method="POST" action="" class="add-admin-form" id="addAdminForm" novalidate>
				<input type="hidden" name="csrf_token" value="<?= csrfToken() ?>">

				<!-- Username -->
				<div class="form-group">
					<label class="form-label" for="username">
						Username <span class="req" aria-hidden="true">*</span>
					</label>
					<input
						type="text"
						id="username"
						name="username"
						class="form-control<?= isset($errors['username']) ? ' is-invalid' : '' ?>"
						value="<?= e($formUsername) ?>"
						maxlength="100"
						autocomplete="username"
						aria-required="true"
						aria-describedby="username-hint<?= isset($errors['username']) ? ' username-error' : '' ?>"
						<?= isset($errors['username']) ? 'aria-invalid="true"' : '' ?>
					>
					<div class="form-hint" id="username-hint">3–100 characters. Letters, numbers, <code>.</code>, <code>_</code>, <code>-</code> only.</div>
					<?php if (isset($errors['username'])): ?>
					<div class="form-error" id="username-error" role="alert"><?= e($errors['username']) ?></div>
					<?php else: ?>
					<div class="form-error" id="username-error" role="alert" style="display:none;"></div>
					<?php endif; ?>
				</div>

				<!-- Password -->
				<div class="form-group">
					<label class="form-label" for="password">
						Password <span class="req" aria-hidden="true">*</span>
					</label>
					<div class="password-field">
						<input
							type="password"
							id="password"
							name="password"
							class="form-control<?= isset($errors['password']) ? ' is-invalid' : '' ?>"
							maxlength="255"
							autocomplete="new-password"
							aria-required="true"
							aria-describedby="password-hint password-strength-label<?= isset($errors['password']) ? ' password-error' : '' ?>"
							<?= isset($errors['password']) ? 'aria-invalid="true"' : '' ?>
						>
						<button type="button" class="toggle-password" data-target="password" aria-label="Toggle password visibility">
							<svg id="eye-show-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
							<svg id="eye-hide-password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" style="display:none;"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/></svg>
						</button>
					</div>
					<div class="form-hint" id="password-hint">Minimum 8 characters.</div>
					<?php if (isset($errors['password'])): ?>
					<div class="form-error" id="password-error" role="alert"><?= e($errors['password']) ?></div>
					<?php else: ?>
					<div class="form-error" id="password-error" role="alert" style="display:none;"></div>
					<?php endif; ?>
					<div class="password-strength" id="passwordStrengthWrap" aria-hidden="true">
						<div class="password-strength-bar" id="passwordStrengthBar"></div>
					</div>
					<div class="password-strength-label" id="password-strength-label" aria-live="polite"></div>
				</div>

				<!-- Confirm Password -->
				<div class="form-group">
					<label class="form-label" for="confirm_password">
						Confirm Password <span class="req" aria-hidden="true">*</span>
					</label>
					<div class="password-field">
						<input
							type="password"
							id="confirm_password"
							name="confirm_password"
							class="form-control<?= isset($errors['confirm_password']) ? ' is-invalid' : '' ?>"
							maxlength="255"
							autocomplete="new-password"
							aria-required="true"
							aria-describedby="confirm-error"
							<?= isset($errors['confirm_password']) ? 'aria-invalid="true"' : '' ?>
						>
						<button type="button" class="toggle-password" data-target="confirm_password" aria-label="Toggle confirm password visibility">
							<svg id="eye-show-confirm_password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/></svg>
							<svg id="eye-hide-confirm_password" xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="currentColor" viewBox="0 0 16 16" style="display:none;"><path d="m10.79 12.912-1.614-1.615a3.5 3.5 0 0 1-4.474-4.474l-2.06-2.06C.938 6.278 0 8 0 8s3 5.5 8 5.5a7 7 0 0 0 2.79-.588M5.21 3.088A7 7 0 0 1 8 2.5c5 0 8 5.5 8 5.5s-.939 1.721-2.641 3.238l-2.062-2.062a3.5 3.5 0 0 0-4.474-4.474z"/><path d="M5.525 7.646a2.5 2.5 0 0 0 2.829 2.829zm4.95.708-2.829-2.83a2.5 2.5 0 0 1 2.829 2.829zm3.171 6-12-12 .708-.708 12 12z"/></svg>
						</button>
					</div>
					<div class="form-error" id="confirm-error" role="alert"><?= isset($errors['confirm_password']) ? e($errors['confirm_password']) : '' ?></div>
				</div>

				<div class="form-actions">
					<button type="submit" id="submitBtn" class="btn btn-primary">➕ Create Admin User</button>
					<a href="<?= ADMIN_URL ?>/dashboard.php" class="btn btn-secondary">Cancel</a>
				</div>
			</form>
		</div>
	</div>
</div>

<?php if ($flashSuccess): ?>
<div id="flash-data" data-message="<?= e($flashSuccess) ?>" data-type="success" style="display:none;"></div>
<?php elseif ($flashError): ?>
<div id="flash-data" data-message="<?= e($flashError) ?>" data-type="error" style="display:none;"></div>
<?php endif; ?>

<script src="<?= SITE_URL ?>/assets/js/admin-add_admin.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>
