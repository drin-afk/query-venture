<?php
require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { jsonError('Method not allowed.', 405); }

$body       = bodyJson();
$questionId = isset($body['question_id']) ? (int)$body['question_id'] : 0;
// Fix 4: Accept -1 as the timeout sentinel (always wrong).
// Previously only 0–3 were accepted; the timeout path sent selected:0 which
// could incorrectly match option A and mark the answer as correct server-side.
$selected   = isset($body['selected']) ? (int)$body['selected'] : -2;

if ($questionId <= 0)               { jsonError('Invalid question_id.'); }
if ($selected < -1 || $selected > 3) { jsonError('selected must be -1 (timeout) or 0–3.'); }

$stmt = db()->prepare('SELECT correct_index, explanation FROM questions WHERE id = ?');
$stmt->execute([$questionId]);
$q = $stmt->fetch();

if (!$q) { jsonError('Question not found.', 404); }

// selected === -1 is always wrong (timeout), never matches a valid index 0-3
jsonOk([
    'correct'       => $selected >= 0 && $selected === (int)$q['correct_index'],
    'correct_index' => (int)$q['correct_index'],
    'explanation'   => $q['explanation'],
]);
