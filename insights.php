<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$blogs     = getPublishedBlogs($pdo);
$pageTitle = 'Tech Insights — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="CWT Tech Insights — expert articles on software engineering, AI, cloud, DevOps, and more from Sri Lanka's leading tech partner.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/insights.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="ins-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="ins-hero" aria-label="Insights page hero">
	<div class="ins-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="ins-hero-grid" aria-hidden="true"></div>
	<div class="ins-container">
		<div class="ins-hero-inner">
			<span class="ins-hero-pill">
				<span class="ins-hero-pill-dot"></span>
				Knowledge Hub
			</span>
			<h1 class="ins-hero-title">
				Tech <span class="ins-hero-accent">Insights</span>
			</h1>
			<p class="ins-hero-text">
				Expert perspectives on the technologies shaping the future of enterprise software and digital transformation.
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

<section class="ins-section" id="insights" aria-labelledby="insights-heading">
	<div class="ins-container">
		<div class="ins-sec-head reveal">
			<span class="ins-sec-label">Latest Articles</span>
			<h2 class="ins-sec-title" id="insights-heading">Fresh From The Blog</h2>
			<p class="ins-sec-sub">Practical write-ups, deep dives, and field notes from our engineers and architects.</p>
		</div>

		<?php if (empty($blogs)): ?>
		<div class="ins-empty reveal">
			<span class="ins-empty-icon">
				<i class="fa-solid fa-pen-nib" aria-hidden="true"></i>
			</span>
			<p class="ins-empty-text">No insights published yet. <a href="<?= ADMIN_URL ?>/insights.php">Add via admin</a>.</p>
		</div>
		<?php else: ?>
		<div class="ins-grid">
			<?php foreach ($blogs as $i => $blog): ?>
			<?php
				$featuredUrl = imgUrl($blog['featured_image']);
				$tags = parseHashtags($blog['hashtags']);
				$slug = $blog['slug'];
			?>
			<article class="ins-card reveal" style="--i:<?= $i ?>">
				<a href="<?= SITE_URL ?>/insight-detail.php?slug=<?= urlencode($slug) ?>"
				   class="ins-card-link"
				   aria-label="Read <?= e($blog['topic']) ?>">

					<div class="ins-card-media">
						<?php if ($featuredUrl): ?>
							<img src="<?= e($featuredUrl) ?>" alt="<?= e($blog['topic']) ?>" loading="lazy">
						<?php else: ?>
							<div class="ins-card-media-placeholder" aria-hidden="true">
								<i class="fa-solid fa-file-lines"></i>
							</div>
						<?php endif; ?>
						<span class="ins-card-media-shine" aria-hidden="true"></span>
						<span class="ins-card-media-fade" aria-hidden="true"></span>

						<?php if (!empty($blog['publish_date'])): ?>
						<span class="ins-card-date-badge">
							<i class="fa-regular fa-calendar" aria-hidden="true"></i>
							<?= date('M j, Y', strtotime($blog['publish_date'])) ?>
						</span>
						<?php endif; ?>
					</div>

					<div class="ins-card-body">
						<?php if (!empty($tags)): ?>
						<div class="ins-card-tags">
							<?php foreach ($tags as $tag): ?>
							<span class="ins-card-tag">#<?= e($tag) ?></span>
							<?php endforeach; ?>
						</div>
						<?php endif; ?>

						<h3 class="ins-card-title"><?= e($blog['topic']) ?></h3>

						<span class="ins-card-cta">
							<span>Read Article</span>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
						</span>
					</div>

					<span class="ins-card-glow" aria-hidden="true"></span>
					<span class="ins-card-line" aria-hidden="true"></span>
				</a>
			</article>
			<?php endforeach; ?>
		</div>
		<?php endif; ?>
	</div>
</section>

<section class="ins-cta">
	<div class="ins-cta-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="ins-container">
		<div class="ins-cta-inner reveal">
			<span class="ins-cta-eyebrow">Let's Talk</span>
			<h2 class="ins-cta-title">Have a Challenge Worth Solving?</h2>
			<p class="ins-cta-text">Our engineers love a good problem. Let's explore how CWT can help you build what's next.</p>
			<a href="<?= SITE_URL ?>/services.php" class="ins-cta-btn">
				<span>Explore Our Services</span>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/insights.js"></script>
</body>
</html>