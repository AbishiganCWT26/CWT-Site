<?php
session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/auth.php';
if (!defined('ADMIN_URL')) define('ADMIN_URL', SITE_URL . '/admin');
requireAuth();

$msg = '';
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	verifyCsrf();
	$action = $_POST['action'] ?? '';

	if ($action === 'save_service') {
		$id = (int)($_POST['id'] ?? 0);
		$num = trim((string)($_POST['num'] ?? ''));
		$title = trim((string)($_POST['title'] ?? ''));
		$description = trim((string)($_POST['description'] ?? ''));
		$consultant_name = trim((string)($_POST['consultant_name'] ?? ''));
		$consultant_role = trim((string)($_POST['consultant_role'] ?? ''));
		$sort_order = (int)($_POST['sort_order'] ?? 0);
		$is_active = isset($_POST['is_active']) ? 1 : 0;
		$leadsRaw = trim((string)($_POST['lead'] ?? ''));
		$teamRaw = trim((string)($_POST['team'] ?? ''));

		if ($title === '') {
			$err = 'Service title is required.';
		} else {
			if ($id > 0) {
				$pdo->prepare("UPDATE services SET num=?, title=?, description=?, consultant_name=?, consultant_role=?, sort_order=?, is_active=? WHERE id=?")
					->execute([$num, $title, $description, $consultant_name, $consultant_role, $sort_order, $is_active, $id]);
			} else {
				$pdo->prepare("INSERT INTO services (num, title, description, consultant_name, consultant_role, sort_order, is_active) VALUES (?,?,?,?,?,?,?)")
					->execute([$num, $title, $description, $consultant_name, $consultant_role, $sort_order, $is_active]);
				$id = (int)$pdo->lastInsertId();
			}
			$pdo->prepare("DELETE FROM service_leads WHERE service_id = ?")->execute([$id]);
			$leads = array_values(array_filter(array_map('trim', explode(',', $leadsRaw)), function ($v) { return $v !== ''; }));
			foreach ($leads as $i => $name) {
				$pdo->prepare("INSERT INTO service_leads (service_id, name, sort_order) VALUES (?, ?, ?)")->execute([$id, $name, $i + 1]);
			}
			$pdo->prepare("DELETE FROM service_team WHERE service_id = ?")->execute([$id]);
			$team = array_values(array_filter(array_map('trim', explode(',', $teamRaw)), function ($v) { return $v !== ''; }));
			foreach ($team as $i => $name) {
				$pdo->prepare("INSERT INTO service_team (service_id, name, sort_order) VALUES (?, ?, ?)")->execute([$id, $name, $i + 1]);
			}
			$msg = 'service_saved';
		}
	}

	if ($action === 'delete_service') {
		$id = (int)($_POST['id'] ?? 0);
		$pdo->prepare("DELETE FROM services WHERE id = ?")->execute([$id]);
		$msg = 'service_deleted';
	}

	if ($action === 'save_capability') {
		$id = (int)($_POST['id'] ?? 0);
		$label = trim((string)($_POST['label'] ?? ''));
		$slugRaw = trim((string)($_POST['slug'] ?? ''));
		$icon = trim((string)($_POST['icon'] ?? 'box'));
		$accent = trim((string)($_POST['accent'] ?? '#001be4'));
		$accent2 = trim((string)($_POST['accent2'] ?? '#0015b0'));
		$soft = trim((string)($_POST['soft'] ?? 'rgba(0, 27, 228, 0.16)'));
		$sort_order = (int)($_POST['sort_order'] ?? 0);
		$is_active = isset($_POST['is_active']) ? 1 : 0;
		$slug = $slugRaw !== '' ? slugify($slugRaw) : slugify($label);

		if ($label === '' || $slug === '') {
			$err = 'Capability label and slug are required.';
		} else {
			try {
				if ($id > 0) {
					$pdo->prepare("UPDATE capability_sections SET slug=?, label=?, icon=?, accent=?, accent2=?, soft=?, sort_order=?, is_active=? WHERE id=?")
						->execute([$slug, $label, $icon, $accent, $accent2, $soft, $sort_order, $is_active, $id]);
				} else {
					$pdo->prepare("INSERT INTO capability_sections (slug, label, icon, accent, accent2, soft, sort_order, is_active) VALUES (?,?,?,?,?,?,?,?)")
						->execute([$slug, $label, $icon, $accent, $accent2, $soft, $sort_order, $is_active]);
					$id = (int)$pdo->lastInsertId();
				}
				$pdo->prepare("DELETE FROM capability_cards WHERE section_id = ?")->execute([$id]);
				$cards = $_POST['cards'] ?? [];
				if (is_array($cards)) {
					$pos = 0;
					foreach ($cards as $card) {
						$cTitle = trim((string)($card['title'] ?? ''));
						if ($cTitle === '') continue;
						$cIcon = trim((string)($card['icon'] ?? 'box'));
						$cDesc = trim((string)($card['description'] ?? ''));
						$cSort = (int)($card['sort_order'] ?? ($pos + 1));
						$pdo->prepare("INSERT INTO capability_cards (section_id, title, icon, description, sort_order) VALUES (?,?,?,?,?)")
							->execute([$id, $cTitle, $cIcon, $cDesc, $cSort]);
						$pos++;
					}
				}
				$msg = 'capability_saved';
			} catch (PDOException $ex) {
				$err = 'Could not save capability: ' . $ex->getMessage();
			}
		}
	}

	if ($action === 'delete_capability') {
		$id = (int)($_POST['id'] ?? 0);
		$pdo->prepare("DELETE FROM capability_sections WHERE id = ?")->execute([$id]);
		$msg = 'capability_deleted';
	}
}

