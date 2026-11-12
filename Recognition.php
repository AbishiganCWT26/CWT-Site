<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$awards = [];
$certifications = [];
$appreciations = [];
try {
	$awards = get_awards($pdo, 'award');
	$certifications = get_awards($pdo, 'certification');
	$appreciations = get_awards($pdo, 'client_appreciation');
} catch (Throwable $err) {
	$awards = [];
	$certifications = [];
	$appreciations = [];
}

$perPageDesktop = 3;
$perPageTablet  = 2;
$perPageMobile  = 2;

$gridPerDesktop = 6;
$gridPerTablet  = 4;
$gridPerMobile  = 2;

$pageTitle = 'Recognition — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Celebrating Creative Web Technologies' awards and achievements — a commitment to excellence, innovation, and technological leadership.">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/Recognition.css?v=<?= filemtime(__DIR__ . '/assets/css/Recognition.css') ?>">
	<script>document.documentElement.classList.add('js');</script>
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css?v=<?= filemtime(__DIR__ . '/assets/css/modern.css') ?>">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
	<?php require_once __DIR__ . '/includes/theme.php'; ?>
</head>
<body class="rcg-page cwt-public">
<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">

<section class="svc-hero" aria-label="Recognition page hero">
	<div class="svc-hero-grid" aria-hidden="true"></div>

	<div class="svc-wrap">
		<div class="svc-hero-inner">
			<span class="svc-hero-pill">
				<span class="svc-hero-dot"></span>
				Awards &amp; Achievements
			</span>
			<h1 class="svc-hero-title">Our <span class="svc-hero-accent">Recognition</span></h1>
			<p class="svc-hero-text">A testament to our skill and dedication, our latest awards and recognitions have set a new standard in the industry</p>
			<p class="rcg-hero-support">Categorised across our departments and our company, here are some of our global awards and recognitions that we are proud to have, since our inception in 2020.</p>
		</div>
	</div>
</section>

<section class="rcg-section" id="awards" aria-labelledby="awards-section-heading">
	<div class="rcg-container">

		<div class="rcg-sec-head reveal">
			<span class="rcg-sec-label">Recognition</span>
			<h2 class="rcg-sec-title" id="awards-section-heading">Awards &amp; Achievements</h2>
			<p class="rcg-sec-sub">Celebrating our commitment to excellence, innovation, and technological leadership.</p>
		</div>

		<?php if (empty($awards)): ?>
		<div class="rcg-empty reveal">
			<span class="rcg-empty-icon">
				<i class="fa-solid fa-trophy" aria-hidden="true"></i>
			</span>
			<p class="rcg-empty-text">Awards coming soon. <a href="<?= ADMIN_URL ?>/awards.php">Add via admin</a>.</p>
		</div>
		<?php else: ?>

		<div class="awards-grid" id="awardsGrid" aria-live="polite" aria-label="Award listings">
			<?php foreach ($awards as $i => $aw):
				$awId    = (int)$aw['id'];
				$imgFile = trim((string)($aw['image'] ?? ''));
				$imgUrl  = $imgFile !== '' ? SITE_URL . '/uploads/award/' . rawurlencode($imgFile) : '';
				$file3d  = basename(trim((string)($aw['3d_file'] ?? '')));
				$url3d   = ($file3d !== '' && is_file(__DIR__ . '/uploads/award/3D/' . $file3d)) ? SITE_URL . '/uploads/award/3D/' . rawurlencode($file3d) : '';
				$para    = trim((string)($aw['para'] ?? ''));
				$onPage  = $i < $perPageDesktop;
				$cls     = 'award-card' . ($i % 2 === 1 ? ' is-reversed' : '');
				$wrapCls = 'award-image-wrap' . ($imgUrl !== '' ? ' has-image' : '') . ($url3d !== '' ? ' has-3d' : '');
				$number  = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
				$chipIcon  = $url3d !== '' ? 'fa-cube' : 'fa-award';
				$chipLabel = $url3d !== '' ? '3D Award' : 'Award';
			?>
			<article class="<?= $cls ?>" data-index="<?= (int)$i ?>" aria-labelledby="award-title-<?= $awId ?>"<?= $onPage ? '' : ' hidden' ?>>
				<div class="<?= $wrapCls ?>">
					<?php if ($imgUrl !== ''): ?>
					<img class="award-image" src="<?= e($imgUrl) ?>" alt="<?= e($aw['title']) ?>" loading="lazy" draggable="false">
					<?php endif; ?>
					<div class="award-image-placeholder" aria-hidden="true">
						<i class="fa-solid fa-trophy"></i>
					</div>
					<?php if ($url3d !== ''): ?>
					<iframe class="award-3d" data-src="<?= e($url3d) ?>" title="<?= e($aw['title']) ?> 3D model" tabindex="-1" scrolling="no"></iframe>
					<?php endif; ?>
				</div>
				<div class="award-body">
					<div class="award-meta">
						<span class="award-index" aria-hidden="true"><?= e($number) ?></span>
						<span class="award-chip">
							<i class="fa-solid <?= $chipIcon ?>" aria-hidden="true"></i>
							<?= e($chipLabel) ?>
						</span>
					</div>
					<h3 class="award-title" id="award-title-<?= $awId ?>"><?= e($aw['title']) ?></h3>
					<?php if ($para !== ''): ?>
					<p class="award-para"><?= nl2br(e($para)) ?></p>
					<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

		<nav class="rcg-pagination" id="awardsPagination" aria-label="Awards pagination" data-desktop="<?= (int)$perPageDesktop ?>" data-tablet="<?= (int)$perPageTablet ?>" data-mobile="<?= (int)$perPageMobile ?>"></nav>

		<?php endif; ?>

	</div>
