<!-- Cancel Schedule Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black/70 backdrop-blur-md hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col" id="deleteModalContent">
        <!-- Modal Header -->
        <div class="relative bg-gradient-to-r from-red-500/20 to-orange-500/20 border-b border-slate-700/50 rounded-t-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-red-500/10 to-orange-500/10 rounded-t-2xl"></div>
            <div class="relative flex items-center justify-between p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-orange-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Cancel Schedule</h3>
                        <p class="text-slate-300 text-sm">Order #<span id="cancel_schedule_id">-</span></p>
                    </div>
                </div>
                <button onclick="closeDeleteModal()" class="w-10 h-10 bg-slate-700/80 hover:bg-slate-600/80 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all duration-200 shadow-sm border border-slate-600/50">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800">
            <form id="deleteForm" class="space-y-6">
                <!-- Warning Message -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-red-500/30">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-exclamation-triangle text-red-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-red-400 mb-1">Are you sure?</p>
                            <p class="text-sm text-slate-300">This action cannot be undone. Your schedule will be permanently canceled.</p>
                        </div>
                    </div>
                </div>

                <!-- Cancellation Reason -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-200">Reason for cancellation (optional)</label>
                    <textarea name="cancellation_reason" id="cancellation_reason" rows="3" placeholder="Please tell us why you're canceling..." maxlength="200" class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-red-500/50 focus:border-red-500/50 transition-all resize-none text-sm"></textarea>
                    <div class="text-xs text-slate-400 text-right">
                        <span id="char_count">0</span>/200 characters
                    </div>
                </div>
                
                <!-- Hidden input for schedule ID -->
                <input type="hidden" name="schedule_id" id="delete_schedule_id" value="">
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-700/50 bg-slate-800/50 rounded-b-2xl">
            <button onclick="closeDeleteModal()" class="px-4 py-2 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-500 hover:to-slate-600 text-white rounded-lg transition-all duration-200 font-medium text-sm shadow-lg">
                Keep Schedule
            </button>
            <button onclick="submitDeleteForm()" class="px-4 py-2 bg-gradient-to-r from-red-500 to-orange-600 hover:from-red-600 hover:to-orange-700 text-white rounded-lg transition-all duration-200 font-medium text-sm shadow-lg hover:shadow-red-500/25 flex items-center gap-2">
                <i class="fas fa-trash text-xs"></i>
                Cancel Schedule
            </button>
        </div>
    </div>
</div>

<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/customer/schedules/modals/cancel-schedule.blade.php ENDPATH**/ ?>