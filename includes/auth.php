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

// Create a new user (admin only)
function create_user($pdo, $name, $email, $password, $role = 'editor') {
    try {
        // Check if email already exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        
        if ($stmt->fetch()) {
            return ['success' => false, 'message' => 'Email already exists'];
        }
        
        // Hash password
        $hashed_password = hash_password($password);
        
        // Insert user
        $stmt = $pdo->prepare("
            INSERT INTO users (name, email, password, role, created_at)
            VALUES (?, ?, ?, ?, NOW())
        ");
        
        if ($stmt->execute([$name, $email, $hashed_password, $role])) {
            $user_id = $pdo->lastInsertId();
            return ['success' => true, 'message' => 'User created successfully', 'user_id' => $user_id];
        }
        
        return ['success' => false, 'message' => 'Failed to create user'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

// Update user
function update_user($pdo, $user_id, $name, $email, $role = null) {
    try {
        if ($role !== null) {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET name = ?, email = ?, role = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $result = $stmt->execute([$name, $email, $role, $user_id]);
        } else {
            $stmt = $pdo->prepare("
                UPDATE users 
                SET name = ?, email = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $result = $stmt->execute([$name, $email, $user_id]);
        }
        
        return ['success' => $result, 'message' => $result ? 'User updated successfully' : 'Failed to update user'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

// Change user password
function change_password($pdo, $user_id, $current_password, $new_password) {
    try {
        $user = get_current_user($pdo);
        
        if (!$user || !verify_password($current_password, $user['password'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }
        
        $hashed_password = hash_password($new_password);
        
        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        if ($stmt->execute([$hashed_password, $user_id])) {
            return ['success' => true, 'message' => 'Password changed successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to change password'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

// Get all users (admin only)
function get_all_users($pdo) {
    try {
        $stmt = $pdo->query("
            SELECT id, name, email, role, created_at, updated_at
            FROM users
            ORDER BY created_at DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}

// Delete user (admin only)
function delete_user($pdo, $user_id) {
    try {
        // Prevent deleting self
        if ($user_id == $_SESSION['admin_id']) {
            return ['success' => false, 'message' => 'You cannot delete your own account'];
        }
        
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        
        if ($stmt->execute([$user_id])) {
            return ['success' => true, 'message' => 'User deleted successfully'];
        }
        
        return ['success' => false, 'message' => 'Failed to delete user'];
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return ['success' => false, 'message' => 'Database error'];
    }
}

// Log activity
function log_activity($pdo, $user_id, $action, $details = '') {
    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, action, details, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$user_id, $action, $details]);
    } catch (PDOException $e) {
        error_log("Failed to log activity: " . $e->getMessage());
    }
}

// Get user's activity logs
function get_user_activity($pdo, $user_id, $limit = 10) {
    try {
        $stmt = $pdo->prepare("
            SELECT * FROM activity_logs 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT ?
        ");
        $stmt->bindParam(1, $user_id, PDO::PARAM_INT);
        $stmt->bindParam(2, $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        return [];
    }
}