</section>

<section class="rcg-section rcg-cert-section" id="certifications" aria-labelledby="cert-section-heading">
	<div class="rcg-container">

		<div class="rcg-sec-head reveal">
			<span class="rcg-sec-label">Certifications</span>
			<h2 class="rcg-sec-title" id="cert-section-heading">Certified Excellence</h2>
			<p class="rcg-sec-sub">Industry certifications that validate the quality, security, and reliability of our work.</p>
		</div>

		<?php if (empty($certifications)): ?>
		<div class="rcg-empty reveal">
			<span class="rcg-empty-icon">
				<i class="fa-solid fa-certificate" aria-hidden="true"></i>
			</span>
			<p class="rcg-empty-text">Certifications coming soon.</p>
		</div>
		<?php else: ?>

		<div class="cert-carousel reveal" id="certCarousel" aria-label="Certifications">
			<div class="cert-track" id="certTrack">
				<?php foreach ($certifications as $ci => $cert):
					$certId   = (int)$cert['id'];
					$certFile = trim((string)($cert['image'] ?? ''));
					$certUrl  = $certFile !== '' ? SITE_URL . '/uploads/award/' . rawurlencode($certFile) : '';
					$certPara = trim((string)($cert['para'] ?? ''));
					$certOnPage = $ci < $gridPerDesktop;
				?>
				<article class="cert-card" aria-labelledby="cert-title-<?= $certId ?>"<?= $certOnPage ? '' : ' hidden' ?>>
					<div class="cert-media">
						<?php if ($certUrl !== ''): ?>
						<img class="cert-image" src="<?= e($certUrl) ?>" alt="<?= e($cert['title']) ?>" loading="lazy" draggable="false">
						<?php else: ?>
						<span class="cert-placeholder" aria-hidden="true"><i class="fa-solid fa-certificate"></i></span>
						<?php endif; ?>
					</div>
					<div class="cert-body">
						<h3 class="cert-title" id="cert-title-<?= $certId ?>"><?= e($cert['title']) ?></h3>
						<?php if ($certPara !== ''): ?>
						<p class="cert-para"><?= nl2br(e($certPara)) ?></p>
						<?php endif; ?>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
		</div>

		<nav class="rcg-pagination" id="certPagination" aria-label="Certifications pagination" data-desktop="<?= (int)$gridPerDesktop ?>" data-tablet="<?= (int)$gridPerTablet ?>" data-mobile="<?= (int)$gridPerMobile ?>"></nav>

		<?php endif; ?>

	</div>
</section>

<section class="rcg-section rcg-appr-section" id="appreciations" aria-labelledby="appr-section-heading">
	<div class="rcg-container">

		<div class="rcg-sec-head reveal">
			<span class="rcg-sec-label">Client Appreciations</span>
			<h2 class="rcg-sec-title" id="appr-section-heading">Words From Our Clients</h2>
			<p class="rcg-sec-sub">Appreciation from the clients we have had the privilege of working with.</p>
		</div>

		<?php if (empty($appreciations)): ?>
		<div class="rcg-empty reveal">
			<span class="rcg-empty-icon">
				<i class="fa-solid fa-handshake" aria-hidden="true"></i>
			</span>
			<p class="rcg-empty-text">Client appreciations coming soon.</p>
		</div>
		<?php else: ?>

		<div class="appr-grid reveal" id="apprGrid">
			<?php foreach ($appreciations as $ai => $appr):
				$apprId   = (int)$appr['id'];
				$apprFile = trim((string)($appr['image'] ?? ''));
				$apprUrl  = $apprFile !== '' ? SITE_URL . '/uploads/award/' . rawurlencode($apprFile) : '';
				$apprPara = trim((string)($appr['para'] ?? ''));
				$apprOnPage = $ai < $gridPerDesktop;
			?>
			<article class="appr-card" tabindex="0" role="button" aria-pressed="false" aria-label="<?= e($appr['title']) ?>"<?= $apprOnPage ? '' : ' hidden' ?>>
				<div class="appr-inner">
					<div class="appr-face appr-front">
						<?php if ($apprUrl !== ''): ?>
						<img class="appr-image" src="<?= e($apprUrl) ?>" alt="<?= e($appr['title']) ?>" loading="lazy" draggable="false">
						<?php else: ?>
						<span class="appr-placeholder" aria-hidden="true"><i class="fa-solid fa-handshake"></i></span>
						<?php endif; ?>
						<div class="appr-overlay" aria-hidden="true">
							<span class="appr-hint">
								<span class="appr-hint-touch">Tap to view details</span>
							</span>
						</div>
					</div>
					<div class="appr-face appr-back">
						<div class="appr-back-content">
							<span class="appr-icon" aria-hidden="true"><i class="fa-solid fa-handshake"></i></span>
							<h3 class="appr-title" id="appr-title-<?= $apprId ?>"><?= e($appr['title']) ?></h3>
							<?php if ($apprPara !== ''): ?>
							<p class="appr-para"><?= nl2br(e($apprPara)) ?></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

		<nav class="rcg-pagination" id="apprPagination" aria-label="Client appreciations pagination" data-desktop="<?= (int)$gridPerDesktop ?>" data-tablet="<?= (int)$gridPerTablet ?>" data-mobile="<?= (int)$gridPerMobile ?>"></nav>

		<?php endif; ?>

	</div>
</section>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/Recognition.js?v=<?= filemtime(__DIR__ . '/assets/js/Recognition.js') ?>"></script>
</body>
</html>