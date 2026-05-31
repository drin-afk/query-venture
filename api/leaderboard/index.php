<?php
require_once __DIR__ . '/../config.php';

// Show ALL registered players (including new accounts with score 0)
// ORDER BY score DESC so top scorers lead, then by created_at so newer
// accounts appear below existing ones when tied at 0.
$stmt = db()->prepare('
    SELECT p.username, g.score, g.max_unlocked, g.updated_at,
           p.created_at
    FROM game_progress g
    JOIN players p ON p.id = g.player_id
    ORDER BY g.score DESC, p.created_at ASC
    LIMIT 20
');
$stmt->execute();
$rows = $stmt->fetchAll();

$leaderboard = array_map(fn($r, $i) => ['rank' => $i + 1] + $r, $rows, array_keys($rows));

jsonOk(['leaderboard' => $leaderboard]);
