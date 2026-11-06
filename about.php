<?php
// C:\xampp\htdocs\CWT Site\about.php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

/* ---------- About data ---------- */
$about  = getAboutUs($pdo);
$hist   = getHistory($pdo);
$vm     = getVisionMission($pdo);

$yearsExp = !empty($about['start_year']) ? calcYearsExperience((int)$about['start_year']) : 20;
$retention = $about['retention_pct']   ?? 95;
$employed  = $about['employed_count']  ?? 200;
$projects  = $about['projects_count']  ?? 55;

/* ---------- Crew data (from our-crew.php) ---------- */
$placeholder = 'https://cdn-icons-png.flaticon.com/512/3237/3237447.png';

$expertCrew = getCrewByType($pdo, 'Expert');
$teamCrew   = getCrewByType($pdo, 'Team');

$ceo     = !empty($expertCrew) ? $expertCrew[0] : null;
$experts = array_slice($expertCrew, 1);

$teamCols = [[], [], []];
foreach ($teamCrew as $i => $m) {
	$teamCols[$i % 3][] = ['number' => $i + 1, 'member' => $m];
}

$pageTitle = 'About Us — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="About Creative Web Technologies — our story, mission, vision, leadership, expert consultants, team expertise, and commitment to technology excellence.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Poppins:wght@600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&family=Great+Vibes&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/about.css?v=<?= filemtime(__DIR__ . '/assets/css/about.css') ?>">
	<script>document.documentElement.classList.add('js');</script>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css?v=<?= filemtime(__DIR__ . '/assets/css/modern.css') ?>">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
<?php require_once __DIR__ . '/includes/theme.php'; ?>
</head>
<body class="abt-page cwt-public">
<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">

<section class="abt-hero" aria-label="About page hero">
	<div class="abt-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="abt-hero-grid" aria-hidden="true"></div>
	<div class="abt-container">
		<div class="abt-hero-inner">
			<span class="abt-hero-pill">
				<span class="abt-hero-pill-dot"></span>
				Our Story
			</span>
			<h1 class="abt-hero-title">
				About <span class="abt-hero-accent">Creative Web Technologies</span>
			</h1>
			<p class="abt-hero-text">
				Fueled by tech, backed by strong security, and dedicated to solving global challenges with Sri Lankan ingenuity.
			</p>
			<div class="abt-hero-stats">
				<div class="abt-hero-stat">
					<strong><?= $yearsExp ?>+</strong>
					<span>Years</span>
				</div>
				<div class="abt-hero-stat-div"></div>
				<div class="abt-hero-stat">
					<strong><?= $employed ?>+</strong>
					<span>Experts</span>
				</div>
				<div class="abt-hero-stat-div"></div>
				<div class="abt-hero-stat">
					<strong><?= $projects ?>+</strong>
					<span>Projects</span>
				</div>
			</div>
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

	<!-- Nexus Field interactive starfield -->
	<canvas id="heroCanvas" class="hero-canvas" aria-hidden="true"></canvas>
</section>

<section class="abt-section abt-section-light" id="about-cwt" aria-labelledby="about-heading">
	<div class="abt-container">
		<div class="abt-about-grid">
			<div class="abt-about-text reveal">
				<span class="abt-sec-label">Who We Are</span>
				<h2 class="abt-sec-title" id="about-heading">About Creative Web Technologies</h2>
				<p class="abt-para">Creative Web Technologies (CWT) is an enterprise technology and business transformation partner dedicated to turning complex data and technology investments into measurable business value. At our core, we believe that technology should simplify complexity, not add to it. We work at the intersection of enterprise data, artificial intelligence, and business performance, helping organizations transform fragmented information and operational challenges into trusted data foundations and intelligent solutions. By placing business needs before technology choices, we ensure that every architecture, application, and analytics initiative we design is directly aligned with our clients' strategic objectives. Our goal is to empower organizations to make better decisions, improve operational efficiency, and embrace emerging technologies with confidence.</p> 
				<br>
				<p class="abt-para">Our strength lies in our highly integrated operating model, which brings together seven specialist practices—Data & AI, Software Engineering, Operations, Project Delivery & PMO, Infrastructure, Business Development, and Optimization—under one coordinated ecosystem. Rather than functioning as isolated departments, these verticals collaborate seamlessly to support the entire customer lifecycle, from initial discovery and architecture to engineering, integration, and long-term support. What truly sets CWT apart is our signature emphasis on continuous Optimization and our proprietary data-quality framework, which ensures business users have total confidence in their enterprise information. We operate as a long-term partner rather than a transactional vendor, committed to responsible innovation, cross-practice collaboration, and an unwavering focus on customer success that continues long after a solution goes live.</p>
			</div>
		</div>
	</div>
