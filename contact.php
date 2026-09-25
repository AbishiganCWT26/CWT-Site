<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$formSuccess = '';
$formError = '';
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name    = trim($_POST['name'] ?? '');
	$website = trim($_POST['website'] ?? '');
	$email   = trim($_POST['email'] ?? '');
	$phone   = trim($_POST['phone'] ?? '');
	$message = trim($_POST['message'] ?? '');

	if ($name === '' || $phone === '' || $email === '' || $message === '') {
		$formError = 'Please fill in all required fields.';
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$formError = 'Please enter a valid email address.';
	} else {
		$stmt = $pdo->prepare("INSERT INTO client_meetings (client_name, website, email, phone, message) VALUES (?, ?, ?, ?, ?)");
		$stmt->execute([$name, $website !== '' ? $website : null, $email, $phone, $message]);
		$formSuccess = 'Thank you! Your message has been received. We will be in touch shortly.';
	}

	if ($isAjax) {
		header('Content-Type: application/json');
		echo json_encode([
			'success' => $formError === '',
			'message' => $formError !== '' ? $formError : $formSuccess,
		]);
		exit;
	}
}

$footerStmt = $pdo->query("SELECT email, phone, address FROM footer LIMIT 1");
$footerDetails = $footerStmt->fetch(PDO::FETCH_ASSOC) ?: [];

$contactEmail = $footerDetails['email'] ?? 'hello@cwt.lk';
$contactPhone = $footerDetails['phone'] ?? '+94 11 000 0000';
$contactLocation = $footerDetails['address'] ?? 'Colombo, Sri Lanka';

$pageTitle = 'Contact Us — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Get in touch with Creative Web Technologies — tell us about your project and our team will respond promptly.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/contact.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="ct-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="ct-hero" aria-label="Contact page hero">
	<div class="ct-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="ct-hero-grid" aria-hidden="true"></div>
	<div class="ct-container">
		<div class="ct-hero-inner">
			<span class="ct-hero-pill">
				<span class="ct-hero-pill-dot"></span>
				Let's Talk
			</span>
			<h1 class="ct-hero-title">
				Start Your <span class="ct-hero-accent">Next Project</span>
			</h1>
			<p class="ct-hero-text">
			 	Bring us your idea or vision, and we'll partner with you to turn it into something extraordinary.
			</p>
		</div>
	</div>

	<!-- Nexus Field decorative swirl arcs -->
	<svg class="hero-swirl hero-swirl-tr" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
	  <defs>
	    <linearGradient id="heroSwirlGradTR" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
	      <stop offset="0%"  stop-color="#5b9cff" stop-opacity="0"/>
	      <stop offset="18%" stop-color="#2b7bff" stop-opacity="0.9"/>
	      <stop offset="55%" stop-color="#dce9ff" stop-opacity="1"/>
	      <stop offset="100%" stop-color="#1a66ff" stop-opacity="0"/>
	    </linearGradient>
	    <filter id="heroGlowTR" x="-60%" y="-60%" width="220%" height="220%">
	      <feGaussianBlur stdDeviation="4.5" result="blur"/>
	      <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
	    </filter>
	  </defs>
	  <g filter="url(#heroGlowTR)" stroke-linecap="round" fill="none">
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="2.5" opacity="0.55" transform="translate(-14,-10)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="3"   opacity="0.75" transform="translate(-4,-2)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="2.2" opacity="1"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="#ffffff" stroke-width="0.9" opacity="0.85" transform="translate(6,6)"/>
	  </g>
	</svg>

	<svg class="hero-swirl hero-swirl-bl" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
	  <defs>
	    <linearGradient id="heroSwirlGradBL" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
	      <stop offset="0%"  stop-color="#7ea0f8" stop-opacity="0"/>
	      <stop offset="18%" stop-color="#2b7bff" stop-opacity="0.9"/>
	      <stop offset="55%" stop-color="#dce9ff" stop-opacity="1"/>
	      <stop offset="100%" stop-color="#1a66ff" stop-opacity="0"/>
	    </linearGradient>
	    <filter id="heroGlowBL" x="-60%" y="-60%" width="220%" height="220%">
	      <feGaussianBlur stdDeviation="4" result="blur"/>
	      <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
	    </filter>
	  </defs>
	  <g filter="url(#heroGlowBL)" stroke-linecap="round" fill="none">
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2.5" opacity="0.5" transform="translate(-14,-10)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2.8" opacity="0.7" transform="translate(-4,-2)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2"   opacity="1"/>
	  </g>
	</svg>

	<canvas id="heroCanvas" class="hero-canvas" aria-hidden="true"></canvas>
</section>

