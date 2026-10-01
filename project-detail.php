<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = $_GET['slug'] ?? '';
if ($slug === '') {
	header('Location: ' . SITE_URL . '/new-1.php');
	exit;
}

$project = getProjectWithDetails($pdo, $slug);
if (empty($project) || (int)$project['is_active'] !== 1) {
	http_response_code(404);
	header('Location: ' . SITE_URL . '/new-1.php');
	exit;
}

$details = $project['details'] ?? [];
$hasContent = !empty($details['content']) && trim(strip_tags($details['content'])) !== '';
$bannerImg  = !empty($details['banner_image']) ? imgUrl($details['banner_image']) : imgUrl($project['image_path'] ?? '');

$relatedProjects = [];
if (!empty($project['category_id'])) {
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE category_id = ? AND id != ? AND is_active = 1 ORDER BY sort_order ASC LIMIT 4");
	$stmt->execute([$project['category_id'], $project['id']]);
	$relatedProjects = $stmt->fetchAll();
}

$techList = [];
if (!empty($details['technologies'])) {
	$techList = array_filter(array_map('trim', explode(',', $details['technologies'])));
}

// ─── Mockup images ────────────────────────────────────────────────────────────
$mockupUrls = getProjectMockupUrls($pdo, (int)$project['id']);
$hasMockups = !empty($mockupUrls);

$pageTitle = $project['project_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($project['project_name']) ?> — Creative Web Technologies</title>
	<meta name="description" content="<?= htmlspecialchars($project['short_description'] ?? '') ?>">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/project-detail.css">
</head>
<body>

<div class="project-wrap">
	<a href="products.php" class="back-link"><i class="fas fa-arrow-left"></i> Back to Portfolio</a>

	<article class="project-hero">
		<?php if ($bannerImg): ?>
		<img src="<?= e($bannerImg) ?>" alt="<?= e($project['project_name']) ?>" class="hero-banner">
		<?php endif; ?>
		<div class="hero-body">
			<?php if (!empty($project['category_name'])): ?>
			<span class="cat-badge" style="background: <?= e($project['category_color'] ?? '#3b82f6') ?>;">
				<i class="fas <?= e($project['category_icon'] ?? 'fa-folder') ?>"></i>
				<?= e($project['category_name']) ?>
			</span>
			<?php endif; ?>

			<h1 class="project-title"><?= e($project['project_name']) ?></h1>
			<?php if (!empty($project['short_description'])): ?>
			<p class="project-sub"><?= e($project['short_description']) ?></p>
			<?php endif; ?>

			<?php if (!empty($details['client_name']) || !empty($details['industry']) || !empty($details['duration'])): ?>
			<div class="meta-strip">
				<?php if (!empty($details['client_name'])): ?>
				<div class="meta-item">
					<span class="meta-label">Client</span>
					<span class="meta-value"><?= e($details['client_name']) ?></span>
				</div>
				<?php endif; ?>
				<?php if (!empty($details['industry'])): ?>
				<div class="meta-item">
					<span class="meta-label">Industry</span>
					<span class="meta-value"><?= e($details['industry']) ?></span>
				</div>
				<?php endif; ?>
				<?php if (!empty($details['duration'])): ?>
				<div class="meta-item">
					<span class="meta-label">Duration</span>
					<span class="meta-value"><?= e($details['duration']) ?></span>
				</div>
				<?php endif; ?>
			</div>
			<?php endif; ?>

			<?php if (!empty($techList)): ?>
			<div class="tech-chips">
				<?php foreach ($techList as $t): ?>
				<span class="tech-chip"><i class="fas fa-code" style="margin-right:5px;font-size:10px;"></i><?= e($t) ?></span>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</article>

	<?php if ($hasContent): ?>
	<section class="content-section">
		<?= $details['content'] ?>
	</section>
	<?php else: ?>
	<section class="empty-content">
		<i class="fas fa-file-lines"></i>
		<h3>Project details coming soon</h3>
		<p>Detailed content for this project is being prepared.</p>
	</section>
	<?php endif; ?>



	<?php if ($hasMockups): ?>
	<!-- ===================== LAPTOP MOCKUP SHOWCASE ===================== -->
	<section class="mockup-section" id="mockupSection">
		<div class="mockup-heading">
			<span class="mockup-eyebrow"><i class="fas fa-desktop"></i> Interactive Preview</span>
			<h2 class="mockup-title">Project Showcase</h2>
			<p class="mockup-sub">A visual walkthrough of the delivered solution.</p>
		</div>

		<div class="stage" id="mockupStage" data-images="<?= e(json_encode($mockupUrls)) ?>">
			<div class="lid">
				<div class="screen" id="mockupScreen">
					<div class="dots" id="mockupDots"></div>
					<div class="bar" id="mockupBar"></div>
				</div>
			</div>
			<div class="base"></div>
		</div>
	</section>
	<!-- ===================== /LAPTOP MOCKUP SHOWCASE ===================== -->
	<?php endif; ?>



	<?php if (!empty($relatedProjects)): ?>
	<h2 class="related-title">More in <?= e($project['category_name']) ?></h2>
	<div class="related-grid">
		<?php foreach ($relatedProjects as $rp): ?>
		<a href="project-detail.php?slug=<?= urlencode($rp['slug']) ?>" class="related-card">
			<h4><?= e($rp['project_name']) ?></h4>
			<p><?= e($rp['short_description'] ?? '') ?></p>
		</a>
		<?php endforeach; ?>
	</div>
	<?php endif; ?>
</div>

<script src="<?= SITE_URL ?>/assets/js/project-detail.js"></script>
</body>
</html>