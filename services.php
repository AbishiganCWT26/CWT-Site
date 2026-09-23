<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$clients    = getClients($pdo);
$industries = getIndustries($pdo);
$techStack  = getTechStack($pdo);

$techCategories = [
	'software_engineering' => ['label' => 'Software Engineering', 'items' => []],
	'ai_engineering'       => ['label' => 'AI Engineering',       'items' => []],
	'data_engineering'     => ['label' => 'Data Engineering',     'items' => []],
	'devops_cloud'         => ['label' => 'DevOps & Cloud',       'items' => []],
	'project_delivery'     => ['label' => 'Project Delivery & PMO','items' => []],
];
foreach ($techStack as $item) {
	if (isset($techCategories[$item['category']])) {
		$techCategories[$item['category']]['items'][] = $item;
	}
}

$industryIcons = ['🏦','🛡️','🏛️','🚢','🏭','🛒','📡','🚚','🤖','☁️','🖥️','📋','🔧','📈'];

$processSteps = [
	['icon' => 'fa-magnifying-glass', 'title' => 'Discover', 'desc' => 'We dive deep into your business requirements, goals, and technical constraints to build a shared understanding.'],
	['icon' => 'fa-clipboard-list',   'title' => 'Plan',     'desc' => 'We jointly define and prioritise tasks based on business value and potential risk, creating a clear roadmap.'],
	['icon' => 'fa-bolt',             'title' => 'Sprint',   'desc' => 'Development is delivered in focused, time-boxed sprints with daily standups and continuous feedback loops.'],
	['icon' => 'fa-code-pull-request','title' => 'Review',   'desc' => 'Each sprint ends with a demo and retrospective — we adapt and improve continuously.'],
	['icon' => 'fa-rocket',           'title' => 'Deploy',   'desc' => 'Code is continuously integrated, tested, and deployed to production with zero downtime strategies.'],
	['icon' => 'fa-arrows-rotate',    'title' => 'Iterate',  'desc' => 'We monitor performance, gather feedback, and iterate — ensuring your product keeps evolving and improving.'],
];

$pageTitle = 'Our Services — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Explore Creative Web Technologies' full range of services — software engineering, cloud, DevOps, AI, cybersecurity, QA, and more.">
	<link rel="preconnect" href="https://fonts.googleapis.com" />
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" />
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/services.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="svc-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="svc-hero" aria-label="Services page hero">
	<div class="svc-hero-orbs" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="svc-hero-grid" aria-hidden="true"></div>
	<div class="svc-wrap">
		<div class="svc-hero-inner">
			<span class="svc-pill">
				<span class="svc-pill-dot"></span>
				What We Do
			</span>
			<h1 class="svc-hero-title">CWT Services <span class="svc-hero-accent">&amp; Expertise</span></h1>
			<p class="svc-hero-text">Expert tech services, built to scale your business</p>
			<div class="svc-hero-stats">
				<div class="svc-hero-stat">
					<strong>15+</strong>
					<span>Service Lines</span>
				</div>
				<div class="svc-hero-divider"></div>
				<div class="svc-hero-stat">
					<strong>8</strong>
					<span>Verticals</span>
				</div>
				<div class="svc-hero-divider"></div>
				<div class="svc-hero-stat">
					<strong>24/7</strong>
					<span>Support</span>
				</div>
			</div>
		</div>
	</div>
</section>

<section class="svc-sec svc-sec-light" id="all-services" aria-labelledby="all-services-heading">
	<div class="svc-wrap">
		<div class="svc-sec-head reveal">
			<span class="svc-label">Explore</span>
			<h2 class="svc-sec-title" id="all-services-heading">All Services &amp; Verticals</h2>
		</div>
		<main class="verticals-grid" id="verticalsGrid"></main>
	</div>
</section>

