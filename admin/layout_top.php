<?php
/**
 * Admin Sidebar + Layout Helper
 * Include at top of every admin page after auth check.
 *
 * Usage:
 *   $pageTitle = 'Manage Footer';
 *   include __DIR__ . '/layout_top.php';
 *   ... page content ...
 *   include __DIR__ . '/layout_bottom.php';
 */

if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');

$adminUsername   = $_SESSION['admin_username'] ?? 'Admin';
$currentAdminPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($pageTitle ?? 'Admin Panel') ?> — CWT Admin</title>
  <meta name="robots" content="noindex, nofollow">
  <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin.css">
  <!-- Tag pill style for insight editor -->
  <style>
    .tag-pill { display:inline-flex;align-items:center;gap:4px;background:rgba(228,0,70,0.1);color:#E40046;border-radius:99px;padding:4px 12px;font-size:.78rem;font-weight:600;margin:3px; }
    .tag-pill button { background:none;border:none;cursor:pointer;color:#E40046;font-size:1rem;line-height:1;padding:0; }
    #tag_container { display:flex;flex-wrap:wrap;gap:4px;margin-bottom:8px;min-height:28px; }
  </style>
</head>
<body class="admin-body">

<div class="admin-layout">
  <!-- ── Sidebar ── -->
  <aside class="admin-sidebar" id="adminSidebar" role="navigation" aria-label="Admin navigation">
    <div class="sidebar-brand">
      <div class="sidebar-logo">CWT</div>
      <div>
        <div class="sidebar-brand-text">CWT Admin</div>
        <div class="sidebar-brand-sub">Content Management</div>
      </div>
    </div>

    <nav class="sidebar-nav">
      <div class="sidebar-section-label">Overview</div>
      <a href="<?= ADMIN_URL ?>/dashboard.php" class="sidebar-link <?= $currentAdminPage==='dashboard.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4M3.732 5.732a.5.5 0 0 1 .707 0l.915.914a.5.5 0 1 1-.708.708l-.914-.915a.5.5 0 0 1 0-.707M2 10a.5.5 0 0 1 .5-.5h1.586a.5.5 0 0 1 0 1H2.5A.5.5 0 0 1 2 10m9.5 0a.5.5 0 0 1 .5-.5h1.5a.5.5 0 0 1 0 1H12a.5.5 0 0 1-.5-.5m.754-4.246a.39.39 0 0 0-.527-.02L7.547 9.31a.91.91 0 1 0 1.302 1.258l3.434-4.297a.39.39 0 0 0-.029-.517z"/><path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A8 8 0 0 1 0 10"/></svg>
        Dashboard
      </a>

      <div class="sidebar-section-label">Website Content</div>
      <a href="<?= ADMIN_URL ?>/footer.php" class="sidebar-link <?= $currentAdminPage==='footer.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M1 1a1 1 0 0 0-1 1v6a1 1 0 0 0 1 1h6v1H4.5a.5.5 0 0 0 0 1h7a.5.5 0 0 0 0-1H9V9h6a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1zm0 1h14v6H1z"/></svg>
        Footer Settings
      </a>
      <a href="<?= ADMIN_URL ?>/clients.php" class="sidebar-link <?= $currentAdminPage==='clients.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1zm-7.978-1L7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002-.014.002zM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4m3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0M6.936 9.28a6 6 0 0 0-1.23-.247A7 7 0 0 0 5 9c-4 0-5 3-5 4q0 1 1 1h4.216A2.24 2.24 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816M4.92 10A5.5 5.5 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0m3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4"/></svg>
        Client Logos
      </a>
      <a href="<?= ADMIN_URL ?>/about.php" class="sidebar-link <?= $currentAdminPage==='about.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14m0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16"/><path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 1.178-.252 1.465-.598l.088-.416c-.2.176-.492.246-.686.246-.275 0-.375-.193-.304-.533zM9 4.5a1 1 0 1 1-2 0 1 1 0 0 1 2 0"/></svg>
        About Us
      </a>
      <a href="<?= ADMIN_URL ?>/products.php" class="sidebar-link <?= $currentAdminPage==='products.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/></svg>
        Products
      </a>
      <a href="<?= ADMIN_URL ?>/feedback.php" class="sidebar-link <?= $currentAdminPage==='feedback.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M2.678 11.894a1 1 0 0 1 .287.801 11 11 0 0 1-.398 2c1.395-.323 2.247-.697 2.634-.893a1 1 0 0 1 .71-.074A8 8 0 0 0 8 14c3.996 0 7-2.807 7-6s-3.004-6-7-6-7 2.808-7 6c0 1.468.617 2.83 1.678 3.894m-.493 3.905a22 22 0 0 1-.713.129c-.2.032-.352-.176-.273-.362a10 10 0 0 0 .244-.637l.003-.01c.248-.72.45-1.548.524-2.319C.743 12.37 0 10.76 0 9c0-3.866 3.582-7 8-7s8 3.134 8 7-3.582 7-8 7a9 9 0 0 1-2.347-.306c-.52.263-1.639.742-3.468 1.105"/></svg>
        Client Feedback
      </a>
      <a href="<?= ADMIN_URL ?>/client-meeting.php" class="sidebar-link <?= $currentAdminPage==='client-meeting.php'?'active':'' ?>">
				<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
					<path d="m11 17 2 2a1 1 0 1 0 3-3"/>
					<path d="m14 14 2.5 2.5a1 1 0 1 0 3-3l-3.88-3.88a3 3 0 0 0-4.24 0l-.88.88a1 1 0 1 1-3-3l2.81-2.81a5.79 5.79 0 0 1 7.06-.87l.47.28a2 2 0 0 0 1.42.25L21 4"/>
					<path d="m21 3 1 11h-2"/>
					<path d="M3 3 2 14l6.5 6.5a1 1 0 1 0 3-3"/>
					<path d="M3 4h8"/>
				</svg>
				Client Meetings
				<?php
					$__unreadCm = $pdo->query("SELECT COUNT(*) FROM client_meetings WHERE is_read = 0")->fetchColumn();
					if ($__unreadCm > 0):
				?>
				<span style="margin-left:auto;background:linear-gradient(135deg,#001be4,#1a66ff);color:#fff;font-size:.62rem;font-weight:800;padding:2px 8px;border-radius:99px;line-height:1.4;"><?= (int)$__unreadCm ?></span>
				<?php endif; ?>
			</a>
      <a href="<?= ADMIN_URL ?>/insights.php" class="sidebar-link <?= in_array($currentAdminPage,['insights.php','insight-edit.php'])?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1zM5 9.5a.5.5 0 0 1 .5-.5h5a.5.5 0 0 1 0 1h-5a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h2a.5.5 0 0 1 0 1h-2a.5.5 0 0 1-.5-.5"/><path d="M9.5 0H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V4.5zm0 1v2A1.5 1.5 0 0 0 11 4.5h2V14a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1z"/></svg>
        Insights / Blog
      </a>
      <a href="<?= ADMIN_URL ?>/industries.php" class="sidebar-link <?= $currentAdminPage==='industries.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M1 2.5A1.5 1.5 0 0 1 2.5 1h3A1.5 1.5 0 0 1 7 2.5v3A1.5 1.5 0 0 1 5.5 7h-3A1.5 1.5 0 0 1 1 5.5zM2.5 2a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 1h3A1.5 1.5 0 0 1 15 2.5v3A1.5 1.5 0 0 1 13.5 7h-3A1.5 1.5 0 0 1 9 5.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zM1 10.5A1.5 1.5 0 0 1 2.5 9h3A1.5 1.5 0 0 1 7 10.5v3A1.5 1.5 0 0 1 5.5 15h-3A1.5 1.5 0 0 1 1 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5zm6.5.5A1.5 1.5 0 0 1 10.5 9h3a1.5 1.5 0 0 1 1.5 1.5v3a1.5 1.5 0 0 1-1.5 1.5h-3A1.5 1.5 0 0 1 9 13.5zm1.5-.5a.5.5 0 0 0-.5.5v3a.5.5 0 0 0 .5.5h3a.5.5 0 0 0 .5-.5v-3a.5.5 0 0 0-.5-.5z"/></svg>
        Industries
      </a>
      <a href="<?= ADMIN_URL ?>/tech-stack.php" class="sidebar-link <?= $currentAdminPage==='tech-stack.php'?'active':'' ?>">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M5.854 4.854a.5.5 0 1 0-.708-.708l-3.5 3.5a.5.5 0 0 0 0 .708l3.5 3.5a.5.5 0 0 0 .708-.708L2.707 8zm4.292 0a.5.5 0 0 1 .708-.708l3.5 3.5a.5.5 0 0 1 0 .708l-3.5 3.5a.5.5 0 0 1-.708-.708L13.293 8z"/></svg>
        Tech Stack
      </a>

      <div class="sidebar-section-label">Account</div>
      <a href="<?= SITE_URL ?>/index.php" target="_blank" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m7.5-6.923c-.67.204-1.335.82-1.887 1.855A8 8 0 0 0 5.145 4H7.5zM4.09 4a9.3 9.3 0 0 1 .64-1.539 7 7 0 0 1 .597-.933A7.03 7.03 0 0 0 2.255 4zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a7 7 0 0 0-.656 2.5zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5zM8.5 5v2.5h2.99a12.5 12.5 0 0 0-.337-2.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5zM5.145 12q.208.58.468 1.068c.552 1.035 1.218 1.65 1.887 1.855V12zm.182 2.472a7 7 0 0 1-.597-.933A9.3 9.3 0 0 1 4.09 12H2.255a7 7 0 0 0 3.072 2.472M3.82 11a13.7 13.7 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5zm6.853 3.472A7 7 0 0 0 13.745 12H11.91a9.3 9.3 0 0 1-.64 1.539 7 7 0 0 1-.597.933M8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855q.26-.487.468-1.068zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.7 13.7 0 0 1-.312 2.5m2.802-3.5a7 7 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7 7 0 0 0-3.072-2.472c.218.284.418.598.597.933M10.855 4a8 8 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4z"/></svg>
        View Website
      </a>
      <a href="<?= ADMIN_URL ?>/logout.php" class="sidebar-link">
        <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z"/><path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708z"/></svg>
        Logout
      </a>
    </nav>

    <div class="sidebar-footer">
      Logged in as <strong><?= htmlspecialchars($adminUsername) ?></strong>
    </div>
  </aside>

  <!-- ── Main ── -->
  <main class="admin-main">
    <!-- Top bar -->
    <div class="admin-topbar">
      <div style="display:flex;align-items:center;gap:12px;">
        <button id="sidebarToggle" style="background:none;border:none;cursor:pointer;display:none;padding:4px;" aria-label="Toggle sidebar">
          <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5"/></svg>
        </button>
        <span class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Admin Panel') ?></span>
      </div>
      <div class="topbar-right">
        <a href="<?= SITE_URL ?>/index.php" target="_blank" style="font-size:0.8rem;color:var(--admin-muted);text-decoration:none;">View Site ↗</a>
        <div class="topbar-user">
          <div class="topbar-avatar"><?= strtoupper(substr($adminUsername, 0, 1)) ?></div>
          <?= htmlspecialchars($adminUsername) ?>
        </div>
      </div>
    </div>

    <div class="admin-content">
