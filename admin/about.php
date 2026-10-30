<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$about = getAboutUs($pdo);
$hist  = getHistory($pdo);
$vm    = getVisionMission($pdo);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$section = $_POST['section'] ?? '';

	try {
		if ($section === 'stats') {
			$sy = (int)($_POST['start_year'] ?? 2000);
			$rp = (int)($_POST['retention_pct'] ?? 95);
			$ec = (int)($_POST['employed_count'] ?? 200);
			$pc = (int)($_POST['projects_count'] ?? 55);
			if (!empty($about)) {
				$pdo->prepare("UPDATE aboutus SET start_year=?, retention_pct=?, employed_count=?, projects_count=? WHERE id=?")->execute([$sy, $rp, $ec, $pc, $about['id']]);
			} else {
				$pdo->prepare("INSERT INTO aboutus (start_year,retention_pct,employed_count,projects_count) VALUES(?,?,?,?)")->execute([$sy, $rp, $ec, $pc]);
			}
			setFlash('success', 'Company statistics updated successfully.');
			header('Location: ' . ADMIN_URL . '/about.php');
			exit;
		} elseif ($section === 'history') {
			$p1 = trim($_POST['para1'] ?? '');
			$p2 = trim($_POST['para2'] ?? '');
			$p3 = trim($_POST['para3'] ?? '');
			$oldImg1 = $hist['image1_path'] ?? '';
			$oldImg2 = $hist['image2_path'] ?? '';
			$img1 = $oldImg1;
			$img2 = $oldImg2;
			$newImg1Uploaded = false;
			$newImg2Uploaded = false;

			if (!empty($_FILES['image1']['name'])) {
				$n1 = uploadFile($_FILES['image1'], 'about');
				if ($n1) {
					$img1 = $n1;
					$newImg1Uploaded = true;
				} else {
					setFlash('error', 'Image 1 could not be uploaded. Please check file format and size (under 5MB).');
					header('Location: ' . ADMIN_URL . '/about.php');
					exit;
				}
			}

			if (!empty($_FILES['image2']['name'])) {
				$n2 = uploadFile($_FILES['image2'], 'about');
				if ($n2) {
					$img2 = $n2;
					$newImg2Uploaded = true;
				} else {
					// Clean up the first newly uploaded image if the second failed
					if ($newImg1Uploaded && $img1) {
						deleteUploadedFile($pdo, $img1, 'about');
					}
					setFlash('error', 'Image 2 could not be uploaded. Please check file format and size (under 5MB).');
					header('Location: ' . ADMIN_URL . '/about.php');
					exit;
				}
			}

			if (!empty($hist)) {
				$pdo->prepare("UPDATE history SET para1=?, image1_path=?, para2=?, image2_path=?, para3=? WHERE id=?")->execute([$p1, $img1, $p2, $img2, $p3, $hist['id']]);
			} else {
				$pdo->prepare("INSERT INTO history (para1,image1_path,para2,image2_path,para3) VALUES(?,?,?,?,?)")->execute([$p1, $img1, $p2, $img2, $p3]);
			}

			// Clean up previous physical images only after successful DB update
			if ($newImg1Uploaded && $oldImg1 !== '' && $oldImg1 !== $img1) {
				deleteUploadedFile($pdo, $oldImg1, 'about');
			}
			if ($newImg2Uploaded && $oldImg2 !== '' && $oldImg2 !== $img2) {
				deleteUploadedFile($pdo, $oldImg2, 'about');
			}

			setFlash('success', 'History timeline updated successfully.');
			header('Location: ' . ADMIN_URL . '/about.php');
			exit;
		} elseif ($section === 'vision_mission') {
			$vp = trim($_POST['vision_para'] ?? '');
			$mp = trim($_POST['mission_para'] ?? '');
			if (!empty($vm)) {
				$pdo->prepare("UPDATE vision_mission SET vision_para=?, mission_para=? WHERE id=?")->execute([$vp, $mp, $vm['id']]);
			} else {
				$pdo->prepare("INSERT INTO vision_mission (vision_para,mission_para) VALUES(?,?)")->execute([$vp, $mp]);
			}
			setFlash('success', 'Vision & Mission statements updated successfully.');
			header('Location: ' . ADMIN_URL . '/about.php');
			exit;
		}
	} catch (Throwable $e) {
		error_log('[about.php] ' . $e->getMessage());
		setFlash('error', 'Could not save changes. Database or file error occurred.');
		header('Location: ' . ADMIN_URL . '/about.php');
		exit;
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

<div class="admin-card">
	<div class="admin-card-header"><h2>Company Statistics</h2></div>
	<div class="admin-card-body">
		<form method="POST" action="">
			<input type="hidden" name="section" value="stats">
			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Company Start Year</label>
					<input type="number" name="start_year" class="form-control" value="<?= $about['start_year'] ?? 2000 ?>" required>
					<div class="form-hint">Years auto-calculated.</div>
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

<div class="admin-card">
	<div class="admin-card-header"><h2>History Timeline</h2></div>
	<div class="admin-card-body">
		<form method="POST" action="" enctype="multipart/form-data">
			<input type="hidden" name="section" value="history">
			<div class="form-group" style="padding-bottom:14px;border-bottom:1px solid var(--border);margin-bottom:14px;">
				<label class="form-label">Paragraph 1</label>
				<textarea name="para1" class="form-control" rows="4"><?= e($hist['para1'] ?? '') ?></textarea>
				<div style="margin-top:10px;">
					<label class="form-label">Image 1 (Optional)</label>
					<input type="file" name="image1" class="form-control-file" accept="image/*" data-preview="hImg1">
					<img id="hImg1" src="<?= imgUrl($hist['image1_path'] ?? '') ?>" class="img-preview" style="<?= empty($hist['image1_path']) ? 'display:none;' : '' ?>">
				</div>
			</div>
			<div class="form-group" style="padding-bottom:14px;border-bottom:1px solid var(--border);margin-bottom:14px;">
				<label class="form-label">Paragraph 2</label>
				<textarea name="para2" class="form-control" rows="4"><?= e($hist['para2'] ?? '') ?></textarea>
				<div style="margin-top:10px;">
					<label class="form-label">Image 2 (Optional)</label>
					<input type="file" name="image2" class="form-control-file" accept="image/*" data-preview="hImg2">
					<img id="hImg2" src="<?= imgUrl($hist['image2_path'] ?? '') ?>" class="img-preview" style="<?= empty($hist['image2_path']) ? 'display:none;' : '' ?>">
				</div>
			</div>
			<div class="form-group">
				<label class="form-label">Paragraph 3</label>
				<textarea name="para3" class="form-control" rows="4"><?= e($hist['para3'] ?? '') ?></textarea>
			</div>
			<button type="submit" class="btn btn-primary">💾 Save History</button>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>Vision & Mission</h2></div>
	<div class="admin-card-body">
		<form method="POST" action="">
			<input type="hidden" name="section" value="vision_mission">
			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Vision Statement</label>
					<textarea name="vision_para" class="form-control" rows="5"><?= e($vm['vision_para'] ?? '') ?></textarea>
				</div>
				<div class="form-group">
					<label class="form-label">Mission Statement</label>
					<textarea name="mission_para" class="form-control" rows="5"><?= e($vm['mission_para'] ?? '') ?></textarea>
				</div>
			</div>
			<button type="submit" class="btn btn-primary">💾 Save Vision & Mission</button>
		</form>
	</div>
</div>

<script src="<?= SITE_URL ?>/assets/js/admin-about.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-about.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>
