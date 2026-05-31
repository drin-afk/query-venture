<?php
require_once __DIR__ . '/../config.php';
requireLogin();

$pdo = db();
$pid = (int)$_SESSION['player_id'];

$pdo->prepare('UPDATE game_progress SET current_level=1, max_unlocked=1, score=0, player_hp=100 WHERE player_id=?')->execute([$pid]);
$pdo->prepare('DELETE FROM level_completions WHERE player_id=?')->execute([$pid]);

jsonOk(['message' => 'Progress reset to new game.']);
