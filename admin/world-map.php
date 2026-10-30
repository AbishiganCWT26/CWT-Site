<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/world-map.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();
$catalog = worldMapCatalog();
$isAjax = ($_POST['action'] ?? '') === 'toggle';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!is_string($_POST['csrf_token'] ?? null) || empty($_POST['csrf_token']) || empty($_SESSION['csrf_token'])) {
        renderSiteError(403, 'Your session needs a refresh.', 'Please reload the page and try that action again.');
    }
    verifyCsrf();
    try {
        if ($isAjax) {
            $code = $_POST['country_code'] ?? '';
            $active = $_POST['is_active'] ?? '';
            if (!is_string($code) || !isset($catalog[$code]) || !in_array($active, ['0', '1'], true)) {
                http_response_code(422);
                header('Content-Type: application/json');
                echo json_encode(['ok' => false, 'message' => 'Choose a valid country.']);
                exit;
            }
            $pdo->prepare('INSERT INTO world_map_countries (country_code, is_active) VALUES (?, ?) ON DUPLICATE KEY UPDATE is_active = VALUES(is_active)')->execute([$code, (int)$active]);
            header('Content-Type: application/json');
            echo json_encode(['ok' => true]);
            exit;
        }
        $selected = $_POST['countries'] ?? [];
        if (!is_array($selected) || count(array_filter($selected, 'is_string')) !== count($selected) || array_diff($selected, array_keys($catalog))) {
            renderSiteError(422, null, 'Choose valid countries, then try saving the map again.');
        }
        $pdo->beginTransaction();
        $pdo->exec('UPDATE world_map_countries SET is_active = 0');
        $save = $pdo->prepare('INSERT INTO world_map_countries (country_code, is_active) VALUES (?, 1) ON DUPLICATE KEY UPDATE is_active = 1');
        foreach (array_unique($selected) as $code) $save->execute([$code]);
        $pdo->commit();
        setFlash('success', 'World map updated successfully.', 'Success');
        header('Location: ' . ADMIN_URL . '/world-map.php');
        exit;
    } catch (PDOException $error) {
        if ($pdo->inTransaction()) $pdo->rollBack();
        http_response_code(500);
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode(['ok' => false, 'message' => 'Could not save the change. Please try again.']);
            exit;
        }
        setFlash('error', 'Could not save the changes. Please try again.', 'Error');
        header('Location: ' . ADMIN_URL . '/world-map.php');
        exit;
    }
}
$selectedCountries = worldMapSelected($pdo);
$selectedCodes = array_column($selectedCountries, 'code');
$pageTitle = 'World Map';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-world-map.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-world-map.css') ?>">
<div class="page-header">
 <div><h1>World Map</h1><p>Choose the countries shown on the Products page's Our Clients map.</p></div>
 <a href="<?= SITE_URL ?>/products.php#our-clients" class="btn btn-secondary" target="_blank" rel="noopener">View website</a>
</div>
<div class="admin-card world-map-preview-card">
 <div class="admin-card-header"><h2>Map preview</h2><span id="worldMapCount"><?= count($selectedCodes) ?> countries selected</span></div>
 <div class="admin-card-body">
  <div class="world-map-preview" aria-label="Selected client countries">
   <img src="<?= SITE_URL ?>/World-Map-Dots.svg" alt="World map">
   <?php foreach ($catalog as $country): ?>
   <span class="world-map-preview-pin" data-pin="<?= e($country['code']) ?>" style="left:<?= $country['x'] / 2640.041 * 100 ?>%;top:<?= $country['y'] / 1226.7573 * 100 ?>%" <?= in_array($country['code'], $selectedCodes, true) ? '' : 'hidden' ?> title="<?= e($country['name']) ?>"><span><?= e($country['name']) ?></span></span>
   <?php endforeach; ?>
  </div>
 </div>
</div>
<form method="post" id="worldMapForm" action="world-map.php" class="admin-card">
 <input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
 <div class="admin-card-header"><h2>Countries</h2><button type="submit" class="btn btn-primary" id="worldMapSave">Save changes</button></div>
 <div class="admin-card-body">
  <label for="worldMapSearch" class="form-label">Find a country</label>
  <input type="search" class="form-control" id="worldMapSearch" placeholder="Search countries…" autocomplete="off">
  <p id="worldMapStatus" role="status" aria-live="polite">Choose countries, then save your changes.</p>
  <div class="world-map-country-grid">
   <?php foreach ($catalog as $country): ?>
   <label class="world-map-country" data-name="<?= e($country['name']) ?>">
    <span><?= e($country['name']) ?></span>
    <input type="checkbox" role="switch" name="countries[]" value="<?= e($country['code']) ?>" <?= in_array($country['code'], $selectedCodes, true) ? 'checked' : '' ?>>
    <span class="world-map-switch" aria-hidden="true"></span>
   </label>
   <?php endforeach; ?>
  </div>
  <p id="worldMapEmpty" hidden>No countries match your search.</p>
 </div>
</form>
<script src="<?= SITE_URL ?>/assets/js/admin-world-map.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-world-map.js') ?>" defer></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>
