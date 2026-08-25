<?php
/**
 * Shilo Production - Header Include
 */

// Load configuration and database
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/functions.php';

// Load language
$language = load_language();

// Get site settings
$company_name = get_setting($pdo, 'company_name', SITE_NAME);
$logo = get_setting($pdo, 'logo', '');
$phone = get_setting($pdo, 'phone', '');
$email = get_setting($pdo, 'email', '');
$facebook = get_setting($pdo, 'facebook', '');
$instagram = get_setting($pdo, 'instagram', '');
$youtube = get_setting($pdo, 'youtube', '');
$tiktok = get_setting($pdo, 'tiktok', '');
$telegram = get_setting($pdo, 'telegram', '');

// Check session timeout
check_session_timeout();
?>
<!DOCTYPE html>
<html lang="<?php echo get_current_language(); ?>" dir="<?php echo get_current_language() === 'am' ? 'rtl' : 'ltr'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo e(SITE_DESCRIPTION); ?>">
    <meta name="theme-color" content="#000000">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo e(SITE_NAME); ?>">
    <meta property="og:description" content="<?php echo e(SITE_DESCRIPTION); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo SITE_URL; ?>">
    
    <title><?php echo isset($page_title) ? e($page_title) . ' | ' . SITE_NAME : SITE_NAME; ?></title>
    
    <!-- Favicon -->
    <?php if ($logo): ?>
    <link rel="icon" type="image/png" href="<?php echo SITE_URL . '/uploads/logo/' . e($logo); ?>">
    <?php endif; ?>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Styles -->
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?php echo SITE_URL; ?>/assets/css/responsive.css">
    
    <?php if (isset($extra_css)): ?>
    <?php echo $extra_css; ?>
    <?php endif; ?>
</head>
<body>
    <!-- Skip to main content -->
    <a href="#main-content" class="skip-link">Skip to main content</a>