<section class="ct-section" id="contact-form" aria-labelledby="ct-form-heading">
	<div class="ct-container">
		<div class="ct-grid">

			<aside class="ct-side reveal">
				<span class="ct-side-label">Get in Touch</span>
				<h2 class="ct-side-title" id="ct-form-heading">We'd Love to Hear From You</h2>
				<p class="ct-side-text">
					Whether you're planning a new product, modernizing a legacy platform, or scaling your engineering team — we're ready.
				</p>

				<ul class="ct-info-list">
					<li class="ct-info-item">
						<span class="ct-info-icon"><i class="fa-solid fa-envelope" aria-hidden="true"></i></span>
						<div class="ct-info-body">
							<span class="ct-info-label">Email</span>
							<span class="ct-info-value"><?= e($contactEmail) ?></span>
						</div>
					</li>
					<li class="ct-info-item">
						<span class="ct-info-icon"><i class="fa-solid fa-phone" aria-hidden="true"></i></span>
						<div class="ct-info-body">
							<span class="ct-info-label">Phone</span>
							<span class="ct-info-value"><?= e($contactPhone) ?></span>
						</div>
					</li>
					<li class="ct-info-item">
						<span class="ct-info-icon"><i class="fa-solid fa-location-dot" aria-hidden="true"></i></span>
						<div class="ct-info-body">
							<span class="ct-info-label">Location</span>
							<span class="ct-info-value"><?= e($contactLocation) ?></span>
						</div>
					</li>
				</ul>

				<div class="ct-badge">
					<i class="fa-solid fa-shield-halved" aria-hidden="true"></i>
					<span>Your information is safe with us</span>
				</div>
			</aside>

			<div class="ct-card reveal">
				<div class="ct-card-head">
					<h3 class="ct-card-title">Send us a message</h3>
					<p class="ct-card-sub">Fields marked with <span>*</span> are required</p>
				</div>

				<?php if ($formError !== ''): ?>
				<div class="ct-alert ct-alert-error" role="alert">
					<i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i>
					<span><?= e($formError) ?></span>
				</div>
				<?php endif; ?>

				<?php if ($formSuccess !== ''): ?>
				<div class="ct-alert ct-alert-success" role="status">
					<i class="fa-solid fa-circle-check" aria-hidden="true"></i>
					<span><?= e($formSuccess) ?></span>
				</div>
				<?php endif; ?>

				<form class="ct-form" id="ctForm" method="post" action="<?= SITE_URL ?>/contact.php" novalidate>
					<div class="ct-form-grid">

						<div class="ct-field">
							<label for="ct_name">Name <span class="ct-req">*</span></label>
							<div class="ct-input-wrap">
								<i class="fa-solid fa-user ct-input-icon" aria-hidden="true"></i>
								<input type="text" id="ct_name" name="name" placeholder="Enter your name" required autocomplete="name">
							</div>
						</div>

						<div class="ct-field">
							<label for="ct_website">Company Website <span class="ct-opt">(Optional)</span></label>
							<div class="ct-input-wrap">
								<i class="fa-solid fa-globe ct-input-icon" aria-hidden="true"></i>
								<input type="text" id="ct_website" name="website" placeholder="https://example.com" autocomplete="url">
							</div>
						</div>

						<div class="ct-field">
							<label for="ct_email">E-mail <span class="ct-req">*</span></label>
							<div class="ct-input-wrap">
								<i class="fa-solid fa-at ct-input-icon" aria-hidden="true"></i>
								<input type="email" id="ct_email" name="email" placeholder="you@company.com" required autocomplete="email">
							</div>
						</div>

						<div class="ct-field">
							<label for="ct_phone">Phone <span class="ct-req">*</span></label>
							<div class="ct-input-wrap">
								<i class="fa-solid fa-phone ct-input-icon" aria-hidden="true"></i>
								<input type="tel" id="ct_phone" name="phone" placeholder="+94 7X XXX XXXX" required autocomplete="tel">
							</div>
						</div>

						<div class="ct-field ct-field-full">
							<label for="ct_message">What are you planning to build? <span class="ct-req">*</span></label>
							<div class="ct-input-wrap ct-input-wrap-textarea">
								<i class="fa-solid fa-comment-dots ct-input-icon ct-input-icon-top" aria-hidden="true"></i>
								<textarea id="ct_message" name="message" placeholder="Tell us about your project, goals, and timeline..." required></textarea>
							</div>
						</div>

					</div>

					<label class="ct-terms">
						<input type="checkbox" id="ct_terms" name="terms" required>
						<span class="ct-terms-box" aria-hidden="true"><i class="fa-solid fa-check"></i></span>
						<span class="ct-terms-text">
							By submitting your information, you agree to our website's <a href="<?= SITE_URL ?>/privacy-policy.php">Privacy Policy</a>
						</span>
					</label>

					<div class="ct-form-actions">
						<button type="submit" class="ct-submit" id="ctSubmit" disabled>
							<span class="ct-submit-text">Send Message</span>
							<span class="ct-submit-ico">
								<i class="fa-solid fa-paper-plane" aria-hidden="true"></i>
							</span>
						</button>
					</div>
				</form>
			</div>

		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/contact.js"></script>
</body>
</html>