</section>

<section class="abt-section abt-section-tint" id="stats" aria-labelledby="stats-heading">
	<div class="abt-container">
		<div class="abt-sec-head reveal">
			<span class="abt-sec-label">By the Numbers</span>
			<h2 class="abt-sec-title" id="stats-heading">CWT at a Glance</h2>
			<p class="abt-sec-sub">A snapshot of our growth, reach, and impact across global technology engagements.</p>
		</div>
		<div class="abt-stats-grid reveal">
			<div class="abt-stat-card">
				<span class="abt-stat-card-icon"><i class="fa-solid fa-clock" aria-hidden="true"></i></span>
				<div class="abt-stat-number">
					<span class="count-up" data-target="<?= $yearsExp ?>" data-suffix="+" data-prefix="">0</span>
				</div>
				<div class="abt-stat-label">Years of Experience</div>
				<span class="abt-stat-line" aria-hidden="true"></span>
			</div>
			<div class="abt-stat-card">
				<span class="abt-stat-card-icon"><i class="fa-solid fa-heart" aria-hidden="true"></i></span>
				<div class="abt-stat-number">
					<span class="count-up" data-target="<?= $retention ?>" data-suffix="%" data-prefix="">0</span>
				</div>
				<div class="abt-stat-label">Client Retention</div>
				<span class="abt-stat-line" aria-hidden="true"></span>
			</div>
			<div class="abt-stat-card">
				<span class="abt-stat-card-icon"><i class="fa-solid fa-users" aria-hidden="true"></i></span>
				<div class="abt-stat-number">
					<span class="count-up" data-target="<?= $employed ?>" data-suffix="+" data-prefix="">0</span>
				</div>
				<div class="abt-stat-label">Employed Professionals</div>
				<span class="abt-stat-line" aria-hidden="true"></span>
			</div>
			<div class="abt-stat-card">
				<span class="abt-stat-card-icon"><i class="fa-solid fa-rocket" aria-hidden="true"></i></span>
				<div class="abt-stat-number">
					<span class="count-up" data-target="<?= $projects ?>" data-suffix="+" data-prefix="">0</span>
				</div>
				<div class="abt-stat-label">Projects Delivered</div>
				<span class="abt-stat-line" aria-hidden="true"></span>
			</div>
		</div>
	</div>
</section>

