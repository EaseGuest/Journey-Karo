<?php
/**
 * Shared admin CRUD helpers
 */

function adminUploadImage(array $file, string $subdir = 'general'): ?string {
    if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) {
        return null;
    }
    if (($file['size'] ?? 0) > MAX_UPLOAD_SIZE) {
        return null;
    }
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    if (!in_array($mime, ALLOWED_TYPES, true)) {
        return null;
    }

    $dir = UPLOAD_DIR . trim($subdir, '/') . '/';
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $ext = match ($mime) {
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        default      => 'jpg',
    };
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $path = $dir . $name;

    if (!move_uploaded_file($file['tmp_name'], $path)) {
        return null;
    }

    return trim($subdir, '/') . '/' . $name;
}

function adminFlash(string $type, string $message): void {
    $_SESSION['admin_flash'] = ['type' => $type, 'message' => $message];
}

function adminShowFlash(): void {
    if (empty($_SESSION['admin_flash'])) return;
    $f = $_SESSION['admin_flash'];
    unset($_SESSION['admin_flash']);
    $class = $f['type'] === 'success' ? 'alert-success' : 'alert-error';
    echo '<div class="alert ' . $class . '">' . e($f['message']) . '</div>';
}

function jsonDecodeArray(?string $json): array {
    if (!$json) return [];
    $data = json_decode($json, true);
    return is_array($data) ? $data : array_filter(array_map('trim', explode("\n", $json)));
}

function jsonEncodeArray(array $arr): string {
    return json_encode(array_values(array_filter($arr)), JSON_UNESCAPED_UNICODE);
}
