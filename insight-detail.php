<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$slug = trim($_GET['slug'] ?? '');
if (empty($slug)) {
	header('Location: ' . SITE_URL . '/insights.php');
	exit;
}

$blog = getBlogBySlug($pdo, $slug);
if (!$blog) {
	http_response_code(404);
	$pageTitle = '404 — Post Not Found';
	include __DIR__ . '/includes/navbar.php';
	echo '<section class="page-hero"><div class="container"><h1>Post Not Found</h1><p>The article you are looking for does not exist or has been unpublished.</p><a href="' . SITE_URL . '/insights.php" class="btn btn-primary" style="margin-top:24px;">Back to Insights</a></div></section>';
	include __DIR__ . '/includes/footer.php';
	exit;
}

$featuredUrl = imgUrl($blog['featured_image']);
$tags        = parseHashtags($blog['hashtags']);
$pageTitle   = e($blog['topic']) . ' — CWT Insights';

$readMinutes = 1;
$plain = trim(strip_tags($blog['content'] ?? ''));
if ($plain !== '') {
	$readMinutes = max(1, (int)ceil(str_word_count($plain) / 220));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= $pageTitle ?></title>
	<meta name="description" content="<?= e(mb_substr(strip_tags($blog['topic']), 0, 160)) ?>">
	<?php if ($featuredUrl): ?>
	<meta property="og:image" content="<?= e($featuredUrl) ?>">
	<?php endif; ?>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Exo+2:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/insight-detail.css">
	<script>document.documentElement.classList.add('js');</script>
</head>
<body class="ind-page">

<div class="ind-progress" id="indProgress" aria-hidden="true">
	<span class="ind-progress-bar" id="indProgressBar"></span>
</div>

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

<article class="ind-article" itemscope itemtype="https://schema.org/Article">

	<header class="ind-hero">
		<div class="ind-hero-bg" aria-hidden="true">
			<span></span>
			<span></span>
			<span></span>
		</div>
		<div class="ind-hero-grid" aria-hidden="true"></div>
		<div class="ind-container">
			<div class="ind-hero-inner">

				<a href="<?= SITE_URL ?>/insights.php" class="ind-back">
					<i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
					<span>Back to Insights</span>
				</a>

				<?php if (!empty($tags)): ?>
				<div class="ind-tags">
					<?php foreach ($tags as $tag): ?>
					<span class="ind-tag">#<?= e($tag) ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>

				<h1 class="ind-title" itemprop="headline"><?= e($blog['topic']) ?></h1>

				<div class="ind-meta">
					<?php if (!empty($blog['publish_date'])): ?>
					<span class="ind-meta-item">
						<i class="fa-regular fa-calendar" aria-hidden="true"></i>
						<time datetime="<?= e(date('Y-m-d', strtotime($blog['publish_date']))) ?>" itemprop="datePublished">
							<?= date('F j, Y', strtotime($blog['publish_date'])) ?>
						</time>
					</span>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</header>

	<?php if ($featuredUrl): ?>
	<div class="ind-container">
		<figure class="ind-featured reveal">
			<img src="<?= e($featuredUrl) ?>" alt="<?= e($blog['topic']) ?>" loading="lazy" itemprop="image">
		</figure>
	</div>
	<?php endif; ?>

	<div class="ind-container">
		<div class="ind-body">

			<div class="ind-content blog-post reveal" itemprop="articleBody">
				<?= renderBlogContent($blog['content']) ?>
			</div>

			<div class="ind-share reveal">
				<span class="ind-share-label">
					<i class="fa-solid fa-share-nodes" aria-hidden="true"></i>
					Share this article
				</span>
				<div class="ind-share-buttons">
					<?php $shareUrl = urlencode((isset($_SERVER['HTTPS']) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>
					<a href="https://www.linkedin.com/shareArticle?mini=true&url=<?= $shareUrl ?>"
					   target="_blank" rel="noopener"
					   class="ind-share-btn ind-share-linkedin" aria-label="Share on LinkedIn">
						<i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
						<span>LinkedIn</span>
					</a>
					<a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= urlencode($blog['topic']) ?>"
					   target="_blank" rel="noopener"
					   class="ind-share-btn ind-share-x" aria-label="Share on X">
						<i class="fa-brands fa-x-twitter" aria-hidden="true"></i>
						<span>X</span>
					</a>
					<a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"
					   target="_blank" rel="noopener"
					   class="ind-share-btn ind-share-facebook" aria-label="Share on Facebook">
						<i class="fa-brands fa-facebook-f" aria-hidden="true"></i>
						<span>Facebook</span>
					</a>
					<button type="button" class="ind-share-btn ind-share-copy" id="indCopyLink" aria-label="Copy link">
						<i class="fa-solid fa-link" aria-hidden="true"></i>
						<span class="ind-copy-text">Copy Link</span>
					</button>
				</div>
			</div>

			<div class="ind-cta reveal">
				<div class="ind-cta-bg" aria-hidden="true">
					<span></span>
					<span></span>
				</div>
				<div class="ind-cta-inner">
					<span class="ind-cta-eyebrow">Let's Build</span>
					<h3 class="ind-cta-title">Want to Apply These Insights?</h3>
					<p class="ind-cta-text">Talk to our team of experts about how we can help your business leverage cutting-edge technology.</p>
					<a href="<?= SITE_URL ?>/services.php" class="ind-cta-btn">
						<span>Explore Our Services</span>
						<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
					</a>
				</div>
			</div>

		</div>
	</div>

</article>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/insight-detail.js"></script>
</body>
</html>