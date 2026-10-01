<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$action = $_POST['action'] ?? '';

	if ($action === 'add' || $action === 'edit') {
		$id      = (int)($_POST['id'] ?? 0);
		$name    = trim($_POST['product_name'] ?? '');
		$desc    = trim($_POST['description'] ?? '');
		$url     = trim($_POST['visit_url'] ?? '');
		$showBtn = isset($_POST['show_button']) ? 1 : 0;
		$order   = (int)($_POST['sort_order'] ?? 0);

		$logoPath = '';
		if ($action === 'edit') {
			$existing = $pdo->prepare("SELECT logo_path FROM products WHERE id=?");
			$existing->execute([$id]);
			$logoPath = $existing->fetchColumn() ?: '';
		}

		if (!empty($_FILES['logo']['name'])) {
			$newLogo = uploadFile($_FILES['logo'], 'products');
			if ($newLogo) $logoPath = $newLogo;
		}

		if ($name === '' || $desc === '' || $logoPath === '') {
			$msg = 'error_required';
		} else {
			if ($action === 'add') {
				$pdo->prepare("INSERT INTO products (product_name, description, visit_url, show_button, sort_order, logo_path) VALUES (?,?,?,?,?,?)")
					->execute([$name, $desc, $url, $showBtn, $order, $logoPath]);
				$msg = 'added';
			} else {
				$pdo->prepare("UPDATE products SET product_name=?, description=?, visit_url=?, show_button=?, sort_order=?, logo_path=? WHERE id=?")
					->execute([$name, $desc, $url, $showBtn, $order, $logoPath, $id]);
				$msg = 'updated';
			}
		}
	} elseif ($action === 'delete') {
		$id = (int)$_POST['id'];
		$pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);
		$msg = 'deleted';
	}

	header('Location: ' . ADMIN_URL . '/products.php?msg=' . $msg);
	exit;
}

if (isset($_GET['msg'])) $msg = $_GET['msg'];

$editProduct = null;
if (isset($_GET['edit'])) {
	$id = (int)$_GET['edit'];
	$stmt = $pdo->prepare("SELECT * FROM products WHERE id=?");
	$stmt->execute([$id]);
	$editProduct = $stmt->fetch();
}

$products = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC")->fetchAll();
$pageTitle = 'Products Portfolio';
include __DIR__ . '/layout_top.php';
?>

<div class="page-header">
	<div>
		<h1>Products Portfolio</h1>
		<p>Manage the products and projects showcased on the Our Products page.</p>
	</div>
	<?php if ($editProduct): ?>
	<a href="products.php" class="btn btn-secondary btn-sm">+ New Product</a>
	<?php endif; ?>
</div>

<?php if ($msg === 'added' || $msg === 'updated' || $msg === 'deleted'): ?>
<div class="alert alert-success" data-auto-dismiss="2600">Product <?= $msg ?> successfully.</div>
<?php elseif ($msg === 'error_required'): ?>
<div class="alert alert-danger" data-auto-dismiss="3000">Description, Logo Image and Product Name are required.</div>
<?php endif; ?>

