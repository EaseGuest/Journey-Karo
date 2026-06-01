<?php
require_once __DIR__ . '/includes/init.php';
adminLogout();
header('Location: ' . adminUrl('login.php'));
exit;
