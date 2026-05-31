<?php
require_once __DIR__ . '/../config.php';

$level = isset($_GET['level']) ? (int)$_GET['level'] : 0;
if ($level < 1 || $level > 5) { jsonError('level must be 1–5.'); }

// correct_index intentionally omitted — answers are validated server-side
$stmt = db()->prepare('
    SELECT id, level_id, question_text, opt_a, opt_b, opt_c, opt_d, topic
    FROM questions
    WHERE level_id = ?
    ORDER BY id
');
$stmt->execute([$level]);
$questions = $stmt->fetchAll();

jsonOk(['level' => $level, 'questions' => $questions]);
