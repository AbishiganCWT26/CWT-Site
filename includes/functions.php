<?php

/**
 * Global helper functions
 * CWT Corporate Website
 */

define('SITE_URL',   'http://localhost/CWT-Site');
define('ADMIN_URL',  SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
require_once __DIR__ . '/project-content-images.php';

/* --------------------------------------------------------------------------
 * Core content fetchers
 * ------------------------------------------------------------------------ */

function getFooter(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM footer ORDER BY id DESC LIMIT 1");
	return $stmt->fetch() ?: [];
}

function getClients(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM clients WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
	return $stmt->fetchAll();
}

function getAboutUs(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM aboutus ORDER BY id DESC LIMIT 1");
	return $stmt->fetch() ?: [];
}

/**
 * Fetch active home showcase items (added from includes/functions.php snippet).
 */
function getHomeShowcase(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM home_showcase WHERE is_active = 1 ORDER BY section_name ASC, sort_order ASC, id ASC");
	return $stmt->fetchAll();
}

function calcYearsExperience(int $startYear): int
{
	return (int)date('Y') - $startYear;
}

function getHistory(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM history ORDER BY id DESC LIMIT 1");
	return $stmt->fetch() ?: [];
}

function getVisionMission(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM vision_mission ORDER BY id DESC LIMIT 1");
	return $stmt->fetch() ?: [];
}

function getProducts(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC");
	return $stmt->fetchAll();
}

function getClientFeedback(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM client_feedback WHERE is_active = 1 ORDER BY feedback_date DESC");
	return $stmt->fetchAll();
}

function getPublishedBlogs(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM blog_details WHERE is_published = 1 ORDER BY publish_date DESC");
	return $stmt->fetchAll();
}

function getBlogBySlug(PDO $pdo, string $slug): array|false
{
	$stmt = $pdo->prepare("SELECT * FROM blog_details WHERE slug = ? AND is_published = 1");
	$stmt->execute([$slug]);
	return $stmt->fetch();
}

function getBlogById(PDO $pdo, int $id): array|false
{
	$stmt = $pdo->prepare("SELECT * FROM blog_details WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch();
}

function getIndustries(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM industries ORDER BY sort_order ASC, id ASC");
	return $stmt->fetchAll();
}

function getTechStack(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM tech_stack ORDER BY category ASC, sort_order ASC");
	return $stmt->fetchAll();
}

function getTechStackByCategory(PDO $pdo, string $category): array
{
	$stmt = $pdo->prepare("SELECT * FROM tech_stack WHERE category = ? ORDER BY sort_order ASC");
	$stmt->execute([$category]);
	return $stmt->fetchAll();
}

function getCrew(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT * FROM crew ORDER BY crew_type ASC, sort_order ASC, id ASC");
	return $stmt->fetchAll();
}

function getCrewByType(PDO $pdo, string $type): array
{
	$stmt = $pdo->prepare("SELECT * FROM crew WHERE crew_type = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$type]);
	return $stmt->fetchAll();
}

/* --------------------------------------------------------------------------
 * Upload / URL / escaping helpers
 * ------------------------------------------------------------------------ */

/**
 * Store an uploaded file under UPLOAD_DIR/<subdir>/ and return the relative path.
 *
 * Returns false on ANY failure. The specific reason is written to the PHP
 * error log (see [uploadFile] entries) so admins can diagnose without
 * exposing server internals to the browser.
 */
function uploadFile(array $file, string $subdir): string|false
{
	// --- 1. Upload transport must have succeeded.
	if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
		error_log('[uploadFile] upload error code: ' . ($file['error'] ?? 'none'));
		return false;
	}
	if (empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
		error_log('[uploadFile] tmp_name missing or is_uploaded_file() failed');
		return false;
	}
	if (!is_readable($file['tmp_name'])) {
		error_log('[uploadFile] tmp file is not readable');
		return false;
	}

	// --- 2. Size + emptiness check.
	$maxSize = 5 * 1024 * 1024;
	$size    = @filesize($file['tmp_name']);
	if ($size === false || $size === 0) {
		error_log('[uploadFile] file size is 0 or unreadable');
		return false;
	}
	if ($size > $maxSize) {
		error_log('[uploadFile] file too large: ' . $size . ' bytes');
		return false;
	}

	// --- 3. Extension gate (drives the saved filename, not client MIME).
	$ext = strtolower(pathinfo($file['name'] ?? '', PATHINFO_EXTENSION));
	if ($ext === 'jpeg') $ext = 'jpg';
	$allowedExt = ['jpg', 'png', 'gif', 'webp', 'svg'];
	if (!in_array($ext, $allowedExt, true)) {
		error_log('[uploadFile] disallowed extension: ' . $ext);
		return false;
	}

	// --- 4. Server-side MIME detection (never trust $_FILES['type']).
	if (class_exists('finfo')) {
		$finfo    = new finfo(FILEINFO_MIME_TYPE);
		$mimeType = $finfo->file($file['tmp_name']);

		// finfo reports SVG inconsistently depending on whether the file starts
		// with an XML prolog (image/svg+xml) or directly with <svg> (text/plain,
		// text/html, application/xml, or application/octet-stream). Because the
		// content check below also runs for SVG, the MIME list can be lenient.
		$allowedMime = [
			'jpg'  => ['image/jpeg', 'image/jpg', 'image/pjpeg'],
			'png'  => ['image/png', 'image/x-png'],
			'gif'  => ['image/gif'],
			'webp' => ['image/webp'],
			'svg'  => ['image/svg+xml', 'image/svg', 'text/plain', 'text/html',
			           'application/xml', 'text/xml', 'application/octet-stream'],
		];
		if (!in_array($mimeType, $allowedMime[$ext] ?? [], true)) {
			error_log("[uploadFile] MIME $mimeType rejected for .$ext");
			return false;
		}
	}

	// --- 5. SVG content sanity (cheap; not a security gate on its own).
	// Scripts inside an SVG do NOT execute when the file is served through an
	// <img> tag, so we no longer reject valid SVGs that happen to contain
	// <script> / javascript: strings (some design-tool exports contain them
	// harmlessly). We only verify the payload actually looks like SVG.
	if ($ext === 'svg') {
		$svg = @file_get_contents($file['tmp_name']);
		if ($svg === false || strlen($svg) < 20) {
			error_log('[uploadFile] SVG content too small / unreadable');
			return false;
		}
		if (stripos($svg, '<svg') === false) {
			error_log('[uploadFile] file does not contain an <svg> root');
			return false;
		}
	}

	// --- 6. Destination directory.
	$dir = rtrim(UPLOAD_DIR . $subdir, '/') . '/';
	if (!is_dir($dir)) {
		if (!@mkdir($dir, 0755, true) && !is_dir($dir)) {
			error_log('[uploadFile] cannot create directory: ' . $dir);
			return false;
		}
	}
	if (!is_writable($dir)) {
		error_log('[uploadFile] directory not writable: ' . $dir);
		return false;
	}

	// --- 7. Move + verify.
	$filename = uniqid('cwt_', true) . '.' . $ext;
	$dest     = $dir . $filename;

	if (!@move_uploaded_file($file['tmp_name'], $dest)) {
		error_log('[uploadFile] move_uploaded_file failed -> ' . $dest);
		return false;
	}
	if (!is_file($dest) || filesize($dest) === 0) {
		@unlink($dest);
		error_log('[uploadFile] file missing or empty after move -> ' . $dest);
		return false;
	}

	return $subdir . '/' . $filename;
}

function imgUrl(string $path, string $placeholder = ''): string
{
	if (empty($path)) return $placeholder;
	// Allow fully-qualified URLs (merged from legacy includes/functions.php).
	if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) return $path;
	return UPLOAD_URL . $path;
}

function projectImageUrl(array $project): string
{
    $image = trim((string)($project['image_path'] ?? ''));
    $banner = trim((string)($project['fallback_banner_image'] ?? $project['details']['banner_image'] ?? ''));
    return imgUrl($image !== '' ? $image : $banner);
}
function projectBannerUrl(array $project, array $details = []): string
{
    $custom = trim((string)($details['banner_image'] ?? ''));
    return imgUrl($custom !== '' ? $custom : (string)($project['image_path'] ?? ''));
}
function e(string $str): string
{
	return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

function slugify(string $text): string
{
	$text = mb_strtolower($text, 'UTF-8');
	$text = preg_replace('/[^a-z0-9\s-]/', '', $text);
	$text = preg_replace('/[\s-]+/', '-', $text);
	return trim($text, '-');
}

/* --------------------------------------------------------------------------
 * Blog rendering
 * ------------------------------------------------------------------------ */

function parseHashtags(string $tags): array
{
	if (empty($tags)) return [];
	return array_filter(array_map('trim', explode(',', $tags)));
}

function renderBlogContent(string $jsonContent): string
{
	$jsonContent = trim($jsonContent);
	if ($jsonContent === '') return '';

	$blocks = json_decode($jsonContent, true);

	if (!is_array($blocks) && (str_starts_with($jsonContent, '[') || str_starts_with($jsonContent, '{'))) {
		$repairedJson = preg_replace_callback('/"content"\s*:\s*"(.*?)"\s*(?=\}\s*[\],]|\}\s*$)/s', function ($m) {
			$inner = $m[1];
			$innerClean = preg_replace('/(?<!\\\\)"/', '\"', $inner);
			return '"content":"' . $innerClean . '"';
		}, $jsonContent);

		$blocks = json_decode($repairedJson, true);

		if (!is_array($blocks)) {
			$repairedJson = preg_replace_callback('/"content"\s*:\s*"(.*?)"\s*(?=\}\s*[\],]|\}\s*$)/s', function ($m) {
				$inner = $m[1];
				$innerClean = str_replace(["\r\n", "\n", "\r", "\t"], ["\\n", "\\n", "\\r", "\\t"], $inner);
				$innerClean = preg_replace('/(?<!\\\\)"/', '\"', $innerClean);
				return '"content":"' . $innerClean . '"';
			}, $jsonContent);
			$blocks = json_decode($repairedJson, true);
		}
	}

	if (!is_array($blocks)) return $jsonContent;

	$html = '';
	foreach ($blocks as $block) {
		if (!is_array($block)) continue;
		$type    = $block['type']    ?? 'paragraph';
		$content = $block['content'] ?? '';

		$content = str_replace(['\r\n', '\n', '\r', '\t', '\"'], ["\n", "\n", "\r", "\t", '"'], $content);

		switch ($type) {
			case 'heading1':
				$html .= '<h1>' . e($content) . '</h1>';
				break;
			case 'heading2':
				$html .= '<h2>' . e($content) . '</h2>';
				break;
			case 'heading3':
				$html .= '<h3>' . e($content) . '</h3>';
				break;
			case 'paragraph':
				$html .= '<p>' . nl2br(e($content)) . '</p>';
				break;
			case 'image':
				$src = imgUrl($content);
				if ($src) {
					$html .= '<figure class="blog-figure"><img src="' . e($src) . '" alt="Insight image" loading="lazy"></figure>';
				}
				break;
			case 'bullets':
				$items = explode('|', $content);
				$html .= '<ul class="blog-list">';
				foreach ($items as $item) {
					$html .= '<li>' . e(trim($item)) . '</li>';
				}
				$html .= '</ul>';
				break;
			case 'codeblock':
				$html .= '<pre class="blog-code"><code>' . e($content) . '</code></pre>';
				break;
			case 'table':
				$rows = explode("\n", $content);
				$html .= '<div class="blog-table-wrap"><table class="blog-table"><tbody>';
				foreach ($rows as $i => $row) {
					$cells = explode('|', $row);
					$tag   = ($i === 0) ? 'th' : 'td';
					$html .= '<tr>';
					foreach ($cells as $cell) {
						$html .= "<{$tag}>" . e(trim($cell)) . "</{$tag}>";
					}
					$html .= '</tr>';
				}
				$html .= '</tbody></table></div>';
				break;
			default:
				$html .= '<p>' . nl2br(e($content)) . '</p>';
				break;
		}
	}
	return $html;
}

/* --------------------------------------------------------------------------
 * Projects
 * ------------------------------------------------------------------------ */

function getProjectDetails(PDO $pdo, int $projectId): array
{
	$stmt = $pdo->prepare("SELECT * FROM project_details WHERE project_id = ?");
	$stmt->execute([$projectId]);
	return $stmt->fetch() ?: [];
}

function saveProjectDetails(PDO $pdo, int $projectId, array $data): void
{
	$data['content'] = storeProjectContentImages($data['content'] ?? '');
	$existing = getProjectDetails($pdo, $projectId);
	$liveSiteUrl = (isset($data['live_site_url']) && trim($data['live_site_url']) !== '')
		? trim($data['live_site_url'])
		: null;
	if (!empty($existing)) {
		$sql = "UPDATE project_details SET banner_image=?, client_name=?, industry=?, duration=?, technologies=?, content=?, live_site_url=? WHERE project_id=?";
		$pdo->prepare($sql)->execute([
			$data['banner_image'] ?? null,
			$data['client_name'] ?? null,
			$data['industry'] ?? null,
			$data['duration'] ?? null,
			$data['technologies'] ?? null,
			$data['content'] ?? '',
			$liveSiteUrl,
			$projectId
		]);
	} else {
		$sql = "INSERT INTO project_details (project_id, banner_image, client_name, industry, duration, technologies, content, live_site_url) VALUES (?,?,?,?,?,?,?,?)";
		$pdo->prepare($sql)->execute([
			$projectId,
			$data['banner_image'] ?? null,
			$data['client_name'] ?? null,
			$data['industry'] ?? null,
			$data['duration'] ?? null,
			$data['technologies'] ?? null,
			$data['content'] ?? '',
			$liveSiteUrl
		]);
	}
}

function getProjectWithDetails(PDO $pdo, string $slug): array
{
	$project = getProjectBySlug($pdo, $slug);
	if (empty($project)) return [];
	$project['details'] = getProjectDetails($pdo, (int)$project['id']);
	return $project;
}

/**
 * Fetch active projects for admin dropdowns (merged from includes/functions.php snippet).
 * Added short_description so admin dropdowns can display slug + short_description.
 */
function getActiveProjects(PDO $pdo): array
{
	$stmt = $pdo->prepare("SELECT slug, project_name, short_description FROM projects WHERE is_active = 1 ORDER BY project_name ASC");
	$stmt->execute();
	return $stmt->fetchAll();
}

/* --------------------------------------------------------------------------
 * Admin users / security
 * ------------------------------------------------------------------------ */

function getAdminUsers(PDO $pdo): array
{
	$stmt = $pdo->query("SELECT id, username, created_at FROM admin_users ORDER BY id ASC");
	return $stmt->fetchAll();
}

function getAdminUserByUsername(PDO $pdo, string $username): array|false
{
	$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
	$stmt->execute([$username]);
	return $stmt->fetch();
}

function getAdminUserById(PDO $pdo, int $id): array|false
{
	$stmt = $pdo->prepare("SELECT id, username, created_at FROM admin_users WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch();
}

function createAdminUser(PDO $pdo, string $username, string $plainPassword): int|false
{
	if (getAdminUserByUsername($pdo, $username)) return false;
	$hash = password_hash($plainPassword, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("INSERT INTO admin_users (username, password_hash) VALUES (?, ?)");
	$stmt->execute([$username, $hash]);
	return (int)$pdo->lastInsertId();
}

function updateAdminPassword(PDO $pdo, int $id, string $plainPassword): bool
{
	$hash = password_hash($plainPassword, PASSWORD_DEFAULT);
	$stmt = $pdo->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
	return $stmt->execute([$hash, $id]);
}

function validateAdminUsername(string $username): bool
{
	return (bool)preg_match('/^[A-Za-z0-9._-]{3,100}$/', $username);
}

function csrfToken(): string
{
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function verifyCsrf(): void
{
	$token = $_POST['csrf_token'] ?? '';
	if (!is_string($token) || $token === '' || !hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
		require_once __DIR__ . '/error-page.php';
		renderSiteError(403, 'Your session needs a refresh.', 'Please reload the page and try that action again.');
	}
}

/* --------------------------------------------------------------------------
 * Solutions / projects
 * ------------------------------------------------------------------------ */

function getSolutionCategories(PDO $pdo, bool $activeOnly = true): array
{
	$sql = "SELECT * FROM solution_category";
	if ($activeOnly) $sql .= " WHERE is_active = 1";
	$sql .= " ORDER BY sort_order ASC, id ASC";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll();
}

function getSolutionCategory(PDO $pdo, int $id): array
{
	$stmt = $pdo->prepare("SELECT * FROM solution_category WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch() ?: [];
}

function getProjectsByCategory(PDO $pdo, int $categoryId, bool $activeOnly = true): array
{
	$sql = "SELECT p.*, d.banner_image AS fallback_banner_image, logo.logo_path AS product_logo_path, logo.product_name AS product_logo_name FROM projects p LEFT JOIN project_details d ON d.project_id=p.id LEFT JOIN products logo ON logo.id = p.logo_product_id WHERE p.category_id = ?";
	if ($activeOnly) $sql .= " AND p.is_active = 1";
	$sql .= " ORDER BY p.sort_order ASC, p.id ASC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$categoryId]);
	return $stmt->fetchAll();
}

function getAllProjects(PDO $pdo, bool $activeOnly = true): array
{
	$sql = "SELECT p.*, c.name AS category_name, c.color AS category_color FROM projects p LEFT JOIN solution_category c ON p.category_id = c.id";
	if ($activeOnly) $sql .= " WHERE p.is_active = 1";
	$sql .= " ORDER BY c.sort_order ASC, p.sort_order ASC, p.id ASC";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll();
}

function getProjectById(PDO $pdo, int $id): array
{
	$stmt = $pdo->prepare("SELECT p.*, d.banner_image AS fallback_banner_image FROM projects p LEFT JOIN project_details d ON d.project_id=p.id WHERE p.id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch() ?: [];
}

function getProjectBySlug(PDO $pdo, string $slug): array
{
    $select = 'SELECT p.*, c.name AS category_name, c.color AS category_color, c.icon AS category_icon, logo.logo_path AS product_logo_path, logo.product_name AS product_logo_name FROM projects p LEFT JOIN solution_category c ON p.category_id = c.id LEFT JOIN products logo ON logo.id = p.logo_product_id';
    $stmt = $pdo->prepare($select . ' WHERE p.slug = ?');
    $stmt->execute([$slug]);
    $project = $stmt->fetch();
    if ($project) return $project;
    $stmt = $pdo->prepare($select . ' JOIN project_slug_history h ON h.project_id = p.id WHERE h.slug = ?');
    $stmt->execute([$slug]);
    return $stmt->fetch() ?: [];
}

function availableProjectSlug(PDO $pdo, string $name, int $projectId = 0): string
{
    $base = substr(projectSlug($name), 0, 240) ?: 'project';
    $candidate = $base;
    $suffix = 1;
    $query = $pdo->prepare('SELECT id FROM projects WHERE slug = ? AND id <> ? UNION ALL SELECT project_id FROM project_slug_history WHERE slug = ? AND project_id <> ? LIMIT 1');
    do {
        $query->execute([$candidate, $projectId, $candidate, $projectId]);
        if (!$query->fetchColumn()) return $candidate;
        $candidate = $base . '-' . ++$suffix;
    } while (true);
}

function rememberProjectSlug(PDO $pdo, int $projectId, string $slug): void
{
    if ($slug === '') return;
    $pdo->prepare('INSERT IGNORE INTO project_slug_history (slug, project_id) VALUES (?, ?)')->execute([$slug, $projectId]);
}

function projectSlug(string $name): string
{
	$clean = preg_replace('/[^a-zA-Z0-9\s-]/', '', $name);
	$clean = preg_replace('/[\s-]+/', '-', $clean);
	return trim($clean, '-');
}

function getProjectMockups(PDO $pdo, int $projectId): array
{
	$stmt = $pdo->prepare("SELECT * FROM project_mockups WHERE project_id = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$projectId]);
	return $stmt->fetchAll();
}

function addProjectMockup(PDO $pdo, int $projectId, string $imagePath): int
{
	$stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM project_mockups WHERE project_id = ?");
	$stmt->execute([$projectId]);
	$next = (int)$stmt->fetchColumn();

	$stmt = $pdo->prepare("INSERT INTO project_mockups (project_id, image_path, sort_order) VALUES (?, ?, ?)");
	$stmt->execute([$projectId, $imagePath, $next]);
	return (int)$pdo->lastInsertId();
}

function deleteProjectMockup(PDO $pdo, int $mockupId, int $projectId): bool
{
	$stmt = $pdo->prepare("SELECT image_path FROM project_mockups WHERE id = ? AND project_id = ?");
	$stmt->execute([$mockupId, $projectId]);
	$row = $stmt->fetch();
	if (!$row) return false;

	$fullPath = UPLOAD_DIR . $row['image_path'];
	if (is_file($fullPath)) {
		@unlink($fullPath);
	}

	$stmt = $pdo->prepare("DELETE FROM project_mockups WHERE id = ? AND project_id = ?");
	return $stmt->execute([$mockupId, $projectId]);
}

function getProjectMockupUrls(PDO $pdo, int $projectId): array
{
	$mockups = getProjectMockups($pdo, $projectId);
	$urls = [];
	foreach ($mockups as $m) {
		if (!empty($m['image_path'])) {
			$urls[] = imgUrl($m['image_path']);
		}
	}
	return $urls;
}

/* --------------------------------------------------------------------------
 * Services
 * ------------------------------------------------------------------------ */

function getServiceSections(PDO $pdo, bool $activeOnly = true): array
{
	$sql = "SELECT * FROM services";
	if ($activeOnly) $sql .= " WHERE is_active = 1";
	$sql .= " ORDER BY sort_order ASC, id ASC";
	$stmt = $pdo->query($sql);
	$services = $stmt->fetchAll();

	$leadsStmt = $pdo->prepare("SELECT name FROM service_leads WHERE service_id = ? ORDER BY sort_order ASC, id ASC");
	$teamStmt  = $pdo->prepare("SELECT name FROM service_team WHERE service_id = ? ORDER BY sort_order ASC, id ASC");

	foreach ($services as &$service) {
		$leadsStmt->execute([$service['id']]);
		$service['lead'] = $leadsStmt->fetchAll(PDO::FETCH_COLUMN);

		$teamStmt->execute([$service['id']]);
		$service['team'] = $teamStmt->fetchAll(PDO::FETCH_COLUMN);

		$service['consultant'] = [
			$service['consultant_name'] ?? '',
			$service['consultant_role'] ?? ''
		];
	}
	unset($service);

	return $services;
}

function getServiceById(PDO $pdo, int $id): array
{
	$stmt = $pdo->prepare("SELECT * FROM services WHERE id = ?");
	$stmt->execute([$id]);
	$service = $stmt->fetch();
	if (!$service) return [];

	$stmt = $pdo->prepare("SELECT name FROM service_leads WHERE service_id = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$id]);
	$service['lead'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

	$stmt = $pdo->prepare("SELECT name FROM service_team WHERE service_id = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$id]);
	$service['team'] = $stmt->fetchAll(PDO::FETCH_COLUMN);

	$service['consultant'] = [
		$service['consultant_name'] ?? '',
		$service['consultant_role'] ?? ''
	];

	return $service;
}

/* --------------------------------------------------------------------------
 * Capabilities
 * ------------------------------------------------------------------------ */

function getCapabilitySections(PDO $pdo, bool $activeOnly = true): array
{
	$sql = "SELECT * FROM capability_sections";
	if ($activeOnly) $sql .= " WHERE is_active = 1";
	$sql .= " ORDER BY sort_order ASC, id ASC";
	$stmt = $pdo->query($sql);
	$sections = $stmt->fetchAll();

	$cardStmt = $pdo->prepare("SELECT * FROM capability_cards WHERE section_id = ? ORDER BY sort_order ASC, id ASC");

	foreach ($sections as &$section) {
		$cardStmt->execute([$section['id']]);
		$section['cards'] = $cardStmt->fetchAll();
	}
	unset($section);

	return $sections;
}

function getCapabilitySectionById(PDO $pdo, int $id): array
{
	$stmt = $pdo->prepare("SELECT * FROM capability_sections WHERE id = ?");
	$stmt->execute([$id]);
	$section = $stmt->fetch();
	if (!$section) return [];

	$stmt = $pdo->prepare("SELECT * FROM capability_cards WHERE section_id = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$id]);
	$section['cards'] = $stmt->fetchAll();

	return $section;
}