// DOM Elements
const mobileMenuToggle = document.querySelector('.mobile-menu-toggle');
const mobileMenu = document.querySelector('.mobile-menu');
const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');
const navLinks = document.querySelectorAll('.nav-link, .mobile-nav-link');
const contactForm = document.getElementById('contactForm');
const allCtaButtons = document.querySelectorAll('a[href="#contact"], .btn[href="#contact"]');

// Hero image elements
const heroImage1 = document.getElementById('heroImage1');
const heroImage2 = document.getElementById('heroImage2');

// Hero image alternating functionality
let currentImage = 1;
let imageInterval;
let isImageTransitioning = false;

function startImageAlternation() {
    if (heroImage1 && heroImage2) {
        // Preload images for smoother transitions
        preloadImages();
        
        imageInterval = setInterval(() => {
            if (!isImageTransitioning) {
                switchImage();
            }
        }, 4000); // Change image every 4 seconds
    }
}

function preloadImages() {
    const img1 = new Image();
    const img2 = new Image();
    img1.src = heroImage1.src;
    img2.src = heroImage2.src;
}

function switchImage() {
    if (isImageTransitioning) return;
    
    isImageTransitioning = true;
    
    if (currentImage === 1) {
        heroImage1.style.opacity = '0';
        setTimeout(() => {
            heroImage1.style.display = 'none';
            heroImage2.style.display = 'block';
            heroImage2.style.opacity = '1';
            currentImage = 2;
            isImageTransitioning = false;
        }, 300);
    } else {
        heroImage2.style.opacity = '0';
        setTimeout(() => {
            heroImage2.style.display = 'none';
            heroImage1.style.display = 'block';
            heroImage1.style.opacity = '1';
            currentImage = 1;
            isImageTransitioning = false;
        }, 300);
    }
}

function stopImageAlternation() {
    if (imageInterval) {
        clearInterval(imageInterval);
    }
}

// Mobile Menu Toggle
function toggleMobileMenu() {
    if (mobileMenu) {
        mobileMenu.classList.toggle('hidden');
        document.body.style.overflow = mobileMenu.classList.contains('hidden') ? 'auto' : 'hidden';
    }
}

// Close mobile menu when clicking outside
function closeMobileMenu(e) {
    if (e.target === mobileMenu) {
        mobileMenu.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Smooth scrolling for navigation links
function smoothScroll(e) {
    e.preventDefault();
    const targetId = e.target.getAttribute('href');
    
    if (targetId && targetId.startsWith('#')) {
        const targetElement = document.querySelector(targetId);
        if (targetElement) {
            const headerHeight = document.querySelector('.header').offsetHeight;
            const targetPosition = targetElement.offsetTop - headerHeight - 20;
            
            window.scrollTo({
                top: targetPosition,
                behavior: 'smooth'
            });
            
            // Close mobile menu if open
            if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }
    }
}

// Form submission handler
function handleFormSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(contactForm);
    const name = formData.get('name');
    const email = formData.get('email');
    const phone = formData.get('phone');
    const message = formData.get('message');
    
    // Basic form validation
    if (!name || !email || !message) {
        showNotification('Please fill in all required fields.', 'error');
        return;
    }
    
    if (!isValidEmail(email)) {
        showNotification('Please enter a valid email address.', 'error');
        return;
    }
    
    // Simulate form submission with better loading state
    const submitButton = contactForm.querySelector('button[type="submit"]');
    const originalText = submitButton.textContent;
    const originalHTML = submitButton.innerHTML;
    
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
    submitButton.disabled = true;
    
    // Add loading class for styling
    submitButton.classList.add('btn--loading');
    
    setTimeout(() => {
        submitButton.innerHTML = originalHTML;
        submitButton.disabled = false;
        submitButton.classList.remove('btn--loading');
        contactForm.reset();
        showNotification('Thank you for your message! We\'ll get back to you soon.', 'success');
    }, 1500);
}

// Email validation helper
function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

// Show notification
function showNotification(message, type = 'info') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification notification--${type}`;
    
    // Determine colors based on type
    let backgroundColor, textColor;
    switch(type) {
        case 'success':
            backgroundColor = 'var(--color-success)';
            textColor = 'var(--color-btn-primary-text)';
            break;
        case 'error':
            backgroundColor = 'var(--color-error)';
            textColor = 'var(--color-btn-primary-text)';
            break;
        default:
            backgroundColor = 'var(--color-info)';
            textColor = 'var(--color-btn-primary-text)';
    }
    
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close" onclick="this.parentElement.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        </div>
    `;
    
    // Add notification styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${backgroundColor};
        color: ${textColor};
        padding: 16px;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 2000;
        max-width: 400px;
        animation: slideIn 0.3s ease-out;
        font-size: 14px;
        font-weight: 500;
    `;
    
    const notificationContent = notification.querySelector('.notification-content');
    notificationContent.style.cssText = `
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    `;
    
    const closeButton = notification.querySelector('.notification-close');
    closeButton.style.cssText = `
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        padding: 4px;
        border-radius: 4px;
        transition: background-color 0.15s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
    `;
    
    closeButton.addEventListener('mouseenter', () => {
        closeButton.style.backgroundColor = 'rgba(255, 255, 255, 0.2)';
    });
    
    closeButton.addEventListener('mouseleave', () => {
        closeButton.style.backgroundColor = 'transparent';
    });
    
    // Add animation styles if not already present
    if (!document.querySelector('#notification-animations')) {
        const style = document.createElement('style');
        style.id = 'notification-animations';
        style.textContent = `
            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentElement) {
            notification.style.animation = 'slideOut 0.3s ease-out forwards';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// Header scroll effect
function handleHeaderScroll() {
    const header = document.querySelector('.header');
    if (header) {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > 50) {
            header.style.boxShadow = '0 4px 20px rgba(0,0,0,0.1)';
            header.style.backdropFilter = 'blur(8px)';
        } else {
            header.style.boxShadow = '0 1px 3px rgba(0,0,0,0.04), 0 1px 2px rgba(0,0,0,0.02)';
            header.style.backdropFilter = 'none';
        }
    }
}

