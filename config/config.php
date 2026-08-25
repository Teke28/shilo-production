<?php
/**
 * Shilo Production - Application Configuration
 */

// Site information
define('SITE_NAME', 'Shilo Production');
define('SITE_URL', 'http://localhost/shilo-production');
define('SITE_DESCRIPTION', 'Professional Photography, Videography & Media Production');

// File upload paths
define('UPLOAD_DIR', __DIR__ . '/../uploads');
define('UPLOAD_PORTFOLIO', UPLOAD_DIR . '/portfolio');
define('UPLOAD_VIDEOS', UPLOAD_DIR . '/videos');
define('UPLOAD_SERVICES', UPLOAD_DIR . '/services');
define('UPLOAD_BLOG', UPLOAD_DIR . '/blog');
define('UPLOAD_TEAM', UPLOAD_DIR . '/team');
define('UPLOAD_TESTIMONIALS', UPLOAD_DIR . '/testimonials');

// File upload restrictions
define('MAX_FILE_SIZE', 50 * 1024 * 1024); // 50MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'webp']);
define('ALLOWED_VIDEO_TYPES', ['mp4', 'webm', 'mov']);
define('ALLOWED_IMAGE_MIMES', ['image/jpeg', 'image/png', 'image/webp']);
define('ALLOWED_VIDEO_MIMES', ['video/mp4', 'video/webm', 'video/quicktime']);

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour

// Pagination
define('ITEMS_PER_PAGE', 12);

// Default language
define('DEFAULT_LANG', 'en');
define('SUPPORTED_LANGS', ['en', 'am']);

// Admin settings
define('ADMIN_LOGIN_PATH', '/admin/login.php');
define('ADMIN_DASHBOARD_PATH', '/admin/dashboard.php');
