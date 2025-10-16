<!-- New Schedule Modal -->
<div id="scheduleModal" class="fixed inset-0 bg-black/70 backdrop-blur-md hidden z-[9999] flex items-center justify-center p-4">
    <div class="bg-gradient-to-br from-slate-800 to-slate-900 rounded-2xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] transform transition-all duration-300 scale-95 opacity-0 flex flex-col" id="modalContent">
        <!-- Modal Header -->
        <div class="relative bg-gradient-to-r from-emerald-500/20 to-teal-500/20 border-b border-slate-700/50 rounded-t-2xl">
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-500/10 to-teal-500/10 rounded-t-2xl"></div>
            <div class="relative flex items-center justify-between p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-plus text-white text-xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-white">New Schedule</h3>
                        <p class="text-slate-300 text-sm">Schedule your laundry service</p>
                    </div>
                </div>
                <button onclick="closeScheduleModal()" class="w-10 h-10 bg-slate-700/50 hover:bg-slate-600/50 rounded-xl flex items-center justify-center text-slate-400 hover:text-white transition-all">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
        </div>

        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto flex-1 min-h-0 scrollbar-thin scrollbar-thumb-slate-600 scrollbar-track-slate-800">
            <form id="scheduleForm" class="space-y-6">
                <!-- Service Selection -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-200">Service Type</label>
                    <select name="service_id" required class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                        <option value="">Select a service</option>
                        @foreach($services as $service)
                            <option value="{{ $service->id }}">{{ $service->name }} - ₱{{ number_format($service->price, 2) }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Drop-off Date -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-200">Drop-off Date</label>
                    <input type="date" name="dropoff_date" required class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                </div>

                <!-- Pickup Date -->
                <div class="space-y-3">
                    <label class="block text-sm font-semibold text-slate-200">Pickup Date</label>
                    <input type="date" name="pickup_date" required class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                </div>

            </form>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end gap-3 p-6 border-t border-slate-700/50 bg-slate-800/50 rounded-b-2xl">
            <button onclick="closeScheduleModal()" class="px-6 py-2.5 text-slate-400 hover:text-white hover:bg-slate-700/50 rounded-xl transition-all font-medium">
                Cancel
            </button>
            <button onclick="submitScheduleForm()" class="px-6 py-2.5 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white rounded-xl transition-all font-medium shadow-lg hover:shadow-emerald-500/25">
                <i class="fas fa-calendar-plus mr-2"></i>
                Create Schedule
            </button>
        </div>
    </div>
</div>

