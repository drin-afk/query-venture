<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Method not allowed.', 405); }

$body     = bodyJson();
$username = trim($body['username'] ?? '');
$password = $body['password'] ?? '';

if (!$username || !$password) { jsonError('Username and password are required.'); }

function ensureThrottleTable(PDO $pdo): void {
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS auth_throttle (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username_key VARCHAR(64) NOT NULL,
            ip_addr VARCHAR(45) NOT NULL,
            failed_count INT NOT NULL DEFAULT 0,
            lock_until DATETIME NULL DEFAULT NULL,
            last_attempt DATETIME NULL DEFAULT NULL,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            UNIQUE KEY uq_user_ip (username_key, ip_addr),
            INDEX idx_lock_until (lock_until)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
}

function throttleKey(string $username): string {
    return strtolower($username);
}

function throttleIp(): string {
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    return substr((string)$ip, 0, 45);
}

function throttleUpsert(PDO $pdo, string $userKey, string $ip, int $count, ?string $lockUntil): void {
    $stmt = $pdo->prepare("
        INSERT INTO auth_throttle (username_key, ip_addr, failed_count, lock_until, last_attempt)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE
            failed_count = VALUES(failed_count),
            lock_until = VALUES(lock_until),
            last_attempt = NOW()
    ");
    $stmt->execute([$userKey, $ip, $count, $lockUntil]);
}

$pdo = db();
ensureThrottleTable($pdo);

$userKey = throttleKey($username);
$ipAddr  = throttleIp();
$maxAttempts = 5;
$lockSeconds = 60;

$th = $pdo->prepare('SELECT failed_count, lock_until FROM auth_throttle WHERE username_key = ? AND ip_addr = ?');
$th->execute([$userKey, $ipAddr]);
$throttle = $th->fetch();

if ($throttle && !empty($throttle['lock_until'])) {
    $retry = strtotime((string)$throttle['lock_until']) - time();
    if ($retry > 0) {
        jsonOk([
            'error' => 'Too many attempts. Please wait before trying again.',
            'retry_after' => $retry,
        ], 429);
    }
}

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM players WHERE username = ?');
$stmt->execute([$username]);
$player = $stmt->fetch();

if (!$player || !password_verify($password, $player['password_hash'])) {
    $failedCount = (int)($throttle['failed_count'] ?? 0) + 1;
    if ($failedCount >= $maxAttempts) {
        $lockUntil = date('Y-m-d H:i:s', time() + $lockSeconds);
        throttleUpsert($pdo, $userKey, $ipAddr, $failedCount, $lockUntil);
        jsonOk([
            'error' => 'Too many failed attempts. Temporary lock enabled.',
            'retry_after' => $lockSeconds,
        ], 429);
    }

    throttleUpsert($pdo, $userKey, $ipAddr, $failedCount, null);
    $remaining = $maxAttempts - $failedCount;
    jsonOk([
        'error' => 'Invalid username or password. ' . $remaining . ' attempt' . ($remaining === 1 ? '' : 's') . ' left before temporary lock.',
    ], 401);
}

$pdo->prepare('UPDATE players SET last_login = NOW() WHERE id = ?')->execute([$player['id']]);
throttleUpsert($pdo, $userKey, $ipAddr, 0, null);

session_regenerate_id(true);
$_SESSION['player_id'] = (int)$player['id'];
$_SESSION['username']  = $player['username'];

jsonOk(['message' => 'Logged in.', 'username' => $player['username'], 'playerId' => (int)$player['id']]);
