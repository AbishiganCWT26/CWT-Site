<?php
/**
 * Global helper functions
 * CWT Corporate Website
 */

// ─── Site-wide Constants ─────────────────────────────────────────────────────
define('SITE_URL',   'http://localhost/CWT Site');
define('ADMIN_URL',  SITE_URL . '/admin');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');

// ─── Footer ──────────────────────────────────────────────────────────────────
function getFooter(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM footer ORDER BY id DESC LIMIT 1");
    return $stmt->fetch() ?: [];
}

// ─── Clients (for marquee) ────────────────────────────────────────────────────
function getClients(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM clients WHERE is_active = 1 ORDER BY sort_order ASC, id ASC");
    return $stmt->fetchAll();
}

// ─── About Us ─────────────────────────────────────────────────────────────────
function getAboutUs(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM aboutus ORDER BY id DESC LIMIT 1");
    return $stmt->fetch() ?: [];
}

function calcYearsExperience(int $startYear): int {
    return (int)date('Y') - $startYear;
}

// ─── History ──────────────────────────────────────────────────────────────────
function getHistory(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM history ORDER BY id DESC LIMIT 1");
    return $stmt->fetch() ?: [];
}

// ─── Vision & Mission ─────────────────────────────────────────────────────────
function getVisionMission(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM vision_mission ORDER BY id DESC LIMIT 1");
    return $stmt->fetch() ?: [];
}

// ─── Products ─────────────────────────────────────────────────────────────────
function getProducts(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM products ORDER BY sort_order ASC, id ASC");
    return $stmt->fetchAll();
}

// ─── Client Feedback ──────────────────────────────────────────────────────────
function getClientFeedback(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM client_feedback WHERE is_active = 1 ORDER BY feedback_date DESC");
    return $stmt->fetchAll();
}

// ─── Blog / Insights ──────────────────────────────────────────────────────────
function getPublishedBlogs(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM blog_details WHERE is_published = 1 ORDER BY publish_date DESC");
    return $stmt->fetchAll();
}

