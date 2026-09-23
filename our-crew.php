<?php
session_start();
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';

$placeholder = 'https://cdn-icons-png.flaticon.com/512/3237/3237447.png';

$expertCrew = getCrewByType($pdo, 'Expert');
$teamCrew   = getCrewByType($pdo, 'Team');

$ceo     = !empty($expertCrew) ? $expertCrew[0] : null;
$experts = array_slice($expertCrew, 1, 8);

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
	<meta name="description" content="Meet our team — a CEO and eight expert consultants leading our crew of specialists.">
	<title><?= e($pageTitle) ?></title>

	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

	<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/our-crew.css">
	<script>document.documentElement.classList.add('js');</script>
</head>

<body class="cr-page">

<?php require_once __DIR__ . '/includes/navbar.php'; ?>

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
				A dedicated team led by our CEO, with eight specialist consultants working together to deliver excellence at every step.
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
				<span class="cr-ceo-badge">
					<i class="fa-solid fa-crown" aria-hidden="true"></i>
					Leadership
				</span>
				<h3 class="cr-ceo-name"><?= e($ceo['name']) ?></h3>
				<p class="cr-ceo-role"><?= e($ceo['position']) ?></p>
				<p class="cr-ceo-text">
					Leading our team with vision, expertise, and an unwavering commitment to technology excellence.
				</p>

				<?php if (!empty($ceo['linkedin_url'])): ?>
				<a href="<?= e($ceo['linkedin_url']) ?>" target="_blank" rel="noopener"
				   class="cr-ceo-link" aria-label="<?= e($ceo['name']) ?> on LinkedIn">
					<i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
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
			<p class="cr-sec-sub">Eight senior consultants leading key verticals across engineering, AI, security, and strategy.</p>
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
						<i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
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
					<i class="fa-solid fa-users" aria-hidden="true"></i>
					Team Directory
				</span>
				<?php foreach ($teamCrew as $i => $m):
					$n = $i + 1;
					$photo = !empty($m['profile_photo']) ? imgUrl($m['profile_photo'], $placeholder) : $placeholder;
				?>
				<div class="cr-team-item" data-id="<?= (int)$n ?>">
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
						<i class="fa-brands fa-linkedin-in" aria-hidden="true"></i>
					</a>
					<?php endif; ?>
				</div>
				<?php endforeach; ?>
			</div>

		</div>
	</div>
</section>
<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
<script src="<?= SITE_URL ?>/assets/js/our-crew.js"></script>
</body>

</html>