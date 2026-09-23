<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$clients  = getClients($pdo);
$pageTitle = 'Creative Web Technologies — Expert Tech Teams';

$services = [
  ['icon'=> 'assets/Images/Software Engineering.png',  'title'=>'Software Engineering & Development',       'desc'=>'We design, build, and modernize enterprise applications, platforms, APIs, and digital products across all major technology stacks. Our teams support dedicated delivery pods, staff augmentation, fixed-scope projects, and managed engineering engagements.'],
  ['icon'=> 'assets/Images/DevOps, DataOps & SRE.png',  'title'=>'DevOps, DataOps & SRE',                    'desc'=>'We streamline build, release, data, and reliability workflows to accelerate delivery and reduce failure risk. Services include CI/CD, infrastructure as code, observability, SLOs, incident response, and platform engineering.'],
  ['icon'=> 'assets/Images/Data Engineering & Analytics.png',  'title'=>'Data Engineering & Analytics',             'desc'=>'We build modern data architectures, pipelines, warehouses, and lakehouses that make enterprise data trustworthy and analytics-ready. Our solutions improve decision-making, operational visibility, governance, and cost savings.'],
  ['icon'=> 'assets/Images/AI Engineering & Big Data.png',  'title'=>'AI Engineering & Big Data',                'desc'=>'We turn data into intelligent systems using AI, machine learning, generative AI, IoT, and advanced analytics. From model development to MLOps and deployment, we help extract maximum value from enterprise data.'],
  ['icon'=> 'assets/Images/Cybersecurity, Network & Infrastructure.png',  'title'=>'Cybersecurity, Network & Infrastructure',  'desc'=>'We design, build, and protect the technology backbone with secure network architecture, cloud and on-prem infrastructure, identity management, and monitoring. Our security services include risk assessments, penetration testing, compliance support, and incident response.'],
  ['icon'=> 'assets/Images/Support & Maintenance.png',  'title'=>'Support & Maintenance',                    'desc'=>'We provide 24/7 global application and infrastructure support aligned to SLAs. Services include proactive monitoring, patching, troubleshooting, enhancements, root-cause analysis, and continuous service improvement.'],
];


