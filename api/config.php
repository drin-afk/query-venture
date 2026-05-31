<?php
// Shared config — included by every API endpoint

// Fix 1: Suppress PHP error output so errors return JSON, not HTML.
// XAMPP ships with display_errors=On; without this, any warning/exception
// would prepend HTML to the response body and break JSON.parse() in the browser.
ini_set('display_errors', '0');
error_reporting(0);

// Global safety net — catches any uncaught exception anywhere and returns
// proper JSON instead of an empty 500 page (fixes "Registration failed" blank body).
set_exception_handler(function(Throwable $e) {
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
    }
    echo json_encode(['error' => $e->getMessage()]);
    exit;
});

// Fix 2: CORS + OPTIONS preflight — allows fetch() with credentials from
// any XAMPP-served origin (localhost, 127.0.0.1, custom vhost, etc.)
$origin = $_SERVER['HTTP_ORIGIN'] ?? '*';
header("Access-Control-Allow-Origin: $origin");
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Headers: Content-Type, X-Requested-With');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ── DATABASE ──────────────────────────────────────────────────────────────────

define('DB_HOST', 'localhost');
define('DB_NAME', 'query_venture');
define('DB_USER', 'root');
define('DB_PASS', '');

function db(): PDO {
    static $pdo = null;
    if ($pdo === null) {
        // Fix 3: Wrap PDO constructor so a DB failure returns clean JSON
        // instead of an uncaught PDOException that becomes an HTML error page.
        try {
            $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            http_response_code(503);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode([
                'error' => 'Database unavailable. Make sure MySQL is running in XAMPP and you have imported database/schema.sql.',
            ]);
            exit;
        }
    }
    return $pdo;
}

// ── RESPONSE HELPERS ──────────────────────────────────────────────────────────

function jsonOk(array $data, int $status = 200): void {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function jsonError(string $message, int $status = 400): void {
    jsonOk(['error' => $message], $status);
}

function requireLogin(): void {
    if (empty($_SESSION['player_id'])) {
        jsonError('Not logged in. Please log in first.', 401);
    }
}

function bodyJson(): array {
    $raw = file_get_contents('php://input');
    return json_decode($raw, true) ?? [];
}
