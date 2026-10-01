<?php
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$categories = getSolutionCategories($pdo, true);

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

function decideGridSpan(int $count): int {
	if ($count <= 1) return 1;
	if ($count <= 3) return 2;
	return 2;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Creative Web Technologies - Digital Solutions Portfolio</title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	<style>
		:root {
			--bg-color: #f0f4f8;
			--text-main: #1e293b;
			--text-light: #64748b;
			--glass-bg: rgba(255, 255, 255, 0.55);
			--glass-bg-strong: rgba(255, 255, 255, 0.75);
			--glass-border: rgba(255, 255, 255, 0.85);
			--glass-border-soft: rgba(255, 255, 255, 0.55);
			--glass-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.12);
			--glass-shadow-hover: 0 20px 50px 0 rgba(31, 38, 135, 0.22);
			--glass-inner: inset 0 1px 0 0 rgba(255, 255, 255, 0.9);
		}
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
			font-family: 'Poppins', sans-serif;
		}
		body {
			background: linear-gradient(135deg, #e0eafc 0%, #cfdef3 100%);
			color: var(--text-main);
			min-height: 100vh;
			padding: 2rem;
			overflow-x: hidden;
			position: relative;
		}
		body::before,
		body::after {
			content: '';
			position: fixed;
			border-radius: 50%;
			filter: blur(120px);
			opacity: 0.55;
			z-index: 0;
			pointer-events: none;
		}
		body::before {
			width: 520px;
			height: 520px;
			top: -180px;
			left: -140px;
			background: radial-gradient(circle, #7ea0f8 0%, transparent 70%);
		}
		body::after {
			width: 460px;
			height: 460px;
			bottom: -180px;
			right: -140px;
			background: radial-gradient(circle, #a5b4fc 0%, transparent 70%);
		}
		header {
			text-align: center;
			margin-bottom: 3rem;
			animation: fadeInDown 1s ease;
			position: relative;
			z-index: 1;
		}
		header h1 {
			font-size: 2.5rem;
			color: #1e3a8a;
			font-weight: 700;
			margin-bottom: 0.5rem;
		}
		header p {
			font-size: 1.1rem;
			color: var(--text-light);
			margin-bottom: 1.5rem;
		}
		.portfolio-grid {
			display: grid;
			grid-template-columns: repeat(4, 1fr);
			gap: 1.5rem;
			max-width: 1400px;
			margin: 0 auto;
			position: relative;
			z-index: 1;
		}
		.card {
			background: var(--glass-bg);
			backdrop-filter: blur(20px) saturate(180%);
			-webkit-backdrop-filter: blur(20px) saturate(180%);
			border: 1px solid var(--glass-border);
			border-radius: 20px;
			padding: 1.5rem;
			box-shadow: var(--glass-shadow), var(--glass-inner);
			transition: transform 0.4s cubic-bezier(0.2, 0.8, 0.2, 1),
			            box-shadow 0.4s ease,
			            background 0.4s ease,
			            border-color 0.4s ease;
			position: relative;
			overflow: hidden;
			display: flex;
			flex-direction: column;
		}
		.card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			height: 50%;
			background: linear-gradient(180deg, rgba(255, 255, 255, 0.55), rgba(255, 255, 255, 0));
			pointer-events: none;
			border-radius: 20px 20px 0 0;
			opacity: 0.7;
		}
		.card::after {
			content: '';
			position: absolute;
			inset: 1px;
			border-radius: 19px;
			border: 1px solid rgba(255, 255, 255, 0.35);
			pointer-events: none;
			opacity: 0.6;
		}
		.card > * {
			position: relative;
			z-index: 1;
		}
		.card:hover {
			transform: translateY(-8px);
			background: var(--glass-bg-strong);
			box-shadow: var(--glass-shadow-hover), var(--glass-inner);
			border-color: rgba(255, 255, 255, 1);
		}
		.card-header {
			display: flex;
			align-items: center;
			gap: 0.8rem;
			margin-bottom: 0.5rem;
			border-bottom: 1px solid rgba(255, 255, 255, 0.7);
			padding-bottom: 0.8rem;
			text-shadow: 0 1px 1px rgba(255, 255, 255, 0.6);
		}
		.card-header i { font-size: 1.5rem; }
		.card-header h3 {
			font-size: 1rem;
			font-weight: 700;
			color: var(--text-main);
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
		.card-subtitle {
			font-size: 0.8rem;
			color: var(--text-light);
			margin-bottom: 1rem;
		}
		.card-items {
			display: flex;
			flex-wrap: wrap;
			gap: 1rem;
			flex-grow: 1;
		}
		.card-item {
			flex: 1 1 45%;
			background: rgba(255, 255, 255, 0.45);
			backdrop-filter: blur(8px);
			-webkit-backdrop-filter: blur(8px);
			border-radius: 12px;
			padding: 0.8rem;
			border: 1px solid rgba(255, 255, 255, 0.85);
			box-shadow: inset 0 1px 0 0 rgba(255, 255, 255, 0.95);
			transition: transform 0.3s ease,
			            background 0.3s ease,
			            box-shadow 0.3s ease,
			            border-color 0.3s ease;
			display: flex;
			flex-direction: column;
		}
		.card-item:hover {
			transform: translateY(-3px);
			background: rgba(255, 255, 255, 0.68);
			border-color: rgba(255, 255, 255, 1);
			box-shadow: 0 10px 24px rgba(31, 38, 135, 0.12),
			            inset 0 1px 0 0 rgba(255, 255, 255, 0.95);
		}
		.card-item h4 {
			font-size: 0.8rem;
			font-weight: 600;
			color: #1e3a8a;
			margin-bottom: 0.3rem;
		}
		.card-item h4 a {
			color: #1e3a8a;
			text-decoration: none;
			transition: color 0.25s ease;
		}
		.card-item h4 a:hover {
			color: #3b82f6;
			text-decoration: underline;
		}
		.card-item p {
			font-size: 0.7rem;
			color: var(--text-light);
			line-height: 1.3;
			flex-grow: 1;
			margin-bottom: 0.3rem;
		}
		.card-image {
			width: 100%;
			height: 80px;
			object-fit: cover;
			border-radius: 8px;
			margin-top: 0.6rem;
			margin-bottom: 0.6rem;
			border: 1px solid rgba(255, 255, 255, 0.7);
			box-shadow: 0 4px 14px rgba(31, 38, 135, 0.12);
			transition: transform 0.35s ease, box-shadow 0.35s ease;
		}
		.card-item:hover .card-image {
			transform: scale(1.03);
			box-shadow: 0 8px 22px rgba(31, 38, 135, 0.18);
		}
		.view-more-btn {
			display: inline-block;
			text-align: center;
			padding: 0.55rem 1rem;
			border-radius: 8px;
			color: #ffffff;
			text-decoration: none;
			font-size: 0.72rem;
			font-weight: 600;
			letter-spacing: 0.5px;
			transition: all 0.3s ease;
			margin-top: auto;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15),
			            inset 0 1px 0 0 rgba(255, 255, 255, 0.35);
			backdrop-filter: blur(4px);
			-webkit-backdrop-filter: blur(4px);
			border: 1px solid rgba(255, 255, 255, 0.25);
		}
		.view-more-btn:hover {
			transform: translateY(-2px);
			box-shadow: 0 8px 20px rgba(0, 0, 0, 0.22),
			            inset 0 1px 0 0 rgba(255, 255, 255, 0.45);
			filter: brightness(1.08);
			color: #ffffff;
		}
		@keyframes fadeInDown {
			from { opacity: 0; transform: translateY(-20px); }
			to { opacity: 1; transform: translateY(0); }
		}
		@media (max-width: 1024px) {
			.portfolio-grid { grid-template-columns: repeat(2, 1fr); }
			.card { grid-column: span 1 !important; }
			.card.grid-span-2 { grid-column: span 2 !important; }
		}
		@media (max-width: 450px) {
			body { padding: 1.25rem; }
			header { margin-bottom: 2rem; }
			header h1 { font-size: 1.75rem; }
			header p { font-size: 0.95rem; }
			.portfolio-grid { grid-template-columns: 1fr; gap: 1.25rem; }
			.card, .card.grid-span-2 { grid-column: span 1 !important; }
			.card { padding: 1.25rem; }
			.card-items { gap: 0.75rem; }
			.card-item { flex: 1 1 100%; }
			.card-image { height: 140px; }
		}
		@media (max-width: 480px) {
			body { padding: 1rem; }
			header h1 { font-size: 1.5rem; }
			header p { font-size: 0.85rem; }
			.card { padding: 1rem; border-radius: 16px; }
			.card-header h3 { font-size: 0.9rem; }
			.card-header i { font-size: 1.25rem; }
			.card-item { padding: 0.7rem; }
			.card-item h4 { font-size: 0.78rem; }
			.card-item p { font-size: 0.68rem; }
			.card-image { height: 130px; }
			.view-more-btn { font-size: 0.7rem; padding: 0.5rem 0.9rem; }
		}
	</style>
</head>
<body>

<header>
	<h1>Our Digital Solutions Portfolio</h1>
	<p>Transforming Data into Smarter Decisions for a Better Tomorrow</p>
</header>

<main class="portfolio-grid">
	<?php foreach ($categories as $solution): ?>
	<?php
		$span = decideGridSpan((int)$solution['project_count']);
		$spanClass = $span === 2 ? 'grid-span-2' : '';
	?>
	<div class="card <?= $spanClass ?>" style="border-top: 4px solid <?= e($solution['color']) ?>; grid-column: span <?= $span ?>;">
		<div class="card-header" style="color: <?= e($solution['color']) ?>;">
			<i class="fas <?= e($solution['icon']) ?>"></i>
			<h3><?= e(strtoupper($solution['name'])) ?></h3>
		</div>
		<p class="card-subtitle"><?= e($solution['description'] ?? '') ?></p>

		<div class="card-items">
			<?php if (empty($solution['projects'])): ?>
				<p style="font-size:.75rem;color:var(--text-light);width:100%;text-align:center;padding:1rem 0;">No projects yet.</p>
			<?php else: ?>
				<?php foreach ($solution['projects'] as $item): ?>
				<div class="card-item">
					<h4><a href="project.php?slug=<?= urlencode($item['slug']) ?>"><?= e($item['project_name']) ?></a></h4>
					<p><?= e($item['short_description'] ?? '') ?></p>
					<?php if (!empty($item['image_path'])): ?>
					<img src="<?= e(imgUrl($item['image_path'])) ?>" alt="<?= e($item['project_name']) ?>" class="card-image">
					<?php endif; ?>
					<a href="project.php?slug=<?= urlencode($item['slug']) ?>" class="view-more-btn" style="background-color: <?= e($solution['color']) ?>;">
						View More <i class="fas fa-arrow-right" style="margin-left: 4px;"></i>
					</a>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
</main>

</body>
</html>