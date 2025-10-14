<x-settings.settings-card 
    title="Data & Backup" 
    description="Manage data backup and system maintenance"
    icon="fas fa-database"
>
    <div class="space-y-6">
        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Backup Settings</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <x-settings.form-input 
                    type="select"
                    label="Backup Frequency"
                    model="settings.backup.frequency"
                    :options="[
                        'daily' => 'Daily',
                        'weekly' => 'Weekly',
                        'monthly' => 'Monthly',
                        'manual' => 'Manual Only'
                    ]"
                />
                <x-settings.form-input 
                    type="number"
                    label="Retention Days"
                    model="settings.backup.retention_days"
                    :min="7"
                    :max="365"
                    description="How long to keep backup files"
                />
            </div>
        </div>

        <div class="space-y-4">
            <h4 class="text-sm font-medium text-white">Manual Actions</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <button 
                    @click="showBackupModal = true"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-download"></i>
                    Backup Now
                </button>
                <button 
                    @click="downloadBackup()"
                    class="flex items-center justify-center gap-2 px-4 py-3 bg-slate-600 hover:bg-slate-700 text-white rounded-lg font-medium transition-colors"
                >
                    <i class="fas fa-file-download"></i>
                    Download Latest
                </button>
            </div>
        </div>

        <div class="p-4 bg-slate-700/50 rounded-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h5 class="text-sm font-medium text-white">Last Backup</h5>
                    <p class="text-xs text-slate-300" x-text="lastBackupDate"></p>
                </div>
                <div class="text-right">
                    <p class="text-sm font-medium text-white" x-text="backupSize"></p>
                    <p class="text-xs text-slate-300">Backup Size</p>
                </div>
            </div>
        </div>

        <!-- Backup Confirmation Modal -->
        <div x-show="showBackupModal" x-cloak x-transition class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" style="display: none;">
            <div class="bg-white dark:bg-slate-800 rounded-xl p-6 max-w-md w-full mx-4">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg flex items-center justify-center">
                        <i class="fas fa-database text-indigo-600 dark:text-indigo-400"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Create Backup</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">This may take a few minutes</p>
                    </div>
                </div>
                
                <p class="text-sm text-slate-600 dark:text-slate-300 mb-6">
                    Are you sure you want to create a new backup? This will include all customer data, orders, and system settings.
                </p>
                
                <div class="flex gap-3">
                    <button 
                        @click="showBackupModal = false"
                        class="flex-1 px-4 py-2 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        @click="createBackup()"
                        :disabled="isBackingUp"
                        class="flex-1 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 text-white rounded-lg transition-colors"
                    >
                        <span x-show="!isBackingUp">Create Backup</span>
                        <span x-show="isBackingUp" class="flex items-center justify-center gap-2">
                            <i class="fas fa-spinner fa-spin"></i>
                            Creating...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</x-settings.settings-card>