<div class="admin-card" style="margin-bottom:20px;">
	<div class="admin-card-header"><h2><?= $editProduct ? 'Edit Product' : 'Add New Product' ?></h2></div>
	<div class="admin-card-body">
		<form method="POST" action="products.php" enctype="multipart/form-data">
			<input type="hidden" name="action" value="<?= $editProduct ? 'edit' : 'add' ?>">
			<?php if ($editProduct): ?><input type="hidden" name="id" value="<?= $editProduct['id'] ?>"><?php endif; ?>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Product Name <span class="req">*</span></label>
					<input type="text" name="product_name" class="form-control" value="<?= e($editProduct['product_name'] ?? '') ?>" required>
				</div>
				<div class="form-group">
					<label class="form-label">Visit URL</label>
					<input type="url" name="visit_url" class="form-control" value="<?= e($editProduct['visit_url'] ?? '') ?>" placeholder="https://...">
				</div>
			</div>

			<div class="form-group">
				<label class="form-label">Description <span class="req">*</span></label>
				<textarea name="description" class="form-control" rows="4" required><?= e($editProduct['description'] ?? '') ?></textarea>
			</div>

			<div class="form-row">
				<div class="form-group">
					<label class="form-label">Logo Image <?= $editProduct ? '' : '<span class="req">*</span>' ?></label>
					<input type="file" name="logo" class="form-control-file" accept="image/*" data-preview="prodLogo" <?= $editProduct ? '' : 'required' ?>>
					<img id="prodLogo" src="<?= imgUrl($editProduct['logo_path'] ?? '') ?>" class="img-preview" style="<?= empty($editProduct['logo_path']) ? 'display:none;' : '' ?>">
				</div>
				<div>
					<div class="form-group">
						<label class="form-label">Sort Order</label>
						<input type="number" name="sort_order" class="form-control" value="<?= $editProduct['sort_order'] ?? 0 ?>">
					</div>
					<div class="form-group">
						<label class="form-check">
							<input type="checkbox" name="show_button" value="1" <?= (!isset($editProduct) || $editProduct['show_button']) ? 'checked' : '' ?>>
							<span>Show "Visit Site" button</span>
						</label>
					</div>
				</div>
			</div>

			<button type="submit" class="btn btn-primary"><?= $editProduct ? '💾 Update Product' : '➕ Add Product' ?></button>
			<?php if ($editProduct): ?>
			<a href="products.php" class="btn btn-secondary" style="margin-left:8px;">Cancel</a>
			<?php endif; ?>
		</form>
	</div>
</div>

<div class="admin-card">
	<div class="admin-card-header"><h2>All Products (<?= count($products) ?>)</h2></div>
	<div class="admin-card-body">
		<?php if (empty($products)): ?>
			<p style="color:var(--text-muted);text-align:center;padding:40px;">No products added yet.</p>
		<?php else: ?>
		<div class="admin-table-wrap">
			<table class="admin-table">
				<thead>
					<tr>
						<th>Logo</th>
						<th>Name</th>
						<th>Description</th>
						<th>URL / Button</th>
						<th>Order</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($products as $prod): ?>
					<tr>
						<td>
							<?php $lu = imgUrl($prod['logo_path']); ?>
							<?php if ($lu): ?>
								<img src="<?= e($lu) ?>" alt="Logo">
							<?php else: ?>
								<span class="badge badge-grey">No logo</span>
							<?php endif; ?>
						</td>
						<td><strong><?= e($prod['product_name']) ?></strong></td>
						<td style="max-width:280px;">
							<div style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:12px;color:var(--text-muted);line-height:1.45;">
								<?= e($prod['description'] ?? '') ?>
							</div>
						</td>
						<td>
							<?php if ($prod['visit_url']): ?>
								<a href="<?= e($prod['visit_url']) ?>" target="_blank" style="color:var(--primary);font-weight:600;">Link ↗</a>
							<?php else: ?>—<?php endif; ?>
							<div style="margin-top:4px;">
								<span class="badge <?= $prod['show_button'] ? 'badge-success' : 'badge-grey' ?>">
									Btn: <?= $prod['show_button'] ? 'Shown' : 'Hidden' ?>
								</span>
							</div>
						</td>
						<td><?= $prod['sort_order'] ?></td>
						<td>
							<div style="display:flex;gap:6px;flex-wrap:wrap;">
								<a href="products.php?edit=<?= $prod['id'] ?>" class="btn btn-secondary btn-xs">Edit</a>
								<form method="POST" style="margin:0;">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id" value="<?= $prod['id'] ?>">
									<button type="submit" class="btn btn-danger btn-xs" data-confirm="Delete '<?= e($prod['product_name']) ?>'?">Delete</button>
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

<script src="<?= SITE_URL ?>/assets/js/admin-products.js"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>