<?php if (!empty($hist)): ?>
<section class="abt-section abt-section-light" id="history" aria-labelledby="history-heading">
	<div class="abt-container">
		<div class="abt-sec-head reveal">
			<span class="abt-sec-label">Timeline</span>
			<h2 class="abt-sec-title" id="history-heading">Our History</h2>
			<p class="abt-sec-sub">A journey of purpose-driven engineering, shaped by our team and clients.</p>
		</div>

		<div class="abt-history">
			<?php if (!empty($hist['para1'])): ?>
			<p class="abt-history-para abt-history-para-1 reveal"><?= nl2br(e($hist['para1'])) ?></p>
			<?php endif; ?>

			<?php $hist1 = imgUrl($hist['image1_path'] ?? ''); ?>
			<?php if ($hist1): ?>
			<div class="abt-history-media abt-history-media-1 reveal">
				<img src="<?= e($hist1) ?>" alt="CWT History" class="abt-history-image" loading="lazy">
			</div>
			<?php endif; ?>

			<?php $hasMore = !empty($hist['para2']) || !empty($hist['para3']) || !empty($hist['image2_path']); ?>
			<?php if ($hasMore): ?>
			<div class="abt-history-more" id="historyMore">
				<?php if (!empty($hist['para2'])): ?>
				<p class="abt-history-para abt-history-para-2"><?= nl2br(e($hist['para2'])) ?></p>
				<?php endif; ?>

				<?php $hist2 = imgUrl($hist['image2_path'] ?? ''); ?>
				<?php if ($hist2): ?>
				<div class="abt-history-media abt-history-media-2">
					<img src="<?= e($hist2) ?>" alt="CWT History" class="abt-history-image" loading="lazy">
				</div>
				<?php endif; ?>

				<?php if (!empty($hist['para3'])): ?>
				<p class="abt-history-para abt-history-para-3"><?= nl2br(e($hist['para3'])) ?></p>
				<?php endif; ?>
			</div>

			<div class="abt-history-more-btn-wrap reveal">
				<button class="abt-read-more" id="readMoreBtn" aria-expanded="false" aria-controls="historyMore">
					<span class="abt-read-more-text">Read More</span>
					<i class="fa-solid fa-chevron-right abt-read-more-ico" aria-hidden="true"></i>
				</button>
			</div>
			<?php endif; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="abt-section abt-section-tint abt-recognition-section" id="awards" aria-labelledby="awards-heading">
	<div class="abt-container">
		<div class="abt-sec-head reveal">
			<span class="abt-sec-label">Recognition</span>
			<h2 class="abt-sec-title" id="awards-heading">Awards &amp; Achievements</h2>
			<p class="abt-sec-sub">Celebrating excellence through innovation and industry recognition.</p>
		</div>
		<div class="abt-recognition-wrap">

			<!-- Left Side: Text Content -->
			<div class="abt-recognition-content">
				<h2 class="abt-recognition-heading" id="awards-heading">
					Celebrating Our <span class="abt-recognition-accent">Achievements</span>
				</h2>
				<p class="abt-recognition-para">Celebrating our achievements and industry recognition, our awards reflect a commitment to excellence, innovation, and technological leadership. Each recognition represents our dedication to delivering impactful solutions, exceeding industry standards, and continuously advancing the future of technology.</p>
				<a href="#about-cwt" class="abt-recognition-cta" aria-label="View our about section for more details">View Details</a>
			</div>

			<!-- Right Side: Carousel -->
			<div class="abt-recognition-showcase" id="abtRecognitionShowcase" data-interval="2000" aria-label="Award images carousel">
				<?php
				$awardSampleDir = __DIR__ . '/uploads/award/sample';
				$awardSampleImages = [];
				if (is_dir($awardSampleDir)) {
					$scanFiles = scandir($awardSampleDir);
					if ($scanFiles !== false) {
						natsort($scanFiles);
						foreach ($scanFiles as $f) {
							if ($f === '.' || $f === '..') continue;
							$ext = strtolower(pathinfo($f, PATHINFO_EXTENSION));
							if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg'])) {
								$awardSampleImages[] = $f;
							}
						}
					}
				}
				// Fallback if directory is empty
				if (empty($awardSampleImages)) {
					$awardSampleImages = ['award sample - 1.jpg', 'award sample - 2.jpeg', 'certificate sample-1.jpg'];
				}
				// Guarantee at least 3 cards for carousel structure
				if (count($awardSampleImages) === 1) {
					$awardSampleImages = [$awardSampleImages[0], $awardSampleImages[0], $awardSampleImages[0]];
				} elseif (count($awardSampleImages) === 2) {
					$awardSampleImages = [$awardSampleImages[0], $awardSampleImages[1], $awardSampleImages[0]];
				}

				foreach ($awardSampleImages as $idx => $imgFile):
					$imgUrl = SITE_URL . '/uploads/award/sample/' . rawurlencode($imgFile);
					$rawTitle = pathinfo($imgFile, PATHINFO_FILENAME);
					$altText = ucwords(trim(str_replace(['-', '_'], ' ', preg_replace('/\s+/', ' ', $rawTitle))));

					// Initial positions:
					// Card 0: left
					// Card 1: center (featured)
					// Card 2: right
					// Card 3+: hidden offstage right
					$posClass = 'abt-recognition-card--hidden-right';
					if ($idx === 0) {
						$posClass = 'abt-recognition-card--left';
					} elseif ($idx === 1) {
						$posClass = 'abt-recognition-card--center';
					} elseif ($idx === 2) {
						$posClass = 'abt-recognition-card--right';
					}
				?>
				<div class="abt-recognition-card <?= $posClass ?>" id="abtRecCard<?= $idx ?>" data-index="<?= $idx ?>" aria-label="<?= e($altText) ?>">
					<img src="<?= e($imgUrl) ?>" alt="<?= e($altText) ?>" loading="lazy" draggable="false">
				</div>
				<?php endforeach; ?>

				<!-- Navigation Arrows -->
				<div class="abt-recognition-nav" role="group" aria-label="Carousel navigation">
					<button class="abt-recognition-arrow abt-recognition-arrow--prev" id="abtRecPrev" aria-label="Previous award">&#10094;</button>
					<button class="abt-recognition-arrow abt-recognition-arrow--next" id="abtRecNext" aria-label="Next award">&#10095;</button>
				</div>
			</div>

		</div><!-- /.abt-recognition-wrap -->

		<!-- Decorative glow -->
		<span class="abt-recognition-glow" aria-hidden="true"></span>

	</div>