$services    = getServiceSections($pdo, false);
$capabilities = getCapabilitySections($pdo, false);

$iconOptions = [
	'grid','shuffle','box','layers','filter','chart','monitor','cpu','trending','star','branch','robot','compass','code','refresh','sliders','gear','database','dollar','activity','file','target','smartphone','link','cloud','terminal','shield','globe','wrench','users','harddrive','lock','eye','briefcase','clipboard','alert','check','check-circle','userplus','cart','award','message','workflow','tech-evolution','project-validation','organizational-excellence','financial-planning'
];

$flash = null;
if ($err !== '') {
	$flash = ['type' => 'error', 'text' => $err];
} elseif ($msg === 'service_saved') {
	$flash = ['type' => 'success', 'text' => 'Service saved successfully.'];
} elseif ($msg === 'service_deleted') {
	$flash = ['type' => 'success', 'text' => 'Service deleted.'];
} elseif ($msg === 'capability_saved') {
	$flash = ['type' => 'success', 'text' => 'Capability section saved.'];
} elseif ($msg === 'capability_deleted') {
	$flash = ['type' => 'success', 'text' => 'Capability section deleted.'];
}

$pageTitle = 'Services & Capabilities';
include __DIR__ . '/layout_top.php';
?>
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/admin-services.css?v=<?= filemtime(__DIR__ . '/../assets/css/admin-services.css') ?>">

<svg class="svc-sprite" width="0" height="0" aria-hidden="true" focusable="false">
	<symbol id="svc-i-layers" viewBox="0 0 24 24"><path d="M12 2 2 7l10 5 10-5-10-5z"/><path d="m2 17 10 5 10-5"/><path d="m2 12 10 5 10-5"/></symbol>
	<symbol id="svc-i-plus" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></symbol>
	<symbol id="svc-i-edit" viewBox="0 0 24 24"><path d="M12 20h9"/><path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/></symbol>
	<symbol id="svc-i-trash" viewBox="0 0 24 24"><path d="M3 6h18"/><path d="M8 6V4h8v2"/><path d="m19 6-1 14H6L5 6"/><path d="M10 11v6M14 11v6"/></symbol>
	<symbol id="svc-i-close" viewBox="0 0 24 24"><path d="M18 6 6 18M6 6l12 12"/></symbol>
	<symbol id="svc-i-grid" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/></symbol>
	<symbol id="svc-i-spark" viewBox="0 0 24 24"><path d="m12 2 3 7 7 3-7 3-3 7-3-7-7-3 7-3z"/></symbol>
	<symbol id="svc-i-inbox" viewBox="0 0 24 24"><path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.5 5h13L22 12v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2v-6z"/></symbol>
	<symbol id="svc-i-save" viewBox="0 0 24 24"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><path d="M17 21v-8H7v8M7 3v5h8"/></symbol>
	<symbol id="svc-i-tag" viewBox="0 0 24 24"><path d="M20.6 13.4 13.4 20.6a2 2 0 0 1-2.8 0L2 12V2h10l8.6 8.6a2 2 0 0 1 0 2.8z"/><circle cx="7" cy="7" r="1.2"/></symbol>
