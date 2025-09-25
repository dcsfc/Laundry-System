/**
 * Modern Table Headers - Interactive Enhancements
 * Provides smooth interactions and accessibility features for table headers
 */

class ModernTableHeaders {
    constructor() {
        this.init();
    }

    init() {
        this.setupSortButtons();
        this.setupKeyboardNavigation();
        this.setupAccessibility();
        this.setupThemeDetection();
    }

    /**
     * Setup sort button interactions
     */
    setupSortButtons() {
        document.addEventListener('click', (e) => {
            const sortButton = e.target.closest('.sort-button');
            if (!sortButton) return;

            e.preventDefault();
            
            // Add visual feedback
            this.addSortFeedback(sortButton);
            
            // Dispatch custom event for data table integration
            const columnKey = sortButton.getAttribute('data-column') || 
                            sortButton.closest('th').getAttribute('data-column');
            
            if (columnKey) {
                sortButton.dispatchEvent(new CustomEvent('sort', {
                    detail: { column: columnKey },
                    bubbles: true
                }));
            }
        });
    }

    /**
     * Add visual feedback for sort interactions
     */
    addSortFeedback(button) {
        // Remove active state from siblings
        const headerRow = button.closest('tr');
        const otherButtons = headerRow.querySelectorAll('.sort-button');
        otherButtons.forEach(btn => btn.classList.remove('active'));
        
        // Add active state to clicked button
        button.classList.add('active');
        
        // Add ripple effect
        this.createRippleEffect(button);
    }

    /**
     * Create ripple effect for button clicks
     */
    createRippleEffect(button) {
        const ripple = document.createElement('div');
        ripple.style.cssText = `
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.3);
            transform: scale(0);
            animation: ripple 0.6s linear;
            pointer-events: none;
        `;
        
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = (rect.width / 2 - size / 2) + 'px';
        ripple.style.top = (rect.height / 2 - size / 2) + 'px';
        
        button.style.position = 'relative';
        button.style.overflow = 'hidden';
        button.appendChild(ripple);
        
        setTimeout(() => {
            ripple.remove();
        }, 600);
    }

    /**
     * Setup keyboard navigation
     */
    setupKeyboardNavigation() {
        document.addEventListener('keydown', (e) => {
            const sortButton = document.activeElement.closest('.sort-button');
            if (!sortButton) return;

            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                sortButton.click();
            }
        });
    }

    /**
     * Setup accessibility features
     */
    setupAccessibility() {
        // Add ARIA labels to sort buttons
        document.querySelectorAll('.sort-button').forEach(button => {
            if (!button.getAttribute('aria-label')) {
                const headerText = button.closest('th').querySelector('.header-text')?.textContent;
                if (headerText) {
                    button.setAttribute('aria-label', `Sort by ${headerText}`);
                }
            }
        });

        // Add role attributes
        document.querySelectorAll('.modern-table-header').forEach(header => {
            if (!header.getAttribute('role')) {
                header.setAttribute('role', 'rowgroup');
            }
        });

        // Add sort direction indicators
        document.querySelectorAll('.sort-button').forEach(button => {
            const upIcon = button.querySelector('.sort-icon-up');
            const downIcon = button.querySelector('.sort-icon-down');
            
            if (upIcon && !upIcon.getAttribute('aria-hidden')) {
                upIcon.setAttribute('aria-hidden', 'true');
            }
            if (downIcon && !downIcon.getAttribute('aria-hidden')) {
                downIcon.setAttribute('aria-hidden', 'true');
            }
        });
    }

    /**
     * Setup theme detection and switching
     */
    setupThemeDetection() {
        // Detect system theme preference
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
        
        const updateTheme = () => {
            document.documentElement.setAttribute('data-theme', prefersDark.matches ? 'dark' : 'light');
        };

        updateTheme();
        prefersDark.addEventListener('change', updateTheme);

        // Add theme toggle functionality
        this.addThemeToggle();
    }

    /**
     * Add theme toggle button (optional)
     */
    addThemeToggle() {
        // Only add if no existing theme toggle is found
        if (document.querySelector('[data-theme-toggle]')) return;

        const toggle = document.createElement('button');
        toggle.setAttribute('data-theme-toggle', '');
        toggle.innerHTML = `
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
            </svg>
        `;
        toggle.className = 'fixed top-4 right-4 p-2 bg-slate-800 text-slate-300 rounded-lg hover:bg-slate-700 transition-colors z-50';
        toggle.setAttribute('aria-label', 'Toggle theme');
        
        toggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
        });

        // Check for saved theme preference
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme) {
            document.documentElement.setAttribute('data-theme', savedTheme);
        }

        document.body.appendChild(toggle);
    }

    /**
     * Update sort direction indicator
     */
    updateSortDirection(columnKey, direction) {
        const headerRow = document.querySelector('.modern-table-header tr');
        if (!headerRow) return;

        const targetButton = headerRow.querySelector(`[data-column="${columnKey}"]`);
        if (!targetButton) return;

        // Remove active state from all buttons
        headerRow.querySelectorAll('.sort-button').forEach(btn => {
            btn.classList.remove('active');
            btn.querySelectorAll('.sort-icon').forEach(icon => {
                icon.classList.remove('active');
            });
        });

        // Add active state to target button
        targetButton.classList.add('active');
        
        // Update icon states
        const upIcon = targetButton.querySelector('.sort-icon-up');
        const downIcon = targetButton.querySelector('.sort-icon-down');
        
        if (direction === 'asc' && upIcon) {
            upIcon.classList.add('active');
        } else if (direction === 'desc' && downIcon) {
            downIcon.classList.add('active');
        }
    }

    /**
     * Add loading state to headers
     */
    setLoadingState(isLoading) {
        const headers = document.querySelectorAll('.modern-table-header th');
        headers.forEach(header => {
            if (isLoading) {
                header.classList.add('loading');
            } else {
                header.classList.remove('loading');
            }
        });
    }

    /**
     * Add error state to headers
     */
    setErrorState(hasError) {
        const headers = document.querySelectorAll('.modern-table-header');
        headers.forEach(header => {
            if (hasError) {
                header.classList.add('error');
            } else {
                header.classList.remove('error');
            }
        });
    }
}

// CSS for ripple animation
const rippleCSS = `
@keyframes ripple {
    to {
        transform: scale(4);
        opacity: 0;
    }
}

.modern-table-header th.loading {
    position: relative;
    overflow: hidden;
}

.modern-table-header th.loading::after {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
    animation: shimmer 1.5s infinite;
}

@keyframes shimmer {
    0% { left: -100%; }
    100% { left: 100%; }
}

.modern-table-header.error {
    border-color: var(--accent-error) !important;
    box-shadow: 0 0 0 1px var(--accent-error) !important;
}
`;

// Inject CSS
const style = document.createElement('style');
style.textContent = rippleCSS;
document.head.appendChild(style);

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => {
        new ModernTableHeaders();
    });
} else {
    new ModernTableHeaders();
}

// Export for module usage
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernTableHeaders;
}
