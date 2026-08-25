<?php
/**
 * Shilo Production - Navigation Bar Include
 */
?>
<nav class="navbar navbar-main">
    <div class="container-fluid">
        <div class="navbar-wrapper">
            <!-- Logo -->
            <div class="navbar-logo">
                <a href="<?php echo SITE_URL; ?>" class="logo-link">
                    <?php if ($logo): ?>
                    <img src="<?php echo SITE_URL . '/uploads/logo/' . e($logo); ?>" alt="<?php echo e($company_name); ?>" class="logo-img">
                    <?php else: ?>
                    <span class="logo-text"><?php echo e($company_name); ?></span>
                    <?php endif; ?>
                </a>
            </div>
            
            <!-- Menu Toggle Button -->
            <button class="navbar-toggle" id="navbar-toggle" aria-label="Toggle navigation">
                <span class="toggle-icon"></span>
                <span class="toggle-icon"></span>
                <span class="toggle-icon"></span>
            </button>
            
            <!-- Navigation Menu -->
            <div class="navbar-menu" id="navbar-menu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'index.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_home']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'about.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_about']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/services.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'services.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_services']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/portfolio.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'portfolio.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_portfolio']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/videos.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'videos.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_videos']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/blog.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'blog.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_blog']; ?>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="<?php echo SITE_URL; ?>/pages/contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) === 'contact.php' ? 'active' : ''; ?>">
                            <?php echo $language['nav_contact']; ?>
                        </a>
                    </li>
                </ul>
            </div>
            
            <!-- Right Actions -->
            <div class="navbar-actions">
                <!-- Language Switcher -->
                <div class="language-switcher">
                    <button class="lang-btn" id="lang-toggle">
                        <i class="fas fa-globe"></i>
                        <span><?php echo strtoupper(get_current_language()); ?></span>
                    </button>
                    <div class="lang-menu" id="lang-menu">
                        <a href="?lang=en" class="lang-option <?php echo get_current_language() === 'en' ? 'active' : ''; ?>">
                            English
                        </a>
                        <a href="?lang=am" class="lang-option <?php echo get_current_language() === 'am' ? 'active' : ''; ?>">
                            አማርኛ
                        </a>
                    </div>
                </div>
                
                <!-- CTA Button -->
                <a href="<?php echo SITE_URL; ?>/pages/booking.php" class="btn btn-primary btn-book">
                    <i class="fas fa-calendar-check"></i>
                    <?php echo $language['nav_book']; ?>
                </a>
                
                <!-- Admin/Auth -->
                <?php if (is_logged_in()): ?>
                <div class="user-menu">
                    <button class="user-btn" id="user-toggle">
                        <i class="fas fa-user-circle"></i>
                        <span><?php echo e(get_current_user($pdo)['name'] ?? 'Admin'); ?></span>
                    </button>
                    <div class="user-dropdown" id="user-dropdown">
                        <a href="<?php echo SITE_URL; ?>/admin/dashboard.php" class="dropdown-item">
                            <i class="fas fa-th-large"></i> <?php echo $language['admin_dashboard']; ?>
                        </a>
                        <a href="<?php echo SITE_URL; ?>/admin/profile.php" class="dropdown-item">
                            <i class="fas fa-user"></i> <?php echo 'Profile'; ?>
                        </a>
                        <hr class="dropdown-divider">
                        <a href="<?php echo SITE_URL; ?>/admin/logout.php" class="dropdown-item text-danger">
                            <i class="fas fa-sign-out-alt"></i> <?php echo $language['nav_logout']; ?>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="<?php echo SITE_URL; ?>/admin/login.php" class="btn btn-outline">
                    <i class="fas fa-lock"></i>
                    <?php echo $language['nav_login']; ?>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobile-menu-overlay"></div>

<script>
// Navbar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    const langToggle = document.getElementById('lang-toggle');
    const langMenu = document.getElementById('lang-menu');
    const userToggle = document.getElementById('user-toggle');
    const userDropdown = document.getElementById('user-dropdown');
    
    // Toggle mobile menu
    if (navbarToggle) {
        navbarToggle.addEventListener('click', function() {
            navbarMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            this.classList.toggle('active');
        });
    }
    
    // Close mobile menu when overlay is clicked
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', function() {
            navbarMenu.classList.remove('active');
            mobileOverlay.classList.remove('active');
            navbarToggle.classList.remove('active');
        });
    }
    
    // Close mobile menu when nav link is clicked
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function() {
            navbarMenu.classList.remove('active');
            mobileOverlay.classList.remove('active');
            navbarToggle.classList.remove('active');
        });
    });
    
    // Language switcher toggle
    if (langToggle) {
        langToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            langMenu.classList.toggle('show');
            if (userDropdown) userDropdown.classList.remove('show');
        });
    }
    
    // User menu toggle
    if (userToggle) {
        userToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            userDropdown.classList.toggle('show');
            if (langMenu) langMenu.classList.remove('show');
        });
    }
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function() {
        if (langMenu) langMenu.classList.remove('show');
        if (userDropdown) userDropdown.classList.remove('show');
    });
});
</script>