</section>

<?php if (!empty($vm)): ?>
<section class="abt-section abt-section-tint" id="vision-mission" aria-labelledby="vm-heading">
	<div class="abt-container">
		<div class="abt-sec-head reveal">
			<span class="abt-sec-label">Direction</span>
			<h2 class="abt-sec-title" id="vm-heading">Vision &amp; Mission</h2>
			<p class="abt-sec-sub">The principles that guide every engagement and every decision we make.</p>
		</div>

		<div class="abt-vm-grid">
			<article class="abt-vm-card reveal">
				<span class="abt-vm-icon">
					<i class="fa-solid fa-eye" aria-hidden="true"></i>
				</span>
				<h3 class="abt-vm-title">Vision</h3>
				<p class="abt-vm-text"><?= nl2br(e($vm['vision_para'] ?? '')) ?></p>
				<span class="abt-vm-glow" aria-hidden="true"></span>
			</article>
			<article class="abt-vm-card reveal">
				<span class="abt-vm-icon">
					<i class="fa-solid fa-bullseye" aria-hidden="true"></i>
				</span>
				<h3 class="abt-vm-title">Mission</h3>
				<p class="abt-vm-text"><?= nl2br(e($vm['mission_para'] ?? '')) ?></p>
				<span class="abt-vm-glow" aria-hidden="true"></span>
			</article>
		</div>
	</div>
</section>
<?php endif; ?>

<!-- =========================================================
     OUR CREW (merged from our-crew.php)
     Sits after Vision & Mission, before the closing CTA
     ========================================================= -->
<div id="our-crew">

<?php if ($ceo):
	$ceoPhoto = !empty($ceo['profile_photo']) ? imgUrl($ceo['profile_photo'], $placeholder) : $placeholder;
?>
<section class="cr-section cr-section-dark" aria-labelledby="cr-ceo-heading">
	<div class="cr-container">
		<div class="cr-sec-head cr-sec-head-left reveal">
			<span class="cr-sec-label">Leadership</span>
			<h2 class="cr-sec-title" id="cr-ceo-heading">Meet Our <em>Director &amp; CEO</em></h2>
		</div>

		<article class="cr-ceo-card reveal">
			<span class="cr-ceo-card-glow" aria-hidden="true"></span>
			<span class="cr-ceo-card-shine" aria-hidden="true"></span>

			<div class="cr-ceo-photo">
				<img src="<?= e($ceoPhoto) ?>" alt="<?= e($ceo['name']) ?>"
					 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
				<span class="cr-ceo-photo-ring" aria-hidden="true"></span>
			</div>

			<div class="cr-ceo-body">
				<h3 class="cr-ceo-name"><?= e($ceo['name']) ?></h3>
				<svg class="cr-ceo-signature" viewBox="0 0 260 28" fill="none" aria-hidden="true" focusable="false">
					<path class="cr-ceo-underline-main" pathLength="1" d="M5 19C34 14 65 12 99 11C140 9 184 7 220 11C232 12 243 14 254 17"/>
					<path class="cr-ceo-underline-return" pathLength="1" d="M248 21C223 16 197 17 170 22"/>
				</svg>
				<p class="cr-ceo-role"><?= e($ceo['position']) ?></p>
				<p class="cr-ceo-text">
					Leading our team with vision, expertise, and an unwavering commitment to technology excellence.
				</p>

				<?php if (!empty($ceo['linkedin_url'])): ?>
				<a href="<?= e($ceo['linkedin_url']) ?>" target="_blank" rel="noopener"
				   class="cr-ceo-link" aria-label="<?= e($ceo['name']) ?> on LinkedIn">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					<span>Connect on LinkedIn</span>
				</a>
				<?php endif; ?>
			</div>
		</article>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($experts)): ?>
