/**
 * admin-add_admin.js
 * Client-side enhancements for the Add Admin form.
 * Vanilla JS only — no external libraries beyond SweetAlert2.
 */

document.addEventListener('DOMContentLoaded', function () {

	/* ──────────────────────────────────────────────────────────────
	   1. Flash message via SweetAlert2
	   ────────────────────────────────────────────────────────────── */
	var flashEl = document.getElementById('flash-data');
	if (flashEl && typeof Swal !== 'undefined') {
		var flashMsg  = flashEl.getAttribute('data-message') || '';
		var flashType = flashEl.getAttribute('data-type') || 'info';
		if (flashMsg) {
			Swal.fire(Object.assign({}, window.SwalDefaults || {}, {
				icon: flashType,
				title: flashType === 'success' ? 'Success!' : 'Error',
				text: flashMsg,
				timer: 2800,
				timerProgressBar: true,
				showConfirmButton: false
			}));
		}
	}

	/* ──────────────────────────────────────────────────────────────
	   Guard: exit early if form is not on this page
	   ────────────────────────────────────────────────────────────── */
	var form = document.getElementById('addAdminForm');
	if (!form) return;

	var passwordInput        = document.getElementById('password');
	var confirmInput         = document.getElementById('confirm_password');
	var usernameInput        = document.getElementById('username');
	var submitBtn            = document.getElementById('submitBtn');
	var strengthBar          = document.getElementById('passwordStrengthBar');
	var strengthLabel        = document.getElementById('password-strength-label');
	var usernameError        = document.getElementById('username-error');
	var passwordError        = document.getElementById('password-error');
	var confirmError         = document.getElementById('confirm-error');

	/* ──────────────────────────────────────────────────────────────
	   2. Password visibility toggle
	   ────────────────────────────────────────────────────────────── */
	document.querySelectorAll('.toggle-password').forEach(function (btn) {
		btn.addEventListener('click', function (e) {
			e.preventDefault();
			var targetId  = btn.getAttribute('data-target');
			var input     = document.getElementById(targetId);
			if (!input) return;

			var isPassword = input.type === 'password';
			input.type = isPassword ? 'text' : 'password';

			var showIcon = document.getElementById('eye-show-' + targetId);
			var hideIcon = document.getElementById('eye-hide-' + targetId);
			if (showIcon) showIcon.style.display = isPassword ? 'none' : '';
			if (hideIcon) hideIcon.style.display = isPassword ? '' : 'none';

			btn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
		});
	});

	/* ──────────────────────────────────────────────────────────────
	   3. Password strength meter
	   ────────────────────────────────────────────────────────────── */
	function computeStrength(pw) {
		if (!pw) return 0;
		var score = 0;
		if (pw.length >= 8)         score++;
		if (/[a-z]/.test(pw) && /[A-Z]/.test(pw)) score++;
		if (/[0-9]/.test(pw))       score++;
		if (/[^A-Za-z0-9]/.test(pw)) score++;
		return score;
	}

	var strengthLabels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
	var strengthColors = ['', '#e53e3e', '#d97706', '#2563eb', '#16a34a'];

	function updateStrength(pw) {
		if (!strengthBar || !strengthLabel) return;
		var score = computeStrength(pw);
		strengthBar.style.width  = (score * 25) + '%';
		strengthBar.style.background = strengthColors[score] || '#e8e8e8';
		strengthLabel.textContent    = pw.length > 0 ? (strengthLabels[score] || '') : '';
		strengthLabel.style.color    = strengthColors[score] || 'var(--text-muted)';
	}

	if (passwordInput) {
		passwordInput.addEventListener('input', function () {
			updateStrength(this.value);
			// Re-run confirm match check when password changes
			if (confirmInput && confirmInput.value) {
				checkPasswordMatch();
			}
			// Clear server-side password error on typing
			clearError(passwordError);
		});
	}

	/* ──────────────────────────────────────────────────────────────
	   4. Confirm password live match check
	   ────────────────────────────────────────────────────────────── */
	function checkPasswordMatch() {
		if (!confirmInput || !passwordInput || !confirmError) return;
		var pw  = passwordInput.value;
		var cpw = confirmInput.value;
		if (cpw === '') {
			clearError(confirmError);
			confirmInput.classList.remove('is-invalid');
			confirmInput.removeAttribute('aria-invalid');
			return;
		}
		if (pw !== cpw) {
			showError(confirmError, 'Passwords do not match.');
			confirmInput.classList.add('is-invalid');
			confirmInput.setAttribute('aria-invalid', 'true');
		} else {
			clearError(confirmError);
			confirmInput.classList.remove('is-invalid');
			confirmInput.removeAttribute('aria-invalid');
		}
	}

	if (confirmInput) {
		confirmInput.addEventListener('input', checkPasswordMatch);
	}

	/* ──────────────────────────────────────────────────────────────
	   5. Username validation
	   ────────────────────────────────────────────────────────────── */
	var usernameRegex = /^[A-Za-z0-9._-]{3,100}$/;

	function validateUsername(val) {
		val = val.trim();
		if (!usernameInput) return;
		usernameInput.value = val;
		if (val === '') {
			clearError(usernameError);
			usernameInput.classList.remove('is-invalid');
			usernameInput.removeAttribute('aria-invalid');
			return;
		}
		if (!usernameRegex.test(val)) {
			showError(usernameError, 'Only letters, numbers, dots, underscores, and hyphens are allowed (3–100 chars).');
			usernameInput.classList.add('is-invalid');
			usernameInput.setAttribute('aria-invalid', 'true');
		} else {
			clearError(usernameError);
			usernameInput.classList.remove('is-invalid');
			usernameInput.removeAttribute('aria-invalid');
		}
	}

	if (usernameInput) {
		usernameInput.addEventListener('blur', function () {
			validateUsername(this.value);
		});
		usernameInput.addEventListener('input', function () {
			// Only clear error while typing (don't show error mid-typing)
			if (usernameInput.classList.contains('is-invalid')) {
				validateUsername(this.value);
			}
		});
	}

	/* ──────────────────────────────────────────────────────────────
	   6. Disable submit on submit (prevent double-submit)
	   ────────────────────────────────────────────────────────────── */
	form.addEventListener('submit', function () {
		if (submitBtn) {
			submitBtn.disabled = true;
			submitBtn.textContent = 'Creating…';
		}
	});

	/* ──────────────────────────────────────────────────────────────
	   Helpers
	   ────────────────────────────────────────────────────────────── */
	function showError(el, msg) {
		if (!el) return;
		el.textContent = msg;
		el.style.display = '';
	}

	function clearError(el) {
		if (!el) return;
		el.textContent = '';
		el.style.display = 'none';
	}

});
