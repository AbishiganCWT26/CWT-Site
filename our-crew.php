<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';


$placeholder = 'https://cdn-icons-png.flaticon.com/512/3237/3237447.png';

$expertCrew = getCrewByType($pdo, 'Expert');
$teamCrew   = getCrewByType($pdo, 'Team');

$ceo     = !empty($expertCrew) ? $expertCrew[0] : null;
$experts = array_slice($expertCrew, 1);

$teamCols = [[], [], []];
foreach ($teamCrew as $i => $m) {
	$teamCols[$i % 3][] = ['number' => $i + 1, 'member' => $m];
}

$pageTitle = 'Our Crew — Creative Web Technologies';
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="Meet our team — a CEO and <?= count($experts) ?> expert consultants leading our crew of specialists.">
	<title><?= e($pageTitle) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@600&display=swap" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Poppins:wght@600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/our-crew.css?v=<?= filemtime(__DIR__ . '/assets/css/our-crew.css') ?>">
	<script>document.documentElement.classList.add('js');</script>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/modern.css?v=<?= filemtime(__DIR__ . '/assets/css/modern.css') ?>">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/design.css?v=<?= filemtime(__DIR__ . '/assets/css/design.css') ?>">
<?php require_once __DIR__ . '/includes/theme.php'; ?>
<?php require_once __DIR__ . '/includes/loader.php'; ?>
</head>

<body class="cr-page cwt-public">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>
<main id="main-content" tabindex="-1">

<section class="cr-hero" aria-label="Our crew hero">
	<div class="cr-hero-bg" aria-hidden="true">
		<span></span>
		<span></span>
		<span></span>
	</div>
	<div class="cr-hero-grid" aria-hidden="true"></div>
	<div class="cr-container">
		<div class="cr-hero-inner">
			<span class="cr-hero-pill">
				<span class="cr-hero-pill-dot"></span>
				Our Crew
			</span>
			<h1 class="cr-hero-title">
				Meet Our <span class="cr-hero-accent">Expert Crew</span>
			</h1>
			<p class="cr-hero-text">
				A dedicated team led by our CEO, with <?= count($experts) ?> specialist consultants working together to deliver excellence at every step.
			</p>
			<div class="cr-hero-stats">
				<div class="cr-hero-stat">
					<strong><?= count($expertCrew) ?></strong>
					<span>Experts</span>
				</div>
				<div class="cr-hero-stat-div"></div>
				<div class="cr-hero-stat">
					<strong><?= count($teamCrew) ?></strong>
					<span>Team Members</span>
				</div>
			</div>
		</div>
	</div>

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
	      <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
	    </filter>
	  </defs>
	  <g filter="url(#heroGlowTR)" stroke-linecap="round" fill="none">
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="2.5" opacity="0.55" transform="translate(-14,-10)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="3"   opacity="0.75" transform="translate(-4,-2)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradTR)" stroke-width="2.2" opacity="1"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="#ffffff" stroke-width="0.9" opacity="0.85" transform="translate(6,6)"/>
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
	      <feMerge><feMergeNode in="blur"/><feMergeNode in="SourceGraphic"/></feMerge>
	    </filter>
	  </defs>
	  <g filter="url(#heroGlowBL)" stroke-linecap="round" fill="none">
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2.5" opacity="0.5" transform="translate(-14,-10)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2.8" opacity="0.7" transform="translate(-4,-2)"/>
	    <path d="M 300 -20 C 190 40, 150 140, 230 210 C 300 270, 400 250, 560 150" stroke="url(#heroSwirlGradBL)" stroke-width="2"   opacity="1"/>
	  </g>
	</svg>

	
</section>

<?php if ($ceo):
	$ceoPhoto = !empty($ceo['profile_photo']) ? imgUrl($ceo['profile_photo'], $placeholder) : $placeholder;
