<?php
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
$slug = $_GET['slug'] ?? '';
if ($slug === '') { header('Location: ' . SITE_URL . '/products'); exit; }
$project = getProjectWithDetails($pdo, $slug);
if (empty($project) || (int)$project['is_active'] !== 1) renderSiteError(404);
if ($slug !== $project['slug']) {
    header('Location: ' . SITE_URL . '/project-detail?slug=' . rawurlencode($project['slug']), true, 301);
    exit;
}
$details = $project['details'] ?? [];
$content = $details['content'] ?? '';
$hasContent = trim(strip_tags($content)) !== '' || preg_match('/<img\b/i', $content);
$bannerImg = imgUrl(($details['banner_image'] ?? '') ?: ($project['image_path'] ?? ''));
$mockupUrls = getProjectMockupUrls($pdo, (int)$project['id']);
$techList = array_filter(array_map('trim', explode(',', $details['technologies'] ?? '')));
$liveUrl = $details['live_site_url'] ?? '';
if (!preg_match('~^https?://~i', $liveUrl)) $liveUrl = '';
$sectionLinks = [];
// Add navigation anchors to the rendered content; stored editor HTML is unchanged.
if ($hasContent) {
    $doc = new DOMDocument('1.0', 'UTF-8');
    $previous = libxml_use_internal_errors(true);
    $doc->loadHTML('<?xml encoding="UTF-8"><div id="pd-rich-content">' . $content . '</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    $wrapper = $doc->getElementById('pd-rich-content');
    if ($wrapper) {
        $headingIndex = 0;
        foreach ($wrapper->getElementsByTagName('h2') as $heading) {
            $id = 'project-section-' . ++$headingIndex;
            $heading->setAttribute('id', $id);
            $sectionLinks[] = ['id' => $id, 'title' => trim($heading->textContent)];
        }
        $content = '';
        foreach ($wrapper->childNodes as $child) $content .= $doc->saveHTML($child);
    }
    libxml_clear_errors();
    libxml_use_internal_errors($previous);
}
$stmt = $pdo->prepare('SELECT * FROM projects WHERE category_id = ? AND id != ? AND is_active = 1 ORDER BY sort_order ASC LIMIT 3');
$stmt->execute([$project['category_id'], $project['id']]);
$relatedProjects = $stmt->fetchAll();
$nameParts = preg_split('/\s+/', trim($project['project_name']));
$titleAccent = count($nameParts) > 1 ? array_pop($nameParts) : '';
$titleMain = implode(' ', $nameParts);
$pageTitle = $project['project_name'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle) ?> | Creative Web Technologies</title>
    <meta name="description" content="<?= e($project['short_description'] ?? '') ?>">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css?v=<?= filemtime(__DIR__ . '/assets/css/modern.css') ?>">
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
    <?php require_once __DIR__ . '/includes/theme.php'; ?>
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/project-detail.css?v=<?= filemtime(__DIR__ . '/assets/css/project-detail.css') ?>">
</head>
<body class="cwt-public pd-page">
<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">
<header class="pd-masthead" id="project-overview">
    <?php if ($bannerImg): ?><img class="pd-masthead-image" src="<?= e($bannerImg) ?>" alt="<?= e($project['project_name']) ?> project overview" fetchpriority="high"><?php endif; ?>
    <div class="pd-masthead-shade" aria-hidden="true"></div>
    <div class="pd-masthead-inner">
        <a href="<?= SITE_URL ?>/products" class="pd-back"><span aria-hidden="true">&larr;</span> Back to portfolio</a>
        <div class="pd-hero-top">
            <div>
                <span class="pd-eyebrow"><span aria-hidden="true"></span><?= e($project['category_name'] ?? 'Our work') ?></span>
                <h1 class="pd-title"><?= e($titleMain) ?><?php if ($titleAccent): ?><span><?= e($titleAccent) ?></span><?php endif; ?></h1>
            </div>
            <div class="pd-hero-summary">
                <?php if (!empty($project['short_description'])): ?><p class="pd-client-label">A SOLUTION FOR</p><p class="pd-client"><?= e($project['short_description']) ?></p><?php endif; ?>
                <div class="pd-hero-actions">
                    <?php if ($liveUrl): ?><a href="<?= e($liveUrl) ?>" class="pd-text-link" target="_blank" rel="noopener noreferrer">Visit live site <span aria-hidden="true">&nearr;</span></a><?php endif; ?>
                </div>
            </div>
        </div>

    </div>
</header>
<div class="project-wrap">
        <?php if (!empty($details['client_name']) || !empty($details['industry']) || !empty($details['duration']) || $techList): ?>
        <dl class="pd-facts">
            <?php foreach (['client_name' => 'Client', 'industry' => 'Industry', 'duration' => 'Duration'] as $key => $label): if (empty($details[$key])) continue; ?>
            <div><dt><?= $label ?></dt><dd><?= e($details[$key]) ?></dd></div>
            <?php endforeach; ?>
            <?php if ($techList): ?><div class="pd-facts-tech"><dt>Built with</dt><dd><?php foreach ($techList as $tech): ?><span><?= e($tech) ?></span><?php endforeach; ?></dd></div><?php endif; ?>
        </dl>
        <?php endif; ?>


    <?php if ($hasContent): ?>
    <section class="pd-story pd-reveal" id="project-story" aria-label="Project details">
        <aside class="pd-sidebar"><nav class="pd-toc" aria-label="In this project"><span class="pd-eyebrow">IN THIS PROJECT</span><a href="#project-overview">Overview</a><?php foreach ($sectionLinks as $link): ?><a href="#<?= e($link['id']) ?>"><?= e($link['title']) ?></a><?php endforeach; ?><?php if ($mockupUrls): ?><a href="#project-showcase">The experience</a><?php endif; ?></nav><div class="pd-side-note"><span aria-hidden="true">&nearr;</span><p>Have a similar challenge?</p><a href="<?= SITE_URL ?>/contact">Let's build what's next &rarr;</a></div></aside>
        <article class="content-section pd-reading"><?= $content ?></article>
    </section>
    <?php else: ?>
    <section class="pd-empty"><span class="pd-eyebrow">THE PROJECT</span><h2>More of the story, coming soon.</h2><p>Explore our portfolio to discover more of our work.</p><a class="pd-text-link" href="<?= SITE_URL ?>/products">Browse our solutions &rarr;</a></section>
    <?php endif; ?>

    <?php if ($relatedProjects): ?>
    <section class="pd-related pd-reveal" aria-labelledby="related-title"><div class="pd-section-heading"><div><span class="pd-eyebrow">KEEP EXPLORING</span><h2 id="related-title">More ideas. <span>More impact.</span></h2></div><a class="pd-text-link" href="<?= SITE_URL ?>/products">View all projects &nearr;</a></div><div class="pd-related-grid">
        <?php foreach ($relatedProjects as $item): ?><a class="pd-related-card" href="<?= SITE_URL ?>/project-detail?slug=<?= rawurlencode($item['slug']) ?>"><?php if (!empty($item['image_path'])): ?><div class="pd-related-image"><img src="<?= e(imgUrl($item['image_path'])) ?>" alt="" loading="lazy"></div><?php endif; ?><div class="pd-related-body"><span class="pd-eyebrow"><?= e($project['category_name']) ?></span><h3><?= e($item['project_name']) ?></h3><p><?= e($item['short_description'] ?? '') ?></p><span class="pd-related-link">Explore project <span aria-hidden="true">&nearr;</span></span></div></a><?php endforeach; ?>
    </div></section>
    <?php endif; ?>
    <?php if ($mockupUrls): ?>
    <section class="pd-showcase pd-reveal" id="project-showcase" aria-labelledby="showcase-title">
        <div class="pd-section-heading"><div><span class="pd-eyebrow">THE EXPERIENCE</span><h2 id="showcase-title">See the solution <span>in action.</span></h2></div><p>Explore the screens behind the solution.<br>Select a preview to take a closer look.</p></div>
        <div class="pd-showcase-layout">
        <div class="pd-gallery" data-project-gallery>
            <div class="pd-computer">
                <div class="pd-monitor-shell">
                    <div class="pd-monitor-camera" aria-hidden="true"></div>
                    <div class="pd-monitor-display">
            <div class="pd-browser-bar"><span class="pd-browser-dots" aria-hidden="true"><i></i><i></i><i></i></span><span><?= e($project['project_name']) ?></span><span class="pd-screen-count" data-gallery-count>01 / <?= str_pad((string)count($mockupUrls), 2, '0', STR_PAD_LEFT) ?></span></div>
            <a class="pd-screen" href="<?= e($mockupUrls[0]) ?>" data-gallery-open aria-label="View screenshot 1 at full size">
                <img src="<?= e($mockupUrls[0]) ?>" alt="<?= e($project['project_name']) ?> screenshot 1" data-gallery-image loading="lazy" width="1280" height="800">
                <span class="pd-expand"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M8 3H3v5m13-5h5v5M3 16v5h5m13-5v5h-5"/></svg> View full size</span>
            </a>
                                </div>
                    <div class="pd-monitor-chin" aria-hidden="true"><span>CWT</span><i></i></div>
                </div>
                <div class="pd-monitor-stand" aria-hidden="true"><div class="pd-monitor-neck"></div><div class="pd-monitor-foot"></div></div>
            </div>
            <div class="pd-gallery-footer"><p data-gallery-caption aria-live="polite">Screen 01 of <?= str_pad((string)count($mockupUrls), 2, '0', STR_PAD_LEFT) ?></p><div class="pd-gallery-arrows" data-gallery-controls hidden><button type="button" data-gallery-prev aria-label="Previous screenshot">&larr;</button><button type="button" data-gallery-next aria-label="Next screenshot">&rarr;</button></div></div>
        </div>
        <aside class="pd-preview-rail" aria-label="Project screenshots">
        <div class="pd-thumbnails" data-preview-scroll>
            <?php foreach ($mockupUrls as $index => $url): ?><a href="<?= e($url) ?>" class="pd-thumbnail" data-screen-index="<?= $index ?>" aria-label="Show screenshot <?= $index + 1 ?>" <?= $index === 0 ? 'aria-current="true"' : '' ?>><img src="<?= e($url) ?>" alt="" loading="lazy" width="160" height="100"><span>Screen <?= str_pad((string)($index + 1), 2, '0', STR_PAD_LEFT) ?></span></a><?php endforeach; ?>
        </div>
        </aside>
        </div>
    </section>
    <?php endif; ?>


</div>
<?php if ($mockupUrls): ?>
<dialog class="pd-lightbox" aria-label="Full-size project screenshot" data-gallery-dialog>
    <div class="pd-lightbox-top"><span><?= e($project['project_name']) ?></span><button type="button" data-gallery-close aria-label="Close screenshot viewer">&times;</button></div>
    <div class="pd-lightbox-image"><img src="<?= e($mockupUrls[0]) ?>" alt="<?= e($project['project_name']) ?> screenshot 1" data-dialog-image></div>
    <div class="pd-lightbox-bottom"><button type="button" data-dialog-prev aria-label="Previous screenshot">&larr;</button><span data-dialog-count>Screen 1 of <?= count($mockupUrls) ?></span><button type="button" data-dialog-next aria-label="Next screenshot">&rarr;</button></div>
</dialog>
<?php endif; ?>
</main>
<script src="<?= SITE_URL ?>/assets/js/project-detail.js?v=<?= filemtime(__DIR__ . '/assets/js/project-detail.js') ?>" defer></script>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
</body>
</html>