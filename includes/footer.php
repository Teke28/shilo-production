<?php
/**
 * Shilo Production - Footer Include
 */
?>
<footer class="footer">
    <div class="footer-content">
        <div class="container">
            <div class="footer-grid">
                <!-- About Section -->
                <div class="footer-section">
                    <div class="footer-logo">
                        <?php if ($logo): ?>
                        <img src="<?php echo SITE_URL . '/uploads/logo/' . e($logo); ?>" alt="<?php echo e($company_name); ?>" class="footer-logo-img">
                        <?php else: ?>
                        <h3 class="footer-logo-text"><?php echo e($company_name); ?></h3>
                        <?php endif; ?>
                    </div>
                    <p class="footer-description">
                        <?php echo nl2br(e(get_setting($pdo, 'about_text', 'Professional media production company.'))); ?>
                    </p>
                    <div class="social-links">
                        <?php if ($facebook): ?>
                        <a href="<?php echo e($facebook); ?>" target="_blank" rel="noopener" class="social-link" title="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($instagram): ?>
                        <a href="<?php echo e($instagram); ?>" target="_blank" rel="noopener" class="social-link" title="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($youtube): ?>
                        <a href="<?php echo e($youtube); ?>" target="_blank" rel="noopener" class="social-link" title="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($tiktok): ?>
                        <a href="<?php echo e($tiktok); ?>" target="_blank" rel="noopener" class="social-link" title="TikTok">
                            <i class="fab fa-tiktok"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ($telegram): ?>
                        <a href="<?php echo e($telegram); ?>" target="_blank" rel="noopener" class="social-link" title="Telegram">
                            <i class="fab fa-telegram"></i>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Quick Links -->
                <div class="footer-section">
                    <h4 class="footer-title"><?php echo $language['footer_quick_links']; ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo SITE_URL; ?>"><?php echo $language['nav_home']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/about.php"><?php echo $language['nav_about']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/services.php"><?php echo $language['nav_services']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/portfolio.php"><?php echo $language['nav_portfolio']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/blog.php"><?php echo $language['nav_blog']; ?></a></li>
                    </ul>
                </div>
                
                <!-- Services -->
                <div class="footer-section">
                    <h4 class="footer-title"><?php echo $language['footer_services']; ?></h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo SITE_URL; ?>/pages/services.php#photography"><?php echo $language['footer_photography']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/services.php#videography"><?php echo $language['footer_videography']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/services.php#production"><?php echo $language['footer_production']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/services.php#creative"><?php echo $language['footer_creative']; ?></a></li>
                        <li><a href="<?php echo SITE_URL; ?>/pages/booking.php"><?php echo $language['nav_book']; ?></a></li>
                    </ul>
                </div>
                
                <!-- Contact Info -->
                <div class="footer-section">
                    <h4 class="footer-title"><?php echo $language['footer_contact']; ?></h4>
                    <div class="contact-info">
                        <?php if ($phone): ?>
                        <div class="contact-item">
                            <i class="fas fa-phone"></i>
                            <div>
                                <p class="contact-label"><?php echo $language['contact_phone_label']; ?></p>
                                <a href="tel:<?php echo preg_replace('/[^0-9+]/', '', $phone); ?>">
                                    <?php echo e($phone); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($email): ?>
                        <div class="contact-item">
                            <i class="fas fa-envelope"></i>
                            <div>
                                <p class="contact-label"><?php echo $language['contact_email_label']; ?></p>
                                <a href="mailto:<?php echo e($email); ?>">
                                    <?php echo e($email); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php $address = get_setting($pdo, 'address', ''); ?>
                        <?php if ($address): ?>
                        <div class="contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <div>
                                <p class="contact-label"><?php echo $language['contact_address']; ?></p>
                                <p><?php echo nl2br(e($address)); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <?php $working_hours = get_setting($pdo, 'working_hours', ''); ?>
                        <?php if ($working_hours): ?>
                        <div class="contact-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <p class="contact-label"><?php echo $language['contact_hours']; ?></p>
                                <p><?php echo nl2br(e($working_hours)); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-content">
                <div class="footer-copyright">
                    <p><?php echo e(get_setting($pdo, 'footer_text', $language['footer_copyright'])); ?></p>
                </div>
                <div class="footer-links-bottom">
                    <a href="<?php echo SITE_URL; ?>/pages/privacy.php">Privacy Policy</a>
                    <span class="separator">|</span>
                    <a href="<?php echo SITE_URL; ?>/pages/terms.php">Terms & Conditions</a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Back to Top Button -->
    <button class="back-to-top" id="back-to-top" aria-label="Back to top">
        <i class="fas fa-arrow-up"></i>
    </button>
</footer>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo SITE_URL; ?>/assets/js/main.js"></script>

<?php if (isset($extra_js)): ?>
<?php echo $extra_js; ?>
<?php endif; ?>

</body>
</html>
