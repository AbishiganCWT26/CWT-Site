<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Catch silent failure when the request exceeds post_max_size (PHP empties $_POST and $_FILES)
    if (empty($_POST) && empty($_FILES) && (int)($_SERVER['CONTENT_LENGTH'] ?? 0) > 0) {
        $msg = 'error_too_large';
    } else {
        $action = $_POST['action'] ?? '';

        try {
            if ($action === 'add') {
                $name  = trim($_POST['company_name'] ?? '');
                $order = (int)($_POST['sort_order'] ?? 0);

                $logoPath  = '';
                $fileError = $_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE;

                if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
                    $msg = 'error_too_large';
                } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
                    $uploaded = ($fileError === UPLOAD_ERR_OK) ? uploadFile($_FILES['logo'], 'clients') : false;
                    if ($uploaded === false) {
                        $msg = 'error_upload';
                    } else {
                        $logoPath = $uploaded;
                    }
                }

                if ($msg === '') {
                    if ($name === '' || $logoPath === '') {
                        $msg = 'error_required';
                    } else {
                        $stmt = $pdo->prepare("INSERT INTO clients (company_name,logo_path,sort_order,is_active) VALUES(?,?,?,1)");
                        $ok   = $stmt->execute([$name, $logoPath, $order]);
                        $msg  = $ok ? 'added' : 'error_db';
                    }
                }
            } elseif ($action === 'edit') {
                $id    = (int)($_POST['id'] ?? 0);
                $name  = trim($_POST['company_name'] ?? '');
                $order = (int)($_POST['sort_order'] ?? 0);

                $stmt = $pdo->prepare("SELECT logo_path FROM clients WHERE id=?");
                $stmt->execute([$id]);
                $current = $stmt->fetchColumn();

                if ($current === false) {
                    $msg = 'error_notfound';
                } else {
                    $logoPath  = $current ?: '';
                    $fileError = $_FILES['logo']['error'] ?? UPLOAD_ERR_NO_FILE;

                    if ($fileError === UPLOAD_ERR_INI_SIZE || $fileError === UPLOAD_ERR_FORM_SIZE) {
                        $msg = 'error_too_large';
                    } elseif ($fileError !== UPLOAD_ERR_NO_FILE) {
                        $uploaded = ($fileError === UPLOAD_ERR_OK) ? uploadFile($_FILES['logo'], 'clients') : false;
                        if ($uploaded === false) {
                            // Keep the previous logo AND report the failure
                            header('Location: ' . ADMIN_URL . '/clients?msg=error_upload&edit=' . $id);
                            exit;
                        }
                        $logoPath = $uploaded;
                    }

                    if ($msg === '') {
                        if ($name === '') {
                            $msg = 'error_required';
                        } else {
                            $stmt = $pdo->prepare("UPDATE clients SET company_name=?, logo_path=?, sort_order=? WHERE id=?");
                            $ok   = $stmt->execute([$name, $logoPath, $order, $id]);
                            $msg  = $ok ? 'updated' : 'error_db';
                        }
                    }
                }
            } elseif ($action === 'toggle') {
                $id     = (int)$_POST['id'];
                $active = (int)$_POST['is_active'];
                $stmt   = $pdo->prepare("UPDATE clients SET is_active=? WHERE id=?");
                $msg    = $stmt->execute([$active, $id]) ? 'updated' : 'error_db';
            } elseif ($action === 'delete') {
                $id   = (int)$_POST['id'];
                $stmt = $pdo->prepare("DELETE FROM clients WHERE id=?");
                $msg  = $stmt->execute([$id]) ? 'deleted' : 'error_db';
            }
        } catch (Throwable $e) {
            error_log('[clients.php] ' . $e->getMessage());
            $msg = 'error_db';
        }
    }

    header('Location: ' . ADMIN_URL . '/clients?msg=' . urlencode($msg));
    exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

/* Flash configuration: [type, message] */
$flash = null;
switch ($msg) {
    case 'added':           $flash = ['success', 'Client added successfully.']; break;
    case 'updated':         $flash = ['success', 'Client updated successfully.']; break;
    case 'deleted':         $flash = ['success', 'Client deleted successfully.']; break;
    case 'error_required':  $flash = ['error', 'Logo image and company name are required.']; break;
    case 'error_upload':    $flash = ['error', 'The logo could not be uploaded. Please use a JPG, PNG, GIF, WebP or SVG image under 5 MB.']; break;
    case 'error_too_large': $flash = ['error', 'The selected file is too large. Please upload an image under 5 MB.']; break;
    case 'error_notfound':  $flash = ['error', 'That client could not be found. Please reload the page.']; break;
    case 'error_db':        $flash = ['error', 'The change could not be saved. Database error.']; break;
}

$clients = $pdo->query("SELECT * FROM clients ORDER BY sort_order ASC, id ASC")->fetchAll();
$nextOrder = 1;
if (!empty($clients)) {
    $maxOrd = 0;
    foreach ($clients as $c) { if ((int)$c['sort_order'] > $maxOrd) $maxOrd = (int)$c['sort_order']; }
    $nextOrder = $maxOrd + 1;
}

$editClient = null;
if (isset($_GET['edit'])) {
    $id = (int)$_GET['edit'];
    $stmt = $pdo->prepare("SELECT * FROM clients WHERE id=?");
    $stmt->execute([$id]);
    $editClient = $stmt->fetch() ?: null;
}

