<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$feedbacks = getClientFeedback($pdo);
$pageTitle = 'Clients Say — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Customer stories — see how CWT's extended tech teams empower businesses to grow faster and reach ambitious goals.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/clients-say.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="cs-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="cs-hero" aria-label="Clients page hero">
	<div class="cs-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="cs-hero-grid" aria-hidden="true"></div>
	<div class="cs-container">
		<div class="cs-hero-inner">
			<span class="cs-hero-pill">
				<span class="cs-hero-pill-dot"></span>
				Testimonials
			</span>
			<h1 class="cs-hero-title">
				Customer <span class="cs-hero-accent">Stories</span>
			</h1>
			<p class="cs-hero-text">
				See how CWT's extended tech teams empower customers to grow faster and reach ambitious goals.
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

<section class="cs-section" id="testimonials" aria-labelledby="testimonials-heading">
	<div class="cs-container">
		<div class="cs-sec-head reveal">
			<span class="cs-sec-label">What Clients Say</span>
			<h2 class="cs-sec-title" id="testimonials-heading">Real Results, Real Clients</h2>
			<p class="cs-sec-sub">Don't take our word for it — hear directly from the businesses we've helped transform.</p>
		</div>

		<?php if (empty($feedbacks)): ?>
		<div class="cs-empty reveal">
			<span class="cs-empty-icon">
				<i class="fa-solid fa-comment-dots" aria-hidden="true"></i>
			</span>
			<p class="cs-empty-text">Testimonials coming soon. <a href="<?= ADMIN_URL ?>/feedback.php">Add via admin</a>.</p>
		</div>
		<?php else: ?>
		<div class="cs-grid">
			<?php foreach ($feedbacks as $i => $fb): ?>
			<?php $photoUrl = imgUrl($fb['photo_path']); ?>
			<article class="cs-card reveal" style="--i:<?= $i ?>">
				<span class="cs-card-glow" aria-hidden="true"></span>
				<span class="cs-card-shine" aria-hidden="true"></span>
				<span class="cs-card-quote" aria-hidden="true">
					<i class="fa-solid fa-quote-right"></i>
				</span>

				<p class="cs-card-text">"<?= e($fb['feedback']) ?>"</p>

				<footer class="cs-card-foot">
					<div class="cs-card-avatar">
						<?php if ($photoUrl): ?>
							<img src="<?= e($photoUrl) ?>" alt="<?= e($fb['client_name']) ?>" loading="lazy">
						<?php else: ?>
							<span class="cs-card-avatar-fallback"><?= mb_strtoupper(mb_substr($fb['client_name'], 0, 1)) ?></span>
						<?php endif; ?>
						<span class="cs-card-avatar-ring" aria-hidden="true"></span>
					</div>
					<div class="cs-card-author">
						<span class="cs-card-name"><?= e($fb['client_name']) ?></span>
						<?php if (!empty($fb['feedback_date'])): ?>
						<span class="cs-card-date">
							<i class="fa-regular fa-calendar" aria-hidden="true"></i>
							<?= date('F j, Y', strtotime($fb['feedback_date'])) ?>
						</span>
						<?php else: ?>
						<span class="cs-card-date">
							<i class="fa-solid fa-circle-check" aria-hidden="true"></i>
							Verified Client
						</span>
						<?php endif; ?>
					</div>
				</footer>
			</article>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<section class="cs-cta">
	<div class="cs-cta-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="cs-container">
		<div class="cs-cta-inner reveal">
			<span class="cs-cta-eyebrow">Your Turn</span>
			<h2 class="cs-cta-title">Let’s Talk About Your Project Goals</h2>
			<p class="cs-cta-text">Discover how we help teams deliver faster</p>
			<a href="<?= SITE_URL ?>/contact.php" class="cs-cta-btn">
				<span>Get Started</span>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/clients-say.js"></script>
</body>
</html>