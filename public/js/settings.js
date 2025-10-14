/**
 * System Settings JavaScript
 * Handles all settings functionality including loading, saving, and form interactions
 */

function systemSettings() {
    return {
        activeSection: '{{ request()->get("section", "general") }}',
        isSaving: false,
        hasUnsavedChanges: false,
        originalSettings: {},
        settings: {
            general: {
                app_name: 'Latino Laundry System',
                company_name: 'Latino Laundry Co.',
                timezone: 'UTC',
                default_language: 'en',
                logo_url: '',
                maintenance_mode: false
            },
            email: {
                smtp_host: 'smtp.gmail.com',
                smtp_port: 587,
                smtp_username: '',
                smtp_password: '',
                encryption: 'tls',
                system_email: 'noreply@latino-laundry.com',
                in_app_notifications: true,
                email_notifications: true
            },
            security: {
                password_min_length: 8,
                session_timeout: 30,
                require_uppercase: true,
                require_numbers: true,
                require_special_chars: true,
                lockout_attempts: 5,
                allowed_domains: '',
                two_factor_enforcement: false
            },
            data: {
                upload_limit: 10,
                allowed_file_types: 'jpg,jpeg,png,pdf,doc,docx',
                storage_type: 'local',
                retention_days: 90,
                backup_schedule: 'daily'
            },
            laundry: {
                default_pickup_days: 3,
                express_service_available: true,
                express_service_hours: 24,
                auto_assign_staff: true,
                require_payment_before_pickup: false
            }
        },

        async init() {
            await this.loadSettings();
            this.originalSettings = JSON.parse(JSON.stringify(this.settings));
        },

        async loadSettings() {
            try {
                const response = await fetch('/superadmin/settings/data', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (response.ok) {
                    const data = await response.json();
                    if (data.success && data.settings) {
                        // Deep merge the loaded settings with defaults
                        this.settings = this.deepMerge(this.settings, data.settings);
                    }
                } else {
                    console.warn('Failed to load settings from server, using defaults');
                }
            } catch (error) {
                console.error('Error loading settings:', error);
                console.warn('Using default settings due to error');
            }
        },

        deepMerge(target, source) {
            const result = { ...target };
            
            for (const key in source) {
                if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
                    result[key] = this.deepMerge(target[key] || {}, source[key]);
                } else {
                    result[key] = source[key];
                }
            }
            
            return result;
        },

        markAsChanged() {
            this.hasUnsavedChanges = true;
        },

        async saveAllSettings() {
            this.isSaving = true;
            
            try {
                const response = await fetch('/superadmin/settings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        settings: this.settings
                    })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.hasUnsavedChanges = false;
                    this.originalSettings = JSON.parse(JSON.stringify(this.settings));
                    
                    if (typeof window.showSuccess === 'function') {
                        window.showSuccess('System settings updated successfully!', {
                            title: 'Success'
                        });
                    } else {
                        alert('System settings updated successfully!');
                    }
                } else {
                    if (typeof window.showError === 'function') {
                        window.showError(data.message || 'Failed to save settings. Please try again.', {
                            title: 'Error'
                        });
                    } else {
                        alert('Failed to save settings. Please try again.');
                    }
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                if (typeof window.showError === 'function') {
                    window.showError('Network error. Please try again.', {
                        title: 'Error'
                    });
                } else {
                    alert('Network error. Please try again.');
                }
            } finally {
                this.isSaving = false;
            }
        },

        handleLogoUpload(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.settings.general.logo_url = e.target.result;
                    this.markAsChanged();
                };
                reader.readAsDataURL(file);
            }
        }
    }
}
