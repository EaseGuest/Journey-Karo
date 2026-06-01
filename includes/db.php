<?php
/**
 * Journey Karo — PDO Database Connection (backward compatibility)
 * Canonical implementation: includes/database.php
 */

require_once __DIR__ . '/database.php';

/** @deprecated Use DatabaseConnection::pdo() or db() */
class Database {
    public static function getInstance(): PDO {
        return db();
    }
}
