<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$about  = getAboutUs($pdo);
$hist   = getHistory($pdo);
$vm     = getVisionMission($pdo);

$yearsExp = !empty($about['start_year']) ? calcYearsExperience((int)$about['start_year']) : 20;
$retention = $about['retention_pct']   ?? 95;
$employed  = $about['employed_count']  ?? 200;
$projects  = $about['projects_count']  ?? 55;

$pageTitle = 'About Us — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="About Creative Web Technologies — our story, mission, vision, team expertise, and commitment to technology excellence.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/about.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="abt-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

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
				Built on expertise, collaboration, and an unwavering commitment to technology excellence.
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
</section>

<section class="abt-section abt-section-light" id="about-cwt" aria-labelledby="about-heading">
	<div class="abt-container">
		<div class="abt-about-grid">
			<div class="abt-about-text reveal">
				<span class="abt-sec-label">Who We Are</span>
				<h2 class="abt-sec-title" id="about-heading">About Creative Web Technologies</h2>
				<p class="abt-para">CWT was founded on the principle that true capability is built on a foundation of expertise, collaboration, and a commitment to excellence.</p>
				<p class="abt-para">Our story is one of purpose. We are a collective of engineers, architects, strategists, and specialists who are passionate about solving complex business challenges with robust, secure, and innovative technology solutions. We don't just build software; we build the frameworks that empower our clients to succeed and our team to grow.</p>
				<p class="abt-para">Our strength lies in our people and our structured approach. We have organised our deep expertise into distinct capability streams, ensuring we deliver focused, best-in-class solutions for every facet of our clients' needs.</p>
				<p class="abt-para">This isn't just about the services we offer; it's about why we do it. Our identity is defined by a passion for technology, a steadfast commitment to security, and a belief in the power of our collective innovation to solve global problems.</p>
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
			<p class="abt-history-para reveal"><?= nl2br(e($hist['para1'])) ?></p>
			<?php endif; ?>

			<?php $hist1 = imgUrl($hist['image1_path'] ?? ''); ?>
			<?php if ($hist1): ?>
			<div class="abt-history-media reveal">
				<img src="<?= e($hist1) ?>" alt="CWT History" class="abt-history-image" loading="lazy">
			</div>
			<?php endif; ?>

			<?php $hasMore = !empty($hist['para2']) || !empty($hist['para3']) || !empty($hist['image2_path']); ?>
			<?php if ($hasMore): ?>
			<div class="abt-history-more" id="historyMore">
				<?php if (!empty($hist['para2'])): ?>
				<p class="abt-history-para"><?= nl2br(e($hist['para2'])) ?></p>
				<?php endif; ?>

				<?php $hist2 = imgUrl($hist['image2_path'] ?? ''); ?>
				<?php if ($hist2): ?>
				<div class="abt-history-media">
					<img src="<?= e($hist2) ?>" alt="CWT History" class="abt-history-image" loading="lazy">
				</div>
				<?php endif; ?>

				<?php if (!empty($hist['para3'])): ?>
				<p class="abt-history-para"><?= nl2br(e($hist['para3'])) ?></p>
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

<section class="abt-values" id="values" aria-labelledby="values-heading">
	<div class="abt-values-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="abt-container">
		<div class="abt-values-head reveal">
			<span class="abt-values-eyebrow">Our DNA</span>
			<h2 class="abt-values-title" id="values-heading">Our Core Values</h2>
			<p class="abt-values-sub">The values we live by every day — with our team, our clients, and our community.</p>
		</div>
		<div class="abt-values-grid reveal">
			<?php
			$values = [
				['fa-trophy',    'Excellence',    'We set high standards and deliver nothing less.'],
				['fa-handshake', 'Integrity',     'Transparency and honesty in every engagement.'],
				['fa-lightbulb', 'Innovation',    'Embracing emerging tech to solve real problems.'],
				['fa-lock',      'Security',      'ISO-certified processes protecting your assets.'],
				['fa-seedling',  'Growth',        'We grow together — our team and your business.'],
				['fa-globe',     'Collaboration', 'True partnership, not just vendor relationships.'],
			];
			foreach ($values as $val):
			?>
			<article class="abt-value-card">
				<span class="abt-value-icon">
					<i class="fa-solid <?= $val[0] ?>" aria-hidden="true"></i>
				</span>
				<h3 class="abt-value-title"><?= e($val[1]) ?></h3>
				<p class="abt-value-text"><?= e($val[2]) ?></p>
				<span class="abt-value-line" aria-hidden="true"></span>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/about.js"></script>
</body>
</html>