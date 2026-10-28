<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$stats = [
	'clients'    => $pdo->query("SELECT COUNT(*) FROM clients WHERE is_active=1")->fetchColumn(),
	'products'   => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
	'feedback'   => $pdo->query("SELECT COUNT(*) FROM client_feedback WHERE is_active=1")->fetchColumn(),
	'industries' => $pdo->query("SELECT COUNT(*) FROM industries")->fetchColumn(),
	'sol_cats'   => $pdo->query("SELECT COUNT(*) FROM solution_category")->fetchColumn(),
	'projects'   => $pdo->query("SELECT COUNT(*) FROM projects")->fetchColumn(),
	'tech'       => $pdo->query("SELECT COUNT(*) FROM tech_stack")->fetchColumn(),
	'crew'       => $pdo->query("SELECT COUNT(*) FROM crew")->fetchColumn(),
	'world_map'  => $pdo->query("SELECT COUNT(*) FROM world_map_countries WHERE is_active=1")->fetchColumn(),
	'meetings'   => $pdo->query("SELECT COUNT(*) FROM client_meetings WHERE is_read=0")->fetchColumn(),
];

$pageTitle = 'Dashboard';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-dashboard.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-dashboard.css') ?>">

<div class="page-header">
	<div>
		<h1>👋 Welcome back, <?= e($_SESSION['admin_username']) ?>!</h1>
		<p>Manage all CWT website content from this dashboard.</p>
	</div>
	<a href="<?= SITE_URL ?>/index.php" target="_blank" class="btn btn-secondary btn-sm">View Website ↗</a>
</div>

<div class="dash-stats">
	<div class="dash-stat-card"><div class="dash-stat-icon pink">🏢</div><div><div class="dash-stat-num"><?= $stats['clients'] ?></div><div class="dash-stat-label">Active Clients</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon blue">🚀</div><div><div class="dash-stat-num"><?= $stats['products'] ?></div><div class="dash-stat-label">Products</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon green">💬</div><div><div class="dash-stat-num"><?= $stats['feedback'] ?></div><div class="dash-stat-label">Testimonials</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon purple">🏭</div><div><div class="dash-stat-num"><?= $stats['industries'] ?></div><div class="dash-stat-label">Industries</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon purple">🗂️</div><div><div class="dash-stat-num"><?= $stats['sol_cats'] ?></div><div class="dash-stat-label">Solution Categories</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon pink">📁</div><div><div class="dash-stat-num"><?= $stats['projects'] ?></div><div class="dash-stat-label">Solution Projects</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon blue">⚙️</div><div><div class="dash-stat-num"><?= $stats['tech'] ?></div><div class="dash-stat-label">Tech Stack Items</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon green">👥</div><div><div class="dash-stat-num"><?= $stats['crew'] ?></div><div class="dash-stat-label">Crew Members</div></div></div>
	<div class="dash-stat-card"><div class="dash-stat-icon amber">🗺️</div><div><div class="dash-stat-num"><?= $stats['world_map'] ?></div><div class="dash-stat-label">World Map Countries</div></div></div>
	<div class="dash-stat-card dash-stat-accent"><div class="dash-stat-icon white">📩</div><div><div class="dash-stat-num"><?= $stats['meetings'] ?></div><div class="dash-stat-label">Unread Meetings</div></div></div>
	<div class="dash-stat-card">
		<div class="dash-stat-icon blue">🔐</div>
		<div>
			<div class="dash-stat-num"><?= $pdo->query("SELECT COUNT(*) FROM admin_users")->fetchColumn() ?></div>
			<div class="dash-stat-label">Admin Users</div>
		</div>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>Quick Actions</h2></div>
	<div class="admin-card-body">
		<div class="dash-links">
			<?php
			$links = [
				['href'=>'footer.php','icon'=>'🦶','label'=>'Edit Footer'],
				['href'=>'world-map.php','icon'=>'🗺️','label'=>'World Map'],
				['href'=>'clients.php','icon'=>'🏢','label'=>'Client Logos'],
				['href'=>'about.php','icon'=>'ℹ️','label'=>'About Us'],
				['href'=>'products.php','icon'=>'🚀','label'=>'Products'],
				['href'=>'feedback.php','icon'=>'💬','label'=>'Testimonials'],
				['href'=>'insights.php','icon'=>'📝','label'=>'Insights'],
				['href'=>'industries.php','icon'=>'🏭','label'=>'Industries'],
				['href'=>'solution-category.php','icon'=>'🗂️','label'=>'Solution Categories'],
				['href'=>'solution-projects.php','icon'=>'📁','label'=>'Solution Projects'],
				['href'=>'tech-stack.php','icon'=>'⚙️','label'=>'Tech Stack'],
				['href'=>'crew.php','icon'=>'👥','label'=>'Crew Members'],
				['href'=>'add-admin.php','icon'=>'🔐','label'=>'Add Admin User'],
				['href'=>'client-meeting.php','icon'=>'📩','label'=>'Client Meetings'],
			];
			foreach ($links as $lnk):
			?>
			<a href="<?= ADMIN_URL ?>/<?= $lnk['href'] ?>" class="dash-link">
				<span class="dash-link-ico"><?= $lnk['icon'] ?></span>
				<span><?= e($lnk['label']) ?></span>
			</a>
			<?php endforeach; ?>
		</div>
	</div>
</div>

<script src="<?= SITE_URL ?>/assets/js/admin-dashboard.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-dashboard.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>