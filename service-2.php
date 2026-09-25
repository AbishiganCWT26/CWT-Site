<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$clients    = getClients($pdo);
$industries = getIndustries($pdo);
$techStack  = getTechStack($pdo);

$techCategories = [
	'software_engineering' => ['label' => 'Software Engineering',   'items' => []],
	'ai_engineering'       => ['label' => 'AI Engineering',         'items' => []],
	'data_engineering'     => ['label' => 'Data Engineering',       'items' => []],
	'devops_cloud'         => ['label' => 'DevOps & Cloud',         'items' => []],
	'project_delivery'     => ['label' => 'Project Delivery & PMO', 'items' => []],
];

foreach ($techStack as $item) {
	if (isset($techCategories[$item['category']])) {
		$techCategories[$item['category']]['items'][] = $item;
	}
}
$techKeys  = array_keys($techCategories);
$techFirst = $techKeys[0];

$services = [
	[
		'num'   => '01',
		'icon'  => '<path d="M3 3v18h18"/><path d="M7 15v3M12 10v8M17 6v12"/>',
		'title' => 'Data Engineering &amp; Analytics',
		'desc'  => 'We design and deploy robust architectures, pipelines, and governance frameworks to turn complex, scattered enterprise data into trusted, analytics-ready assets that drive strategic decision-making.',
		'consultant' => ['Renien', 'Senior Solution Architect / CTO'],
		'lead'  => ['Chamod'],
		'team'  => ['Wikasith', 'Amasha'],
	],
	[
		'num'   => '02',
		'icon'  => '<rect x="7" y="7" width="10" height="10" rx="2.5"/><path d="M10 3v4M14 3v4M10 17v4M14 17v4M3 10h4M3 14h4M17 10h4M17 14h4"/>',
		'title' => 'AI Engineering &amp; Intelligent Automation',
		'desc'  => 'We build intelligent systems, machine learning models, and automated workflows—from generative AI to process orchestration—that automate repetitive tasks and deliver high-impact business solutions.',
		'consultant' => ['Eranga', 'AI Research Engineer'],
		'lead'  => ['Sharumathan'],
		'team'  => ['Tenuka', 'Ashiru'],
	],
	[
		'num'   => '03',
		'icon'  => '<path d="M4 7h16M4 12h16M4 17h16"/><circle cx="9" cy="7" r="2.3"/><circle cx="15" cy="12" r="2.3"/><circle cx="8" cy="17" r="2.3"/>',
		'title' => 'Optimization',
		'desc'  => 'We optimize enterprise systems, cloud-native architectures, and CI/CD pipelines to accelerate software delivery, strengthen operational reliability, and drive continuous efficiency.',
		'consultant' => ['Amal', 'Senior Optimization Consultant'],
		'lead'  => ['Sharumathan'],
		'team'  => ['Tenuka', 'Abishigan', 'Chirath'],
	],
	[
		'num'   => '04',
		'icon'  => '<path d="M8 6l-5 6 5 6M16 6l5 6-5 6"/>',
		'title' => 'Software Engineering &amp; Development',
		'desc'  => 'We design, build, and modernize scalable enterprise applications, digital platforms, and custom APIs to establish secure, high-performing technology environments.',
		'consultant' => ['Rashintha', 'Senior Solution Architect'],
		'lead'  => ['Chamod'],
		'team'  => ['Abishigan', 'Chirath'],
	],
	[
		'num'   => '05',
		'icon'  => '<path d="M12 3l7 3v5.2c0 4.5-3 8.1-7 9.8-4-1.7-7-5.3-7-9.8V6z"/><path d="M9.2 12.2l2 2 3.6-3.8"/>',
		'title' => 'Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support &amp; Services',
		'desc'  => 'We architect, protect, and maintain hybrid infrastructure, cloud environments, and managed IT services to guarantee enterprise compliance, robust security, and 24/7 reliability.',
		'consultant' => ['Isuru', 'Senior IT Architect'],
		'lead'  => ['Chamod'],
		'team'  => ['Menura', 'Ashiru'],
	],
	[
		'num'   => '06',
		'icon'  => '<rect x="6" y="4" width="12" height="17" rx="2.5"/><path d="M9 4V3h6v1"/><path d="M9.2 12.4l2 2 3.6-3.8"/>',
		'title' => 'Project Delivery &amp; PMO',
		'desc'  => 'We enforce disciplined project governance, strategic planning, and continuous quality assurance to deliver transparent stakeholder visibility and predictable, high-value project outcomes.',
		'consultant' => ['Priyath', 'Senior Project Consultant'],
		'lead'  => ['Chamod'],
		'team'  => ['Chamini', 'Lehan'],
	],
	[
		'num'   => '07',
		'icon'  => '<path d="M12 3v18M8 21h8"/><path d="M6 7l-3 6h6zM18 7l-3 6h6zM6 7l6-2 6 2"/>',
		'title' => 'Legal, Internal Operations &amp; HR',
		'desc'  => 'We enable new market opportunities, cultivate strategic partnerships, and drive sustainable organizational growth by aligning internal operations and strategic governance with proactive marketing, customer engagement, and RFP management.',
		'consultant' => ['Chameera', 'Senior Consultant'],
		'lead'  => ['Unknown'],
		'team'  => ['Samadhi'],
	],
	[
		'num'   => '08',
		'icon'  => '<path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z"/><path d="M14 3v5h5"/><path d="M9 13h6M9 17h4"/>',
		'title' => 'Legal, Internal Operations &amp; HR',
		'desc'  => 'We enable new market opportunities, cultivate strategic partnerships, and drive sustainable organizational growth by aligning internal operations and strategic governance with proactive marketing, customer engagement, and RFP management.',
		'consultant' => ['Chameera', 'Senior Consultant'],
		'lead'  => ['Unknown'],
		'team'  => ['Samadhi'],
	],
	[
		'num'   => '09',
		'icon'  => '<path d="M3 17l6-6 4 4 8-8"/><path d="M15 7h6v6"/>',
		'title' => 'Business Development',
		'desc'  => 'We identify strategic market opportunities, optimize commercial engagement processes, and cultivate technology partnerships to drive sustainable revenue growth and enterprise expansion.',
		'consultant' => ['Ayesh', 'Senior Business Development Consultant'],
		'lead'  => ['Menura'],
		'team'  => ['Chenuli', 'Lehan'],
	],
];

