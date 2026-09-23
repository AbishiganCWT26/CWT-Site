<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$products  = getProducts($pdo);
$pageTitle = 'Our Products — Creative Web Technologies';

$productIcons = ['🚀','💎','🛠️','🔮','⚡','🌐'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Explore Creative Web Technologies' product portfolio — innovative AI-powered solutions built for real-world business challenges.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/products.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="prd-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="prd-hero" aria-label="Products page hero">
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
				Product Portfolio
			</span>
			<h1 class="prd-hero-title">
				What We Have <span class="prd-hero-accent">Done</span>
			</h1>
			<p class="prd-hero-text">
				This portfolio highlights our most impactful work, showcasing the tangible results we've achieved and demonstrating how we turn ambitious ideas into real-world success.
			</p>
		</div>
	</div>
</section>

<section class="prd-section prd-section-light" id="products" aria-labelledby="products-heading">
	<div class="prd-container">

		<?php if (empty($products)): ?>
			<div class="prd-empty reveal">
				<span class="prd-empty-icon"><i class="fa-solid fa-rocket" aria-hidden="true"></i></span>
				<p class="prd-empty-text">Products coming soon. <a href="<?= ADMIN_URL ?>/products.php">Add via admin</a>.</p>
			</div>
		<?php else: ?>
			<div class="prd-carousel" id="prdCarousel">

				<!-- horizontally scrolling track (mobile/tablet) / paginated grid (desktop) -->
				<div class="prd-grid" id="prdGrid" tabindex="0"
				     role="region" aria-label="Product portfolio, scrolls automatically">

					<?php foreach ($products as $i => $product): ?>
						<?php $logoUrl = imgUrl($product['logo_path']); ?>
						<article class="prd-card" tabindex="0" data-index="<?= $i ?>">

							<span class="prd-card-glow" aria-hidden="true"></span>
							<span class="prd-card-shine" aria-hidden="true"></span>
							<span class="prd-card-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>

							<!-- FRONT: logo + name (always visible) -->
							<div class="prd-card-face">
								<div class="prd-card-logo">
									<?php if ($logoUrl): ?>
										<img src="<?= e($logoUrl) ?>" alt="<?= e($product['product_name']) ?> logo" loading="lazy">
									<?php else: ?>
										<span class="prd-card-logo-placeholder"><?= $productIcons[$i % count($productIcons)] ?></span>
									<?php endif; ?>
								</div>
								<h3 class="prd-card-title"><?= e($product['product_name']) ?></h3>
							</div>

							<!-- HOVER REVEAL: description + CTA -->
							<div class="prd-card-reveal">
								<div class="prd-card-reveal-inner">
									<p class="prd-card-desc"><?= nl2br(e($product['description'])) ?></p>

									<?php if ($product['show_button'] && !empty($product['visit_url'])): ?>
										<a href="<?= e($product['visit_url']) ?>"
										   target="_blank"
										   rel="noopener noreferrer"
										   class="prd-card-cta">
											<span>Visit Site</span>
											<svg viewBox="0 0 16 16" aria-hidden="true" focusable="false"><path fill-rule="evenodd" d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5"/><path fill-rule="evenodd" d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0z"/></svg>
										</a>
									<?php endif; ?>
								</div>
							</div>

						</article>
					<?php endforeach; ?>

				</div>

				<!-- dots (mobile / tablet only) -->
				<div class="prd-dots" id="prdDots" aria-label="Card navigation"></div>

				<!-- pagination (desktop only) -->
				<div class="prd-pagination" id="prdPagination" aria-label="Product Page Navigation">
					<button type="button" class="prd-pag-btn prd-pag-prev" id="prdPrevBtn" aria-label="Previous Page">
						<i class="fa-solid fa-chevron-left" aria-hidden="true"></i>
					</button>
					<div class="prd-pag-dots" id="prdPagDots" role="tablist" aria-label="Page selection"></div>
					<button type="button" class="prd-pag-btn prd-pag-next" id="prdNextBtn" aria-label="Next Page">
						<i class="fa-solid fa-chevron-right" aria-hidden="true"></i>
					</button>
				</div>

			</div>
		<?php endif; ?>
	</div>
</section>

<section class="prd-cta">
	<div class="prd-cta-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="prd-container">
		<div class="prd-cta-inner reveal">
			<span class="prd-cta-eyebrow">Let's Build</span>
			<h2 class="prd-cta-title">Have a Project in Mind?</h2>
			<p class="prd-cta-text">Let's build the next great thing together. <br> Our teams are ready to bring your ideas to life.</p>
			<a href="<?= SITE_URL ?>/services.php" class="prd-cta-btn">
				<span>Explore Our Services</span>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/products.js"></script>
</body>
</html>