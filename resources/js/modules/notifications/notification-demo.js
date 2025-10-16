/**
 * Modern Notification System Demo
 * Comprehensive demo and test functions for the new notification system
 */

// Demo function to showcase all notification types
function demoAllNotifications() {
    console.log('🎨 Demo: All notification types');
    
    // Success notification
    setTimeout(() => {
        showSuccess('User created successfully!', {
            title: 'Operation Complete'
        });
    }, 500);
    
    // Error notification
    setTimeout(() => {
        showError('Failed to save user data. Please try again.', {
            title: 'Save Failed'
        });
    }, 2000);
    
    // Warning notification
    setTimeout(() => {
        showWarning('This action cannot be undone.', {
            title: 'Warning'
        });
    }, 3500);
    
    // Info notification
    setTimeout(() => {
        showInfo('System will be updated tonight at 2 AM.', {
            title: 'Maintenance Notice'
        });
    }, 5000);
}

// Demo function for user status notifications
function demoUserStatusNotifications() {
    console.log('👤 Demo: User status notifications');
    
    // Successful activation
    setTimeout(() => {
        showUserStatusNotification('Alice Johnson', 'Active', 'success');
    }, 500);
    
    // Successful deactivation
    setTimeout(() => {
        showUserStatusNotification('Bob Smith', 'Inactive', 'success');
    }, 2000);
    
    // Failed status change
    setTimeout(() => {
        showUserStatusNotification('Charlie Brown', 'Active', 'error', 'User is currently logged in');
    }, 3500);
}

// Demo function for notification stacking
function demoNotificationStacking() {
    console.log('📚 Demo: Notification stacking');
    
    const users = ['Alice', 'Bob', 'Charlie', 'Diana', 'Eve'];
    
    users.forEach((user, index) => {
        setTimeout(() => {
            showSuccess(`${user} has been updated successfully.`, {
                title: 'User Updated'
            });
        }, index * 800);
    });
}

// Demo function for different durations
function demoNotificationDurations() {
    console.log('⏱️ Demo: Different durations');
    
    const durations = [
        { type: 'success', message: 'Quick notification (1s)', duration: 1000 },
        { type: 'info', message: 'Standard notification (3s)', duration: 3000 },
        { type: 'warning', message: 'Long notification (6s)', duration: 6000 },
        { type: 'error', message: 'Persistent notification', duration: 0, persistent: true }
    ];
    
    durations.forEach((config, index) => {
        setTimeout(() => {
            showNotification(config.type, config.message, {
                title: 'Duration Test',
                duration: config.duration,
                persistent: config.persistent || false
            });
        }, index * 8000);
    });
}

// Demo function for custom styling
function demoCustomNotifications() {
    console.log('🎨 Demo: Custom notifications');
    
    // Custom success with action
    setTimeout(() => {
        showSuccess('File uploaded successfully!', {
            title: 'Upload Complete',
            action: {
                label: 'View',
                onClick: () => console.log('View file clicked')
            }
        });
    }, 500);
    
    // Custom error with longer duration
    setTimeout(() => {
        showError('Network connection lost. Retrying...', {
            title: 'Connection Error',
            duration: 8000
        });
    }, 2000);
}

// Demo function for position changes
function demoNotificationPositions() {
    console.log('📍 Demo: Notification positions');
    
    const positions = ['top-right', 'top-left', 'bottom-right', 'bottom-left'];
    
    positions.forEach((position, index) => {
        setTimeout(() => {
            // Change position
            window.modernNotifications.setPosition(position);
            
            // Show notification
            showInfo(`Notification in ${position} position`, {
                title: 'Position Test'
            });
        }, index * 2000);
    });
}

// Demo function for enterprise scenarios
function demoEnterpriseNotifications() {
    console.log('🏢 Demo: Enterprise scenarios');
    
    // System maintenance
    setTimeout(() => {
        showWarning('Scheduled maintenance will begin in 30 minutes. Please save your work.', {
            title: 'System Maintenance',
            duration: 10000
        });
    }, 500);
    
    // Security alert
    setTimeout(() => {
        showError('Unusual login activity detected from a new device.', {
            title: 'Security Alert',
            duration: 8000
        });
    }, 3000);
    
    // Feature announcement
    setTimeout(() => {
        showInfo('New dashboard features are now available! Check out the updated analytics.', {
            title: 'Feature Update',
            duration: 6000
        });
    }, 6000);
}

// Comprehensive demo that runs all scenarios
function runFullDemo() {
    console.log('🚀 Running full notification demo...');
    
    demoAllNotifications();
    
    setTimeout(() => {
        demoUserStatusNotifications();
    }, 8000);
    
    setTimeout(() => {
        demoNotificationStacking();
    }, 15000);
    
    setTimeout(() => {
        demoCustomNotifications();
    }, 20000);
    
    setTimeout(() => {
        demoEnterpriseNotifications();
    }, 25000);
}

// Quick test function
function quickTest() {
    console.log('⚡ Quick test: Basic notifications');
    
    showSuccess('Test successful!');
    
    setTimeout(() => {
        showError('Test error!');
    }, 1000);
    
    setTimeout(() => {
        showWarning('Test warning!');
    }, 2000);
    
    setTimeout(() => {
        showInfo('Test info!');
    }, 3000);
}

// Make all demo functions available globally
window.demoAllNotifications = demoAllNotifications;
window.demoUserStatusNotifications = demoUserStatusNotifications;
window.demoNotificationStacking = demoNotificationStacking;
window.demoNotificationDurations = demoNotificationDurations;
window.demoCustomNotifications = demoCustomNotifications;
window.demoNotificationPositions = demoNotificationPositions;
window.demoEnterpriseNotifications = demoEnterpriseNotifications;
window.runFullDemo = runFullDemo;
window.quickTest = quickTest;

// Auto-run quick test in development
if (window.location.hostname === 'localhost' || window.location.hostname === '127.0.0.1') {
    console.log('🎉 Modern Notification System loaded!');
    console.log('Available demo functions:');
    console.log('- quickTest() - Basic notification test');
    console.log('- demoAllNotifications() - All notification types');
    console.log('- demoUserStatusNotifications() - User status changes');
    console.log('- demoNotificationStacking() - Multiple notifications');
    console.log('- demoCustomNotifications() - Custom styling');
    console.log('- demoEnterpriseNotifications() - Enterprise scenarios');
    console.log('- runFullDemo() - Complete demo');
    console.log('');
    console.log('Or use the notification functions directly:');
    console.log('- showSuccess(message, options)');
    console.log('- showError(message, options)');
    console.log('- showWarning(message, options)');
    console.log('- showInfo(message, options)');
    console.log('- showUserStatusNotification(userName, status, type)');
}