</svg>

<div class="svc-page">
	<div class="svc-head">
		<span class="svc-head-icon">
			<svg class="svc-ico svc-ico-lg" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-layers"></use></svg>
		</span>
		<div class="svc-head-text">
			<h1>Services &amp; Capabilities</h1>
			<p>Manage the "All Services &amp; Verticals" and "What We Offer" sections.</p>
		</div>
		<div class="svc-head-stats">
			<span class="svc-chip"><b><?= count($services) ?></b> Services</span>
			<span class="svc-chip"><b><?= count($capabilities) ?></b> Sections</span>
		</div>
	</div>

	<section class="svc-card">
		<div class="svc-card-head">
			<h2>
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-grid"></use></svg>
				<span>All Services &amp; Verticals</span>
				<em class="svc-count"><?= count($services) ?></em>
			</h2>
			<button type="button" class="svc-btn svc-btn-primary" id="addServiceBtn">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-plus"></use></svg>
				<span>Add Service</span>
			</button>
		</div>
		<div class="svc-card-body">
			<?php if (empty($services)): ?>
			<div class="svc-empty">
				<svg class="svc-ico svc-ico-xl" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-inbox"></use></svg>
				<p>No services yet. Click "Add Service" to create one.</p>
			</div>
			<?php else: ?>
			<div class="svc-grid">
				<?php foreach ($services as $k => $s): ?>
				<article class="svc-tile" style="--i:<?= (int)$k ?>">
					<div class="svc-tile-main">
						<span class="svc-num"><?= e($s['num']) ?></span>
						<div class="svc-info">
							<h3 title="<?= e($s['title']) ?>"><?= e($s['title']) ?></h3>
							<p><?= e(mb_substr($s['description'] ?? '', 0, 100)) ?><?= mb_strlen($s['description'] ?? '') > 100 ? '…' : '' ?></p>
						</div>
					</div>
					<div class="svc-tile-foot">
						<span class="svc-status <?= $s['is_active'] ? 'is-on' : 'is-off' ?>"><i></i><?= $s['is_active'] ? 'Active' : 'Inactive' ?></span>
						<div class="svc-actions">
							<button type="button" class="svc-icon-btn edit-service-btn" title="Edit" aria-label="Edit" data-service='<?= e(json_encode($s, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?>'>
								<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-edit"></use></svg>
							</button>
							<form method="POST" action="" class="delete-form" data-name="<?= e($s['title']) ?>">
								<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
								<input type="hidden" name="action" value="delete_service">
								<input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
								<button type="submit" class="svc-icon-btn svc-icon-danger" title="Delete" aria-label="Delete">
									<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-trash"></use></svg>
								</button>
							</form>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>

	<section class="svc-card">
		<div class="svc-card-head">
			<h2>
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-spark"></use></svg>
				<span>What We Offer</span>
				<em class="svc-count"><?= count($capabilities) ?></em>
			</h2>
			<button type="button" class="svc-btn svc-btn-primary" id="addCapabilityBtn">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-plus"></use></svg>
				<span>Add Section</span>
			</button>
		</div>
		<div class="svc-card-body">
			<?php if (empty($capabilities)): ?>
			<div class="svc-empty">
				<svg class="svc-ico svc-ico-xl" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-inbox"></use></svg>
				<p>No capability sections yet. Click "Add Section" to create one.</p>
			</div>
			<?php else: ?>
			<div class="svc-grid">
				<?php foreach ($capabilities as $k => $c): ?>
				<article class="svc-tile" style="--i:<?= (int)$k ?>;--tile-accent:<?= e(!empty($c['accent']) ? $c['accent'] : '#1d3bff') ?>">
					<div class="svc-tile-main">
						<span class="svc-num svc-num-icon">
							<svg class="svc-ico svc-ico-lg" viewBox="0 0 24 24" aria-hidden="true"><use href="#i-<?= e($c['icon']) ?>"></use></svg>
						</span>
						<div class="svc-info">
							<h3 title="<?= e($c['label']) ?>"><?= e($c['label']) ?></h3>
							<p><?= count($c['cards']) ?> card<?= count($c['cards']) === 1 ? '' : 's' ?> · slug <code><?= e($c['slug']) ?></code></p>
						</div>
					</div>
					<div class="svc-tile-foot">
						<span class="svc-status <?= $c['is_active'] ? 'is-on' : 'is-off' ?>"><i></i><?= $c['is_active'] ? 'Active' : 'Inactive' ?></span>
						<div class="svc-actions">
							<button type="button" class="svc-icon-btn edit-capability-btn" title="Edit" aria-label="Edit" data-capability='<?= e(json_encode($c, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)) ?>'>
								<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-edit"></use></svg>
							</button>
							<form method="POST" action="" class="delete-form" data-name="<?= e($c['label']) ?>">
								<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
								<input type="hidden" name="action" value="delete_capability">
								<input type="hidden" name="id" value="<?= (int)$c['id'] ?>">
								<button type="submit" class="svc-icon-btn svc-icon-danger" title="Delete" aria-label="Delete">
									<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-trash"></use></svg>
								</button>
							</form>
						</div>
					</div>
				</article>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>
		</div>
	</section>
