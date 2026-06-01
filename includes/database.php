<?php
/**
 * Journey Karo — Database Layer (canonical)
 * PDO singleton, prepared statements, transactions, helpers.
 */

require_once __DIR__ . '/config.php';

final class DatabaseConnection
{
    private static ?PDO $pdo = null;

    public static function pdo(): PDO
    {
        if (self::$pdo === null) {
            $dsn = sprintf(
                'mysql:host=%s;dbname=%s;charset=%s',
                DB_HOST,
                DB_NAME,
                DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci',
            ];

            try {
                self::$pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                if (APP_ENV === 'production') {
                    if (php_sapi_name() === 'cli') {
                        throw $e;
                    }
                    http_response_code(500);
                    $isJson = str_contains($_SERVER['HTTP_ACCEPT'] ?? '', 'json')
                        || str_contains($_SERVER['REQUEST_URI'] ?? '', '/api/');
                    if ($isJson) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Database unavailable. Please try again later.']);
                    } else {
                        echo '<!DOCTYPE html><html><body><h1>Service temporarily unavailable</h1><p>Please try again shortly.</p></body></html>';
                    }
                    exit;
                }
                throw $e;
            }
        }

        return self::$pdo;
    }

    private function __construct() {}
    private function __clone() {}
}

/** @return PDO */
function db(): PDO
{
    return DatabaseConnection::pdo();
}

function dbQuery(string $sql, array $params = []): PDOStatement
{
    $stmt = db()->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function dbFetch(string $sql, array $params = []): ?array
{
    $row = dbQuery($sql, $params)->fetch();
    return $row ?: null;
}

function dbFetchAll(string $sql, array $params = []): array
{
    return dbQuery($sql, $params)->fetchAll();
}

function dbLastId(): string
{
    return db()->lastInsertId();
}

function dbCount(string $table, string $where = '1=1', array $params = []): int
{
    $allowed = ['users','destinations','packages','itineraries','blogs','reviews','gallery','inquiries','seo_meta','settings','login_attempts'];
    if (!in_array($table, $allowed, true)) {
        throw InvalidArgumentException('Invalid table name');
    }
    $row = dbFetch("SELECT COUNT(*) AS cnt FROM `{$table}` WHERE {$where}", $params);
    return (int)($row['cnt'] ?? 0);
}

function dbTransaction(callable $callback): mixed
{
    $pdo = db();
    $pdo->beginTransaction();
    try {
        $result = $callback($pdo);
        $pdo->commit();
        return $result;
    } catch (Throwable $e) {
        $pdo->rollBack();
        throw $e;
    }
}

function dbSoftDelete(string $table, int $id): bool
{
    $allowed = ['destinations','packages','itineraries','blogs','reviews','gallery','inquiries','users'];
    if (!in_array($table, $allowed, true)) {
        return false;
    }
    dbQuery("UPDATE `{$table}` SET deleted_at = NOW() WHERE id = ?", [$id]);
    return true;
}

function dbIsConnected(): bool
{
    try {
        db()->query('SELECT 1');
        return true;
    } catch (Throwable) {
        return false;
    }
}
