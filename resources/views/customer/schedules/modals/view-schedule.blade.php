<!-- Schedule Details View Modal -->
<div id="viewModal" class="fixed inset-0 bg-black/70 backdrop-blur-md hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col" id="viewModalContent">
        <!-- Modal Header -->
        <div class="relative bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border-b border-slate-700/50 rounded-t-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 rounded-t-2xl"></div>
            <div class="relative flex items-center justify-between p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-eye text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">Schedule Details</h3>
                        <p class="text-slate-300 text-sm">Order #<span id="view_schedule_id">-</span></p>
                    </div>
                </div>
                <button onclick="closeViewModal()" class="w-10 h-10 bg-slate-700/80 hover:bg-slate-600/80 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all duration-200 shadow-sm border border-slate-600/50">
                    <i class="fas fa-times text-sm"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800">
            <!-- Order Information -->
            <div class="space-y-4">
                <!-- Service Information -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-concierge-bell text-emerald-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-slate-400">Service Type</p>
                            <p class="text-sm font-medium text-white" id="view_service_id">-</p>
                        </div>
                    </div>
                </div>

                <!-- Drop-off Information -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-down text-blue-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-slate-400">Drop-off</p>
                            <p class="text-sm font-medium text-white" id="view_dropoff_info">-</p>
                        </div>
                    </div>
                </div>

                <!-- Pickup Information -->
                <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center">
                            <i class="fas fa-arrow-up text-green-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-slate-400">Pickup</p>
                            <p class="text-sm font-medium text-white" id="view_pickup_info">-</p>
                        </div>
                    </div>
                </div>

                <!-- Weight & Price -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-purple-500/20 rounded flex items-center justify-center">
                                <i class="fas fa-weight text-purple-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Weight</p>
                                <p class="text-sm font-medium text-white" id="view_weight">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-yellow-500/20 rounded flex items-center justify-center">
                                <i class="fas fa-peso-sign text-yellow-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Price</p>
                                <p class="text-sm font-medium text-white" id="view_price">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status & Payment -->
                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-blue-500/20 rounded flex items-center justify-center">
                                <i class="fas fa-info-circle text-blue-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Status</p>
                                <p class="text-sm font-medium text-white" id="view_status">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-green-500/20 rounded flex items-center justify-center">
                                <i class="fas fa-credit-card text-green-400 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400">Payment</p>
                                <p class="text-sm font-medium text-white" id="view_payment_status">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes Section -->
                <div id="view_notes_container" class="bg-slate-700/30 rounded-lg p-4 border border-slate-600/30 hidden">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 bg-slate-500/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-sticky-note text-slate-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-xs text-slate-400 mb-1">Notes</p>
                            <p class="text-sm text-slate-300" id="view_notes">-</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-700/50 bg-slate-800/50 rounded-b-2xl">
            <button onclick="closeViewModal()" class="px-6 py-3 bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-500 hover:to-slate-600 text-white rounded-xl transition-all duration-200 font-medium text-sm shadow-lg">
                Close
            </button>
        </div>
    </div>
</div>