</div>

<div class="svc-modal" id="serviceModal" aria-hidden="true">
	<div class="svc-modal-backdrop" data-close="serviceModal"></div>
	<div class="svc-dialog" role="dialog" aria-modal="true" aria-labelledby="serviceModalTitle">
		<div class="svc-dialog-head">
			<span class="svc-dialog-icon">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-grid"></use></svg>
			</span>
			<h3 id="serviceModalTitle">Add Service</h3>
			<button type="button" class="svc-close" data-close="serviceModal" aria-label="Close">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-close"></use></svg>
			</button>
		</div>
		<form method="POST" action="" id="serviceForm" class="svc-form">
			<div class="svc-dialog-body">
				<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
				<input type="hidden" name="action" value="save_service">
				<input type="hidden" name="id" value="">
				<div class="svc-row svc-row-3">
					<div class="svc-field">
						<label class="svc-label">Number <span class="svc-req">*</span></label>
						<input type="text" name="num" class="svc-input" required maxlength="10">
					</div>
					<div class="svc-field">
						<label class="svc-label">Sort Order</label>
						<input type="number" name="sort_order" class="svc-input" value="0">
					</div>
					<div class="svc-field">
						<label class="svc-label">Active</label>
						<label class="svc-switch">
							<input type="checkbox" name="is_active" checked>
							<span class="svc-switch-track"></span>
							<span class="svc-switch-text">Show on site</span>
						</label>
					</div>
				</div>
				<div class="svc-field">
					<label class="svc-label">Title <span class="svc-req">*</span></label>
					<input type="text" name="title" class="svc-input" required maxlength="500">
				</div>
				<div class="svc-field">
					<label class="svc-label">Description</label>
					<textarea name="description" class="svc-input" rows="3"></textarea>
				</div>
				<div class="svc-row svc-row-2">
					<div class="svc-field">
						<label class="svc-label">Consultant Name</label>
						<input type="text" name="consultant_name" class="svc-input" maxlength="255">
					</div>
					<div class="svc-field">
						<label class="svc-label">Consultant Role</label>
						<input type="text" name="consultant_role" class="svc-input" maxlength="255">
					</div>
				</div>
				<div class="svc-row svc-row-2">
					<div class="svc-field">
						<label class="svc-label">Lead (comma-separated)</label>
						<input type="text" name="lead" class="svc-input">
					</div>
					<div class="svc-field">
						<label class="svc-label">Team (comma-separated)</label>
						<input type="text" name="team" class="svc-input">
					</div>
				</div>
			</div>
			<div class="svc-dialog-foot">
				<button type="button" class="svc-btn svc-btn-ghost" data-close="serviceModal">Cancel</button>
				<button type="submit" class="svc-btn svc-btn-primary">
					<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-save"></use></svg>
					<span>Save Service</span>
				</button>
			</div>
		</form>
	</div>
</div>

