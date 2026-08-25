<?php
/**
 * Shilo Production - Core Functions
 */

// Input validation and sanitization
function sanitize_input($data) {
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

// Validate email
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

// Validate phone
function is_valid_phone($phone) {
    return preg_match('/^[0-9\s\-\+\(\)]+$/', $phone) && strlen(preg_replace('/[^0-9]/', '', $phone)) >= 7;
}

// Validate date
function is_valid_date($date) {
    $d = DateTime::createFromFormat('Y-m-d', $date);
    return $d && $d->format('Y-m-d') === $date;
}

// Check if date is in future
function is_future_date($date) {
    return strtotime($date) > time();
}

// Generate unique filename
function generate_unique_filename($original_name) {
    $ext = pathinfo($original_name, PATHINFO_EXTENSION);
    return date('YmdHis') . '_' . uniqid() . '.' . $ext;
}

// Validate file upload
function validate_file_upload($file, $allowed_types, $allowed_mimes, $max_size = MAX_FILE_SIZE) {
    $errors = [];
    
    if (!isset($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        $errors[] = 'No file uploaded';
        return $errors;
    }
    
    // Check file size
    if ($file['size'] > $max_size) {
        $errors[] = 'File size exceeds maximum limit';
    }
    
    // Check file extension
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed_types)) {
        $errors[] = 'File type not allowed';
    }
    
    // Check MIME type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime, $allowed_mimes)) {
        $errors[] = 'Invalid file format';
    }
    
    return $errors;
}

// Upload file securely
function upload_file($file, $upload_dir, $allowed_types, $allowed_mimes) {
    $errors = validate_file_upload($file, $allowed_types, $allowed_mimes);
    
    if (!empty($errors)) {
        return ['success' => false, 'errors' => $errors];
    }
    
    // Ensure upload directory exists
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    
    // Generate unique filename
    $filename = generate_unique_filename($file['name']);
    $filepath = $upload_dir . '/' . $filename;
    
    // Move uploaded file
    if (move_uploaded_file($file['tmp_name'], $filepath)) {
        return [
            'success' => true,
            'filename' => $filename,
            'path' => $filepath
        ];
    }
    
    return ['success' => false, 'errors' => ['Failed to upload file']];
}

// Delete file
function delete_file($filepath) {
    if (file_exists($filepath) && is_file($filepath)) {
        return unlink($filepath);
    }
    return false;
}

// Pagination
function get_pagination_info($total_items, $items_per_page, $current_page) {
    $total_pages = ceil($total_items / $items_per_page);
    $current_page = max(1, min($current_page, $total_pages));
    $offset = ($current_page - 1) * $items_per_page;
    
    return [
        'total_items' => $total_items,
        'total_pages' => $total_pages,
        'current_page' => $current_page,
        'offset' => $offset,
        'items_per_page' => $items_per_page
    ];
}

// Generate booking reference
function generate_booking_reference($booking_id) {
    return 'SHILO-' . date('Y') . '-' . str_pad($booking_id, 4, '0', STR_PAD_LEFT);
}

// Format price
function format_price($price) {
    return '$' . number_format($price, 2);
}

// Format date
function format_date($date, $format = 'M d, Y') {
    return date($format, strtotime($date));
}

// Get page language
function get_current_language() {
    if (isset($_GET['lang']) && in_array($_GET['lang'], SUPPORTED_LANGS)) {
        $_SESSION['lang'] = $_GET['lang'];
    }
    
    return $_SESSION['lang'] ?? DEFAULT_LANG;
}

// Load language file
function load_language() {
    $lang = get_current_language();
    $lang_file = __DIR__ . '/../languages/' . $lang . '.php';
    
    if (file_exists($lang_file)) {
        include $lang_file;
    } else {
        include __DIR__ . '/../languages/en.php';
    }
    
    return $t ?? [];
}

// Get setting value
function get_setting($pdo, $key, $default = null) {
    try {
        $stmt = $pdo->prepare("SELECT value FROM site_settings WHERE setting_key = ?");
        $stmt->execute([$key]);
        $result = $stmt->fetch();
        return $result ? $result['value'] : $default;
    } catch (PDOException $e) {
        error_log("Error fetching setting: " . $e->getMessage());
        return $default;
    }
}

// Set setting value
function set_setting($pdo, $key, $value) {
    try {
        $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, value) VALUES (?, ?) ON DUPLICATE KEY UPDATE value = ?");
        return $stmt->execute([$key, $value, $value]);
    } catch (PDOException $e) {
        error_log("Error setting value: " . $e->getMessage());
        return false;
    }
}

// Send email (basic implementation)
function send_email($to, $subject, $message) {
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8" . "\r\n";
    $headers .= "From: " . SITE_NAME . " <noreply@shiloproduction.com>" . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

// Log activity
function log_activity($pdo, $user_id, $action, $details = null) {
    try {
        $stmt = $pdo->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$user_id, $action, $details]);
    } catch (PDOException $e) {
        error_log("Error logging activity: " . $e->getMessage());
        return false;
    }
}

// Get file URL
function get_file_url($filename, $type = 'portfolio') {
    return SITE_URL . '/uploads/' . $type . '/' . $filename;
}

// Escape output
function e($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
