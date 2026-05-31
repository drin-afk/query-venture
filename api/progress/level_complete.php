<?php
require_once __DIR__ . '/../config.php';
requireLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Method not allowed.', 405); }

$body   = bodyJson();
$pid    = (int)$_SESSION['player_id'];
$lvl    = isset($body['level_id'])        ? (int)$body['level_id']        : null;
$stars  = isset($body['stars'])           ? (int)$body['stars']           : null;
$cor    = isset($body['correct_answers']) ? (int)$body['correct_answers'] : null;
$earned = isset($body['score_earned'])    ? (int)$body['score_earned']    : null;

if ($lvl === null || $stars === null || $cor === null || $earned === null) {
    jsonError('level_id, stars, correct_answers, score_earned are required.');
}
if ($lvl < 1 || $lvl > 5) { jsonError('level_id must be 1–5.'); }

// Upsert — keep best result per level
db()->prepare('
    INSERT INTO level_completions (player_id, level_id, stars, correct_answers, score_earned)
    VALUES (?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        stars           = GREATEST(stars,           VALUES(stars)),
        correct_answers = GREATEST(correct_answers, VALUES(correct_answers)),
        score_earned    = GREATEST(score_earned,    VALUES(score_earned)),
        completed_at    = NOW()
')->execute([$pid, $lvl, $stars, $cor, $earned]);

jsonOk(['message' => 'Level completion recorded.']);