<section class="cr-section cr-section-light" aria-labelledby="cr-experts-heading">
	<div class="cr-container">
		<div class="cr-sec-head reveal">
			<span class="cr-sec-label">Expert Consultants</span>
			<h2 class="cr-sec-title" id="cr-experts-heading">Specialists Shaping <em>Our Craft</em></h2>
			<p class="cr-sec-sub"><?= count($experts) ?> senior consultants leading key verticals across engineering, AI, security, and strategy.</p>
		</div>

		<div class="cr-experts-grid">
			<?php foreach ($experts as $i => $m):
				$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
			?>
			<article class="cr-expert-card reveal" style="--i:<?= $i ?>">
				<span class="cr-expert-glow" aria-hidden="true"></span>
				<span class="cr-expert-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>

				<div class="cr-expert-photo">
					<img src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
						 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
					<span class="cr-expert-photo-shine" aria-hidden="true"></span>
				</div>

				<div class="cr-expert-body">
					<h3 class="cr-expert-name"><?= e($m['name']) ?></h3>
					<p class="cr-expert-role"><?= e($m['position']) ?></p>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" target="_blank" rel="noopener"
					   class="cr-expert-linkedin" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($teamCrew)): ?>
<section class="cr-section cr-section-tint" aria-labelledby="cr-team-heading">
	<div class="cr-container">
		<div class="cr-sec-head reveal">
			<span class="cr-sec-label">Our Crew</span>
			<h2 class="cr-sec-title" id="cr-team-heading">The People Behind <em>Every Project</em></h2>
			<p class="cr-sec-sub">Engineers, analysts, designers, and specialists — meet the team that brings the work to life.</p>
		</div>

		<div class="cr-team" id="crTeam">

			<div class="cr-team-photos">
				<?php foreach ($teamCols as $colIdx => $colItems): ?>
				<div class="cr-team-col cr-team-col-<?= $colIdx + 1 ?>">
					<?php foreach ($colItems as $item):
						$m = $item['member'];
						$n = $item['number'];
						$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
					?>
					<div class="cr-team-photo" data-id="<?= (int)$n ?>">
						<img src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
							 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
						<span class="cr-team-photo-overlay" aria-hidden="true"></span>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="cr-team-details">
				<span class="cr-team-details-eyebrow">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="11" height="11" fill="currentColor" aria-hidden="true"><path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>
					Team Directory
				</span>
				<?php foreach ($teamCrew as $i => $m):
					$n = $i + 1;
					$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
				?>
				<div class="cr-team-item" data-id="<?= (int)$n ?>" tabindex="0">
					<span class="cr-team-item-dot" aria-hidden="true"></span>

					<div class="cr-team-item-avatar" aria-hidden="true">
						<img src="<?= e($photo) ?>" alt=""
							 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
					</div>

					<div class="cr-team-item-body">
						<div class="cr-team-item-head">
							<h3 class="cr-team-item-name"><?= e($m['name']) ?></h3>
							<?php if ((int)$m['intern'] === 1): ?>
							<span class="cr-team-item-badge">Intern</span>
							<?php endif; ?>
						</div>
						<p class="cr-team-item-role"><?= e($m['position']) ?></p>
					</div>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" class="cr-team-item-linkedin" target="_blank" rel="noopener" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

		<div class="cr-team-cards" aria-label="Team members">
			<?php foreach ($teamCrew as $i => $m):
				$n     = $i + 1;
				$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
			?>
			<article class="cr-team-card" style="--cr-img:url('<?= e($photo) ?>');">

				<img class="cr-team-card-img" src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
					 loading="lazy"
					 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">

				<span class="cr-team-card-shade" aria-hidden="true"></span>

				<div class="cr-team-card-body">
					<div class="cr-team-card-text">
						<h3 class="cr-team-card-name"><?= e($m['name']) ?></h3>
						<p class="cr-team-card-role"><?= e($m['position']) ?></p>
						<?php if ((int)$m['intern'] === 1): ?>
							<span class="cr-team-card-badge">Intern</span>
						<?php endif; ?>
					</div>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" target="_blank" rel="noopener"
					   class="cr-team-card-linkedin" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
<?php endif; ?>

</div><!-- /#our-crew -->

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/about.js?v=<?= filemtime(__DIR__ . '/assets/js/about.js') ?>"></script>
</body>
</html>