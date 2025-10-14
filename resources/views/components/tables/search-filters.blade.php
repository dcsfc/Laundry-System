<!-- Professional Search and Filters Component -->
@php($showRoleFilter = $showRoleFilter ?? true)
<div class="bg-slate-800 border-b border-slate-700 px-6 py-4">
    <div class="flex items-center gap-3 overflow-visible">
        <!-- Search Input -->
        <div class="flex-1 max-w-sm group">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400 group-focus-within:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    class="block w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-sm text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 hover:border-blue-500/60 transition-all duration-200 ease-in-out shadow-sm" 
                    placeholder="Search by name or email..." 
                    x-model="searchQuery"
                    @input="handleSearch()"
                    @focus="$el.parentElement.parentElement.classList.add('search-focused')"
                    @blur="$el.parentElement.parentElement.classList.remove('search-focused')"
                >
                <!-- Clear search button -->
                <button 
                    type="button"
                    x-show="searchQuery && searchQuery.length > 0"
                    @click="clearSearch()"
                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-200 transition-colors duration-200"
                >
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Role Filter (optional) -->
        @if($showRoleFilter)
        <div class="relative group">
            <select 
                class="appearance-none bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 hover:border-blue-500/60 transition-all duration-200 ease-in-out cursor-pointer shadow-sm min-w-[140px] group-hover:shadow-md" 
                :class="{ 'border-blue-500/60 bg-blue-500/5': roleFilter }"
                x-model="roleFilter" 
                @change="handleFilterChange('roleFilter', $event.target.value)"
            >
                <option value="" class="bg-slate-900 text-gray-200">All Roles</option>
                <option value="superadmin" class="bg-slate-900 text-gray-200">Super Admin</option>
                <option value="administrator" class="bg-slate-900 text-gray-200">Administrator</option>
                <option value="staff" class="bg-slate-900 text-gray-200">Staff</option>
                <option value="customer" class="bg-slate-900 text-gray-200">Customer</option>
            </select>
            <!-- Custom dropdown arrow -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <!-- Active filter indicator -->
            <div x-show="roleFilter" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-slate-800"></div>
        </div>
        @endif

        <!-- Status Filter -->
        <div class="relative group">
            <select 
                class="appearance-none bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 hover:border-blue-500/60 transition-all duration-200 ease-in-out cursor-pointer shadow-sm min-w-[140px] group-hover:shadow-md" 
                :class="{ 'border-blue-500/60 bg-blue-500/5': statusFilter }"
                x-model="statusFilter" 
                @change="handleFilterChange('statusFilter', $event.target.value)"
            >
                <option value="" class="bg-slate-900 text-gray-200">All Status</option>
                <option value="scheduled" class="bg-slate-900 text-gray-200">Scheduled</option>
                <option value="priced" class="bg-slate-900 text-gray-200">Priced</option>
                <option value="in_progress" class="bg-slate-900 text-gray-200">In Progress</option>
                <option value="completed" class="bg-slate-900 text-gray-200">Completed</option>
                <option value="cancelled" class="bg-slate-900 text-gray-200">Cancelled</option>
            </select>
            <!-- Custom dropdown arrow -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <!-- Active filter indicator -->
            <div x-show="statusFilter" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-slate-800"></div>
        </div>

        <!-- Payment Status Filter -->
        <div class="relative group">
            <select 
                class="appearance-none bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 hover:border-blue-500/60 transition-all duration-200 ease-in-out cursor-pointer shadow-sm min-w-[140px] group-hover:shadow-md" 
                :class="{ 'border-blue-500/60 bg-blue-500/5': paymentFilter }"
                x-model="paymentFilter" 
                @change="handleFilterChange('paymentFilter', $event.target.value)"
            >
                <option value="" class="bg-slate-900 text-gray-200">All Payments</option>
                <option value="paid" class="bg-slate-900 text-gray-200">Paid</option>
                <option value="unpaid" class="bg-slate-900 text-gray-200">Unpaid</option>
            </select>
            <!-- Custom dropdown arrow -->
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                <svg class="h-4 w-4 text-gray-400 group-hover:text-blue-400 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <!-- Active filter indicator -->
            <div x-show="paymentFilter" class="absolute -top-1 -right-1 w-3 h-3 bg-blue-500 rounded-full border-2 border-slate-800"></div>
        </div>
        
        <!-- Clear Filters Button -->
        <button 
            type="button" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-slate-600 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-200 ease-in-out shadow-sm hover:shadow-md group" 
            :class="{ 'border-blue-500/60 bg-blue-500/5': searchQuery || (typeof roleFilter !== 'undefined' && roleFilter) || statusFilter || paymentFilter }"
            @click="clearAllFilters()"
            x-show="searchQuery || (typeof roleFilter !== 'undefined' && roleFilter) || statusFilter || paymentFilter"
        >
            <svg class="h-4 w-4 group-hover:rotate-90 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
            Clear Filters
        </button>
    </div>
</div>
