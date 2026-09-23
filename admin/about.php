<?php
/**
 * Admin — Manage About Us, History, Vision & Mission
 */

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

$about = getAboutUs($pdo);
$hist  = getHistory($pdo);
$vm    = getVisionMission($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $section = $_POST['section'] ?? '';

    if ($section === 'stats') {
        $sy = (int)($_POST['start_year'] ?? 2000);
        $rp = (int)($_POST['retention_pct'] ?? 95);
        $ec = (int)($_POST['employed_count'] ?? 200);
        $pc = (int)($_POST['projects_count'] ?? 55);

        if (!empty($about)) {
            $pdo->prepare("UPDATE aboutus SET start_year=?, retention_pct=?, employed_count=?, projects_count=? WHERE id=?")
                ->execute([$sy, $rp, $ec, $pc, $about['id']]);
        } else {
            $pdo->prepare("INSERT INTO aboutus (start_year,retention_pct,employed_count,projects_count) VALUES(?,?,?,?)")
                ->execute([$sy, $rp, $ec, $pc]);
        }
        $about = getAboutUs($pdo);
        $msg = 'stats_updated';

    } elseif ($section === 'history') {
        $p1 = trim($_POST['para1'] ?? '');
        $p2 = trim($_POST['para2'] ?? '');
        $p3 = trim($_POST['para3'] ?? '');
        
        $img1 = $hist['image1_path'] ?? '';
        if (!empty($_FILES['image1']['name'])) {
            $newImg = uploadFile($_FILES['image1'], 'about');
            if ($newImg) $img1 = $newImg;
        }

        $img2 = $hist['image2_path'] ?? '';
        if (!empty($_FILES['image2']['name'])) {
            $newImg = uploadFile($_FILES['image2'], 'about');
            if ($newImg) $img2 = $newImg;
        }

        if (!empty($hist)) {
            $pdo->prepare("UPDATE history SET para1=?, image1_path=?, para2=?, image2_path=?, para3=? WHERE id=?")
                ->execute([$p1, $img1, $p2, $img2, $p3, $hist['id']]);
        } else {
            $pdo->prepare("INSERT INTO history (para1,image1_path,para2,image2_path,para3) VALUES(?,?,?,?,?)")
                ->execute([$p1, $img1, $p2, $img2, $p3]);
        }
        $hist = getHistory($pdo);
        $msg = 'history_updated';

    } elseif ($section === 'vision_mission') {
        $vp = trim($_POST['vision_para'] ?? '');
        $mp = trim($_POST['mission_para'] ?? '');

        if (!empty($vm)) {
            $pdo->prepare("UPDATE vision_mission SET vision_para=?, mission_para=? WHERE id=?")
                ->execute([$vp, $mp, $vm['id']]);
        } else {
            $pdo->prepare("INSERT INTO vision_mission (vision_para,mission_para) VALUES(?,?)")
                ->execute([$vp, $mp]);
        }
        $vm = getVisionMission($pdo);
        $msg = 'vm_updated';
    }
}

$pageTitle = 'About Us Content';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
  <div>
    <h1>About Us Content</h1>
    <p>Manage stats, history timeline, and vision/mission statements.</p>
  </div>
</div>

<?php if ($msg): ?>
<div class="alert alert-success" data-auto-dismiss="3000">
  ✅ Section updated successfully!
</div>
<?php endif; ?>

<!-- 1. Stats -->
<div class="admin-card">
  <div class="admin-card-header"><h2>Company Statistics (4 Cards)</h2></div>
  <div class="admin-card-body">
    <form method="POST" action="">
      <input type="hidden" name="section" value="stats">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Company Start Year (e.g., 2005)</label>
          <input type="number" name="start_year" class="form-control" value="<?= $about['start_year'] ?? 2000 ?>" required>
          <div class="form-hint">Years of experience will be auto-calculated (Current Year - Start Year).</div>
        </div>
        <div class="form-group">
          <label class="form-label">Retention Percentage (%)</label>
          <input type="number" name="retention_pct" class="form-control" value="<?= $about['retention_pct'] ?? 95 ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Employed Count</label>
          <input type="number" name="employed_count" class="form-control" value="<?= $about['employed_count'] ?? 200 ?>" required>
        </div>
        <div class="form-group">
          <label class="form-label">Projects Count</label>
          <input type="number" name="projects_count" class="form-control" value="<?= $about['projects_count'] ?? 55 ?>" required>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">💾 Save Stats</button>
    </form>
  </div>
</div>

<!-- 2. History -->
<div class="admin-card">
  <div class="admin-card-header"><h2>History Timeline</h2></div>
  <div class="admin-card-body">
    <form method="POST" action="" enctype="multipart/form-data">
      <input type="hidden" name="section" value="history">
      
      <div class="form-group" style="padding-bottom:16px;border-bottom:1px solid var(--admin-border);">
        <label class="form-label">Paragraph 1 (Always Visible)</label>
        <textarea name="para1" class="form-control" rows="4"><?= htmlspecialchars($hist['para1'] ?? '') ?></textarea>
        
        <div style="margin-top:12px;">
          <label class="form-label">Image 1 (Optional)</label>
          <input type="file" name="image1" class="form-control-file" accept="image/*" data-preview="hImg1">
          <img id="hImg1" src="<?= imgUrl($hist['image1_path'] ?? '') ?>" class="img-preview" style="<?= empty($hist['image1_path']) ? 'display:none;' : '' ?>">
        </div>
      </div>

      <p class="form-hint" style="margin:16px 0;font-weight:600;color:var(--admin-primary);">The following content appears when the user clicks "Read More".</p>

      <div class="form-group" style="padding-bottom:16px;border-bottom:1px solid var(--admin-border);">
        <label class="form-label">Paragraph 2</label>
        <textarea name="para2" class="form-control" rows="4"><?= htmlspecialchars($hist['para2'] ?? '') ?></textarea>
        
        <div style="margin-top:12px;">
          <label class="form-label">Image 2 (Optional)</label>
          <input type="file" name="image2" class="form-control-file" accept="image/*" data-preview="hImg2">
          <img id="hImg2" src="<?= imgUrl($hist['image2_path'] ?? '') ?>" class="img-preview" style="<?= empty($hist['image2_path']) ? 'display:none;' : '' ?>">
        </div>
      </div>

      <div class="form-group">
        <label class="form-label">Paragraph 3</label>
        <textarea name="para3" class="form-control" rows="4"><?= htmlspecialchars($hist['para3'] ?? '') ?></textarea>
      </div>

      <button type="submit" class="btn btn-primary">💾 Save History</button>
    </form>
  </div>
</div>

<!-- 3. Vision & Mission -->
<div class="admin-card">
  <div class="admin-card-header"><h2>Vision & Mission</h2></div>
  <div class="admin-card-body">
    <form method="POST" action="">
      <input type="hidden" name="section" value="vision_mission">
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Vision Statement</label>
          <textarea name="vision_para" class="form-control" rows="5"><?= htmlspecialchars($vm['vision_para'] ?? '') ?></textarea>
        </div>
        <div class="form-group">
          <label class="form-label">Mission Statement</label>
          <textarea name="mission_para" class="form-control" rows="5"><?= htmlspecialchars($vm['mission_para'] ?? '') ?></textarea>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">💾 Save Vision & Mission</button>
    </form>
  </div>
</div>

<?php include __DIR__ . '/layout_bottom.php'; ?>
