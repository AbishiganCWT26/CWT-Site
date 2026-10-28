<?php
/**
 * Authentication helper for admin panel
 * CWT Corporate Website
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Require admin login. Redirects to login page if not authenticated.
 */
function requireAuth(): void {
    if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header('Location: ' . ADMIN_URL . '/login.php');
        exit;
    }
}

/**
 * Check if admin is logged in
 */
function isLoggedIn(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Log in an admin user
 */
function loginAdmin(string $username): void {
    session_regenerate_id(true);
    $_SESSION['admin_logged_in'] = true;
    $_SESSION['admin_username']  = $username;
}

/**
 * Log out admin
 */
function logoutAdmin(): void {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