?>
<section class="cr-section cr-section-dark" aria-labelledby="cr-ceo-heading">
	<div class="cr-container">
		<div class="cr-sec-head cr-sec-head-left reveal">
			<span class="cr-sec-label">Leadership</span>
			<h2 class="cr-sec-title" id="cr-ceo-heading">Meet Our <em>Director &amp; CEO</em></h2>
		</div>

		<article class="cr-ceo-card reveal">
			<span class="cr-ceo-card-glow" aria-hidden="true"></span>
			<span class="cr-ceo-card-shine" aria-hidden="true"></span>

			<div class="cr-ceo-photo">
				<img src="<?= e($ceoPhoto) ?>" alt="<?= e($ceo['name']) ?>"
					 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
				<span class="cr-ceo-photo-ring" aria-hidden="true"></span>
			</div>

			<div class="cr-ceo-body">
				<h3 class="cr-ceo-name"><?= e($ceo['name']) ?></h3>
				<svg class="cr-ceo-signature" viewBox="0 0 260 28" fill="none" aria-hidden="true" focusable="false">
					<path class="cr-ceo-underline-main" pathLength="1" d="M5 19C34 14 65 12 99 11C140 9 184 7 220 11C232 12 243 14 254 17"/>
					<path class="cr-ceo-underline-return" pathLength="1" d="M248 21C223 16 197 17 170 22"/>
				</svg>
				<p class="cr-ceo-role"><?= e($ceo['position']) ?></p>
				<p class="cr-ceo-text">
					Leading our team with vision, expertise, and an unwavering commitment to technology excellence.
				</p>

				<?php if (!empty($ceo['linkedin_url'])): ?>
				<a href="<?= e($ceo['linkedin_url']) ?>" target="_blank" rel="noopener"
				   class="cr-ceo-link" aria-label="<?= e($ceo['name']) ?> on LinkedIn">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					<span>Connect on LinkedIn</span>
				</a>
				<?php endif; ?>
			</div>
		</article>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($experts)): ?>
<section class="cr-section cr-section-light" aria-labelledby="cr-experts-heading">
	<div class="cr-container">
		<div class="cr-sec-head reveal">
			<span class="cr-sec-label">Expert Consultants</span>
			<h2 class="cr-sec-title" id="cr-experts-heading">Specialists Shaping <em>Our Craft</em></h2>
			<p class="cr-sec-sub"><?= count($experts) ?> senior consultants leading key verticals across engineering, AI, security, and strategy.</p>
		</div>

		<div class="cr-experts-grid">
			<?php foreach ($experts as $i => $m):
				$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
			?>
			<article class="cr-expert-card reveal" style="--i:<?= $i ?>">
				<span class="cr-expert-glow" aria-hidden="true"></span>
				<span class="cr-expert-num"><?= str_pad($i + 1, 2, '0', STR_PAD_LEFT) ?></span>

				<div class="cr-expert-photo">
					<img src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
						 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
					<span class="cr-expert-photo-shine" aria-hidden="true"></span>
				</div>

				<div class="cr-expert-body">
					<h3 class="cr-expert-name"><?= e($m['name']) ?></h3>
					<p class="cr-expert-role"><?= e($m['position']) ?></p>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" target="_blank" rel="noopener"
					   class="cr-expert-linkedin" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="12" height="12" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
<?php endif; ?>

