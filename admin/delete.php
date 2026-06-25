<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';
requireLogin();

$id = $_GET['id'] ?? '';
if ($id) {
    deleteContent($id);
}
header('Location: history.php');
exit;
