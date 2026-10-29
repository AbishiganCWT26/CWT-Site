<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/world-map.php';

$categories = getSolutionCategories($pdo, true);
$portfolioClients = getClients($pdo);
$clientCountries = worldMapSelected($pdo);

foreach ($categories as &$cat) {
	$cat['projects'] = getProjectsByCategory($pdo, (int)$cat['id'], true);
	$cat['project_count'] = count($cat['projects']);
}
unset($cat);

usort($categories, function($a, $b) {
	if ($a['project_count'] === $b['project_count']) {
		return (int)$a['sort_order'] - (int)$b['sort_order'];
	}
	return $b['project_count'] - $a['project_count'];
});

$pageTitle = 'Our Digital Solutions Portfolio — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Explore Creative Web Technologies' digital solutions portfolio — transforming data into smarter decisions across industries.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/products.css?v=<?= filemtime(__DIR__ . '/assets/css/products.css') ?>">
	<script>document.documentElement.classList.add('js');</script>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css?v=<?= filemtime(__DIR__ . '/assets/css/modern.css') ?>">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
<?php require_once __DIR__ . '/includes/theme.php'; ?>
</head>
<body class="prd-page cwt-public">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">

<section class="prd-hero" aria-label="Portfolio hero">
	<div class="prd-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="prd-hero-grid" aria-hidden="true"></div>
	<div class="prd-container">
		<div class="prd-hero-inner">
			<span class="prd-hero-pill">
				<span class="prd-hero-pill-dot"></span>
				Digital Solutions Portfolio
			</span>
			<h1 class="prd-hero-title">
				Our Digital Solutions <span class="prd-hero-accent">Portfolio</span>
			</h1>
			<p class="prd-hero-text">
				Transforming Data into Smarter Decisions for a Better Tomorrow. Explore the solutions we've delivered across industries, built with the latest technology and a relentless focus on outcomes.
			</p>
		</div>
	</div>

	<svg class="hero-swirl hero-swirl-tr" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="heroSwirlGradTR" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
				<stop offset="0%" stop-color="#5b9cff" stop-opacity="0"/>
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
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="3" opacity="0.75" transform="translate(-4,-2)"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="2.2" opacity="1"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="#ffffff" stroke-width="0.9" opacity="0.85" transform="translate(6,6)"/>
		</g>
	</svg>

	<svg class="hero-swirl hero-swirl-bl" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="heroSwirlGradBL" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
				<stop offset="0%" stop-color="#7ea0f8" stop-opacity="0"/>
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
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2" opacity="1"/>
		</g>
	</svg>

	
</section>

<section class="prd-clients prd-section" id="our-clients" aria-labelledby="prd-clients-heading">
 <div class="prd-container">
  <div class="prd-clients-heading">
   <span class="prd-clients-eyebrow">Our Clients</span>
   <h2 id="prd-clients-heading">Great work knows no borders.</h2>
   <p>Built in Sri Lanka. Made for ambitious teams.</p>
  </div>
  <div class="prd-client-map-slot">
   <div class="prd-travel-map">
    <img class="prd-travel-map-base" src="<?= SITE_URL ?>/World-Map-Dots.svg" alt="">
    <?php foreach ($clientCountries as $country): ?>
    <button type="button" class="prd-country-pin" data-country="<?= e($country['code']) ?>" aria-label="<?= e($country['name']) ?>" style="left:<?= $country['x'] / 2640.041 * 100 ?>%;top:<?= $country['y'] / 1226.7573 * 100 ?>%"><span aria-hidden="true"><?= e($country['name']) ?></span></button>
    <?php endforeach; ?>
   </div>
   <div class="prd-travel-highlight" aria-hidden="true">
    <img src="<?= SITE_URL ?>/assets/Images/sri-lanka-highlight.svg?v=2" alt="">
   </div>
  </div>
  <?php if ($clientCountries): ?>
  <h3 class="prd-marquee-title">Countries We Have Worked With</h3>
  <ul class="prd-client-countries" aria-label="Countries we have worked with">
   <?php foreach ($clientCountries as $country): ?><li><?= e($country['name']) ?></li><?php endforeach; ?>
  </ul>
  <?php endif; ?>
  <?php if ($portfolioClients): ?>
  <h3 class="prd-marquee-title">Worldwide Trusted Clients</h3>
  <div class="prd-client-logos" aria-label="Our clients">
   <?php foreach ($portfolioClients as $client): $clientLogo = imgUrl($client['logo_path']); ?>
   <div><?php if ($clientLogo): ?><img src="<?= e($clientLogo) ?>" alt="<?= e($client['company_name']) ?>" loading="lazy"><?php else: ?><span><?= e($client['company_name']) ?></span><?php endif; ?></div>
   <?php endforeach; ?>
  </div>
  <?php endif; ?>
 </div>
</section>

<section class="prd-section prd-section-light" id="portfolio" aria-labelledby="portfolio-heading">
	<div class="prd-container">

		<?php if (empty($categories)): ?>
			<div class="prd-empty reveal">
				<span class="prd-empty-icon"><i class="fa-solid fa-rocket" aria-hidden="true"></i></span>
				<p class="prd-empty-text">Solutions coming soon. <a href="<?= ADMIN_URL ?>/solution-category.php">Add via admin</a>.</p>
			</div>
		<?php else: ?>
			<div class="prd-portfolio-rows">
				<?php foreach ($categories as $index => $solution): ?>
				<?php
					$rowSide       = ($index % 2 === 0) ? 'left' : 'right';
					$projects      = $solution['projects'];
					$projectCount  = count($projects);
					$isSingle      = ($projectCount === 1);
					$fullWidthLast = ($projectCount > 1 && $projectCount % 2 !== 0);
				?>
				<div class="prd-row prd-row-<?= $rowSide ?> reveal" style="--cat-color: <?= e($solution['color']) ?>;">
					<div class="prd-image-area">
						<div class="prd-project-grid">
							<?php if (empty($projects)): ?>
							<div class="prd-project-card prd-project-card-empty prd-project-card-full">
								<h4>No projects yet</h4>
								<p>Coming soon</p>
							</div>
							<?php else: ?>
								<?php foreach ($projects as $pIndex => $item): ?>
								<?php
									$isLast    = ($pIndex === $projectCount - 1);
									$fullClass = ($isSingle || ($fullWidthLast && $isLast)) ? ' prd-project-card-full' : '';
									$hasImg    = !empty($item['image_path']);
									$bgStyle   = $hasImg ? 'style="--item-bg: url(\'' . e(imgUrl($item['image_path'])) . '\');"' : '';
								?>
								<a href="project-detail.php?slug=<?= urlencode($item['slug']) ?>" class="prd-project-card<?= $fullClass ?>" <?= $bgStyle ?>>
									<h4><?= e($item['project_name']) ?></h4>
									<?php if (!empty($item['short_description'])): ?>
									<p><?= e($item['short_description']) ?></p>
									<?php endif; ?>
								</a>
								<?php endforeach; ?>
							<?php endif; ?>
						</div>
					</div>
					<div class="prd-text-area">
						<h3><?= e(strtoupper($solution['name'])) ?></h3>
						<div class="prd-line"></div>
						<?php if (!empty($solution['description'])): ?>
						<p><?= e($solution['description']) ?></p>
						<?php endif; ?>
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>
	</div>
</section>



</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/products.js?v=<?= filemtime(__DIR__ . '/assets/js/products.js') ?>"></script>
</body>
</html>