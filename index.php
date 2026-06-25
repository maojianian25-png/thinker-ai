<?php
/**
 * Thinker AI - Entry Point
 * Redirects to admin panel
 */
session_start();

// Check if installed
if (!file_exists(__DIR__ . '/storage/installed.json')) {
    header('Location: install.php');
    exit;
}

header('Location: admin/');
exit;