$pageTitle = 'Client Logos';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
    <div>
        <h1>Client Logos</h1>
        <p>Manage client/company logos shown in the marquee sections.</p>
    </div>
    <?php if ($editClient): ?>
    <a href="clients.php" class="btn btn-secondary btn-sm">+ New Client</a>
    <?php endif; ?>
</div>

<div class="admin-card" style="margin-bottom:20px;">
    <div class="admin-card-header"><h2><?= $editClient ? 'Edit Client' : 'Add New Client' ?></h2></div>
    <div class="admin-card-body">
        <form method="POST" action="<?= e(ADMIN_URL) ?>/clients" enctype="multipart/form-data">
            <input type="hidden" name="action" value="<?= $editClient ? 'edit' : 'add' ?>">
            <?php if ($editClient): ?><input type="hidden" name="id" value="<?= $editClient['id'] ?>"><?php endif; ?>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Company Name <span class="req">*</span></label>
                    <input type="text" name="company_name" class="form-control" value="<?= e($editClient['company_name'] ?? '') ?>" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Sort Order <span class="req">*</span></label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $editClient['sort_order'] ?? $nextOrder ?>" min="1" required>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Logo Image <?= $editClient ? '' : '<span class="req">*</span>' ?></label>
                <input type="file" name="logo" class="form-control-file" accept="image/*" data-preview="logoPreview" <?= $editClient ? '' : 'required' ?>>
                <?php
                    $editLogoUrl = '';
                    if (!empty($editClient['logo_path'])) {
                        $editLogoUrl = imgUrl($editClient['logo_path']);
                        $fullPath    = UPLOAD_DIR . $editClient['logo_path'];
                        if (is_file($fullPath)) {
                            $editLogoUrl .= '?v=' . filemtime($fullPath);
                        }
                    }
                ?>
                <img id="logoPreview" src="<?= $editLogoUrl ? e($editLogoUrl) : '' ?>" class="img-preview" style="<?= $editLogoUrl === '' ? 'display:none;' : '' ?>">
                <p class="form-hint">JPG, PNG, WebP, SVG. Max 5MB.</p>
            </div>
            <button type="submit" class="btn btn-primary"><?= $editClient ? '💾 Update Client' : '➕ Add Client' ?></button>
            <?php if ($editClient): ?>
            <a href="clients.php" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<div class="admin-card">
    <div class="admin-card-header"><h2>All Clients (<?= count($clients) ?>)</h2></div>
    <div class="admin-card-body">
        <?php if (empty($clients)): ?>
            <p style="color:var(--text-muted);text-align:center;padding:40px;">No clients added yet.</p>
        <?php else: ?>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Logo</th>
                        <th>Company Name</th>
                        <th>Sort Order</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clients as $client): ?>
                    <tr>
                        <td>
                            <?php
                                $lu = '';
                                if (!empty($client['logo_path'])) {
                                    $lu = imgUrl($client['logo_path']);
                                    $fp = UPLOAD_DIR . $client['logo_path'];
                                    if (is_file($fp)) {
                                        $lu .= '?v=' . filemtime($fp);
                                    }
                                }
                            ?>
                            <?php if ($lu): ?>
                                <img src="<?= e($lu) ?>" alt="<?= e($client['company_name']) ?>">
                            <?php else: ?>
                                <span class="badge badge-grey">No logo</span>
                            <?php endif; ?>
                        </td>
                        <td><strong><?= e($client['company_name']) ?></strong></td>
                        <td><?= (int)$client['sort_order'] ?></td>
                        <td>
                            <span class="badge <?= $client['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                <?= $client['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </td>
                        <td>
                            <div style="display:flex;gap:6px;align-items:center;flex-wrap:wrap;">
                                <a href="clients.php?edit=<?= $client['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                    <input type="hidden" name="is_active" value="<?= $client['is_active'] ? 0 : 1 ?>">
                                    <button type="submit" class="btn btn-<?= $client['is_active'] ? 'warning' : 'success' ?> btn-xs">
                                        <?= $client['is_active'] ? 'Deactivate' : 'Activate' ?>
                                    </button>
                                </form>
                                <form method="POST" style="margin:0;">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?= $client['id'] ?>">
                                    <button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($client['company_name']) ?>'?">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?= SITE_URL ?>/assets/js/admin-clients.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-clients.js') ?>"></script>

<!-- SweetAlert2: CDN first, local copy as fallback, native alert as last resort -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<?php if ($flash): ?>
<script>
(function () {
    var flashIcon  = <?= json_encode($flash[0]) ?>;
    var flashTitle = <?= json_encode($flash[1]) ?>;
    var localSwal  = <?= json_encode(SITE_URL . '/assets/vendor/sweetalert2.all.min.js') ?>;

    function fire() {
        Swal.fire({
            icon: flashIcon,
            title: flashTitle,
            confirmButtonText: 'OK',
            confirmButtonColor: '#001be4'
        });
    }

    function loadLocalCopy() {
        var s = document.createElement('script');
        s.src = localSwal;
        s.onload = function () { if (window.Swal) { fire(); } else { alert(flashTitle); } };
        s.onerror = function () { alert(flashTitle); };
        document.head.appendChild(s);
    }

    document.addEventListener('DOMContentLoaded', function () {
        if (window.Swal) { fire(); } else { loadLocalCopy(); }
    });
})();
</script>
<?php endif; ?>

<?php include __DIR__ . '/layout_bottom.php'; ?>