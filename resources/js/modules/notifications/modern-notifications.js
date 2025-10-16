/**
 * Modern SaaS-Style Notification System
 * Professional toast/snackbar component for enterprise dashboards
 * Inspired by Linear, Vercel, and Notion design patterns
 */

class ModernNotificationSystem {
    constructor() {
        this.notifications = new Map();
        this.container = null;
        this.maxNotifications = 5;
        this.defaultDuration = 4000;
        this.animationDuration = 300;
        this.init();
    }

    init() {
        this.createContainer();
        this.addStyles();
    }

    createContainer() {
        // Remove existing container if it exists
        const existing = document.getElementById('modern-notification-container');
        if (existing) {
            existing.remove();
        }

        this.container = document.createElement('div');
        this.container.id = 'modern-notification-container';
        this.container.className = `
            fixed top-4 right-4 z-[9999] 
            flex flex-col gap-3 
            max-w-sm w-full
            pointer-events-none
        `;
        
        document.body.appendChild(this.container);
    }

    addStyles() {
        if (document.getElementById('modern-notification-styles')) return;

        const style = document.createElement('style');
        style.id = 'modern-notification-styles';
        style.textContent = `
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(100%) scale(0.95);
                }
                to {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
            }

            @keyframes slideOutRight {
                from {
                    opacity: 1;
                    transform: translateX(0) scale(1);
                }
                to {
                    opacity: 0;
                    transform: translateX(100%) scale(0.95);
                }
            }

            @keyframes progressShrink {
                from { width: 100%; }
                to { width: 0%; }
            }

            .notification-enter {
                animation: slideInRight ${this.animationDuration}ms cubic-bezier(0.16, 1, 0.3, 1);
            }

            .notification-exit {
                animation: slideOutRight ${this.animationDuration}ms cubic-bezier(0.16, 1, 0.3, 1);
            }

            .notification-progress {
                animation: progressShrink var(--duration) linear;
            }

            /* Dark mode support */
            @media (prefers-color-scheme: dark) {
                .notification-card {
                    background: rgba(17, 24, 39, 0.95);
                    border: 1px solid rgba(55, 65, 81, 0.3);
                    backdrop-filter: blur(12px);
                }
                
                .notification-title {
                    color: rgb(249, 250, 251);
                }
                
                .notification-message {
                    color: rgb(209, 213, 219);
                }
                
                .notification-close {
                    color: rgb(156, 163, 175);
                }
                
                .notification-close:hover {
                    color: rgb(229, 231, 235);
                    background: rgba(55, 65, 81, 0.5);
                }
            }
        `;
        document.head.appendChild(style);
    }

    show(type, message, options = {}) {
        const {
            title = this.getDefaultTitle(type),
            duration = this.getDefaultDuration(type),
            position = 'top-right',
            showProgress = true,
            persistent = false,
            action = null
        } = options;

        const id = this.generateId();
        const notification = this.createNotification(id, type, title, message, showProgress, action);
        
        this.notifications.set(id, {
            element: notification,
            duration: persistent ? null : duration,
            showProgress,
            timer: null
        });

        this.container.appendChild(notification);
        this.animateIn(notification);

        // Auto-dismiss if not persistent
        if (!persistent && duration > 0) {
            const timer = setTimeout(() => {
                this.dismiss(id);
            }, duration);
            
            this.notifications.get(id).timer = timer;
        }

        // Limit number of notifications
        this.limitNotifications();

        return id;
    }

    createNotification(id, type, title, message, showProgress, action) {
        const notification = document.createElement('div');
        notification.id = `notification-${id}`;
        notification.className = `
            notification-card
            bg-white/95 backdrop-blur-xl
            border border-gray-200/50
            rounded-xl shadow-lg shadow-black/5
            p-4
            pointer-events-auto
            relative overflow-hidden
            transition-all duration-200
            hover:shadow-xl hover:shadow-black/10
            hover:scale-[1.02]
        `;

        const icon = this.getIcon(type);
        const colors = this.getTypeColors(type);

        notification.innerHTML = `
            <div class="flex items-start gap-3">
                <!-- Icon -->
                <div class="flex-shrink-0 mt-0.5">
                    <div class="w-5 h-5 ${colors.icon}">
                        ${icon}
                    </div>
                </div>

                <!-- Content -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1">
                            <h4 class="notification-title text-sm font-semibold text-gray-900 mb-1">
                                ${title}
                            </h4>
                            <p class="notification-message text-sm text-gray-600 leading-relaxed">
                                ${message}
                            </p>
                        </div>
                        
                        <!-- Close Button -->
                        <button 
                            class="notification-close
                                flex-shrink-0
                                w-6 h-6 rounded-md
                                flex items-center justify-center
                                transition-colors duration-150
                                hover:bg-gray-100
                                focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-1
                            "
                            onclick="window.modernNotifications.dismiss('${id}')"
                            aria-label="Close notification"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            ${showProgress ? `
                <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-gray-200/50 overflow-hidden">
                    <div 
                        class="notification-progress h-full ${colors.progress}"
                        style="--duration: ${this.getDefaultDuration(type)}ms;"
                    ></div>
                </div>
            ` : ''}
        `;

        return notification;
    }