// Intersection Observer for animations
function setupScrollAnimations() {
    const animatedElements = document.querySelectorAll('.step-card, .feature-card, .testimonial-card');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = '1';
                entry.target.style.transform = 'translateY(0)';
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    });
    
    animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(20px)';
        element.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(element);
    });
}

// Initialize scroll-triggered counter animation
function animateCounters() {
    const trustIndicators = document.querySelectorAll('.trust-item');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Simple animation for trust indicators
                element.style.opacity = '0';
                element.style.transform = 'scale(0.8)';
                
                setTimeout(() => {
                    element.style.transition = 'all 0.5s ease-out';
                    element.style.opacity = '1';
                    element.style.transform = 'scale(1)';
                }, 200);
                
                observer.unobserve(element);
            }
        });
    }, {
        threshold: 0.5
    });
    
    trustIndicators.forEach(indicator => {
        observer.observe(indicator);
    });
}

// Handle keyboard navigation
function handleKeyboardNavigation(e) {
    if (e.key === 'Escape') {
        if (mobileMenu && !mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    }
}

// Setup CTA button handlers
function setupCtaButtons() {
    // Handle all CTA buttons that should scroll to contact section
    const ctaButtons = document.querySelectorAll('a[href="#contact"]');
    ctaButtons.forEach(button => {
        button.addEventListener('click', smoothScroll);
    });
    
    // Handle "Learn More" button to scroll to services
    const learnMoreButtons = document.querySelectorAll('a[href="#services"]');
    learnMoreButtons.forEach(button => {
        button.addEventListener('click', smoothScroll);
    });
}

// Initialize all functionality
function init() {
    // Event listeners
    if (mobileMenuToggle) {
        mobileMenuToggle.addEventListener('click', toggleMobileMenu);
    }
    
    if (mobileMenu) {
        mobileMenu.addEventListener('click', closeMobileMenu);
    }
    
    // Navigation links
    navLinks.forEach(link => {
        link.addEventListener('click', smoothScroll);
    });
    
    // Setup CTA buttons
    setupCtaButtons();
    
    // Contact form
    if (contactForm) {
        contactForm.addEventListener('submit', handleFormSubmit);
    }
    
    // Scroll effects
    window.addEventListener('scroll', handleHeaderScroll);
    
    // Keyboard navigation
    document.addEventListener('keydown', handleKeyboardNavigation);
    
    // Initialize animations
    setupScrollAnimations();
    animateCounters();
    
    // Start hero image alternation
    startImageAlternation();
    
    // Focus management for accessibility
    const focusableElements = document.querySelectorAll(
        'a, button, input, textarea, select, [tabindex]:not([tabindex="-1"])'
    );
    
    focusableElements.forEach(element => {
        element.addEventListener('focus', function() {
            this.style.outline = '2px solid var(--color-primary)';
            this.style.outlineOffset = '2px';
        });
        
        element.addEventListener('blur', function() {
            this.style.outline = '';
            this.style.outlineOffset = '';
        });
    });
    
    console.log('Latino Laundry App initialized successfully');
}

// Run initialization when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
} else {
    init();
}

// Handle resize events
window.addEventListener('resize', () => {
    // Close mobile menu on resize to larger screen
    if (window.innerWidth > 768 && mobileMenu && !mobileMenu.classList.contains('hidden')) {
        mobileMenu.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
});

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    stopImageAlternation();
});

// Export functions for potential external use
window.LatinoLaundryApp = {
    toggleMobileMenu,
    showNotification,
    smoothScroll,
    init
};


