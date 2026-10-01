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
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/1.css">
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

<section class="svc-sec svc-hub" id="all-services" aria-labelledby="all-services-heading">
	<div class="svc-wrap">
		<div class="svc-hub-head reveal">
			<span class="svc-label">Explore</span>
			<h2 class="svc-sec-title" id="all-services-heading">All Services &amp; Verticals</h2>
		</div>

		<div class="svc-hub-grid reveal">
			<?php foreach ($services as $s): ?>
			<div class="svc-hub-card">
				<div class="svc-hub-top">
					<span class="svc-hub-icon" aria-hidden="true">
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round"><?= $s['icon'] ?></svg>
					</span>
					<span class="svc-hub-num"><?= e($s['num']) ?></span>
				</div>
				<h3 class="svc-hub-name"><?= $s['title'] ?></h3>
				<p class="svc-hub-text"><?= e($s['desc']) ?></p>
				<div class="svc-hub-foot">
					<div class="svc-hub-consultant">
						<span class="svc-hub-avatar"><?= e(mb_substr($s['consultant'][0], 0, 1)) ?></span>
						<div class="svc-hub-consultant-info">
							<span class="svc-hub-cname"><?= e($s['consultant'][0]) ?></span>
							<span class="svc-hub-crole"><?= e($s['consultant'][1]) ?></span>
						</div>
					</div>
					<div class="svc-hub-teams">
						<div class="svc-hub-team-row">
							<span class="svc-hub-key">Lead</span>
							<span class="svc-hub-chips">
								<?php foreach ($s['lead'] as $name): ?>
								<span class="svc-hub-chip"><?= e($name) ?></span>
								<?php endforeach; ?>
							</span>
						</div>
						<div class="svc-hub-team-row">
							<span class="svc-hub-key">Team</span>
							<span class="svc-hub-chips">
								<?php foreach ($s['team'] as $name): ?>
								<span class="svc-hub-chip"><?= e($name) ?></span>
								<?php endforeach; ?>
							</span>
						</div>
					</div>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<section class="section section-light" id="what-we-offer" aria-labelledby="offer-heading">
	<div class="container">
		<div class="section-header reveal">
			<span class="section-label">Solutions</span>
			<h2 class="section-title" id="offer-heading">What We Offer</h2>
			<p class="section-subtitle">A comprehensive suite of technology solutions tailored to your unique business needs.</p>
		</div>

		<svg class="svg-sprite" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
			<symbol id="i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></symbol>
			<symbol id="i-shuffle" viewBox="0 0 24 24"><path d="M16 3h5v5"/><path d="M4 20 21 3"/><path d="M21 16v5h-5"/><path d="m15 15 6 6"/><path d="m4 4 5 5"/></symbol>
			<symbol id="i-box" viewBox="0 0 24 24"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/><path d="m3.27 6.96 8.73 5.05 8.73-5.05"/><path d="M12 22.08V12"/></symbol>
			<symbol id="i-layers" viewBox="0 0 24 24"><path d="m12 2 10 5-10 5L2 7l10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></symbol>
			<symbol id="i-filter" viewBox="0 0 24 24"><path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3z"/></symbol>
			<symbol id="i-chart" viewBox="0 0 24 24"><path d="M3 3v18h18"/><rect x="7" y="11" width="3" height="7" rx="1"/><rect x="12.5" y="7" width="3" height="11" rx="1"/><rect x="18" y="13" width="3" height="5" rx="1"/></symbol>
			<symbol id="i-monitor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2"/><path d="M8 21h8"/><path d="M12 17v4"/></symbol>
			<symbol id="i-cpu" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2"/><rect x="9" y="9" width="6" height="6" rx="1"/><path d="M9 1v3M15 1v3M9 20v3M15 20v3M1 9h3M1 15h3M20 9h3M20 15h3"/></symbol>
			<symbol id="i-trending" viewBox="0 0 24 24"><path d="m23 6-9.5 9.5-5-5L1 18"/><path d="M17 6h6v6"/></symbol>
			<symbol id="i-star" viewBox="0 0 24 24"><path d="m12 2 3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/></symbol>
			<symbol id="i-branch" viewBox="0 0 24 24"><path d="M6 3v12"/><circle cx="18" cy="6" r="3"/><circle cx="6" cy="18" r="3"/><path d="M18 9a9 9 0 0 1-9 9"/></symbol>
			<symbol id="i-robot" viewBox="0 0 24 24"><rect x="3" y="8" width="18" height="12" rx="2"/><path d="M12 8V5"/><circle cx="12" cy="3.5" r="1.5"/><path d="M8 13h.01M16 13h.01"/><path d="M9 17h6"/></symbol>
			<symbol id="i-compass" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="m16.24 7.76-2.12 6.36-6.36 2.12 2.12-6.36 6.36-2.12z"/></symbol>
			<symbol id="i-code" viewBox="0 0 24 24"><path d="m16 18 6-6-6-6"/><path d="m8 6-6 6 6 6"/></symbol>
			<symbol id="i-refresh" viewBox="0 0 24 24"><path d="M23 4v6h-6"/><path d="M1 20v-6h6"/><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10"/><path d="M1 14l4.64 4.36A9 9 0 0 0 20.49 15"/></symbol>
			<symbol id="i-sliders" viewBox="0 0 24 24"><path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3"/><path d="M1 14h6M9 8h6M17 16h6"/></symbol>
			<symbol id="i-gear" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></symbol>
			<symbol id="i-database" viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.66 4.03 3 9 3s9-1.34 9-3V5"/><path d="M3 12c0 1.66 4.03 3 9 3s9-1.34 9-3"/></symbol>
			<symbol id="i-dollar" viewBox="0 0 24 24"><path d="M12 1v22"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></symbol>
			<symbol id="i-activity" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></symbol>
			<symbol id="i-file" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6"/><path d="M16 13H8"/><path d="M16 17H8"/><path d="M10 9H8"/></symbol>
			<symbol id="i-target" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="12" r="6"/><circle cx="12" cy="12" r="2"/></symbol>
			<symbol id="i-smartphone" viewBox="0 0 24 24"><rect x="5" y="2" width="14" height="20" rx="2"/><path d="M12 18h.01"/></symbol>
			<symbol id="i-link" viewBox="0 0 24 24"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></symbol>
			<symbol id="i-cloud" viewBox="0 0 24 24"><path d="M18 10h-1.26A8 8 0 1 0 9 20h9a5 5 0 0 0 0-10z"/></symbol>
			<symbol id="i-terminal" viewBox="0 0 24 24"><path d="m4 17 6-6-6-6"/><path d="M12 19h8"/></symbol>
			<symbol id="i-shield" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></symbol>
			<symbol id="i-globe" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M2 12h20"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></symbol>
			<symbol id="i-wrench" viewBox="0 0 24 24"><path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"/></symbol>
			<symbol id="i-users" viewBox="0 0 24 24"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></symbol>
			<symbol id="i-harddrive" viewBox="0 0 24 24"><path d="M22 12H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><path d="M6 16h.01"/><path d="M10 16h.01"/></symbol>
			<symbol id="i-lock" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></symbol>
			<symbol id="i-eye" viewBox="0 0 24 24"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></symbol>
			<symbol id="i-briefcase" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></symbol>
			<symbol id="i-clipboard" viewBox="0 0 24 24"><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><rect x="8" y="2" width="8" height="4" rx="1"/><path d="M9 12h6"/><path d="M9 16h4"/></symbol>
			<symbol id="i-alert" viewBox="0 0 24 24"><path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><path d="M12 9v4"/><path d="M12 17h.01"/></symbol>
			<symbol id="i-check" viewBox="0 0 24 24"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="M22 4 12 14.01l-3-3"/></symbol>
			<symbol id="i-userplus" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><path d="M20 8v6"/><path d="M23 11h-6"/></symbol>
			<symbol id="i-cart" viewBox="0 0 24 24"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/></symbol>
			<symbol id="i-award" viewBox="0 0 24 24"><circle cx="12" cy="8" r="7"/><path d="m8.21 13.89-1.21 9.11 5-3 5 3-1.21-9.12"/></symbol>
			<symbol id="i-message" viewBox="0 0 24 24"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></symbol>
		</svg>

		<div class="capabilities-explorer-wrap">
			<nav class="tabs" id="tabs" role="tablist" aria-label="Capability areas"></nav>

			<section class="panel" id="panel" role="tabpanel" aria-labelledby="tab-data" tabindex="0">
				<div class="panel__head">
					<span class="panel__badge" aria-hidden="true">
						<svg class="ico" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
							<use id="panelIconUse" href="#i-database"></use>
						</svg>
					</span>
					<div class="panel__head-text">
						<h2 class="panel__title" id="panelTitle">Data Engineering &amp; Analytics</h2>
						<p class="panel__meta" id="panelMeta">7 capabilities</p>
					</div>
				</div>

				<div class="cards" id="cards"></div>
				<div class="cards-dots" id="cardsDots" aria-hidden="true"></div>
			</section>
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
<script src="<?= SITE_URL ?>/assets/js/1.js"></script>
</body>
</html>