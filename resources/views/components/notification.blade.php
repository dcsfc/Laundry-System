@props([
    'type' => 'info',
    'message' => '',
    'duration' => 4000,
    'position' => 'top-right'
])

<div 
    x-data="notificationComponent()"
    x-show="isVisible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    class="fixed z-[10000] notification-container"
    :class="positionClasses"
    style="display: none;"
    x-cloak
>
    <div class="notification-wrapper">
        <div class="notification-content" :class="typeClasses">
            <!-- Icon -->
            <div class="notification-icon">
                <svg x-show="type === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'warning'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'info'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            
            <!-- Message -->
            <div class="notification-message">
                <p class="notification-title" x-text="title"></p>
                <p class="notification-text" x-text="message"></p>
            </div>
            
            <!-- Close Button -->
            <button 
                @click="close()"
                class="notification-close"
                aria-label="Close notification"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <!-- Progress Bar -->
        <div class="notification-progress" x-show="showProgress">
            <div class="progress-bar" :style="progressStyle"></div>
        </div>
    </div>
</div>

<style>
.notification-container {
    max-width: 400px;
    width: 100%;
}

.notification-container.top-right {
    top: 1rem;
    right: 1rem;
}

.notification-container.top-left {
    top: 1rem;
    left: 1rem;
}

.notification-container.bottom-right {
    bottom: 1rem;
    right: 1rem;
}

.notification-container.bottom-left {
    bottom: 1rem;
    left: 1rem;
}

.notification-wrapper {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    border: 1px solid rgba(0, 0, 0, 0.05);
    overflow: hidden;
}

.notification-content {
    display: flex;
    align-items: flex-start;
    padding: 1rem;
    gap: 0.75rem;
}

.notification-content.success {
    border-left: 4px solid #10b981;
}

.notification-content.error {
    border-left: 4px solid #ef4444;
}

.notification-content.warning {
    border-left: 4px solid #f59e0b;
}

.notification-content.info {
    border-left: 4px solid #3b82f6;
}

.notification-icon {
    flex-shrink: 0;
    margin-top: 0.125rem;
}

.notification-icon.success {
    color: #10b981;
}

.notification-icon.error {
    color: #ef4444;
}

.notification-icon.warning {
    color: #f59e0b;
}

.notification-icon.info {
    color: #3b82f6;
}

.notification-message {
    flex: 1;
    min-width: 0;
}

.notification-title {
    font-size: 0.875rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0 0 0.25rem 0;
    line-height: 1.25;
}

.notification-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin: 0;
    line-height: 1.4;
}

.notification-close {
    flex-shrink: 0;
    padding: 0.25rem;
    color: #9ca3af;
    background: none;
    border: none;
    border-radius: 0.375rem;
    cursor: pointer;
    transition: all 0.2s ease;
    margin-top: -0.25rem;
    margin-right: -0.25rem;
}

.notification-close:hover {
    color: #6b7280;
    background-color: #f3f4f6;
}

.notification-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background-color: rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #3b82f6, #1d4ed8);
    transition: width linear;
    border-radius: 0 0 12px 12px;
}

