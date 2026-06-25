<?php
/**
 * Thinker AI - Configuration
 * AI SEO Content Generator
 */

// Site configuration
define('SITE_NAME', 'Thinker AI');
define('SITE_VERSION', '1.0.0');
define('SITE_URL', ''); // Set your full URL during installation

// Installation check
define('INSTALL_FILE', __DIR__ . '/../storage/installed.json');

// Session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Auto-load installation if not installed
if (!file_exists(INSTALL_FILE) && basename($_SERVER['SCRIPT_NAME']) !== 'install.php') {
    header('Location: install.php');
    exit;
}

// Load settings if installed
if (file_exists(INSTALL_FILE)) {
    $config = json_decode(file_get_contents(INSTALL_FILE), true);
    define('ADMIN_USERNAME', $config['username'] ?? 'admin');
    define('ADMIN_PASSWORD_HASH', $config['password_hash'] ?? '');
    define('OPENAI_API_KEY', $config['openai_key'] ?? '');
    define('OPENAI_MODEL', $config['openai_model'] ?? 'gpt-4o-mini');
    define('DEFAULT_LANGUAGE', $config['language'] ?? 'en');
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
