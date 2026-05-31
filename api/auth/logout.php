<?php
require_once __DIR__ . '/../config.php';

session_destroy();
jsonOk(['message' => 'Logged out.']);
