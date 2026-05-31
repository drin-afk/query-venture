<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = db();
$pid = (int)$_SESSION['player_id'];

// Fix 5: Handle PUT first and return early — avoids running two expensive
// SELECT queries that are immediately discarded on every character-save call.
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    $body      = bodyJson();
    $charType  = $body['char_type']  ?? null;
    $skinColor = $body['skin_color'] ?? null;
    $hairColor = $body['hair_color'] ?? null;

    if ($charType && !in_array($charType, ['boy', 'girl'])) {
        jsonError('char_type must be "boy" or "girl".');
    }

    $pdo->prepare('
        UPDATE characters
        SET char_type  = COALESCE(?, char_type),
            skin_color = COALESCE(?, skin_color),
            hair_color = COALESCE(?, hair_color)
        WHERE player_id = ?
    ')->execute([$charType, $skinColor, $hairColor, $pid]);

    jsonOk(['message' => 'Character updated.']);
}

// GET — return full profile
$stmt = $pdo->prepare('
    SELECT p.id, p.username, p.email, p.created_at, p.last_login,
           c.char_type, c.skin_color, c.hair_color,
           g.current_level, g.max_unlocked, g.score, g.player_hp,
           g.updated_at AS progress_updated
    FROM players p
    LEFT JOIN characters    c ON c.player_id = p.id
    LEFT JOIN game_progress g ON g.player_id = p.id
    WHERE p.id = ?
');
$stmt->execute([$pid]);
$player = $stmt->fetch();

if (!$player) { jsonError('Player not found.', 404); }

$stmt = $pdo->prepare('
    SELECT level_id, stars, correct_answers, score_earned, completed_at
    FROM level_completions
    WHERE player_id = ?
    ORDER BY level_id
');
$stmt->execute([$pid]);
$completions = $stmt->fetchAll();

jsonOk(['player' => $player, 'completions' => $completions]);
