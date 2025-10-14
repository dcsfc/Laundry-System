/**
 * Announcements System JavaScript
 * Handles all announcement-related functionality including modals, CRUD operations, and dashboard widgets
 */

// ===== GLOBAL ANNOUNCEMENTS MANAGER =====
class AnnouncementsManager {
    constructor() {
        this.isModalOpen = false;
        this.isEdit = false;
        this.isLoading = false;
        this.currentId = null;
        this.form = this.getDefaultForm();
        this.errors = {};
        this.dismissedAnnouncements = this.loadDismissedAnnouncements();
        this.isExpanded = false;
    }

    getDefaultForm() {
        return {
            title: '',
            message: '',
            type: 'new',
            link: '',
            visible_to: 'all',
            expires_at: '',
            is_pinned: false,
            is_active: true
        };
    }

    loadDismissedAnnouncements() {
        try {
            return JSON.parse(localStorage.getItem('dismissedAnnouncements') || '[]');
        } catch (error) {
            console.error('Error loading dismissed announcements:', error);
            return [];
        }
    }

    saveDismissedAnnouncements() {
        try {
            localStorage.setItem('dismissedAnnouncements', JSON.stringify(this.dismissedAnnouncements));
        } catch (error) {
            console.error('Error saving dismissed announcements:', error);
        }
    }

    // ===== MODAL OPERATIONS =====
    openCreateModal() {
        this.isEdit = false;
        this.isModalOpen = true;
        this.resetForm();
        this.errors = {};
        this.showModal();
    }

    openEditModal(id) {
        this.isEdit = true;
        this.currentId = id;
        this.isModalOpen = true;
        this.loadAnnouncement(id);
        this.showModal();
    }

    closeModal() {
        this.isModalOpen = false;
        this.resetForm();
        this.errors = {};
        this.hideModal();
    }

    showModal() {
        const modal = document.querySelector('[x-data*="announcementsManager"]');
        if (modal) {
            modal.style.overflow = 'hidden';
        }
        document.body.style.overflow = 'hidden';
    }

    hideModal() {
        const modal = document.querySelector('[x-data*="announcementsManager"]');
        if (modal) {
            modal.style.overflow = '';
        }
        document.body.style.overflow = '';
    }

    resetForm() {
        this.form = this.getDefaultForm();
    }

    // ===== API OPERATIONS =====
    async loadAnnouncement(id) {
        try {
            this.isLoading = true;
            const response = await fetch(`/superadmin/announcements/${id}`, {
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });
            const data = await response.json();
            
            if (data.success) {
                const announcement = data.announcement;
                this.form = {
                    title: announcement.title,
                    message: announcement.message,
                    type: announcement.type,
                    link: announcement.link || '',
                    visible_to: announcement.visible_to,
                    expires_at: announcement.expires_at ? new Date(announcement.expires_at).toISOString().slice(0, 16) : '',
                    is_pinned: announcement.is_pinned,
                    is_active: announcement.is_active
                };
            } else {
                this.showNotification('Error loading announcement', 'error');
            }
        } catch (error) {
            console.error('Error loading announcement:', error);
            this.showNotification('Failed to load announcement', 'error');
        } finally {
            this.isLoading = false;
        }
    }

