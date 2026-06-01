<?php
/**
 * Journey Karo — CSRF Protection
 * Generates and validates CSRF tokens for all forms.
 */

require_once __DIR__ . '/config.php';

/**
 * Generate a CSRF token and store it in the session.
 */
function csrfToken(): string {
    if (empty($_SESSION[CSRF_TOKEN_NAME])) {
        $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
    }
    return $_SESSION[CSRF_TOKEN_NAME];
}

/**
 * Return an HTML hidden input with the CSRF token.
 */
function csrfField(): string {
    return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . htmlspecialchars(csrfToken()) . '">';
}

/**
 * Validate the CSRF token from a POST request.
 * Terminates with JSON error if invalid.
 */
function validateCsrf(): void {
    $token = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token)) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid security token. Please refresh and try again.']);
        exit;
    }
}

/**
 * Regenerate CSRF token after successful use.
 */
function regenerateCsrf(): void {
    $_SESSION[CSRF_TOKEN_NAME] = bin2hex(random_bytes(32));
}

/**
 * Validate CSRF for traditional HTML forms (returns bool).
 */
function validateCsrfForm(): bool {
    $token = $_POST[CSRF_TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    return hash_equals($_SESSION[CSRF_TOKEN_NAME] ?? '', $token);
}

/**
 * Validate CSRF for HTML forms or die with plain message.
 */
function requireCsrfForm(): void {
    if (!validateCsrfForm()) {
        http_response_code(403);
        die('Invalid security token. Please go back and try again.');
    }
}
