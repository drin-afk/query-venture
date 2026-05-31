<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = db();
$pid = (int)$_SESSION['player_id'];

$stmt = $pdo->prepare('SELECT current_level, max_unlocked, score, player_hp, updated_at FROM game_progress WHERE player_id = ?');
$stmt->execute([$pid]);
$progress = $stmt->fetch();

$stmt = $pdo->prepare('SELECT level_id, stars, correct_answers, score_earned, completed_at FROM level_completions WHERE player_id = ? ORDER BY level_id');
$stmt->execute([$pid]);
$completions = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT char_type, skin_color, hair_color FROM characters WHERE player_id = ?');
$stmt->execute([$pid]);
$character = $stmt->fetch();

jsonOk(['progress' => $progress, 'completions' => $completions, 'character' => $character]);