$offerings = [
  ['icon'=>'🛒','title'=>'eCommerce Solutions','desc'=>'We help you make your online transactions in a flash. Our team ensures secure, scalable, and highly responsive e-commerce platforms that enhance the digital shopping experience.'],
  ['icon'=>'🏢','title'=>'Corporate Website Development','desc'=>'We develop appealing and innovative corporate websites. We craft digital storefronts that effectively communicate your brand identity and engage your target audience.'],
  ['icon'=>'🔧','title'=>'Support and Maintenance','desc'=>'We assist you in keeping your software applications up-to-date and attend to bugs/defects, 24/7. Our dedicated support ensures your operations run seamlessly without downtime.'],
  ['icon'=>'🎨','title'=>'User Experience (UX) Design','desc'=>'We provide innovative software solutions while ensuring the highest level of creativity. We focus on user-centric designs that make complex applications intuitive and enjoyable.'],
  ['icon'=>'👥','title'=>'Resource Outsourcing','desc'=>'We provide dedicated IT professionals to manage your IT operations more conveniently and effectively. Scale your team with our expert resources aligned with your business goals.'],
  ['icon'=>'📱','title'=>'Mobile Applications','desc'=>'We utilize cutting-edge technologies to provide your customers with innovative and incredible mobile experiences. From iOS to Android, we build apps that perform flawlessly.'],
  ['icon'=>'📣','title'=>'Digital Marketing','desc'=>'We help to upsurge your annual turnover by utilizing comprehensive digital marketing strategies. Our data-driven campaigns are designed to maximize your online reach and ROI.'],
  ['icon'=>'🔐','title'=>'Cybersecurity & Infrastructure Solutions','desc'=>'We safeguard your digital assets with robust security protocols and scalable infrastructure. Our proactive approach ensures your systems remain resilient against evolving cyber threats.'],
  ['icon'=>'📋','title'=>'Project Delivery & PMO','desc'=>'We ensure end-to-end project management and seamless delivery. Our senior consultants oversee the lifecycle of your projects, guaranteeing timely execution and quality standards.'],
  ['icon'=>'🧠','title'=>'Data & AI Engineering','desc'=>'Unlock the power of your data. We design robust data pipelines and integrate advanced AI models to drive automation, insights, and intelligent decision-making for your business.'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= e($pageTitle) ?></title>
	<meta name="description" content="Creative Web Technologies builds high-performance offshore development teams. Partner with Sri Lanka's top tech talent for software development, QA, AI, cloud, and more.">
	<meta property="og:title" content="Creative Web Technologies — Expert Tech Teams">
	<meta property="og:description" content="Extend your in-house teams with Sri Lanka's tech talent. High-performance offshore development teams.">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/index.css">
</head>
<body>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<section class="hero" id="hero" aria-label="Hero">
	<canvas id="heroCanvas" class="hero-canvas"></canvas>

	<?php if (file_exists(__DIR__ . '/assets/video/hero.mp4')): ?>
	<video class="hero-video" autoplay muted loop playsinline aria-hidden="true">
		<source src="<?= SITE_URL ?>/assets/video/hero.mp4" type="video/mp4">
	</video>
	<?php endif; ?>

	<div class="hero-overlay" aria-hidden="true"></div>
	<div class="hero-grid" aria-hidden="true"></div>
	<div class="hero-orb hero-orb-1" aria-hidden="true"></div>
	<div class="hero-orb hero-orb-2" aria-hidden="true"></div>
	<div class="hero-orb hero-orb-3" aria-hidden="true"></div>

	<div class="hero-content">

		<h1 class="hero-title animate-fade-up animate-fade-up-delay-1">
			Grow Smarter
			<span class="hero-title-line">
				<span class="accent">With Our</span>
			</span>
			Tech Experts
		</h1>

		<p class="hero-subtitle animate-fade-up animate-fade-up-delay-2">
			Extend your in-house teams with Sri Lanka's finest technology talent — scalable, secure, and seamlessly integrated.
		</p>

		<div class="hero-actions animate-fade-up animate-fade-up-delay-3">
			<a href="<?= SITE_URL ?>/contact.php" class="btn btn-primary">
				<span>Talk to Us</span>
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
			</a>
			<a href="<?= SITE_URL ?>/services.php" class="btn btn-ghost">
				<span>Explore Our Services</span>
			</a>
		</div>

		<div class="hero-meta animate-fade-up animate-fade-up-delay-3">
			<div class="hero-meta-item">
				<strong>50+</strong>
				<span>Projects Delivered</span>
			</div>
			<div class="hero-meta-divider"></div>
			<div class="hero-meta-item">
				<strong>24/7</strong>
				<span>Global Support</span>
			</div>
		</div>
	</div>
</section>

<section class="why-cwt" id="why-trust-us">
	<div class="section-header reveal">
		<span class="section-tag">Why CWT</span>
		<h2 class="section-title" id="trust-heading">Why Companies Trust Us</h2>
		<p class="section-subtitle">We combine world-class talent with enterprise-grade processes to deliver technology that moves your business forward.</p>
	</div>

	<div class="cards-wrapper" id="cardsWrapper">
		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<rect x="3" y="3" width="7" height="7" rx="1.5"/>
					<rect x="14" y="3" width="7" height="7" rx="1.5"/>
					<rect x="3" y="14" width="7" height="7" rx="1.5"/>
					<rect x="14" y="14" width="7" height="7" rx="1.5"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">A Wide Range of Services</h3>
				<p class="card__desc">Our portfolio is designed to be as dynamic as your business. We offer a wide array of technology services—ranging from core product engineering and cloud transitions to cutting-edge AI and IFS ERP consulting.</p>
			</div>
		</article>

		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 3 5 6v6c0 4 3 7.2 7 9 4-1.8 7-5 7-9V6l-7-3Z"/>
					<path d="m9 12 2 2 4-4"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">Premium Talent, Matched to You</h3>
				<p class="card__desc">We connect you with Sri Lanka's premier tech talent. Whether you need a single architect, a dedicated QA, a Data Engineer, or a full UX team, we have the specialists you need.</p>
			</div>
		</article>

		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 3 4 7v10l8 4 8-4V7l-8-4Z"/>
					<path d="m4 7 8 4 8-4M12 11v10"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">Seamless Scalability</h3>
				<p class="card__desc">We offer the ultimate flexibility in team building. Start small with one exceptional specialist, or scale up by assembling a fully dedicated team tailored to your workflow.</p>
			</div>
		</article>

		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2Z"/>
					<path d="M8 10h.01M12 10h.01M16 10h.01"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">Transparent Communication &amp; Workflow</h3>
				<p class="card__desc">We function as a true extension of your internal team. By utilizing open communication channels and collaborative project management tools, we guarantee complete transparency.</p>
			</div>
		</article>

		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<path d="M12 3 5 6v6c0 4 3 7.2 7 9 4-1.8 7-5 7-9V6l-7-3Z"/>
					<path d="m9 12 2 2 4-4"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">Proven Track Record of Reliability</h3>
				<p class="card__desc">Our reputation is built on successful project deliveries and long-term client partnerships. When we commit to a deadline and a scope, we deliver—on time and with the highest quality standards.</p>
			</div>
		</article>

		<article class="card">
			<div class="card__icon">
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
					<circle cx="12" cy="12" r="9"/>
					<path d="M12 7v5l3 3"/>
				</svg>
			</div>
			<div class="card__content">
				<h3 class="card__title">Cost-Effective Solutions with Maximum ROI</h3>
				<p class="card__desc">By leveraging top-tier global talent, we offer highly competitive rates without compromising on quality. We focus on maximizing your return on investment by delivering premium tech solutions.</p>
			</div>
		</article>
	</div>

	<div class="dots" id="dots">
		<button class="dot is-active" type="button" aria-label="Go to card 1"></button>
		<button class="dot" type="button" aria-label="Go to card 2"></button>
		<button class="dot" type="button" aria-label="Go to card 3"></button>
		<button class="dot" type="button" aria-label="Go to card 4"></button>
		<button class="dot" type="button" aria-label="Go to card 5"></button>
		<button class="dot" type="button" aria-label="Go to card 6"></button>
	</div>
</section>

<?php if (!empty($clients)): ?>
<section class="marquee-section" id="clients-marquee" aria-label="Our clients">
	<div class="container marquee-head">
		<div class="section-header reveal">
			<span class="section-label">Global Reach</span>
			<h2 class="section-title">Valued by Clients Worldwide</h2>
		</div>
	</div>

	<div class="marquee-wrapper" role="region" aria-label="Client logos">
		<div class="marquee-track" id="marqueeTrack">
			<?php
				$marqueeItems = array_merge($clients, $clients, $clients);
				foreach ($marqueeItems as $client):
					$logoUrl = imgUrl($client['logo_path']);
			?>
			<div class="marquee-item">
				<div class="marquee-tooltip"><?= e($client['company_name']) ?></div>
				<div class="logo-wrap">
					<?php if ($logoUrl): ?>
						<img src="<?= e($logoUrl) ?>" alt="<?= e($client['company_name']) ?> logo" loading="lazy">
					<?php else: ?>
						<div class="logo-placeholder"><?= e($client['company_name']) ?></div>
					<?php endif; ?>
				</div>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<section id="services-expertise" class="fanout-section">
	<div class="fanout-wrapper">
		<div class="section-header reveal">
			<span class="section-label">Our Services</span>
			<h2 class="section-title" id="services-heading">Our Technology Services</h2>
			<p class="section-subtitle">From data engineering to business development — we bring the full spectrum of technology capabilities to your team.</p>
		</div>

		<div class="card-container">
			<article class="fanout-card fanout-card-1">
				<span class="fanout-number">01</span>
				<div class="fanout-card-content">
					<h3 class="fanout-title">Optimization</h3>
					<p class="fanout-text">
						We optimize enterprise systems, cloud-native
						architectures, and CI/CD pipelines to accelerate
						software delivery, strengthen operational
						reliability, and drive continuous efficiency.
					</p>
				</div>
			</article>

			<article class="fanout-card fanout-card-2">
				<span class="fanout-number">02</span>
				<div class="fanout-card-content">
					<h3 class="fanout-title">AI Engineering &amp; Intelligent Automation</h3>
					<p class="fanout-text">
						We build intelligent systems, machine learning
						models, and automated workflows—from generative
						AI to process orchestration—that automate
						repetitive tasks and deliver high-impact
						business solutions.
					</p>
				</div>
			</article>

			<article class="fanout-card fanout-card-3">
				<span class="fanout-number">03</span>
				<div class="fanout-card-content">
					<h3 class="fanout-title">Data Engineering &amp; Analytics</h3>
					<p class="fanout-text">
						We design and deploy robust architectures,
						pipelines, and governance frameworks to turn
						complex, scattered enterprise data into
						trusted, analytics-ready assets that drive
						strategic decision-making.
					</p>
				</div>
			</article>

			<article class="fanout-card fanout-card-4">
				<span class="fanout-number">04</span>
				<div class="fanout-card-content">
					<h3 class="fanout-title">Software Engineering &amp; Development</h3>
					<p class="fanout-text">
						We design, build, and modernize scalable
						enterprise applications, digital platforms,
						and custom APIs to establish secure,
						high-performing technology environments.
					</p>
				</div>
			</article>

			<article class="fanout-card fanout-card-5">
				<span class="fanout-number">05</span>
				<div class="fanout-card-content">
					<h3 class="fanout-title">Cybersecurity, Network, Infrastructure, Partner Management, Cloud, Support &amp; Services</h3>
					<p class="fanout-text">
						We architect, protect, and maintain hybrid
						infrastructure, cloud environments, and
						managed IT services to guarantee enterprise
						compliance, robust security, and 24/7
						reliability.
					</p>
				</div>
			</article>
		</div>

		<div class="services-btn-wrap">
			<a href="<?= SITE_URL ?>/services.php" class="services-button">
				<span>Our Services</span>
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
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

<section class="cta-band">
	<div class="cta-band-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="container reveal">
		<span class="cta-eyebrow">Let's Build Together</span>
		<h2 class="cta-title">Ready to Extend Your Tech Team?</h2>
		<p class="cta-text">Partner with Creative Web Technologies and gain access to Sri Lanka's finest technology talent — scalable, secure, and seamlessly integrated.</p>
		<a href="<?= SITE_URL ?>/services.php" class="btn btn-white">
			<span>Get Started Today</span>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
		</a>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script src="<?= SITE_URL ?>/assets/js/index.js"></script>
</body>
</html>