    getIcon(type) {
        const icons = {
            success: `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
            `,
            error: `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
            `,
            warning: `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                </svg>
            `,
            info: `
                <svg fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                </svg>
            `
        };
        return icons[type] || icons.info;
    }

    getTypeColors(type) {
        const colors = {
            success: {
                icon: 'text-emerald-500',
                progress: 'bg-emerald-500'
            },
            error: {
                icon: 'text-red-500',
                progress: 'bg-red-500'
            },
            warning: {
                icon: 'text-amber-500',
                progress: 'bg-amber-500'
            },
            info: {
                icon: 'text-blue-500',
                progress: 'bg-blue-500'
            }
        };
        return colors[type] || colors.info;
    }

    getDefaultTitle(type) {
        const titles = {
            success: 'Success',
            error: 'Error',
            warning: 'Warning',
            info: 'Info'
        };
        return titles[type] || 'Notification';
    }

    getDefaultDuration(type) {
        const durations = {
            success: 3000,
            error: 5000,
            warning: 4000,
            info: 4000
        };
        return durations[type] || this.defaultDuration;
    }

    animateIn(notification) {
        notification.classList.add('notification-enter');
        setTimeout(() => {
            notification.classList.remove('notification-enter');
        }, this.animationDuration);
    }

    animateOut(notification, callback) {
        notification.classList.add('notification-exit');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
            if (callback) callback();
        }, this.animationDuration);
    }

    dismiss(id) {
        const notificationData = this.notifications.get(id);
        if (!notificationData) return;

        const { element, timer } = notificationData;

        // Clear timer if exists
        if (timer) {
            clearTimeout(timer);
        }

        // Animate out and remove
        this.animateOut(element, () => {
            this.notifications.delete(id);
        });
    }

    dismissAll() {
        this.notifications.forEach((notificationData, id) => {
            this.dismiss(id);
        });
    }

    limitNotifications() {
        if (this.notifications.size > this.maxNotifications) {
            const oldestId = this.notifications.keys().next().value;
            this.dismiss(oldestId);
        }
    }

    generateId() {
        return Date.now().toString(36) + Math.random().toString(36).substr(2);
    }

    // Update position
    setPosition(position) {
        const positions = {
            'top-right': 'top-4 right-4',
            'top-left': 'top-4 left-4',
            'bottom-right': 'bottom-4 right-4',
            'bottom-left': 'bottom-4 left-4'
        };
        
        if (positions[position]) {
            this.container.className = this.container.className.replace(
                /(top|bottom)-[0-9]+ (left|right)-[0-9]+/,
                positions[position]
            );
        }
    }
}

// Create global instance
const modernNotifications = new ModernNotificationSystem();

// Global API functions
window.showNotification = function(type, message, options = {}) {
    return modernNotifications.show(type, message, options);
};

window.showSuccess = function(message, options = {}) {
    return modernNotifications.show('success', message, options);
};

window.showError = function(message, options = {}) {
    return modernNotifications.show('error', message, options);
};

window.showWarning = function(message, options = {}) {
    return modernNotifications.show('warning', message, options);
};

window.showInfo = function(message, options = {}) {
    return modernNotifications.show('info', message, options);
};

// User status specific notifications
window.showUserStatusNotification = function(userName, status, type = 'success', customMessage = null) {
    const isActive = status === 'Active';
    const statusIcon = isActive ? '✅' : '⏸️';
    
    let title, message;
    
    if (type === 'success') {
        title = 'Status Updated';
        message = customMessage || `${userName}'s account has been ${isActive ? 'activated' : 'deactivated'}`;
    } else {
        title = 'Update Failed';
        message = customMessage || `Failed to update ${userName}'s account status`;
    }
    
    return modernNotifications.show(type, message, {
        title: title,
        duration: type === 'success' ? 3000 : 5000
    });
};

// Utility functions
window.dismissNotification = function(id) {
    modernNotifications.dismiss(id);
};

window.dismissAllNotifications = function() {
    modernNotifications.dismissAll();
};

// Make instance available globally for advanced usage
window.modernNotifications = modernNotifications;

// Export for module use
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ModernNotificationSystem;
}