    async submitForm() {
        this.isLoading = true;
        this.errors = {};

        try {
            const url = this.isEdit 
                ? `/superadmin/announcements/${this.currentId}`
                : '/superadmin/announcements';
            
            const method = this.isEdit ? 'PUT' : 'POST';
            
            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': this.getCSRFToken()
                },
                body: JSON.stringify(this.form)
            });

            const data = await response.json();

            if (data.success) {
                this.closeModal();
                this.showNotification(data.message, 'success');
                this.refreshPage();
            } else {
                this.errors = data.errors || {};
                this.showNotification(data.message || 'Validation failed', 'error');
            }
        } catch (error) {
            console.error('Error submitting form:', error);
            this.showNotification('Failed to save announcement', 'error');
        } finally {
            this.isLoading = false;
        }
    }

    async toggleStatus(id) {
        try {
            const response = await fetch(`/superadmin/announcements/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshPage();
            } else {
                this.showNotification(data.message || 'Failed to update status', 'error');
            }
        } catch (error) {
            console.error('Error toggling status:', error);
            this.showNotification('Failed to update status', 'error');
        }
    }

    async togglePin(id) {
        try {
            const response = await fetch(`/superadmin/announcements/${id}/toggle-pin`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshPage();
            } else {
                this.showNotification(data.message || 'Failed to update pin status', 'error');
            }
        } catch (error) {
            console.error('Error toggling pin:', error);
            this.showNotification('Failed to update pin status', 'error');
        }
    }

    async deleteAnnouncement(id) {
        // Open the delete confirmation modal
        window.dispatchEvent(new CustomEvent('open-delete-confirmation', {
            detail: {
                item: { id: id },
                options: {
                    title: 'Delete Announcement',
                    message: 'Are you sure you want to delete this announcement?',
                    additionalInfo: 'This action cannot be undone. The announcement will be permanently removed.',
                    requireConfirmation: true,
                    confirmationText: 'DELETE',
                    onConfirm: async (itemData) => {
                        return await this.performAnnouncementDeletion(itemData.id);
                    }
                }
            }
        }));
    }

    async performAnnouncementDeletion(id) {
        try {
            const response = await fetch(`/superadmin/announcements/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': this.getCSRFToken()
                }
            });

            const data = await response.json();
            if (data.success) {
                this.showNotification(data.message, 'success');
                this.refreshPage();
            } else {
                this.showNotification(data.message || 'Failed to delete announcement', 'error');
                throw new Error(data.message || 'Failed to delete announcement');
            }
        } catch (error) {
            console.error('Error deleting announcement:', error);
            this.showNotification('Failed to delete announcement', 'error');
            throw error; // Re-throw to let the modal handle the error state
        }
    }

    // ===== DASHBOARD WIDGET OPERATIONS =====
    toggleExpanded() {
        this.isExpanded = !this.isExpanded;
    }

    isDismissed(id) {
        return this.dismissedAnnouncements.includes(id);
    }

    dismissAnnouncement(id) {
        if (!this.dismissedAnnouncements.includes(id)) {
            this.dismissedAnnouncements.push(id);
            this.saveDismissedAnnouncements();
            this.showNotification('Announcement dismissed', 'info');
        }
    }

    // ===== UTILITY FUNCTIONS =====
    getCSRFToken() {
        const token = document.querySelector('meta[name="csrf-token"]');
        return token ? token.getAttribute('content') : '';
    }

    refreshPage() {
        setTimeout(() => {
            window.location.reload();
        }, 1000);
    }

    showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
        
        // Set colors based on type
        const colors = {
            success: 'bg-green-600 text-white',
            error: 'bg-red-600 text-white',
            warning: 'bg-yellow-600 text-white',
            info: 'bg-blue-600 text-white'
        };
        
        notification.className += ` ${colors[type] || colors.info}`;
        notification.textContent = message;
        
        document.body.appendChild(notification);
        
        // Animate in
        setTimeout(() => {
            notification.classList.remove('translate-x-full');
        }, 100);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('translate-x-full');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
    }

    // ===== VALIDATION =====
    validateForm() {
        this.errors = {};
        
        if (!this.form.title.trim()) {
            this.errors.title = 'Title is required';
        }
        
        if (!this.form.message.trim()) {
            this.errors.message = 'Message is required';
        }
        
        if (this.form.link && !this.isValidUrl(this.form.link)) {
            this.errors.link = 'Please enter a valid URL';
        }
        
        if (this.form.expires_at && new Date(this.form.expires_at) <= new Date()) {
            this.errors.expires_at = 'Expiration date must be in the future';
        }
        
        return Object.keys(this.errors).length === 0;
    }

    isValidUrl(string) {
        try {
            new URL(string);
            return true;
        } catch (_) {
            return false;
        }
    }
}

