<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$clients = getClients($pdo);
$about = getAboutUs($pdo);
$projects = $about['projects_count'] ?? 50;
$years = !empty($about['start_year']) ? calcYearsExperience((int)$about['start_year']) : 20;
$services = [
 ['01', 'Software engineering', 'Thoughtfully designed applications and digital platforms, built to grow with your business.', '<path d="m8 6-6 6 6 6m8-12 6 6-6 6m-3-15-2 18"/>'],
 ['02', 'AI & intelligent automation', 'Turn emerging technology into practical solutions that save time and unlock new possibilities.', '<path d="m12 3 2.5 6.5L21 12l-6.5 2.5L12 21l-2.5-6.5L3 12l6.5-2.5L12 3Z"/>'],
 ['03', 'Data engineering & analytics', 'Bring your data together. Find the insights that help you make your next move with confidence.', '<path d="M4 3v17h17M8 15v-4m5 4V7m5 8V4"/>'],
 ['04', 'Cloud & cybersecurity', 'Secure, resilient infrastructure and dependable support for the way you work today.', '<path d="m12 3 8 3v6c0 4-4 7-8 9-4-2-8-5-8-9V6l8-3Z"/><path d="m8 12 3 3 5-6"/>'],
 ['05', 'Optimization', 'Make your systems faster, your delivery smoother, and your technology work harder for you.', '<path d="m13 2-9 12h7l-1 8 10-13h-7l1-7Z"/>'],
 ['06', 'Project delivery & consulting', 'Expert guidance and clear governance, from your first conversation through to launch.', '<path d="m12 3 10 5-10 5L2 8l10-5Zm-10 9 10 5 10-5M2 16l10 5 10-5"/>'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Creative Web Technologies | Your technology partner</title>
<meta name="description" content="Build what comes next with Creative Web Technologies. Software engineering, AI, data, cloud and expert technology teams from Sri Lanka.">
<meta property="og:title" content="Creative Web Technologies | Build what comes next">
<meta property="og:description" content="Great ideas. Expert people. Technology that takes your business forward.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/index.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
</head>
<body class="cwt-public home-page">
<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">
<section class="home-hero">
<div class="home-container hero-layout">
<div class="hero-copy">
<span class="home-eyebrow"><span class="blue-dot"></span> YOUR TECHNOLOGY PARTNER</span>
<h1 class="hero-typegraphic">
<span class="cwt-sr-only">Technology built around your ambition.</span>
<span class="typegraphic-composition" aria-hidden="true">
<span class="typegraphic-kicker">Technology</span>
<span class="typegraphic-built-row"><span class="typegraphic-built">built</span><svg class="typegraphic-spark" viewBox="0 0 80 80" fill="none"><path d="M40 3v74M3 40h74M14 14l52 52M14 66l52-52" stroke="currentColor" stroke-width="3"/><circle cx="40" cy="40" r="12" stroke="currentColor" stroke-width="2"/></svg></span>
<span class="typegraphic-bridge"><span>around your</span><span class="typegraphic-arrow">&#8599;</span></span>
<span class="typegraphic-ambition">ambition.</span>
<svg class="typegraphic-underline" viewBox="0 0 440 24" fill="none" preserveAspectRatio="none"><path d="M3 17C100 1 246 2 437 13M32 22C135 10 254 8 364 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
</span>
</h1>
<p>We bring the right people and technology together to turn your ambitions into real progress. Your vision. Our expertise. One team.</p>
<div class="home-actions"><a class="home-button" href="<?= SITE_URL ?>/contact.php">Let’s build together <span aria-hidden="true">↗</span></a><a class="home-text-link" href="<?= SITE_URL ?>/services.php">Explore our services <span aria-hidden="true">→</span></a></div>
<a class="hero-discover" href="#services-expertise"><span>Discover what we can build</span><span aria-hidden="true">&#8595;</span></a>
<div class="hero-footnote"><span class="footnote-line"></span> Based in Sri Lanka. Building for the world.</div>
</div>
<div class="column-art" aria-label="Illustration of CWT's delivery workflow">
<img class="column-world" src="<?= SITE_URL ?>/World-Map-Dots.svg" alt="" aria-hidden="true">
<img class="column-world column-world-highlight" src="<?= SITE_URL ?>/assets/Images/sri-lanka-highlight.svg?v=2" alt="" aria-hidden="true">
<div class="column-art-content">
<div class="column-workflow">
<div class="column-widget-heading"><span class="column-widget-mark">CWT</span><span>Built around your business<small>A partnership, from idea to impact</small></span></div>
<div class="column-workflow-step"><span>01</span><div>Discover<small>Understand your goals and plan the way forward.</small></div><span class="column-step-check" aria-hidden="true">&#10003;</span></div>
<div class="column-workflow-step"><span>02</span><div>Build<small>The right expertise. One connected team.</small></div><span class="column-step-check" aria-hidden="true">&#10003;</span></div>
<div class="column-workflow-step"><span>03</span><div>Evolve<small>Keep improving as your ambitions grow.</small></div><span class="column-step-check" aria-hidden="true">&#10003;</span></div>
<div class="column-widget-footer"><span class="blue-dot"></span> Local expertise. Global possibilities.</div>
</div>
<div class="column-code">
<div class="column-code-heading"><span>partnership.json</span><span aria-hidden="true">&#123; &#125;</span></div>
<pre aria-label="Illustrative partnership configuration"><code>{
  <span class="code-key">"expertise"</span>: <span class="code-value">"built around you"</span>,
  <span class="code-key">"approach"</span>: <span class="code-value">"one connected team"</span>,
  <span class="code-key">"possibilities"</span>: <span class="code-value">"limitless"</span>
}</code></pre>
</div>
</div>
</div>
</div>
</section>
<section class="home-proof" aria-label="Our experience"><div class="home-container proof-layout">
<p>A technology partner.<br><strong>An extension of your team.</strong></p>
<div><strong><?= (int)$years ?>+</strong><span>Years of experience</span></div>
<div><strong><?= (int)$projects ?>+</strong><span>Projects delivered</span></div>
<div><strong>24/7</strong><span>Global support</span></div>
</div></section>
<?php if (!empty($clients)): ?>
<section class="home-clients home-container" aria-label="Our clients"><p>PARTNERING WITH AMBITIOUS BUSINESSES</p><div class="home-client-logos">
<?php foreach ($clients as $client): $logo = imgUrl($client['logo_path']); ?>
<div><?php if ($logo): ?><img src="<?= e($logo) ?>" alt="<?= e($client['company_name']) ?>" loading="lazy"><?php else: ?><span><?= e($client['company_name']) ?></span><?php endif; ?></div>
<?php endforeach; ?>
</div></section>
<?php endif; ?>
<section class="home-services home-section" id="services-expertise"><div class="home-container">
<div class="home-section-heading"><div><span class="home-eyebrow">WHAT WE DO</span><h2>The expertise you need.<br><span>The possibilities you want.</span></h2></div><p>From your first idea to your next big milestone, we help you build, evolve, and scale with confidence.</p></div>
<div class="home-service-filters" role="group" aria-label="Filter services" hidden>
<button type="button" data-filter="all" aria-pressed="true" aria-controls="home-service-grid">All services</button>
<button type="button" data-filter="engineering" aria-pressed="false" aria-controls="home-service-grid">Engineering</button>
<button type="button" data-filter="intelligence" aria-pressed="false" aria-controls="home-service-grid">AI &amp; data</button>
<button type="button" data-filter="operations" aria-pressed="false" aria-controls="home-service-grid">Cloud &amp; operations</button>
</div>
<p class="cwt-sr-only" id="service-filter-status" role="status" aria-live="polite" aria-atomic="true"></p>
<div class="home-service-grid home-service-slider" id="home-service-grid" role="region" aria-label="Technology services" tabindex="0">
<?php foreach ($services as [$number, $title, $description, $icon]): ?>
<a href="<?= SITE_URL ?>/services.php" class="home-service-card" data-category="<?= e(in_array($number, ['01', '06'], true) ? 'engineering' : (in_array($number, ['02', '03'], true) ? 'intelligence' : 'operations')) ?>"><div class="service-card-top"><svg viewBox="0 0 24 24" aria-hidden="true"><?= $icon ?></svg><span><?= $number ?></span></div><h3><?= e($title) ?></h3><p><?= e($description) ?></p><span class="service-card-link">Explore service <span aria-hidden="true">↗</span></span></a>
<?php endforeach; ?>
</div>
<div class="home-slider-controls" hidden>
<div class="home-slider-dots" role="group" aria-label="Choose service cards"></div>
</div>
<a class="home-text-link all-services" href="<?= SITE_URL ?>/services.php">Discover all our services <span aria-hidden="true">→</span></a>
</div></section>
<section class="home-partnership home-section" id="why-trust-us"><div class="home-container partnership-layout">
<div><span class="home-eyebrow">WHY CREATIVE WEB TECHNOLOGIES</span><h2>Good technology starts<br>with <span>great partnerships.</span></h2><p class="partnership-intro">We work alongside you, bringing curiosity, care, and deep technical expertise to every challenge.</p><a class="home-text-link" href="<?= SITE_URL ?>/about.php">Get to know CWT <span aria-hidden="true">→</span></a></div>
<div class="partnership-list">
<article><span>01</span><div><h3>The right people for your vision</h3><p>Specialists matched to your needs, from a single expert to a dedicated team.</p></div></article>
<article><span>02</span><div><h3>Room to grow, on your terms</h3><p>Flexible teams that adapt as your business and ambitions evolve.</p></div></article>
<article><span>03</span><div><h3>Clear communication. Shared progress.</h3><p>Open conversations, transparent workflows, and a team that feels like your own.</p></div></article>
<article><span>04</span><div><h3>Reliable delivery. Lasting value.</h3><p>A focus on quality, dependable delivery, and making your technology investment count.</p></div></article>
</div></div></section>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>