<div class="svc-modal" id="capabilityModal" aria-hidden="true">
	<div class="svc-modal-backdrop" data-close="capabilityModal"></div>
	<div class="svc-dialog svc-dialog-lg" role="dialog" aria-modal="true" aria-labelledby="capabilityModalTitle">
		<div class="svc-dialog-head">
			<span class="svc-dialog-icon">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-spark"></use></svg>
			</span>
			<h3 id="capabilityModalTitle">Add Capability Section</h3>
			<button type="button" class="svc-close" data-close="capabilityModal" aria-label="Close">
				<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-close"></use></svg>
			</button>
		</div>
		<form method="POST" action="" id="capabilityForm" class="svc-form">
			<div class="svc-dialog-body">
				<input type="hidden" name="csrf_token" value="<?= e(csrfToken()) ?>">
				<input type="hidden" name="action" value="save_capability">
				<input type="hidden" name="id" value="">
				<div class="svc-row svc-row-4">
					<div class="svc-field">
						<label class="svc-label">Label <span class="svc-req">*</span></label>
						<input type="text" name="label" class="svc-input" required maxlength="255">
					</div>
					<div class="svc-field">
						<label class="svc-label">Slug</label>
						<input type="text" name="slug" class="svc-input" maxlength="100" placeholder="auto-generated from label">
					</div>
					<div class="svc-field">
						<label class="svc-label">Sort Order</label>
						<input type="number" name="sort_order" class="svc-input" value="0">
					</div>
					<div class="svc-field">
						<label class="svc-label">Active</label>
						<label class="svc-switch">
							<input type="checkbox" name="is_active" checked>
							<span class="svc-switch-track"></span>
							<span class="svc-switch-text">Show on site</span>
						</label>
					</div>
				</div>
				<div class="svc-row svc-row-4">
					<div class="svc-field">
						<label class="svc-label">Icon</label>
						<select name="icon" class="svc-input">
							<?php foreach ($iconOptions as $ico): ?>
							<option value="<?= e($ico) ?>"><?= e($ico) ?></option>
							<?php endforeach; ?>
						</select>
					</div>
					<div class="svc-field">
						<label class="svc-label">Accent Color</label>
						<input type="color" name="accent" class="svc-input svc-color" value="#001be4">
					</div>
					<div class="svc-field">
						<label class="svc-label">Accent 2</label>
						<input type="color" name="accent2" class="svc-input svc-color" value="#0015b0">
					</div>
					<div class="svc-field">
						<label class="svc-label">Soft (rgba)</label>
						<input type="text" name="soft" class="svc-input" value="rgba(0, 27, 228, 0.16)">
					</div>
				</div>
				<div class="svc-cards-head">
					<h4>
						<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-tag"></use></svg>
						<span>Cards</span>
					</h4>
					<button type="button" class="svc-btn svc-btn-soft svc-btn-sm" id="addCardBtn">
						<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-plus"></use></svg>
						<span>Add Card</span>
					</button>
				</div>
				<div class="svc-cards-list" id="capCardsList"></div>
			</div>
			<div class="svc-dialog-foot">
				<button type="button" class="svc-btn svc-btn-ghost" data-close="capabilityModal">Cancel</button>
				<button type="submit" class="svc-btn svc-btn-primary">
					<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-save"></use></svg>
					<span>Save Section</span>
				</button>
			</div>
		</form>
	</div>
</div>

<template id="capCardRowTemplate">
	<div class="svc-cap-row">
		<div class="svc-cap-grid">
			<div class="svc-field">
				<label class="svc-label">Title</label>
				<input type="text" name="cards[__INDEX__][title]" class="svc-input" maxlength="255">
			</div>
			<div class="svc-field">
				<label class="svc-label">Icon</label>
				<select name="cards[__INDEX__][icon]" class="svc-input">
					<?php foreach ($iconOptions as $ico): ?>
					<option value="<?= e($ico) ?>"><?= e($ico) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="svc-field">
				<label class="svc-label">Order</label>
				<input type="number" name="cards[__INDEX__][sort_order]" class="svc-input" value="0">
			</div>
			<div class="svc-field svc-cap-remove">
				<button type="button" class="svc-btn svc-btn-danger-soft svc-btn-sm cap-card-delete" aria-label="Remove card">
					<svg class="svc-ico" viewBox="0 0 24 24" aria-hidden="true"><use href="#svc-i-trash"></use></svg>
					<span>Remove</span>
				</button>
			</div>
		</div>
		<div class="svc-field">
			<label class="svc-label">Description</label>
			<textarea name="cards[__INDEX__][description]" class="svc-input" rows="2"></textarea>
		</div>
	</div>
</template>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>window.CAP_ICONS = <?= json_encode($iconOptions) ?>;</script>
<script>window.SVC_FLASH = <?= json_encode($flash, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;</script>
<script src="<?= SITE_URL ?>/assets/js/admin-services.js?v=<?= filemtime(__DIR__ . '/../assets/js/admin-services.js') ?>"></script>
<?php include __DIR__ . '/layout_bottom.php'; ?>