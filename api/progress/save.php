<?php
require_once __DIR__ . '/../config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Method not allowed.', 405); }

$body = bodyJson();
$pid  = (int)$_SESSION['player_id'];

$curLvl     = isset($body['current_level']) ? (int)$body['current_level'] : null;
$maxUnlocked = isset($body['max_unlocked']) ? (int)$body['max_unlocked']  : null;
$score      = isset($body['score'])         ? (int)$body['score']         : null;
$hp         = isset($body['player_hp'])     ? (int)$body['player_hp']     : null;

if ($curLvl === null || $maxUnlocked === null || $score === null || $hp === null) {
    jsonError('current_level, max_unlocked, score, player_hp are all required.');
}

db()->prepare('
    UPDATE game_progress
    SET current_level = ?,
        max_unlocked  = GREATEST(max_unlocked, ?),
        score         = ?,
        player_hp     = ?
    WHERE player_id = ?
')->execute([$curLvl, $maxUnlocked, $score, $hp, $pid]);

jsonOk(['message' => 'Progress saved.']);