.progress-bar.success {
    background: linear-gradient(90deg, #10b981, #059669);
}

.progress-bar.error {
    background: linear-gradient(90deg, #ef4444, #dc2626);
}

.progress-bar.warning {
    background: linear-gradient(90deg, #f59e0b, #d97706);
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .notification-wrapper {
        background: #1f2937;
        border-color: rgba(255, 255, 255, 0.1);
    }
    
    .notification-title {
        color: #f9fafb;
    }
    
    .notification-text {
        color: #d1d5db;
    }
    
    .notification-close {
        color: #9ca3af;
    }
    
    .notification-close:hover {
        color: #d1d5db;
        background-color: #374151;
    }
    
    .notification-progress {
        background-color: rgba(255, 255, 255, 0.1);
    }
}
</style>

<script>
function notificationComponent() {
    return {
        isVisible: false,
        type: 'info',
        title: '',
        message: '',
        duration: 4000,
        showProgress: true,
        progressWidth: '100%',
        progressStyle: '',
        positionClasses: 'top-right',
        
        init() {
            // Set initial values from props
            this.type = this.$el.getAttribute('data-type') || 'info';
            this.title = this.$el.getAttribute('data-title') || this.getDefaultTitle();
            this.message = this.$el.getAttribute('data-message') || '';
            this.duration = parseInt(this.$el.getAttribute('data-duration')) || 4000;
            this.positionClasses = this.getPositionClasses(this.$el.getAttribute('data-position') || 'top-right');
            
            // Only show notification if it has a message and is marked as auto-show
            const autoShow = this.$el.getAttribute('data-auto-show') === 'true';
            if (autoShow && this.message && this.message.trim() !== '') {
                this.show();
            }
        },
        
        getDefaultTitle() {
            const titles = {
                success: 'Success!',
                error: 'Error!',
                warning: 'Warning!',
                info: 'Info'
            };
            return titles[this.type] || 'Notification';
        },
        
        getPositionClasses(position) {
            const positions = {
                'top-right': 'top-right',
                'top-left': 'top-left',
                'bottom-right': 'bottom-right',
                'bottom-left': 'bottom-left'
            };
            return positions[position] || 'top-right';
        },
        
        show() {
            this.isVisible = true;
            this.$nextTick(() => {
                this.startProgress();
                this.autoClose();
            });
        },
        
        startProgress() {
            if (!this.showProgress) return;
            
            this.progressWidth = '100%';
            this.progressStyle = `width: ${this.progressWidth}; transition-duration: ${this.duration}ms;`;
            
            this.$nextTick(() => {
                setTimeout(() => {
                    this.progressWidth = '0%';
                    this.progressStyle = `width: ${this.progressWidth}; transition-duration: ${this.duration}ms;`;
                }, 50);
            });
        },
        
        autoClose() {
            setTimeout(() => {
                this.close();
            }, this.duration);
        },
        
        close() {
            this.isVisible = false;
            setTimeout(() => {
                if (this.$el && this.$el.parentNode) {
                    this.$el.parentNode.removeChild(this.$el);
                }
            }, 300);
        }
    }
}

// Global notification function
window.showNotification = function(message, type = 'info', options = {}) {
    const {
        title = null,
        duration = 4000,
        position = 'top-right',
        showProgress = true
    } = options;
    
    // Create notification element
    const notification = document.createElement('div');
    notification.setAttribute('x-data', 'notificationComponent()');
    notification.setAttribute('x-init', 'init()');
    notification.setAttribute('data-type', type);
    notification.setAttribute('data-title', title || '');
    notification.setAttribute('data-message', message);
    notification.setAttribute('data-duration', duration);
    notification.setAttribute('data-position', position);
    notification.setAttribute('data-show-progress', showProgress);
    notification.setAttribute('data-auto-show', 'true');
    notification.setAttribute('x-show', 'isVisible');
    notification.setAttribute('x-transition:enter', 'transition ease-out duration-300');
    notification.setAttribute('x-transition:enter-start', 'opacity-0 transform translate-x-full');
    notification.setAttribute('x-transition:enter-end', 'opacity-100 transform translate-x-0');
    notification.setAttribute('x-transition:leave', 'transition ease-in duration-200');
    notification.setAttribute('x-transition:leave-start', 'opacity-100 transform translate-x-0');
    notification.setAttribute('x-transition:leave-end', 'opacity-0 transform translate-x-full');
    notification.className = 'fixed z-[10000] notification-container';
    notification.style.display = 'none';
    notification.setAttribute('x-cloak', '');
    
    // Add the notification HTML
    notification.innerHTML = `
        <div class="notification-wrapper">
            <div class="notification-content" :class="typeClasses">
                <!-- Icon -->
                <div class="notification-icon">
                    <svg x-show="type === 'success'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="type === 'error'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="type === 'warning'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <svg x-show="type === 'info'" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                </div>
                
                <!-- Message -->
                <div class="notification-message">
                    <p class="notification-title" x-text="title"></p>
                    <p class="notification-text" x-text="message"></p>
                </div>
                
                <!-- Close Button -->
                <button 
                    @click="close()"
                    class="notification-close"
                    aria-label="Close notification"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <!-- Progress Bar -->
            <div class="notification-progress" x-show="showProgress">
                <div class="progress-bar" :style="progressStyle"></div>
            </div>
        </div>
    `;
    
    // Add to DOM
    document.body.appendChild(notification);
    
    // Initialize Alpine.js on the new element
    if (window.Alpine) {
        Alpine.initTree(notification);
    }
    
    return notification;
};
</script>