<?php if (!empty($teamCrew)): ?>
<section class="cr-section cr-section-tint" aria-labelledby="cr-team-heading">
	<div class="cr-container">
		<div class="cr-sec-head reveal">
			<span class="cr-sec-label">Our Crew</span>
			<h2 class="cr-sec-title" id="cr-team-heading">The People Behind <em>Every Project</em></h2>
			<p class="cr-sec-sub">Engineers, analysts, designers, and specialists — meet the team that brings the work to life.</p>
		</div>

		<div class="cr-team" id="crTeam">

			<div class="cr-team-photos">
				<?php foreach ($teamCols as $colIdx => $colItems): ?>
				<div class="cr-team-col cr-team-col-<?= $colIdx + 1 ?>">
					<?php foreach ($colItems as $item):
						$m = $item['member'];
						$n = $item['number'];
						$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
					?>
					<div class="cr-team-photo" data-id="<?= (int)$n ?>">
						<img src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
							 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
						<span class="cr-team-photo-overlay" aria-hidden="true"></span>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>

			<div class="cr-team-details">
				<span class="cr-team-details-eyebrow">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512" width="11" height="11" fill="currentColor" aria-hidden="true"><path d="M144 0a80 80 0 1 1 0 160A80 80 0 1 1 144 0zM512 0a80 80 0 1 1 0 160A80 80 0 1 1 512 0zM0 298.7C0 239.8 47.8 192 106.7 192h42.7c15.9 0 31 3.5 44.6 9.7c-1.3 7.2-1.9 14.7-1.9 22.3c0 38.2 16.8 72.5 43.3 96c-.2 0-.4 0-.7 0H21.3C9.6 320 0 310.4 0 298.7zM405.3 320c-.2 0-.4 0-.7 0c26.6-23.5 43.3-57.8 43.3-96c0-7.6-.7-15-1.9-22.3c13.6-6.3 28.7-9.7 44.6-9.7h42.7C592.2 192 640 239.8 640 298.7c0 11.8-9.6 21.3-21.3 21.3H405.3zM224 224a96 96 0 1 1 192 0 96 96 0 1 1 -192 0zM128 485.3C128 411.7 187.7 352 261.3 352H378.7C452.3 352 512 411.7 512 485.3c0 14.7-11.9 26.7-26.7 26.7H154.7c-14.7 0-26.7-11.9-26.7-26.7z"/></svg>
					Team Directory
				</span>
				<?php foreach ($teamCrew as $i => $m):
					$n = $i + 1;
					$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
				?>
				<div class="cr-team-item" data-id="<?= (int)$n ?>" tabindex="0">
					<span class="cr-team-item-dot" aria-hidden="true"></span>

					<div class="cr-team-item-avatar" aria-hidden="true">
						<img src="<?= e($photo) ?>" alt=""
							 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">
					</div>

					<div class="cr-team-item-body">
						<div class="cr-team-item-head">
							<h3 class="cr-team-item-name"><?= e($m['name']) ?></h3>
							<?php if ((int)$m['intern'] === 1): ?>
							<span class="cr-team-item-badge">Intern</span>
							<?php endif; ?>
						</div>
						<p class="cr-team-item-role"><?= e($m['position']) ?></p>
					</div>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" class="cr-team-item-linkedin" target="_blank" rel="noopener" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="13" height="13" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>

		<div class="cr-team-cards" aria-label="Team members">
			<?php foreach ($teamCrew as $i => $m):
				$n     = $i + 1;
				$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
			?>
			<article class="cr-team-card" style="--cr-img:url('<?= e($photo) ?>');">

				<img class="cr-team-card-img" src="<?= e($photo) ?>" alt="<?= e($m['name']) ?>"
					 loading="lazy"
					 onerror="this.onerror=null;this.src='<?= e($placeholder) ?>';">

				<span class="cr-team-card-shade" aria-hidden="true"></span>

				<div class="cr-team-card-body">
					<div class="cr-team-card-text">
						<h3 class="cr-team-card-name"><?= e($m['name']) ?></h3>
						<p class="cr-team-card-role"><?= e($m['position']) ?></p>
						<?php if ((int)$m['intern'] === 1): ?>
							<span class="cr-team-card-badge">Intern</span>
						<?php endif; ?>
					</div>

					<?php if (!empty($m['linkedin_url'])): ?>
					<a href="<?= e($m['linkedin_url']) ?>" target="_blank" rel="noopener"
					   class="cr-team-card-linkedin" aria-label="<?= e($m['name']) ?> on LinkedIn">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" width="14" height="14" fill="currentColor" aria-hidden="true"><path d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"/></svg>
					</a>
					<?php endif; ?>
				</div>
			</article>
			<?php endforeach; ?>
		</div>

	</div>
</section>
<?php endif; ?>

</main>
<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/our-crew.js?v=<?= filemtime(__DIR__ . '/assets/js/our-crew.js') ?>"></script>
</body>

</html>