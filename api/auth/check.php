<?php
require_once __DIR__ . '/../config.php';

if (!empty($_SESSION['player_id'])) {
    jsonOk([
        'loggedIn' => true,
        'playerId' => (int)$_SESSION['player_id'],
        'username' => $_SESSION['username'],
    ]);
} else {
    jsonOk(['loggedIn' => false]);
}