function getBlogBySlug(PDO $pdo, string $slug): array|false {
    $stmt = $pdo->prepare("SELECT * FROM blog_details WHERE slug = ? AND is_published = 1");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

function getBlogById(PDO $pdo, int $id): array|false {
    $stmt = $pdo->prepare("SELECT * FROM blog_details WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// ─── Industries ───────────────────────────────────────────────────────────────
function getIndustries(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM industries ORDER BY sort_order ASC, id ASC");
    return $stmt->fetchAll();
}

// ─── Tech Stack ───────────────────────────────────────────────────────────────
function getTechStack(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM tech_stack ORDER BY category ASC, sort_order ASC");
    return $stmt->fetchAll();
}

function getTechStackByCategory(PDO $pdo, string $category): array {
    $stmt = $pdo->prepare("SELECT * FROM tech_stack WHERE category = ? ORDER BY sort_order ASC");
    $stmt->execute([$category]);
    return $stmt->fetchAll();
}

// ─── Crew ─────────────────────────────────────────────────────────────────────
function getCrew(PDO $pdo): array {
    $stmt = $pdo->query("SELECT * FROM crew ORDER BY crew_type ASC, sort_order ASC, id ASC");
    return $stmt->fetchAll();
}

function getCrewByType(PDO $pdo, string $type): array {
    $stmt = $pdo->prepare("SELECT * FROM crew WHERE crew_type = ? ORDER BY sort_order ASC, id ASC");
    $stmt->execute([$type]);
    return $stmt->fetchAll();
}

// ─── File Upload ──────────────────────────────────────────────────────────────
/**
 * Handle file upload.
 * @param array  $file      $_FILES['field']
 * @param string $subdir    Sub-directory under uploads/ (e.g. 'clients')
 * @return string|false     Relative path on success, false on failure
 */
function uploadFile(array $file, string $subdir): string|false {
    if ($file['error'] !== UPLOAD_ERR_OK) return false;

    $allowed = ['image/jpeg','image/jpg','image/png','image/gif','image/webp','image/svg+xml'];
    if (!in_array($file['type'], $allowed)) return false;

    $maxSize = 5 * 1024 * 1024; // 5 MB
    if ($file['size'] > $maxSize) return false;

    $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('cwt_', true) . '.' . $ext;
    $dir      = UPLOAD_DIR . $subdir . '/';

    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $dest = $dir . $filename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) return false;

    return $subdir . '/' . $filename;
}

// ─── Image URL Helper ─────────────────────────────────────────────────────────
function imgUrl(string $path, string $placeholder = ''): string {
    if (empty($path)) return $placeholder;
    return UPLOAD_URL . $path;
}

// ─── Sanitize output ──────────────────────────────────────────────────────────
function e(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// ─── Slugify ──────────────────────────────────────────────────────────────────
function slugify(string $text): string {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[^a-z0-9\s-]/', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// ─── Hashtag Parser ───────────────────────────────────────────────────────────
function parseHashtags(string $tags): array {
    if (empty($tags)) return [];
    return array_filter(array_map('trim', explode(',', $tags)));
}

// ─── Blog Content Renderer ────────────────────────────────────────────────────
/**
 * Render blog content JSON blocks to HTML.
 */
function renderBlogContent(string $jsonContent): string {
    $jsonContent = trim($jsonContent);
    if ($jsonContent === '') return '';

    $blocks = json_decode($jsonContent, true);

    // If json_decode failed, check if string starts with JSON structure brackets
    if (!is_array($blocks) && (str_starts_with($jsonContent, '[') || str_starts_with($jsonContent, '{'))) {
        // Attempt to repair unescaped quotes inside JSON "content":"..." values
        $repairedJson = preg_replace_callback('/"content"\s*:\s*"(.*?)"\s*(?=\}\s*[\],]|\}\s*$)/s', function($m) {
            $inner = $m[1];
            // Escape quotes that are not already escaped with a backslash
            $innerClean = preg_replace('/(?<!\\\\)"/', '\"', $inner);
            return '"content":"' . $innerClean . '"';
        }, $jsonContent);

        $blocks = json_decode($repairedJson, true);

        // If it still fails, try normalizing newlines inside strings
        if (!is_array($blocks)) {
            $repairedJson = preg_replace_callback('/"content"\s*:\s*"(.*?)"\s*(?=\}\s*[\],]|\}\s*$)/s', function($m) {
                $inner = $m[1];
                $innerClean = str_replace(["\r\n", "\n", "\r", "\t"], ["\\n", "\\n", "\\r", "\\t"], $inner);
                $innerClean = preg_replace('/(?<!\\\\)"/', '\"', $innerClean);
                return '"content":"' . $innerClean . '"';
            }, $jsonContent);
            $blocks = json_decode($repairedJson, true);
        }
    }

    // If it's not JSON, assume it's raw HTML from Quill.js
    if (!is_array($blocks)) return $jsonContent;

    $html = '';
    foreach ($blocks as $block) {
        if (!is_array($block)) continue;
        $type    = $block['type']    ?? 'paragraph';
        $content = $block['content'] ?? '';

        // Unescape literal \n, \r, \t, \" sequences into real control characters
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
                // content is pipe-delimited rows, rows separated by newlines
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

// ─── Project Details (Rich Content) ──────────────────────────────────────────
function getProjectDetails(PDO $pdo, int $projectId): array {
	$stmt = $pdo->prepare("SELECT * FROM project_details WHERE project_id = ?");
	$stmt->execute([$projectId]);
	return $stmt->fetch() ?: [];
}

function saveProjectDetails(PDO $pdo, int $projectId, array $data): void {
	$existing = getProjectDetails($pdo, $projectId);
	if (!empty($existing)) {
		$sql = "UPDATE project_details SET banner_image=?, client_name=?, industry=?, duration=?, technologies=?, content=? WHERE project_id=?";
		$pdo->prepare($sql)->execute([
			$data['banner_image'] ?? null,
			$data['client_name'] ?? null,
			$data['industry'] ?? null,
			$data['duration'] ?? null,
			$data['technologies'] ?? null,
			$data['content'] ?? '',
			$projectId
		]);
	} else {
		$sql = "INSERT INTO project_details (project_id, banner_image, client_name, industry, duration, technologies, content) VALUES (?,?,?,?,?,?,?)";
		$pdo->prepare($sql)->execute([
			$projectId,
			$data['banner_image'] ?? null,
			$data['client_name'] ?? null,
			$data['industry'] ?? null,
			$data['duration'] ?? null,
			$data['technologies'] ?? null,
			$data['content'] ?? ''
		]);
	}
}

function getProjectWithDetails(PDO $pdo, string $slug): array {
	$project = getProjectBySlug($pdo, $slug);
	if (empty($project)) return [];
	$project['details'] = getProjectDetails($pdo, (int)$project['id']);
	return $project;
}

// ─── CSRF Token ───────────────────────────────────────────────────────────────
function csrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verifyCsrf(): void {
    $token = $_POST['csrf_token'] ?? '';
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }
}

// ─── Solution Categories ──────────────────────────────────────────────────────
function getSolutionCategories(PDO $pdo, bool $activeOnly = true): array {
	$sql = "SELECT * FROM solution_category";
	if ($activeOnly) $sql .= " WHERE is_active = 1";
	$sql .= " ORDER BY sort_order ASC, id ASC";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll();
}

function getSolutionCategory(PDO $pdo, int $id): array {
	$stmt = $pdo->prepare("SELECT * FROM solution_category WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch() ?: [];
}

// ─── Projects ─────────────────────────────────────────────────────────────────
function getProjectsByCategory(PDO $pdo, int $categoryId, bool $activeOnly = true): array {
	$sql = "SELECT * FROM projects WHERE category_id = ?";
	if ($activeOnly) $sql .= " AND is_active = 1";
	$sql .= " ORDER BY sort_order ASC, id ASC";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$categoryId]);
	return $stmt->fetchAll();
}

function getAllProjects(PDO $pdo, bool $activeOnly = true): array {
	$sql = "SELECT p.*, c.name AS category_name, c.color AS category_color FROM projects p LEFT JOIN solution_category c ON p.category_id = c.id";
	if ($activeOnly) $sql .= " WHERE p.is_active = 1";
	$sql .= " ORDER BY c.sort_order ASC, p.sort_order ASC, p.id ASC";
	$stmt = $pdo->query($sql);
	return $stmt->fetchAll();
}

function getProjectById(PDO $pdo, int $id): array {
	$stmt = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
	$stmt->execute([$id]);
	return $stmt->fetch() ?: [];
}

function getProjectBySlug(PDO $pdo, string $slug): array {
	$stmt = $pdo->prepare("SELECT p.*, c.name AS category_name, c.color AS category_color, c.icon AS category_icon FROM projects p LEFT JOIN solution_category c ON p.category_id = c.id WHERE p.slug = ?");
	$stmt->execute([$slug]);
	return $stmt->fetch() ?: [];
}

function projectSlug(string $name): string {
	$clean = preg_replace('/[^a-zA-Z0-9\s-]/', '', $name);
	$clean = preg_replace('/[\s-]+/', '-', $clean);
	return trim($clean, '-');
}

// ─── Project Mockups ──────────────────────────────────────────────────────────
/**
 * Get all mockup images for a project (ordered).
 */
function getProjectMockups(PDO $pdo, int $projectId): array {
	$stmt = $pdo->prepare("SELECT * FROM project_mockups WHERE project_id = ? ORDER BY sort_order ASC, id ASC");
	$stmt->execute([$projectId]);
	return $stmt->fetchAll();
}

/**
 * Add a mockup image reference to a project.
 */
function addProjectMockup(PDO $pdo, int $projectId, string $imagePath): int {
	$stmt = $pdo->prepare("SELECT COALESCE(MAX(sort_order), 0) + 1 FROM project_mockups WHERE project_id = ?");
	$stmt->execute([$projectId]);
	$next = (int)$stmt->fetchColumn();

	$stmt = $pdo->prepare("INSERT INTO project_mockups (project_id, image_path, sort_order) VALUES (?, ?, ?)");
	$stmt->execute([$projectId, $imagePath, $next]);
	return (int)$pdo->lastInsertId();
}

/**
 * Delete a mockup image (both DB row and physical file).
 */
function deleteProjectMockup(PDO $pdo, int $mockupId, int $projectId): bool {
	$stmt = $pdo->prepare("SELECT image_path FROM project_mockups WHERE id = ? AND project_id = ?");
	$stmt->execute([$mockupId, $projectId]);
	$row = $stmt->fetch();
	if (!$row) return false;

	// Delete physical file
	$fullPath = UPLOAD_DIR . $row['image_path'];
	if (is_file($fullPath)) {
		@unlink($fullPath);
	}

	$stmt = $pdo->prepare("DELETE FROM project_mockups WHERE id = ? AND project_id = ?");
	return $stmt->execute([$mockupId, $projectId]);
}

/**
 * Get mockup images as public URLs.
 */
function getProjectMockupUrls(PDO $pdo, int $projectId): array {
	$mockups = getProjectMockups($pdo, $projectId);
	$urls = [];
	foreach ($mockups as $m) {
		if (!empty($m['image_path'])) {
			$urls[] = imgUrl($m['image_path']);
		}
	}
	return $urls;
}