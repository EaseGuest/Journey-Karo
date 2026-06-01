<?php
/**
 * Journey Karo — Admin Authentication
 */

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/database.php';

define('LOGIN_MAX_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_SECONDS', 900);

function isAdmin(): bool {
    return !empty($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

/**
 * Check brute-force lockout for username + IP
 */
function isLoginLocked(string $username): bool {
    $since = date('Y-m-d H:i:s', time() - LOGIN_LOCKOUT_SECONDS);
    try {
        $row = dbFetch(
            "SELECT COUNT(*) AS cnt FROM login_attempts
             WHERE username = ? AND ip_address = ? AND attempted_at > ?",
            [trim($username), $_SERVER['REMOTE_ADDR'] ?? '', $since]
        );
        return ((int)($row['cnt'] ?? 0)) >= LOGIN_MAX_ATTEMPTS;
    } catch (Throwable) {
        return false;
    }
}

/**
 * Attempt admin login
 */
function adminLogin(string $username, string $password, bool $remember = false): bool {
    if (isLoginLocked($username)) {
        return false;
    }

    $user = dbFetch(
        "SELECT * FROM users WHERE username = ? AND role IN ('admin','editor') AND status = 'active' AND deleted_at IS NULL LIMIT 1",
        [trim($username)]
    );

    if (!$user || !password_verify($password, $user['password'])) {
        try {
            dbQuery(
                "INSERT INTO login_attempts (username, ip_address, attempted_at) VALUES (?, ?, NOW())",
                [trim($username), $_SERVER['REMOTE_ADDR'] ?? '']
            );
        } catch (Throwable) {}
        return false;
    }

    session_regenerate_id(true);
    $_SESSION['admin_logged_in']     = true;
    $_SESSION['admin_id']            = (int)$user['id'];
    $_SESSION['admin_name']          = $user['name'];
    $_SESSION['admin_email']         = $user['email'];
    $_SESSION['admin_role']          = $user['role'];
    $_SESSION['admin_last_activity'] = time();

    if ($remember) {
        $token = bin2hex(random_bytes(32));
        dbQuery("UPDATE users SET remember_token = ? WHERE id = ?", [$token, $user['id']]);
        setcookie('jk_remember', $token, [
            'expires'  => time() + 60 * 60 * 24 * 30,
            'path'     => '/admin/',
            'secure'   => (APP_ENV === 'production'),
            'httponly' => true,
            'samesite' => 'Strict',
        ]);
    }

    dbQuery(
        "UPDATE users SET last_login = NOW(), last_ip = ? WHERE id = ?",
        [$_SERVER['REMOTE_ADDR'] ?? '', $user['id']]
    );

    return true;
}

/**
 * Restore session from remember-me cookie
 */
function tryRememberLogin(): bool {
    $token = $_COOKIE['jk_remember'] ?? '';
    if (!$token || !empty($_SESSION['admin_logged_in'])) {
        return !empty($_SESSION['admin_logged_in']);
    }

    $user = dbFetch(
        "SELECT * FROM users WHERE remember_token = ? AND status = 'active' AND deleted_at IS NULL LIMIT 1",
        [$token]
    );
    if (!$user) {
        return false;
    }

    $_SESSION['admin_logged_in']     = true;
    $_SESSION['admin_id']            = (int)$user['id'];
    $_SESSION['admin_name']          = $user['name'];
    $_SESSION['admin_email']         = $user['email'];
    $_SESSION['admin_role']          = $user['role'];
    $_SESSION['admin_last_activity'] = time();
    return true;
}

/**
 * Logout admin
 */
function adminLogout(): void {
    if (!empty($_SESSION['admin_id'])) {
        try {
            dbQuery("UPDATE users SET remember_token = NULL WHERE id = ?", [(int)$_SESSION['admin_id']]);
        } catch (Throwable) {}
    }
    setcookie('jk_remember', '', ['expires' => time() - 3600, 'path' => '/admin/']);
    session_unset();
    session_destroy();
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    session_regenerate_id(true);
}

function requireAdmin(): void {
    tryRememberLogin();

    if (!isAdmin()) {
        $login = (defined('APP_URL') ? rtrim(APP_URL, '/') : '') . '/admin/login.php';
        header('Location: ' . $login);
        exit;
    }

    if (!empty($_SESSION['admin_last_activity']) &&
        (time() - $_SESSION['admin_last_activity']) > SESSION_TIMEOUT) {
        adminLogout();
        header('Location: ' . (defined('APP_URL') ? rtrim(APP_URL, '/') : '') . '/admin/login.php?timeout=1');
        exit;
    }
    $_SESSION['admin_last_activity'] = time();
}
