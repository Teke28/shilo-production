/* ========================================
   Shilo Production - Main JavaScript
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
});

/**
 * Initialize application
 */
function initializeApp() {
    initializeNavbar();
    initializeBackToTop();
    initializeFormValidation();
    initializeLazyLoad();
    initializeAccordion();
    initializeLightbox();
    initializeAOS();
    initializeScrollSpy();
}

/* ========================================
   NAVBAR FUNCTIONALITY
   ======================================== */

function initializeNavbar() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    const navLinks = document.querySelectorAll('.nav-link');
    
    if (!navbarToggle) return;
    
    // Toggle menu on button click
    navbarToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        toggleMenu();
    });
    
    // Close menu on overlay click
    if (mobileOverlay) {
        mobileOverlay.addEventListener('click', closeMenu);
    }
    
    // Close menu when nav link is clicked
    navLinks.forEach(link => {
        link.addEventListener('click', closeMenu);
    });
    
    // Close menu when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.navbar-wrapper')) {
            closeMenu();
        }
    });
    
    // Highlight current page
    highlightCurrentPage();
}

function toggleMenu() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    
    navbarMenu.classList.toggle('active');
    mobileOverlay.classList.toggle('active');
    navbarToggle.classList.toggle('active');
}

function closeMenu() {
    const navbarToggle = document.getElementById('navbar-toggle');
    const navbarMenu = document.getElementById('navbar-menu');
    const mobileOverlay = document.getElementById('mobile-menu-overlay');
    
    navbarMenu.classList.remove('active');
    mobileOverlay.classList.remove('active');
    navbarToggle.classList.remove('active');
}

function highlightCurrentPage() {
    const currentPage = getCurrentPage();
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        const href = link.getAttribute('href');
        if (href.includes(currentPage)) {
            link.classList.add('active');
        } else {
            link.classList.remove('active');
        }
    });
}

function getCurrentPage() {
    const path = window.location.pathname;
    return path.split('/').pop() || 'index.php';
}

/* ========================================
   BACK TO TOP BUTTON
   ======================================== */

function initializeBackToTop() {
    const backToTopBtn = document.getElementById('back-to-top');
    
    if (!backToTopBtn) return;
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopBtn.classList.add('show');
        } else {
            backToTopBtn.classList.remove('show');
        }
    });
    
    backToTopBtn.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/* ========================================
   FORM VALIDATION
   ======================================== */

function initializeFormValidation() {
    const forms = document.querySelectorAll('form[data-validate]');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(this)) {
                e.preventDefault();
            }
        });
    });
}

function validateForm(form) {
    let isValid = true;
    const inputs = form.querySelectorAll('[required]');
    
    inputs.forEach(input => {
        if (!validateInput(input)) {
            isValid = false;
            showError(input);
        } else {
            clearError(input);
        }
    });
    
    return isValid;
}

function validateInput(input) {
    const value = input.value.trim();
    
    if (input.hasAttribute('required') && !value) {
        return false;
    }
    
    if (input.type === 'email' && value && !isValidEmail(value)) {
        return false;
    }
    
    if (input.type === 'tel' && value && !isValidPhone(value)) {
        return false;
    }
    
    if (input.hasAttribute('minlength') && value.length < input.getAttribute('minlength')) {
        return false;
    }
    
    if (input.hasAttribute('maxlength') && value.length > input.getAttribute('maxlength')) {
        return false;
    }
    
    return true;
}

function showError(input) {
    input.classList.add('is-invalid');
    let error = input.parentElement.querySelector('.form-error');
    
    if (!error) {
        error = document.createElement('div');
        error.className = 'form-error';
        input.parentElement.appendChild(error);
    }
    
    if (input.hasAttribute('required') && !input.value.trim()) {
        error.textContent = 'This field is required';
    } else if (input.type === 'email') {
        error.textContent = 'Please enter a valid email address';
    } else if (input.type === 'tel') {
        error.textContent = 'Please enter a valid phone number';
    } else {
        error.textContent = 'Invalid input';
    }
}