$industryIcons = ['🏦','🛡️','🏛️','🚢','🏭','🛒','📡','🚚','🤖','☁️','🖥️','📋','🔧','📈'];

$processSteps = [
	['icon' => 'fa-magnifying-glass',  'title' => 'Discover', 'desc' => 'We dive deep into your business requirements, goals, and technical constraints to build a shared understanding.'],
	['icon' => 'fa-clipboard-list',    'title' => 'Plan',     'desc' => 'We jointly define and prioritise tasks based on business value and potential risk, creating a clear roadmap.'],
	['icon' => 'fa-bolt',              'title' => 'Sprint',   'desc' => 'Development is delivered in focused, time-boxed sprints with daily standups and continuous feedback loops.'],
	['icon' => 'fa-code-pull-request', 'title' => 'Review',   'desc' => 'Each sprint ends with a demo and retrospective — we adapt and improve continuously.'],
	['icon' => 'fa-rocket',            'title' => 'Deploy',   'desc' => 'Code is continuously integrated, tested, and deployed to production with zero downtime strategies.'],
	['icon' => 'fa-arrows-rotate',     'title' => 'Iterate',  'desc' => 'We monitor performance, gather feedback, and iterate — ensuring your product keeps evolving and improving.'],
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
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/service-2.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="svc-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="svc-hero" aria-label="Services page hero">
	<div class="svc-hero-orbs" aria-hidden="true"><span></span><span></span><span></span></div>
	<div class="svc-hero-grid" aria-hidden="true"></div>

	<div class="svc-wrap">
		<div class="svc-hero-inner">
			<span class="svc-hero-pill">
				<span class="svc-hero-dot"></span>
				What We Do
			</span>
			<h1 class="svc-hero-title">CWT Services <span class="svc-hero-accent">&amp; Expertise</span></h1>
			<p class="svc-hero-text">Expert tech services, built to scale your business</p>

			<div class="svc-hero-stats">
				<div class="svc-hero-stat"><strong>15+</strong><span>Service Lines</span></div>
				<div class="svc-hero-divider"></div>
				<div class="svc-hero-stat"><strong>8</strong><span>Verticals</span></div>
				<div class="svc-hero-divider"></div>
				<div class="svc-hero-stat"><strong>24/7</strong><span>Support</span></div>
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

		<div class="svc-nine reveal">
			<?php foreach ($services as $i => $s): ?>
			<article class="svc-pillar" data-index="<?= $i ?>">
				<button type="button" class="svc-pillar-head" aria-expanded="false">
					<span class="svc-pillar-ghost" aria-hidden="true"><?= e($s['num']) ?></span>
					<span class="svc-pillar-top">
						<span class="svc-pillar-icon" aria-hidden="true">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><?= $s['icon'] ?></svg>
						</span>
						<span class="svc-pillar-num"><?= e($s['num']) ?></span>
					</span>
					<h3 class="svc-pillar-title"><?= $s['title'] ?></h3>
					<span class="svc-pillar-toggle" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"><path d="M12 5v14M5 12h14"/></svg>
						<span class="svc-pillar-toggle-text">View team</span>
					</span>
				</button>
				<div class="svc-pillar-body">
					<div class="svc-pillar-body-inner">
						<p class="svc-pillar-desc"><?= e($s['desc']) ?></p>
						<div class="svc-pillar-consultant">
							<span class="svc-pillar-avatar"><?= e(mb_substr($s['consultant'][0], 0, 1)) ?></span>
							<div>
								<span class="svc-pillar-cname"><?= e($s['consultant'][0]) ?></span>
								<span class="svc-pillar-crole"><?= e($s['consultant'][1]) ?></span>
							</div>
						</div>
						<div class="svc-pillar-group">
							<span class="svc-pillar-label">Vertical Lead</span>
							<div class="svc-pillar-chips">
								<?php foreach ($s['lead'] as $name): ?>
								<span class="svc-pillar-chip"><?= e($name) ?></span>
								<?php endforeach; ?>
							</div>
						</div>
						<div class="svc-pillar-group">
							<span class="svc-pillar-label">Team Members</span>
							<div class="svc-pillar-chips">
								<?php foreach ($s['team'] as $name): ?>
								<span class="svc-pillar-chip"><?= e($name) ?></span>
								<?php endforeach; ?>
							</div>
						</div>
						<a href="our-crew.php" class="svc-pillar-cta">
							<span>Meet the team</span>
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
						</a>
					</div>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
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
			<?php foreach ($industries as $i => $ind): $logoUrl = imgUrl($ind['image_path']); ?>
			<div class="industry-card reveal">
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
			<svg class="spokes" viewBox="0 0 100 100" aria-hidden="true" preserveAspectRatio="xMidYMid meet">
				<line x1="50" y1="50" x2="82" y2="50"/>
				<line x1="50" y1="50" x2="66" y2="77.71"/>
				<line x1="50" y1="50" x2="34" y2="77.71"/>
				<line x1="50" y1="50" x2="18" y2="50"/>
				<line x1="50" y1="50" x2="34" y2="22.29"/>
				<line x1="50" y1="50" x2="66" y2="22.29"/>
			</svg>

			<div class="orbit-dash" aria-hidden="true"></div>

			<div class="hub" aria-hidden="true">
				<i class="fa-solid fa-circle-nodes"></i>
				<strong>6 Steps</strong>
				<span>Process</span>
			</div>

			<ul class="ring">
				<?php
				$posMap = [5, 0, 1, 2, 3, 4];
				foreach ($processSteps as $i => $step):
					$posIndex = $posMap[$i] ?? $i;
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
			<?php foreach (array_merge($clients, $clients, $clients) as $client): $logoUrl = imgUrl($client['logo_path']); ?>
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
			<?php foreach ($techCategories as $key => $cat): ?>
			<button class="tech-tab<?= $key === $techFirst ? ' active' : '' ?>" data-target="tech-<?= $key ?>" role="tab" aria-selected="<?= $key === $techFirst ? 'true' : 'false' ?>">
				<?= e($cat['label']) ?>
			</button>
			<?php endforeach; ?>
		</div>

		<?php foreach ($techCategories as $key => $cat): ?>
		<div class="tech-panel tech-logos-grid<?= $key === $techFirst ? ' active' : '' ?>" id="tech-<?= $key ?>" role="tabpanel">
			<?php if (empty($cat['items'])): ?>
				<p class="tech-empty">No tech stack items added yet. <a href="<?= ADMIN_URL ?>/tech-stack.php">Add via admin</a>.</p>
			<?php else: ?>
				<?php foreach ($cat['items'] as $tech): $logoUrl = imgUrl($tech['logo_path']); ?>
				<div class="tech-logo-item">
					<div class="tech-tooltip"><?= e($tech['name']) ?></div>
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
		<?php endforeach; ?>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/service-2.js"></script>
</body>
</html>