// ===== DASHBOARD WIDGET CLASS =====
class AnnouncementsWidget {
    constructor() {
        this.isExpanded = false;
        this.dismissedAnnouncements = this.loadDismissedAnnouncements();
        this.searchQuery = '';
        this.filterType = '';
    }

    loadDismissedAnnouncements() {
        try {
            return JSON.parse(localStorage.getItem('dismissedAnnouncements') || '[]');
        } catch (error) {
            console.error('Error loading dismissed announcements:', error);
            return [];
        }
    }

    saveDismissedAnnouncements() {
        try {
            localStorage.setItem('dismissedAnnouncements', JSON.stringify(this.dismissedAnnouncements));
        } catch (error) {
            console.error('Error saving dismissed announcements:', error);
        }
    }

    toggleExpanded() {
        this.isExpanded = !this.isExpanded;
    }

    isDismissed(id) {
        return this.dismissedAnnouncements.includes(id);
    }

    dismissAnnouncement(id) {
        if (!this.dismissedAnnouncements.includes(id)) {
            this.dismissedAnnouncements.push(id);
            this.saveDismissedAnnouncements();
            this.showNotification('Announcement dismissed', 'info');
        }
    }

    matchesFilter(title, type) {
        const matchesSearch = this.searchQuery === '' || 
            title.toLowerCase().includes(this.searchQuery.toLowerCase());
        const matchesFilter = this.filterType === '' || type === this.filterType;
        return matchesSearch && matchesFilter;
    }

    showNotification(message, type = 'info') {
        // Reuse the same notification system
        const manager = new AnnouncementsManager();
        manager.showNotification(message, type);
    }
}

// ===== ALPINE.JS COMPONENTS =====
function announcementsManager() {
    return {
        isModalOpen: false,
        isEdit: false,
        isLoading: false,
        form: {
            title: '',
            message: '',
            type: 'new',
            link: '',
            visible_to: 'all',
            expires_at: '',
            is_pinned: false,
            is_active: true
        },
        errors: {},
        currentId: null,

        openCreateModal() {
            this.isEdit = false;
            this.isModalOpen = true;
            this.resetForm();
            this.errors = {};
        },

        openEditModal(id) {
            this.isEdit = true;
            this.currentId = id;
            this.isModalOpen = true;
            this.loadAnnouncement(id);
        },

        closeModal() {
            this.isModalOpen = false;
            this.resetForm();
            this.errors = {};
        },

        resetForm() {
            this.form = {
                title: '',
                message: '',
                type: 'new',
                link: '',
                visible_to: 'all',
                expires_at: '',
                is_pinned: false,
                is_active: true
            };
        },

        async loadAnnouncement(id) {
            try {
                const response = await fetch(`/superadmin/announcements/${id}`, {
                    headers: {
                        'X-CSRF-TOKEN': this.getCSRFToken()
                    }
                });
                const data = await response.json();
                
                if (data.success) {
                    const announcement = data.announcement;
                    this.form = {
                        title: announcement.title,
                        message: announcement.message,
                        type: announcement.type,
                        link: announcement.link || '',
                        visible_to: announcement.visible_to,
                        expires_at: announcement.expires_at ? new Date(announcement.expires_at).toISOString().slice(0, 16) : '',
                        is_pinned: announcement.is_pinned,
                        is_active: announcement.is_active
                    };
                }
            } catch (error) {
                console.error('Error loading announcement:', error);
            }
        },

        async submitForm() {
            this.isLoading = true;
            this.errors = {};

            try {
                const url = this.isEdit 
                    ? `/superadmin/announcements/${this.currentId}`
                    : '/superadmin/announcements';
                
                const method = this.isEdit ? 'PUT' : 'POST';
                
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.getCSRFToken()
                    },
                    body: JSON.stringify(this.form)
                });

                const data = await response.json();

                if (data.success) {
                    this.closeModal();
                    this.showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.errors = data.errors || {};
                    this.showNotification(data.message || 'Validation failed', 'error');
                }
            } catch (error) {
                console.error('Error submitting form:', error);
                this.showNotification('Failed to save announcement', 'error');
            } finally {
                this.isLoading = false;
            }
        },

        async toggleStatus(id) {
            try {
                const response = await fetch(`/superadmin/announcements/${id}/toggle-status`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.getCSRFToken()
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.showNotification(data.message || 'Failed to update status', 'error');
                }
            } catch (error) {
                console.error('Error toggling status:', error);
                this.showNotification('Failed to update status', 'error');
            }
        },

        async togglePin(id) {
            try {
                const response = await fetch(`/superadmin/announcements/${id}/toggle-pin`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': this.getCSRFToken()
                    }
                });

                const data = await response.json();
                if (data.success) {
                    this.showNotification(data.message, 'success');
                    setTimeout(() => location.reload(), 1000);
                } else {
                    this.showNotification(data.message || 'Failed to update pin status', 'error');
                }
            } catch (error) {
                console.error('Error toggling pin:', error);
                this.showNotification('Failed to update pin status', 'error');
            }
        },

        async deleteAnnouncement(id) {
            if (confirm('Are you sure you want to delete this announcement? This action cannot be undone.')) {
                try {
                    const response = await fetch(`/superadmin/announcements/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': this.getCSRFToken()
                        }
                    });

                    const data = await response.json();
                    if (data.success) {
                        this.showNotification(data.message, 'success');
                        setTimeout(() => location.reload(), 1000);
                    } else {
                        this.showNotification(data.message || 'Failed to delete announcement', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting announcement:', error);
                    this.showNotification('Failed to delete announcement', 'error');
                }
            }
        },

        getCSRFToken() {
            const token = document.querySelector('meta[name="csrf-token"]');
            return token ? token.getAttribute('content') : '';
        },

        showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transition-all duration-300 transform translate-x-full`;
            
            const colors = {
                success: 'bg-green-600 text-white',
                error: 'bg-red-600 text-white',
                warning: 'bg-yellow-600 text-white',
                info: 'bg-blue-600 text-white'
            };
            
            notification.className += ` ${colors[type] || colors.info}`;
            notification.textContent = message;
            
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);
            
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
        }
    };
}

