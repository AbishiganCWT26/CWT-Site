<?php
/**
 * Admin Dashboard
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

// Count stats
$stats = [
  'clients'    => $pdo->query("SELECT COUNT(*) FROM clients WHERE is_active=1")->fetchColumn(),
  'products'   => $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn(),
  'feedback'   => $pdo->query("SELECT COUNT(*) FROM client_feedback WHERE is_active=1")->fetchColumn(),
  'insights'   => $pdo->query("SELECT COUNT(*) FROM blog_details WHERE is_published=1")->fetchColumn(),
  'industries' => $pdo->query("SELECT COUNT(*) FROM industries")->fetchColumn(),
  'tech'       => $pdo->query("SELECT COUNT(*) FROM tech_stack")->fetchColumn(),
  'crew'       => $pdo->query("SELECT COUNT(*) FROM crew")->fetchColumn(),
];

$pageTitle = 'Dashboard';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
  <div>
    <h1>👋 Welcome back, <?= htmlspecialchars($_SESSION['admin_username']) ?>!</h1>
    <p>Manage all CWT website content from this dashboard.</p>
  </div>
  <a href="<?= SITE_URL ?>/index.php" target="_blank" class="btn btn-secondary btn-sm">View Website ↗</a>
</div>

<!-- Stats -->
<div class="dash-stats">
  <div class="dash-stat-card">
    <div class="dash-stat-icon pink">🏢</div>
    <div>
      <div class="dash-stat-num"><?= $stats['clients'] ?></div>
      <div class="dash-stat-label">Active Clients</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon blue">🚀</div>
    <div>
      <div class="dash-stat-num"><?= $stats['products'] ?></div>
      <div class="dash-stat-label">Products</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon green">💬</div>
    <div>
      <div class="dash-stat-num"><?= $stats['feedback'] ?></div>
      <div class="dash-stat-label">Testimonials</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon amber">📝</div>
    <div>
      <div class="dash-stat-num"><?= $stats['insights'] ?></div>
      <div class="dash-stat-label">Published Insights</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon purple">🏭</div>
    <div>
      <div class="dash-stat-num"><?= $stats['industries'] ?></div>
      <div class="dash-stat-label">Industries</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon blue">⚙️</div>
    <div>
      <div class="dash-stat-num"><?= $stats['tech'] ?></div>
      <div class="dash-stat-label">Tech Stack Items</div>
    </div>
  </div>
  <div class="dash-stat-card">
    <div class="dash-stat-icon green">👥</div>
    <div>
      <div class="dash-stat-num"><?= $stats['crew'] ?></div>
      <div class="dash-stat-label">Crew Members</div>
    </div>
  </div>
</div>

<!-- Quick Links -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>Quick Actions</h2>
  </div>
  <div class="admin-card-body">
    <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;">
      <?php
      $links = [
        ['href'=>'footer.php',     'icon'=>'🦶','label'=>'Edit Footer'],
        ['href'=>'clients.php',    'icon'=>'🏢','label'=>'Client Logos'],
        ['href'=>'about.php',      'icon'=>'ℹ️', 'label'=>'About Us'],
        ['href'=>'products.php',   'icon'=>'🚀','label'=>'Products'],
        ['href'=>'feedback.php',   'icon'=>'💬','label'=>'Testimonials'],
        ['href'=>'insights.php',   'icon'=>'📝','label'=>'Insights'],
        ['href'=>'industries.php', 'icon'=>'🏭','label'=>'Industries'],
        ['href'=>'tech-stack.php', 'icon'=>'⚙️','label'=>'Tech Stack'],
        ['href'=>'crew.php',       'icon'=>'👥','label'=>'Crew Members'],
      ];
      foreach ($links as $lnk):
      ?>
      <a href="<?= ADMIN_URL ?>/<?= $lnk['href'] ?>"
         style="display:flex;align-items:center;gap:12px;padding:16px;border:1px solid #e2e8f0;border-radius:10px;text-decoration:none;color:#1e293b;transition:all 0.2s;font-weight:600;font-size:0.9rem;"
         onmouseover="this.style.borderColor='#E40046';this.style.color='#E40046';"
         onmouseout="this.style.borderColor='#e2e8f0';this.style.color='#1e293b';">
        <span style="font-size:1.4rem;"><?= $lnk['icon'] ?></span>
        <?= htmlspecialchars($lnk['label']) ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Setup guide if DB needs attention -->
<div class="admin-card">
  <div class="admin-card-header">
    <h2>🔑 Default Admin Credentials</h2>
  </div>
  <div class="admin-card-body">
    <div class="alert alert-warning">
      ⚠️ <strong>Important:</strong> The default password hash in the database is a placeholder. Run the SQL below to set a real password.
    </div>
    <pre style="background:#1a1a2e;color:#e0e0f0;padding:16px;border-radius:8px;font-size:0.82rem;overflow-x:auto;">-- Run this once to set the real password (CWT@2026):
-- First generate hash: php -r "echo password_hash('0C0W0T0', PASSWORD_BCRYPT);"
-- Then:
UPDATE admin_users SET password_hash = '$YOUR_GENERATED_HASH' WHERE username = 'admin';</pre>
    <p style="margin-top:12px;font-size:0.85rem;color:#64748b;">Or run the helper script: <code>php <?= realpath(__DIR__ . '/../sql') ?>/set_password.php</code></p>
  </div>
</div>

<?php include __DIR__ . '/layout_bottom.php'; ?>