@push('styles')
@vite(['resources/css/announcements.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/announcements/index.js'])
@endpush

<!-- Modern SaaS Modal -->
<div 
    x-show="isModalOpen" 
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[9999] overflow-hidden"
    style="display: none;"
    x-cloak
    @keydown.escape.window="closeModal()"
    @click.self="closeModal()"
>
    <!-- Backdrop -->
    <div class="fixed inset-0 modern-saas-modal"></div>
    
    <!-- Modal Container -->
    <div class="fixed inset-0 flex items-center justify-center p-4 sm:p-6">
        <div 
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95 translate-y-4"
            x-transition:enter-end="opacity-100 scale-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100 translate-y-0"
            x-transition:leave-end="opacity-0 scale-95 translate-y-4"
            class="relative w-full max-w-2xl max-h-[80vh] bg-slate-900 rounded-xl modern-saas-modal-content border border-indigo-500/20 flex flex-col"
            @click.stop
        >
            <!-- Header -->
            <div class="flex items-center justify-between px-6 py-5 border-b border-indigo-500/20">
                <div>
                    <h3 class="text-xl font-semibold text-white" x-text="isEdit ? 'Edit Announcement' : 'Add Announcement'"></h3>
                    <p class="text-sm text-slate-400 mt-1">Create and configure your announcement settings</p>
                </div>
                <button 
                    @click="closeModal()"
                    class="p-2 hover:bg-slate-700 rounded-lg transition-colors group"
                    aria-label="Close modal"
                >
                    <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Scrollable Body -->
            <div class="flex-1 overflow-y-auto modal-body-scroll">
                <form id="announcement-form" @submit.prevent="submitForm()" class="p-6 space-y-6">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Title <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="text" 
                            x-model="form.title"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            placeholder="Enter announcement title"
                            required
                        >
                        <div x-show="errors.title" class="mt-1 text-sm text-red-400" x-text="errors.title"></div>
                    </div>

                    <!-- Message -->
                    <div>
                        <label class="block text-sm font-medium text-slate-300 mb-2">
                            Message <span class="text-red-400">*</span>
                        </label>
                        <textarea 
                            x-model="form.message"
                            rows="4"
                            class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors resize-none"
                            placeholder="Enter announcement message"
                            required
                        ></textarea>
                        <div x-show="errors.message" class="mt-1 text-sm text-red-400" x-text="errors.message"></div>
                    </div>

                <!-- Type and Visibility Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Type <span class="text-red-400">*</span>
                            </label>
                            <select 
                                x-model="form.type"
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                required
                            >
                                <option value="new">New</option>
                                <option value="improvement">Improvement</option>
                                <option value="fix">Fix</option>
                                <option value="maintenance">Maintenance</option>
                                <option value="alert">Alert</option>
                            </select>
                            <div x-show="errors.type" class="mt-1 text-sm text-red-400" x-text="errors.type"></div>
                        </div>

                        <!-- Visible To -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Visible To <span class="text-red-400">*</span>
                            </label>
                            <select 
                                x-model="form.visible_to"
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                required
                            >
                                <option value="all">All Users</option>
                                <option value="admin">Administrators Only</option>
                                <option value="staff">Staff Only</option>
                                <option value="customer">Customers Only</option>
                            </select>
                            <div x-show="errors.visible_to" class="mt-1 text-sm text-red-400" x-text="errors.visible_to"></div>
                        </div>
                    </div>

                    <!-- Link and Expires Row -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Link -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Link (Optional)
                            </label>
                            <input 
                                type="url" 
                                x-model="form.link"
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                                placeholder="https://example.com"
                            >
                            <div x-show="errors.link" class="mt-1 text-sm text-red-400" x-text="errors.link"></div>
                        </div>

                        <!-- Expires At -->
                        <div>
                            <label class="block text-sm font-medium text-slate-300 mb-2">
                                Expires At (Optional)
                            </label>
                            <input 
                                type="datetime-local" 
                                x-model="form.expires_at"
                                class="w-full px-4 py-3 bg-slate-700 border border-slate-600 rounded-lg text-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                            >
                            <div x-show="errors.expires_at" class="mt-1 text-sm text-red-400" x-text="errors.expires_at"></div>
                        </div>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                x-model="form.is_pinned"
                                class="w-4 h-4 text-indigo-600 bg-slate-700 border-slate-600 rounded focus:ring-indigo-500 focus:ring-2"
                            >
                            <label class="ml-3 text-sm text-slate-300">Pin this announcement to the top</label>
                        </div>
                        <div class="flex items-center">
                            <input 
                                type="checkbox" 
                                x-model="form.is_active"
                                class="w-4 h-4 text-indigo-600 bg-slate-700 border-slate-600 rounded focus:ring-indigo-500 focus:ring-2"
                            >
                            <label class="ml-3 text-sm text-slate-300">Make this announcement active</label>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Sticky Footer -->
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-900 border-t border-indigo-500/20 rounded-b-xl">
                <button 
                    type="button"
                    @click="closeModal()"
                    class="px-6 py-2.5 text-slate-300 bg-slate-700 border border-slate-600 rounded-lg hover:bg-slate-600 transition-colors font-medium modern-button"
                >
                    Cancel
                </button>
                <button 
                    type="submit"
                    form="announcement-form"
                    :disabled="isLoading"
                    class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition-colors font-medium flex items-center gap-2 modern-button"
                >
                    <svg x-show="isLoading" class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span x-text="isLoading ? 'Saving...' : (isEdit ? 'Update Announcement' : 'Create Announcement')"></span>
                </button>
            </div>
        </div>
    </div>
</div>