function announcementsWidget() {
    const widget = new AnnouncementsWidget();
    return {
        isExpanded: widget.isExpanded,
        dismissedAnnouncements: widget.dismissedAnnouncements,
        searchQuery: widget.searchQuery,
        filterType: widget.filterType,

        toggleExpanded: () => widget.toggleExpanded(),
        isDismissed: (id) => widget.isDismissed(id),
        dismissAnnouncement: (id) => widget.dismissAnnouncement(id),
        matchesFilter: (title, type) => widget.matchesFilter(title, type)
    };
}

// ===== GLOBAL FUNCTIONS FOR COMPATIBILITY =====
window.announcementsManager = announcementsManager;
window.announcementsWidget = announcementsWidget;

// Legacy functions for data table compatibility
window.viewAnnouncement = function(row) {
    console.log('View announcement:', row);
    // Implement view functionality if needed
};

window.editAnnouncement = function(row) {
    const manager = new AnnouncementsManager();
    manager.openEditModal(row.id);
};

window.toggleAnnouncementStatus = function(row) {
    const manager = new AnnouncementsManager();
    manager.toggleStatus(row.id);
};

window.deleteAnnouncement = function(row) {
    const manager = new AnnouncementsManager();
    manager.deleteAnnouncement(row.id);
};

window.createAnnouncement = function() {
    const manager = new AnnouncementsManager();
    manager.openCreateModal();
};

// ===== INITIALIZATION =====
document.addEventListener('DOMContentLoaded', function() {
    // Initialize any global announcement functionality
    console.log('Announcements system initialized');
    
    // Add keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Escape key to close modals
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('[x-data*="announcementsManager"]');
            modals.forEach(modal => {
                if (modal.__x && modal.__x.$data.isModalOpen) {
                    modal.__x.$data.closeModal();
                }
            });
        }
    });
});

// ===== EXPORT FOR MODULE USAGE =====
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        AnnouncementsManager,
        AnnouncementsWidget,
        announcementsManager,
        announcementsWidget
    };
}
