<?php
/**
 * Run once on server after DB import:
 * php database/set-admin-password.php
 * Sets admin password to Admin@2025
 */
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/database.php';

$hash = password_hash('Admin@2025', PASSWORD_DEFAULT);
dbQuery("UPDATE users SET password = ? WHERE username = 'journeykaro_admin'", [$hash]);
echo "Admin password updated for journeykaro_admin\n";
