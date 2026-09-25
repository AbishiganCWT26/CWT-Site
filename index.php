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

	<!-- Nexus Field decorative swirl arcs -->
	<svg class="hero-swirl hero-swirl-tr" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="heroSwirlGradTR" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
				<stop offset="0%"  stop-color="#5b9cff" stop-opacity="0"/>
				<stop offset="18%" stop-color="#2b7bff" stop-opacity="0.9"/>
				<stop offset="55%" stop-color="#dce9ff" stop-opacity="1"/>
				<stop offset="100%" stop-color="#1a66ff" stop-opacity="0"/>
			</linearGradient>
			<filter id="heroGlowTR" x="-60%" y="-60%" width="220%" height="220%">
				<feGaussianBlur stdDeviation="4.5" result="blur"/>
				<feMerge>
					<feMergeNode in="blur"/>
					<feMergeNode in="SourceGraphic"/>
				</feMerge>
			</filter>
		</defs>
		<g filter="url(#heroGlowTR)" stroke-linecap="round" fill="none">
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradTR)" stroke-width="2.5" opacity="0.55" transform="translate(-14,-10)"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradTR)" stroke-width="3" opacity="0.75" transform="translate(-4,-2)"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradTR)" stroke-width="2.2" opacity="1"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="#ffffff" stroke-width="0.9" opacity="0.85" transform="translate(6,6)"/>
		</g>
	</svg>

	<svg class="hero-swirl hero-swirl-bl" viewBox="0 0 520 420" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false">
		<defs>
			<linearGradient id="heroSwirlGradBL" x1="60" y1="0" x2="520" y2="260" gradientUnits="userSpaceOnUse">
				<stop offset="0%"  stop-color="#7ea0f8" stop-opacity="0"/>
				<stop offset="18%" stop-color="#2b7bff" stop-opacity="0.9"/>
				<stop offset="55%" stop-color="#dce9ff" stop-opacity="1"/>
				<stop offset="100%" stop-color="#1a66ff" stop-opacity="0"/>
			</linearGradient>
			<filter id="heroGlowBL" x="-60%" y="-60%" width="220%" height="220%">
				<feGaussianBlur stdDeviation="4" result="blur"/>
				<feMerge>
					<feMergeNode in="blur"/>
					<feMergeNode in="SourceGraphic"/>
				</feMerge>
			</filter>
		</defs>
		<g filter="url(#heroGlowBL)" stroke-linecap="round" fill="none">
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradBL)" stroke-width="2.5" opacity="0.5" transform="translate(-14,-10)"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradBL)" stroke-width="2.8" opacity="0.7" transform="translate(-4,-2)"/>
			<path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150"
			      stroke="url(#heroSwirlGradBL)" stroke-width="2" opacity="1"/>
		</g>
	</svg>

	<!-- Nexus Field interactive canvas -->
	<canvas id="heroCanvas" class="hero-canvas" aria-hidden="true"></canvas>

	<div class="hero-content">

		<h1 class="hero-title animate-fade-up animate-fade-up-delay-1">
			Grow Smarter
			<span class="hero-title-line">
				<span class="accent">With Our</span>
			</span>
			Tech Experts
		</h1>

		<p class="hero-subtitle animate-fade-up animate-fade-up-delay-2">
			We turn ambitious ideas into products that move businesses forward with confidence and innovation.
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
				<span>View All Services</span>
				<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
			</a>
		</div>
	</div>
</section>

<!-- =========================================================
     WHAT WE OFFER section has been MOVED to services.php
     ========================================================= -->

<section class="cta-band">
	<div class="cta-band-bg" aria-hidden="true">
		<span></span>
		<span></span>
	</div>
	<div class="container reveal">
		<span class="cta-eyebrow">Let's Build Together</span>
		<h2 class="cta-title">Ready to Extend Your Tech Team?</h2>
		<p class="cta-text">Let’s talk about transforming your vision into reality.</p>
		<a href="<?= SITE_URL ?>/contact.php" class="btn btn-white">
			<span>Schedule a call</span>
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/></svg>
		</a>
	</div>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

<script src="<?= SITE_URL ?>/assets/js/index.js"></script>
</body>
</html>