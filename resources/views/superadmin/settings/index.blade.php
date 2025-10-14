@extends('layouts.sidebar')

@section('title', 'System Settings')

@section('content')
<style>
[x-cloak] { display: none !important; }
</style>
<div class="min-h-screen bg-slate-900" x-data="systemSettings()" x-init="init()">
    <!-- Header matching system theme -->
    <div class="bg-slate-800 border-b border-indigo-500/20">
        <div class="max-w-4xl mx-auto px-6 py-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-white">System Settings</h1>
                    <p class="text-slate-300 mt-1">Manage your laundry shop system preferences</p>
                </div>
                <div class="flex items-center gap-3">
                    <div x-show="hasUnsavedChanges" class="flex items-center gap-2 text-amber-400">
                        <i class="fas fa-exclamation-circle"></i>
                        <span class="text-sm font-medium">Unsaved changes</span>
                    </div>
                    <button 
                        @click="saveAllSettings()"
                        :disabled="isSaving || !hasUnsavedChanges"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg font-medium transition-all duration-300 shadow-lg hover:shadow-indigo-500/25 flex items-center gap-2"
                    >
                        <i x-show="isSaving" class="fas fa-spinner fa-spin"></i>
                        <i x-show="!isSaving" class="fas fa-save"></i>
                        <span x-text="isSaving ? 'Saving...' : 'Save Changes'"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Navigation Tabs -->
    <div class="px-6 py-8">
        <div class="max-w-6xl mx-auto">
            <div class="mb-8">
                <div class="bg-slate-800/30 backdrop-blur-sm rounded-2xl p-2 border border-slate-700/50">
                    <nav class="flex space-x-2">
                        <button 
                            @click="activeSection = 'general'"
                            :class="activeSection === 'general' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'"
                            class="group relative px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2"
                        >
                            <i class="fas fa-sliders-h text-sm"></i>
                            <span>General</span>
                            <div x-show="activeSection === 'general'" class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-20 animate-pulse"></div>
                        </button>
                        <button 
                            @click="activeSection = 'notifications'"
                            :class="activeSection === 'notifications' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'"
                            class="group relative px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2"
                        >
                            <i class="fas fa-envelope text-sm"></i>
                            <span>Notifications</span>
                            <div x-show="activeSection === 'notifications'" class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-20 animate-pulse"></div>
                        </button>
                        <button 
                            @click="activeSection = 'security'"
                            :class="activeSection === 'security' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'"
                            class="group relative px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2"
                        >
                            <i class="fas fa-shield-alt text-sm"></i>
                            <span>Security</span>
                            <div x-show="activeSection === 'security'" class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-20 animate-pulse"></div>
                        </button>
                        <button 
                            @click="activeSection = 'backup'"
                            :class="activeSection === 'backup' ? 'bg-gradient-to-r from-indigo-600 to-purple-600 text-white shadow-lg' : 'text-slate-400 hover:text-slate-200 hover:bg-slate-700/50'"
                            class="group relative px-4 py-3 rounded-xl font-medium text-sm transition-all duration-300 flex items-center gap-2"
                        >
                            <i class="fas fa-database text-sm"></i>
                            <span>Data & Backup</span>
                            <div x-show="activeSection === 'backup'" class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl opacity-20 animate-pulse"></div>
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Settings Content -->
            <div class="space-y-6">
                <!-- General Settings -->
                <div x-show="activeSection === 'general'" x-transition>
                    @include('superadmin.settings.sections.general')
                </div>

                <!-- Notifications Settings -->
                <div x-show="activeSection === 'notifications'" x-transition>
                    @include('superadmin.settings.sections.notifications')
                </div>

                <!-- Security Settings -->
                <div x-show="activeSection === 'security'" x-transition>
                    @include('superadmin.settings.sections.security')
                </div>

                <!-- Data & Backup Settings -->
                <div x-show="activeSection === 'backup'" x-transition>
                    @include('superadmin.settings.sections.backup')
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div x-show="showToast" x-cloak x-transition class="fixed top-4 right-4 z-50" style="display: none;">
        <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-lg p-4 max-w-sm">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                    <i class="fas fa-check text-green-600 dark:text-green-400 text-sm"></i>
                </div>
                <div>
                    <p class="text-sm font-medium text-slate-900 dark:text-white">Settings Updated</p>
                    <p class="text-xs text-slate-500 dark:text-slate-400">Your changes have been saved successfully</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function systemSettings() {
    return {
        activeSection: 'general',
        isSaving: false,
        hasUnsavedChanges: false,
        showToast: false,
        showBackupModal: false,
        isBackingUp: false,
        lastBackupDate: 'Never',
        backupSize: '-',
        settings: {
            general: {
                shop_name: 'Latino Laundry Shop',
                owner_name: 'Juan Dela Cruz',
                phone: '+63 912 345 6789',
                email: 'contact@latino-laundry.ph',
                timezone: 'Asia/Manila',
                language: 'en',
                address: '123 Rizal Street, Barangay Poblacion, Manila 1000',
                maintenance_mode: false
            },
            notifications: {
                smtp_host: 'smtp.gmail.com',
                smtp_port: 587,
                smtp_username: '',
                smtp_password: '',
                email_enabled: true,
                in_app_enabled: true,
                order_updates: true,
                payment_confirmations: true
            },
            security: {
                min_password_length: 8,
                auto_logout: '30',
                two_factor_enabled: false,
                force_password_reset: false,
                session_timeout_enabled: true
            },
            backup: {
                frequency: 'weekly',
                retention_days: 30
            }
        },

        init() {
            console.log('System Settings initialized');
            this.loadSettings();
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
                        this.settings = { ...this.settings, ...data.settings };
                    }
                }
            } catch (error) {
                console.error('Error loading settings:', error);
            }
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
                    body: JSON.stringify({ settings: this.settings })
                });

                const data = await response.json();

                if (response.ok && data.success) {
                    this.hasUnsavedChanges = false;
                    this.showToast = true;
                    setTimeout(() => this.showToast = false, 3000);
                } else {
                    alert('Failed to save settings. Please try again.');
                }
            } catch (error) {
                console.error('Error saving settings:', error);
                alert('Network error. Please try again.');
            } finally {
                this.isSaving = false;
            }
        },

        async createBackup() {
            this.isBackingUp = true;
            
            try {
                // Simulate backup process
                await new Promise(resolve => setTimeout(resolve, 2000));
                
                this.lastBackupDate = new Date().toLocaleDateString();
                this.backupSize = '2.4 MB';
                this.showBackupModal = false;
                this.showToast = true;
                setTimeout(() => this.showToast = false, 3000);
            } catch (error) {
                console.error('Error creating backup:', error);
                alert('Failed to create backup. Please try again.');
            } finally {
                this.isBackingUp = false;
            }
        },

        downloadBackup() {
            // Simulate download
            alert('Download started...');
        }
    }
}
</script>
@endsection