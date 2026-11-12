<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$awards = [];
try {
	$stmt = $pdo->prepare('SELECT id, image, `3d_file`, title, para FROM awards ORDER BY id ASC');
	$stmt->execute();
	$awards = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Throwable $err) {
	$awards = [];
}

$perPageDesktop = 5;
$perPageMobile  = 3;

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
			<p class="svc-hero-text">Celebrating excellence through innovation and industry recognition — each award reflects our commitment to delivering impactful technology solutions.</p>
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
				$chipLabel = $url3d !== '' ? 'Interactive 3D' : 'Award';
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

		<nav class="awards-pagination" id="awardsPagination" aria-label="Awards pagination" data-desktop="<?= (int)$perPageDesktop ?>" data-mobile="<?= (int)$perPageMobile ?>"></nav>

		<?php endif; ?>

	</div>
</section>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/Recognition.js?v=<?= filemtime(__DIR__ . '/assets/js/Recognition.js') ?>"></script>
</body>
</html>