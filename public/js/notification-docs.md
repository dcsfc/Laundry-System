# Modern Notification System Documentation

## Overview

A professional, modern SaaS-style notification (toast/snackbar) component designed for enterprise dashboards. Inspired by Linear, Vercel, and Notion design patterns.

## Features

- ✅ **Modern Design**: Clean, minimal style with professional spacing and typography
- ✅ **Smooth Animations**: Slide-in from top-right with fade and scale effects
- ✅ **Multiple Types**: Success, error, warning, and info notifications
- ✅ **Auto-dismiss**: Configurable duration (3-5 seconds default)
- ✅ **Manual Close**: Click to dismiss anytime
- ✅ **Stacking**: Multiple notifications stack vertically
- ✅ **Dark Mode**: Automatic dark mode support
- ✅ **Responsive**: Works great on all screen sizes
- ✅ **Tailwind CSS**: Built with modern utility classes
- ✅ **Enterprise Ready**: Perfect for SaaS dashboards

## Quick Start

```javascript
// Basic usage
showSuccess('User created successfully!');
showError('Failed to save data');
showWarning('This action cannot be undone');
showInfo('System maintenance tonight');

// With custom options
showSuccess('User created successfully!', {
    title: 'Operation Complete',
    duration: 5000
});
```

## API Reference

### Core Functions

#### `showNotification(type, message, options)`
Main function for displaying notifications.

**Parameters:**
- `type` (string): 'success', 'error', 'warning', or 'info'
- `message` (string): Notification message
- `options` (object): Configuration options

**Options:**
```javascript
{
    title: 'Custom Title',           // Notification title
    duration: 4000,                  // Auto-dismiss duration (ms)
    position: 'top-right',           // Position: 'top-right', 'top-left', 'bottom-right', 'bottom-left'
    showProgress: true,              // Show progress bar
    persistent: false,               // Don't auto-dismiss
    action: {                        // Optional action button
        label: 'View',
        onClick: () => console.log('Clicked')
    }
}
```

#### Convenience Functions

```javascript
// Shortcut functions
showSuccess(message, options)
showError(message, options)
showWarning(message, options)
showInfo(message, options)

// User status specific
showUserStatusNotification(userName, status, type, customMessage)
```

### Advanced Usage

#### Notification Management

```javascript
// Dismiss specific notification
const id = showSuccess('Test message');
dismissNotification(id);

// Dismiss all notifications
dismissAllNotifications();

// Change position
window.modernNotifications.setPosition('bottom-right');
```

#### Custom Styling

The notification system uses Tailwind CSS classes and supports dark mode automatically. You can customize the appearance by modifying the CSS classes in the `createNotification` method.

## Examples

### User Status Changes

```javascript
// Success
showUserStatusNotification('John Doe', 'Active', 'success');
// Shows: "John Doe's account has been activated"

// Error
showUserStatusNotification('Jane Smith', 'Inactive', 'error', 'User is currently logged in');
// Shows: "Failed to update Jane Smith's account status"
```

### Enterprise Scenarios

```javascript
// System maintenance
showWarning('Scheduled maintenance will begin in 30 minutes. Please save your work.', {
    title: 'System Maintenance',
    duration: 10000
});

// Security alert
showError('Unusual login activity detected from a new device.', {
    title: 'Security Alert',
    duration: 8000
});

// Feature announcement
showInfo('New dashboard features are now available!', {
    title: 'Feature Update',
    duration: 6000
});
```

### Form Validation

```javascript
// Success
showSuccess('Form submitted successfully!', {
    title: 'Submission Complete'
});

// Validation errors
showError('Please fill in all required fields.', {
    title: 'Validation Error'
});
```

## Integration

### In Your Views

```html
<!-- Include the notification system -->
<script src="{{ asset('js/modern-notifications.js') }}"></script>
<script src="{{ asset('js/notification-demo.js') }}"></script>
```

### In Your JavaScript

```javascript
// The notification system is automatically available globally
// No additional setup required

// Use in your functions
function handleUserStatusToggle(user) {
    try {
        // Your logic here
        showUserStatusNotification(user.name, 'Active', 'success');
    } catch (error) {
        showError('Failed to update user status');
    }
}
```

## Demo Functions

The system includes comprehensive demo functions for testing:

```javascript
// Quick test
quickTest();

// Individual demos
demoAllNotifications();
demoUserStatusNotifications();
demoNotificationStacking();
demoCustomNotifications();
demoEnterpriseNotifications();

// Full demo
runFullDemo();
```

## Browser Support

- ✅ Chrome 60+
- ✅ Firefox 55+
- ✅ Safari 12+
- ✅ Edge 79+

## Performance

- Lightweight: ~8KB minified
- No external dependencies
- Optimized animations using CSS transforms
- Efficient memory management with automatic cleanup

## Customization

### Colors

The notification system uses semantic color classes:
- Success: `text-emerald-500`, `bg-emerald-500`
- Error: `text-red-500`, `bg-red-500`
- Warning: `text-amber-500`, `bg-amber-500`
- Info: `text-blue-500`, `bg-blue-500`

### Animations

Animations use CSS keyframes with cubic-bezier easing:
- Enter: `slideInRight` with scale effect
- Exit: `slideOutRight` with scale effect
- Progress: `progressShrink` linear animation

### Positioning

Notifications can be positioned in any corner:
- `top-right` (default)
- `top-left`
- `bottom-right`
- `bottom-left`

## Troubleshooting

### Notifications not showing
1. Check if the script is loaded: `typeof window.showNotification`
2. Check browser console for errors
3. Verify Tailwind CSS is loaded

### Styling issues
1. Ensure Tailwind CSS is properly loaded
2. Check for CSS conflicts
3. Verify dark mode classes are available

### Performance issues
1. Limit concurrent notifications (default: 5 max)
2. Use appropriate durations
3. Dismiss old notifications when needed

## License

This notification system is part of the Latino Laundry System project.