<section class="svc-sec svc-sec-tint" id="industries" aria-labelledby="industries-heading">
	<div class="svc-wrap">
		<div class="svc-sec-head reveal">
			<span class="svc-label">Domains</span>
			<h2 class="svc-sec-title" id="industries-heading">Industries &amp; Verticals We Serve</h2>
			<p class="svc-sec-sub">Deep domain expertise across major global industries</p>
		</div>
		<div class="industries-grid">
			<?php foreach ($industries as $i => $ind): ?>
			<div class="industry-card reveal">
				<?php $logoUrl = imgUrl($ind['image_path']); ?>
				<div class="industry-media">
					<?php if ($logoUrl): ?>
						<img src="<?= e($logoUrl) ?>" alt="<?= e($ind['name']) ?>" class="industry-img" loading="lazy">
					<?php else: ?>
						<div class="industry-fallback"><?= $industryIcons[$i % count($industryIcons)] ?></div>
					<?php endif; ?>
				</div>
				<div class="industry-name"><?= e($ind['name']) ?></div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="svc-process" id="how-we-work" aria-labelledby="how-heading">
	<div class="svc-wrap">
		<header class="svc-sec-head">
			<span class="svc-label">
				<i class="fa-solid fa-diagram-project" aria-hidden="true"></i>
				How We Work
			</span>
			<h2 class="svc-sec-title" id="how-heading">Our <em>Delivery</em> Process</h2>
			<p class="svc-sec-sub">A six-step process driving continuous product success</p>
		</header>

		<div class="orbit">

			<!-- radial spokes from hub to each card -->
			<svg class="spokes" viewBox="0 0 100 100" aria-hidden="true" preserveAspectRatio="xMidYMid meet">
				<line x1="50" y1="50" x2="82" y2="50" />
				<line x1="50" y1="50" x2="66" y2="77.71" />
				<line x1="50" y1="50" x2="34" y2="77.71" />
				<line x1="50" y1="50" x2="18" y2="50" />
				<line x1="50" y1="50" x2="34" y2="22.29" />
				<line x1="50" y1="50" x2="66" y2="22.29" />
			</svg>

			<!-- dashed orbit ring -->
			<div class="orbit-dash" aria-hidden="true"></div>

			<!-- central hub -->
			<div class="hub" aria-hidden="true">
				<i class="fa-solid fa-circle-nodes"></i>
				<strong>6 Steps</strong>
				<span>Process</span>
			</div>

			<ul class="ring">
				<?php 
				// Layout angle positions (0 = 0° right, 1 = 60° bottom-right, 2 = 120° bottom-left, 3 = 180° left, 4 = 240° top-left, 5 = 300° top-right)
				$posMap = [5, 0, 1, 2, 3, 4];
				foreach ($processSteps as $i => $step): 
					$posIndex = isset($posMap[$i]) ? $posMap[$i] : $i;
				?>
				<li class="node" style="--i:<?= $posIndex ?>">
					<article class="card" tabindex="0">
						<div class="card-top">
							<div class="icon-box"><i class="fa-solid <?= e($step['icon']) ?>" aria-hidden="true"></i></div>
							<span class="num-badge" aria-hidden="true"></span>
						</div>
						<h3 class="title"><?= e($step['title']) ?></h3>
						<p class="descr"><?= e($step['desc']) ?></p>
					</article>
				</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
</section>

<?php if (!empty($clients)): ?>
<section class="svc-marquee" id="trusted-marquee" aria-label="Trusted by">
	<div class="svc-wrap">
		<div class="svc-sec-head svc-sec-head-sm reveal">
			<span class="svc-label">Trusted By</span>
			<h2 class="svc-sec-title">Trusted by Companies Like Yours</h2>
		</div>
	</div>
	<div class="marquee-wrap">
		<div class="marquee-track">
			<?php
			$marqueeItems = array_merge($clients, $clients, $clients);
			foreach ($marqueeItems as $client):
				$logoUrl = imgUrl($client['logo_path']);
			?>
			<div class="marquee-item">
				<div class="marquee-tooltip"><?= e($client['company_name']) ?></div>
				<div class="marquee-logo">
					<?php if ($logoUrl): ?>
						<img src="<?= e($logoUrl) ?>" alt="<?= e($client['company_name']) ?>" loading="lazy">
					<?php else: ?>
						<div class="marquee-fallback"><?= e($client['company_name']) ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section class="svc-sec svc-sec-light" id="tech-stack" aria-labelledby="tech-heading">
	<div class="svc-wrap">
		<div class="svc-sec-head reveal">
			<span class="svc-label">Technology</span>
			<h2 class="svc-sec-title" id="tech-heading">The Right Talent For Every Tech Stack</h2>
			<p class="svc-sec-sub">Accelerate growth using proven expertise in your tech stack</p>
		</div>

		<div class="tech-tabs reveal" role="tablist">
			<?php $first = true; foreach ($techCategories as $key => $cat): ?>
			<button class="tech-tab <?= $first ? 'active' : '' ?>" data-target="tech-<?= $key ?>" role="tab" aria-selected="<?= $first ? 'true' : 'false' ?>">
				<?= e($cat['label']) ?>
			</button>
			<?php $first = false; endforeach; ?>
		</div>

		<?php $first = true; foreach ($techCategories as $key => $cat): ?>
		<div class="tech-panel tech-logos-grid <?= $first ? 'active' : '' ?>" id="tech-<?= $key ?>" role="tabpanel">
			<?php if (empty($cat['items'])): ?>
				<p class="tech-empty">No tech stack items added yet. <a href="<?= ADMIN_URL ?>/tech-stack.php">Add via admin</a>.</p>
			<?php else: ?>
				<?php foreach ($cat['items'] as $tech): ?>
				<div class="tech-logo-item">
					<div class="tech-tooltip"><?= e($tech['name']) ?></div>
					<?php $logoUrl = imgUrl($tech['logo_path']); ?>
					<?php if ($logoUrl): ?>
						<img src="<?= e($logoUrl) ?>" alt="<?= e($tech['name']) ?>" loading="lazy">
					<?php else: ?>
						<div class="tech-logo-placeholder"><?= mb_substr($tech['name'], 0, 2) ?></div>
					<?php endif; ?>
					<span class="tech-logo-name"><?= e($tech['name']) ?></span>
				</div>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
		<?php $first = false; endforeach; ?>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/services.js"></script>
</body>
</html>