function clearError(input) {
    input.classList.remove('is-invalid');
    const error = input.parentElement.querySelector('.form-error');
    if (error) {
        error.remove();
    }
}

function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function isValidPhone(phone) {
    const re = /^[0-9\s\-\+\(\)]+$/;
    return re.test(phone) && phone.replace(/[^0-9]/g, '').length >= 7;
}

/* ========================================
   LAZY LOADING
   ======================================== */

function initializeLazyLoad() {
    if ('IntersectionObserver' in window) {
        const images = document.querySelectorAll('img[data-src]');
        
        const imageObserver = new IntersectionObserver((entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    img.src = img.getAttribute('data-src');
                    img.removeAttribute('data-src');
                    observer.unobserve(img);
                }
            });
        });
        
        images.forEach(img => imageObserver.observe(img));
    }
}

/* ========================================
   ACCORDION
   ======================================== */

function initializeAccordion() {
    const accordionItems = document.querySelectorAll('[data-accordion-item]');
    
    accordionItems.forEach(item => {
        const trigger = item.querySelector('[data-accordion-trigger]');
        const content = item.querySelector('[data-accordion-content]');
        
        if (trigger && content) {
            trigger.addEventListener('click', function() {
                toggleAccordionItem(item, content);
            });
        }
    });
}

function toggleAccordionItem(item, content) {
    const isOpen = item.classList.contains('active');
    
    if (isOpen) {
        closeAccordionItem(item, content);
    } else {
        // Close other items in same accordion
        const accordion = item.parentElement;
        const otherItems = accordion.querySelectorAll('[data-accordion-item].active');
        
        otherItems.forEach(otherItem => {
            const otherContent = otherItem.querySelector('[data-accordion-content]');
            closeAccordionItem(otherItem, otherContent);
        });
        
        openAccordionItem(item, content);
    }
}

function openAccordionItem(item, content) {
    item.classList.add('active');
    content.style.maxHeight = content.scrollHeight + 'px';
    content.style.opacity = '1';
}

function closeAccordionItem(item, content) {
    item.classList.remove('active');
    content.style.maxHeight = '0';
    content.style.opacity = '0';
}

/* ========================================
   LIGHTBOX / MODAL
   ======================================== */

function initializeLightbox() {
    const lightboxItems = document.querySelectorAll('[data-lightbox]');
    
    lightboxItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();
            openLightbox(this.getAttribute('href'), this.getAttribute('data-lightbox-title'));
        });
    });
}

function openLightbox(src, title) {
    const lightbox = document.createElement('div');
    lightbox.className = 'lightbox';
    lightbox.innerHTML = `
        <div class="lightbox-content">
            <button class="lightbox-close">&times;</button>
            ${src.endsWith('.mp4') || src.endsWith('.webm') ? 
                `<video controls><source src="${src}" type="video/mp4"></video>` :
                `<img src="${src}" alt="${title || 'Lightbox'}">`
            }
            ${title ? `<p class="lightbox-title">${title}</p>` : ''}
        </div>
        <div class="lightbox-overlay"></div>
    `;
    
    document.body.appendChild(lightbox);
    
    lightbox.querySelector('.lightbox-close').addEventListener('click', function() {
        lightbox.remove();
    });
    
    lightbox.querySelector('.lightbox-overlay').addEventListener('click', function() {
        lightbox.remove();
    });
}

/* ========================================
   ANIMATE ON SCROLL (AOS)
   ======================================== */

function initializeAOS() {
    if ('IntersectionObserver' in window) {
        const elements = document.querySelectorAll('[data-aos]');
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const animation = entry.target.getAttribute('data-aos');
                    const delay = entry.target.getAttribute('data-aos-delay') || '0';
                    
                    entry.target.style.animationDelay = delay + 'ms';
                    entry.target.classList.add('aos-animate', animation);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        elements.forEach(el => observer.observe(el));
    }
}

/* ========================================
   SCROLL SPY
   ======================================== */

