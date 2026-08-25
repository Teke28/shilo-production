<?php
/**
 * Shilo Production - Authentication & Authorization
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
function is_logged_in() {
    return isset($_SESSION['admin_id']) && !empty($_SESSION['admin_id']);
}

// Check if user is admin
function is_admin() {
    return is_logged_in() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

// Protect admin pages
function require_admin() {
    if (!is_admin()) {
        header('Location: ' . SITE_URL . '/admin/login.php');
        exit;
    }
}

// Get current user ID
function get_user_id() {
    return $_SESSION['admin_id'] ?? null;
}

// Get current user role
function get_user_role() {
    return $_SESSION['role'] ?? null;
}

// Get current user info
function get_current_user($pdo) {
    if (!is_logged_in()) {
        return null;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, name, email, role FROM users WHERE id = ?");
        $stmt->execute([get_user_id()]);
        return $stmt->fetch();
    } catch (PDOException $e) {
        error_log("Error fetching user: " . $e->getMessage());
        return null;
    }
}

// Logout user
function logout() {
    session_destroy();
    header('Location: ' . SITE_URL . '/admin/login.php');
    exit;
}

// Check session timeout
function check_session_timeout() {
    if (is_logged_in()) {
        $timeout = SESSION_TIMEOUT;
        
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $timeout)) {
            logout();
        }
        
        $_SESSION['last_activity'] = time();
    }
}

// CSRF Token Generation
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

// CSRF Token Verification
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Password hashing
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
}

// Password verification
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

// Authenticate user
function authenticate_user($pdo, $email, $password) {
    try {
        $stmt = $pdo->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && verify_password($password, $user['password'])) {
            return $user;
        }
        
        return false;
    } catch (PDOException $e) {
        error_log("Authentication error: " . $e->getMessage());
        return false;
    }
}
