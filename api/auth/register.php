<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Method not allowed.', 405); }

$body     = bodyJson();
$username = trim($body['username'] ?? '');
$password = $body['password'] ?? '';
$email    = trim($body['email'] ?? '') ?: null;

if (!$username || !$password)       { jsonError('Username and password are required.'); }
if (strlen($username) < 3 || strlen($username) > 20) { jsonError('Username must be 3–20 characters.'); }
if (strlen($password) < 6)         { jsonError('Password must be at least 6 characters.'); }

$pdo = db();

$stmt = $pdo->prepare('SELECT id FROM players WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()) { jsonError('Username already taken.', 409); }

$hash = password_hash($password, PASSWORD_BCRYPT);

$pdo->beginTransaction();
try {
    $stmt = $pdo->prepare('INSERT INTO players (username, password_hash, email) VALUES (?, ?, ?)');
    $stmt->execute([$username, $hash, $email]);
    $playerId = (int)$pdo->lastInsertId();

    $pdo->prepare('INSERT INTO characters (player_id) VALUES (?)')->execute([$playerId]);
    $pdo->prepare('INSERT INTO game_progress (player_id) VALUES (?)')->execute([$playerId]);
    $pdo->commit();
} catch (Exception $e) {
    $pdo->rollBack();
    jsonError('Registration failed: ' . $e->getMessage(), 500);
}

$_SESSION['player_id'] = $playerId;
$_SESSION['username']  = $username;

jsonOk(['message' => 'Registered successfully.', 'username' => $username, 'playerId' => $playerId], 201);