function initializeScrollSpy() {
    const sections = document.querySelectorAll('[data-scroll-section]');
    const navLinks = document.querySelectorAll('[data-scroll-link]');
    
    if (sections.length === 0 || navLinks.length === 0) return;
    
    window.addEventListener('scroll', function() {
        let currentSection = '';
        
        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            
            if (scrollY >= (sectionTop - 200)) {
                currentSection = section.getAttribute('data-scroll-section');
            }
        });
        
        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('data-scroll-link') === currentSection) {
                link.classList.add('active');
            }
        });
    });
}

/* ========================================
   LANGUAGE SWITCHER
   ======================================== */

function initializeLanguageSwitcher() {
    const langToggle = document.getElementById('lang-toggle');
    const langMenu = document.getElementById('lang-menu');
    
    if (!langToggle) return;
    
    langToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        langMenu.classList.toggle('show');
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.language-switcher')) {
            langMenu.classList.remove('show');
        }
    });
}

/* ========================================
   USER MENU
   ======================================== */

function initializeUserMenu() {
    const userToggle = document.getElementById('user-toggle');
    const userDropdown = document.getElementById('user-dropdown');
    
    if (!userToggle) return;
    
    userToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        userDropdown.classList.toggle('show');
    });
    
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-menu')) {
            userDropdown.classList.remove('show');
        }
    });
}

/* ========================================
   TOAST NOTIFICATIONS
   ======================================== */

function showToast(message, type = 'info', duration = 3000) {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: ${getToastColor(type)};
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 4px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 9999;
        animation: slideIn 0.3s ease-in-out;
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-in-out';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

function getToastColor(type) {
    const colors = {
        success: '#28a745',
        danger: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };
    return colors[type] || colors.info;
}

/* ========================================
   UTILITY FUNCTIONS
   ======================================== */

/**
 * Debounce function to limit function calls
 */
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

/**
 * Throttle function to limit function calls
 */
function throttle(func, limit) {
    let inThrottle;
    return function(...args) {
        if (!inThrottle) {
            func.apply(this, args);
            inThrottle = true;
            setTimeout(() => inThrottle = false, limit);
        }
    };
}

/**
 * Get element by ID
 */
function getElement(id) {
    return document.getElementById(id);
}

/**
 * Get all elements by class
 */
function getElements(className) {
    return document.querySelectorAll('.' + className);
}

/**
 * Add event listener to multiple elements
 */
function addEventListeners(elements, event, callback) {
    elements.forEach(element => {
        element.addEventListener(event, callback);
    });
}

/**
 * Remove class from element
 */
function removeClass(element, className) {
    if (element) {
        element.classList.remove(className);
    }
}

/**
 * Add class to element
 */
function addClass(element, className) {
    if (element) {
        element.classList.add(className);
    }
}

/**
 * Toggle class on element
 */
function toggleClass(element, className) {
    if (element) {
        element.classList.toggle(className);
    }
}

/**
 * Check if element has class
 */
function hasClass(element, className) {
    if (element) {
        return element.classList.contains(className);
    }
    return false;
}

/* ========================================
   KEYBOARD SHORTCUTS
   ======================================== */

document.addEventListener('keydown', function(e) {
    // Close menu on Escape
    if (e.key === 'Escape') {
        closeMenu();
        const langMenu = document.getElementById('lang-menu');
        if (langMenu) langMenu.classList.remove('show');
        const userDropdown = document.getElementById('user-dropdown');
        if (userDropdown) userDropdown.classList.remove('show');
    }
    
    // Jump to top on Ctrl+Home or Cmd+Home
    if ((e.ctrlKey || e.metaKey) && e.key === 'Home') {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
});

/* ========================================
   EXPORT FUNCTIONS FOR GLOBAL USE
   ======================================== */

window.ShiloApp = {
    showToast,
    validateForm,
    openLightbox,
    toggleMenu,
    closeMenu,
    debounce,
    throttle,
    addClass,
    removeClass,
    toggleClass,
    hasClass
};
