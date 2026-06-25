<?php
/**
 * Thinker AI - Authentication
 */

function isLoggedIn() {
    return isset($_SESSION['thinker_logged_in']) && $_SESSION['thinker_logged_in'] === true;
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function login($username, $password) {
    if (empty(ADMIN_PASSWORD_HASH)) {
        return false;
    }
    
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['thinker_logged_in'] = true;
        $_SESSION['thinker_username'] = $username;
        $_SESSION['thinker_login_time'] = time();
        return true;
    }
    
    return false;
}

function logout() {
    $_SESSION['thinker_logged_in'] = false;
    unset($_SESSION['thinker_username']);
    unset($_SESSION['thinker_login_time']);
    session_destroy();
}

function getUsername() {
    return $_SESSION['thinker_username'] ?? 'Admin';
}
