@props([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'currentPage' => 1,
    'emptyMessage' => 'No data found',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => true,
    'customClass' => 'bg-slate-800 text-slate-200',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'addButton' => false,
    'addButtonLabel' => 'Add',
    'addButtonAction' => null,
    'showRoleFilter' => false,
    'availableRoles' => [],
    'colorScheme' => 'sky' // 'sky' for Admin, 'indigo' for Super Admin
])

<style>
    .custom-scrollbar::-webkit-scrollbar {
        height: 8px;
        width: 8px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #1e293b;
        border-radius: 4px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #475569;
        border-radius: 4px;
        border: 1px solid #334155;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: #64748b;
    }
    
    .custom-scrollbar::-webkit-scrollbar-corner {
        background: #1e293b;
    }
</style>

<!-- Reusable Data Table Component -->
<div 
    x-data="{
        data: @js($data),
        searchTerm: '',
        statusFilter: 'all',
        roleFilter: 'all',
        sortKey: '',
        sortDirection: 'asc',
        displayedData: [],
        currentPage: 1,
        pageSize: {{ $pageSize }},
        selectedRows: [],

        init() { 
            this.applyFilters();
        },

        applyFilters() {
            let filtered = this.data.filter(row => {
                const matchesSearch = !this.searchTerm || 
                    Object.values(row).some(value => 
                        String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
                    );
                const matchesStatus = this.statusFilter === 'all' 
                    || row.status?.toLowerCase() === this.statusFilter;
                const matchesRole = this.roleFilter === 'all' 
                    || row.role?.toLowerCase() === this.roleFilter
                    || row.role_name?.toLowerCase() === this.roleFilter;
                return matchesSearch && matchesStatus && matchesRole;
            });

            if (this.sortKey) {
                filtered.sort((a, b) => {
                    let aVal = a[this.sortKey] ?? '';
                    let bVal = b[this.sortKey] ?? '';

                    if (!isNaN(aVal) && !isNaN(bVal)) {
                        return this.sortDirection === 'asc' ? aVal - bVal : bVal - aVal;
                    }

                    aVal = aVal.toString().toLowerCase();
                    bVal = bVal.toString().toLowerCase();

                    if (aVal < bVal) return this.sortDirection === 'asc' ? -1 : 1;
                    if (aVal > bVal) return this.sortDirection === 'asc' ? 1 : -1;
                    return 0;
                });
            }

            this.displayedData = filtered;
            this.currentPage = 1;
        },

        sort(columnKey) {
            if (!columnKey) return;
            if (this.sortKey === columnKey) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortKey = columnKey;
                this.sortDirection = 'asc';
            }
            this.applyFilters();
        },

        search() { this.applyFilters() },
        filterStatus() { this.applyFilters() },
        filterRole() { this.applyFilters() },

        goToPage(page) {
            if (page < 1) page = 1;
            if (page > this.totalPages) page = this.totalPages;
            this.currentPage = page;
        },

        changePageSize() {
            // Reset to first page when page size changes
            this.currentPage = 1;
            this.applyFilters();
        },

        get paginatedData() {
            const start = (this.currentPage - 1) * this.pageSize;
            const end = start + this.pageSize;
            return this.displayedData.slice(start, end);
        },

        get totalPages() {
            return Math.ceil(this.displayedData.length / this.pageSize);
        },

        get totalRecords() {
            return this.displayedData.length;
        },

        formatValue(key, value) {
            if (key.toLowerCase() === 'price' || key.toLowerCase() === 'amount' || key.toLowerCase() === 'total_price') {
                return `₱${Number(value).toLocaleString()}`;
            }
            if (key.toLowerCase() === 'created_at') {
                return new Date(value).toLocaleDateString('en-US', { 
                    year: 'numeric', month: 'long', day: 'numeric' 
                });
            }
            return value;
        },

        statusClass(status) {
            const statusLower = status.toLowerCase();
            
            // Active/Completed statuses - Green
            if (statusLower === 'active' || statusLower === 'completed' || statusLower === 'confirmed' || statusLower === 'paid' || statusLower === 'in stock') {
                return 'flex items-center gap-1 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 px-3 py-0.5 rounded-full text-xs font-medium';
            }
            
            // Pending/Waiting statuses - Yellow/Orange
            if (statusLower === 'pending' || statusLower === 'waiting' || statusLower === 'scheduled' || statusLower === 'priced' || statusLower === 'low stock') {
                return 'flex items-center gap-1 bg-yellow-500/20 text-yellow-400 border border-yellow-500/30 px-3 py-0.5 rounded-full text-xs font-medium';
            }
            
            // In Progress statuses - Blue
            if (statusLower === 'in progress' || statusLower === 'processing' || statusLower === 'working') {
                return 'flex items-center gap-1 bg-blue-500/20 text-blue-400 border border-blue-500/30 px-3 py-0.5 rounded-full text-xs font-medium';
            }
            
            // Cancelled/Inactive statuses - Red
            if (statusLower === 'cancelled' || statusLower === 'inactive' || statusLower === 'failed' || statusLower === 'rejected' || statusLower === 'out of stock') {
                return 'flex items-center gap-1 bg-rose-500/20 text-rose-400 border border-rose-500/30 px-3 py-0.5 rounded-full text-xs font-medium';
            }
            
            // Default - Gray for unknown statuses
            return 'flex items-center gap-1 bg-gray-500/20 text-gray-400 border border-gray-500/30 px-3 py-0.5 rounded-full text-xs font-medium';
        },

        getStatusDotColor(status) {
            const statusLower = status.toLowerCase();
            
            // Active/Completed statuses - Green
            if (statusLower === 'active' || statusLower === 'completed' || statusLower === 'confirmed' || statusLower === 'paid' || statusLower === 'in stock') {
                return 'bg-emerald-400';
            }
            
            // Pending/Waiting statuses - Yellow/Orange
            if (statusLower === 'pending' || statusLower === 'waiting' || statusLower === 'scheduled' || statusLower === 'priced' || statusLower === 'low stock') {
                return 'bg-yellow-400';
            }
            
            // In Progress statuses - Blue
            if (statusLower === 'in progress' || statusLower === 'processing' || statusLower === 'working') {
                return 'bg-blue-400';
            }
            
            // Cancelled/Inactive statuses - Red
            if (statusLower === 'cancelled' || statusLower === 'inactive' || statusLower === 'failed' || statusLower === 'rejected' || statusLower === 'out of stock') {
                return 'bg-rose-400';
            }
            
            // Default - Gray for unknown statuses
            return 'bg-gray-400';
        },

        handleAction(row, action) {
            if (action === 'add') {
                if (window['{{ $addButtonAction }}']) {
                    window['{{ $addButtonAction }}']();
                }
            } else {
                // Find the action in the actions array
                const actionConfig = @js($actions).find(a => a.label.toLowerCase() === action.toLowerCase());
                if (actionConfig && window[actionConfig.onclick]) {
                    window[actionConfig.onclick](row);
                }
            }
        }
    }"
    x-init="init()"
    class="w-full bg-slate-900 text-slate-50 p-6 rounded-xl shadow-xl border border-slate-800 {{ $customClass }}"
>
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div class="flex flex-col gap-2">
            <div class="flex items-center gap-3">
                <div class="w-1 h-8 bg-gradient-to-b from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} rounded-full"></div>
                <h2 class="text-2xl font-bold text-slate-50 tracking-wide">{{ $title }}</h2>
            </div>
            <p class="text-slate-400 text-sm ml-4">{{ $description }}</p>
        </div>
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            @if($searchable)
            <!-- Search -->
            <div class="relative w-full md:w-64">
                <input 
                    type="text" 
                    placeholder="Search {{ strtolower($title) }}..." 
                    class="w-full pl-10 pr-4 py-2 rounded-lg border border-slate-700 bg-slate-800 text-slate-50 placeholder-slate-400 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} text-sm transition-all"
                    x-model="searchTerm"
                    @input="search"
                >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400 absolute left-3 top-2.5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.9 14.32a8 8 0 111.414-1.414l4.387 4.386a1 1 0 01-1.414 1.415l-4.387-4.387zM14 8a6 6 0 11-12 0 6 6 0 0112 0z" clip-rule="evenodd" />
                </svg>
            </div>
            @endif
            
            
            @if($addButton)
            <!-- Add New -->
            <button @click="handleAction({}, 'add')" class="px-4 py-2 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} hover:from-{{ $colorScheme === 'indigo' ? 'indigo-600' : 'sky-600' }} hover:to-{{ $colorScheme === 'indigo' ? 'purple-600' : 'cyan-600' }} text-white rounded-lg text-sm font-medium shadow-lg transition-all duration-200 hover:shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                {{ $addButtonLabel }}
            </button>
            @endif
        </div>
    </div>

    <!-- Filters -->
    <div class="mb-4 flex flex-wrap items-center gap-3">
        <!-- Status Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium">Status:</label>
            <select 
                x-model="statusFilter" 
                @change="filterStatus"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
            >
                <option value="all">All statuses</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        @if($showRoleFilter)
        <!-- Role Filter -->
        <div class="flex items-center gap-2">
            <label class="text-sm text-slate-300 font-medium">Role:</label>
            <select 
                x-model="roleFilter" 
                @change="filterRole"
                class="border border-slate-700 rounded-lg px-3 py-2 text-sm text-slate-50 bg-slate-800 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
            >
                <option value="all">All roles</option>
                @if(!empty($availableRoles))
                    @foreach($availableRoles as $role)
                        <option value="{{ strtolower($role->name) }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                @else
                    <!-- Fallback to default roles if no availableRoles provided -->
                    <option value="superadmin">Super Admin</option>
                    <option value="administrator">Administrator</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                @endif
            </select>
        </div>
        @endif
    </div>

    <!-- Table -->
    <div class="overflow-x-auto rounded-xl shadow-lg border border-slate-700/50 custom-scrollbar" style="scrollbar-width: thin; scrollbar-color: #475569 #1e293b;">
        <table class="min-w-full text-sm text-left text-slate-50 rounded-xl overflow-hidden bg-slate-900">
            <thead class="bg-gradient-to-r from-slate-800 via-slate-700 to-slate-800 text-slate-100 uppercase text-xs font-semibold tracking-wider border-b border-slate-600/50 shadow-sm">
                <tr>
                    
                    @foreach($columns as $column)
                    <th 
                        class="px-4 py-3 cursor-pointer select-none hover:text-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all duration-200 whitespace-nowrap {{ $sortable ? '' : 'cursor-default' }}"
                        @if($sortable) @click="sort('{{ $column['key'] }}')" @endif
                    >
                        <div class="flex items-center gap-1 {{ $column['key'] === 'id' ? 'justify-center' : '' }}">
                            <span class="font-medium">{{ $column['label'] }}</span>
                            @if($sortable)
                            <span x-show="sortKey === '{{ $column['key'] }}'">
                                <svg x-show="sortDirection === 'asc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/></svg>
                                <svg x-show="sortDirection === 'desc'" xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </span>
                            @endif
                        </div>
                    </th>
                    @endforeach
                    
                    @if(count($actions) > 0)
                    <th class="px-4 py-3 text-center w-16">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                <template x-for="(row, rowIndex) in paginatedData" :key="row.id">
                    <tr 
                        class="border-b border-slate-700/30 transition-all duration-200 {{ $hoverEffects ? 'hover:bg-slate-800/60 hover:shadow-sm' : '' }}"
                        :class="{{ $alternatingRows ? 'rowIndex % 2 === 0 ? \'bg-slate-900/50\' : \'bg-slate-800/20\'' : 'bg-slate-900/50' }}"
                    >
                        
                        @foreach($columns as $column)
                        <td 
                            class="px-4 py-3 text-slate-100 font-normal whitespace-nowrap"
                            :class="{ 'text-center': '{{ $column['key'] }}' === 'id' }"
                        >
                            <!-- Status -->
                            <template x-if="'{{ $column['key'] }}' === 'status' || '{{ $column['key'] }}' === 'payment_status'">
                                <span :class="statusClass(row['{{ $column['key'] }}'])">
                                    <span class="w-2 h-2 rounded-full" :class="getStatusDotColor(row['{{ $column['key'] }}'])"></span>
                                    <span x-text="row['{{ $column['key'] }}']"></span>
                                </span>
                            </template>
                            
                            <!-- Price/Amount -->
                            <template x-if="'{{ $column['key'] }}' === 'price' || '{{ $column['key'] }}' === 'amount' || '{{ $column['key'] }}' === 'total_price'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <!-- Description (truncate) -->
                            <template x-if="'{{ $column['key'] }}' === 'description' || '{{ $column['key'] }}' === 'message'">
                                <span class="truncate max-w-xs block" :title="row['{{ $column['key'] }}']" x-text="row['{{ $column['key'] }}']"></span>
                            </template>
                            
                            <!-- Created By -->
                            <template x-if="'{{ $column['key'] }}' === 'created_by'">
                                <span x-text="row.created_by_name || row.created_by_user_name || 'System'"></span>
                            </template>
                            
                            <!-- Default -->
                            <template x-if="'{{ $column['key'] }}' !== 'status' && '{{ $column['key'] }}' !== 'payment_status' && '{{ $column['key'] }}' !== 'price' && '{{ $column['key'] }}' !== 'amount' && '{{ $column['key'] }}' !== 'total_price' && '{{ $column['key'] }}' !== 'description' && '{{ $column['key'] }}' !== 'message' && '{{ $column['key'] }}' !== 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                        </td>
                        @endforeach
                        
                        @if(count($actions) > 0)
                        <!-- Action menu -->
                        <td class="px-4 py-3 text-center">
                            <div class="relative" x-data="{ open: false, position: 'bottom' }" 
                                x-init="
                                    $watch('open', value => {
                                        if (value) {
                                            $nextTick(() => {
                                                let menu = $el.querySelector('.action-menu');
                                                if (!menu) return;
                                                let rect = menu.getBoundingClientRect();
                                                let viewportHeight = window.innerHeight;
                                                position = (rect.bottom > viewportHeight) ? 'top' : 'bottom';
                                            });
                                        }
                                    })
                                ">
                                <button @click="open = !open" class="p-2 rounded-full hover:bg-slate-800 focus:outline-none transition-all duration-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm-6 0a2 2 0 114 0 2 2 0 01-4 0zm12 0a2 2 0 114 0 2 2 0 01-4 0z"/>
                                    </svg>
                                </button>
                                <div 
                                    x-show="open" 
                                    x-transition.scale.opacity
                                    @click.away="open=false" 
                                    class="action-menu absolute w-40 bg-slate-800/95 backdrop-blur-sm border border-slate-700 rounded-lg shadow-xl py-1 z-50"
                                    :class="position === 'bottom' ? 'right-0 mt-2' : 'right-0 bottom-full mb-2'"
                                >
                                    @foreach($actions as $action)
                                    <button @click="handleAction(row, '{{ strtolower($action['label']) }}'); open=false" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-slate-200 hover:bg-slate-700 transition-all duration-200">
                                        {{ $action['label'] }}
                                    </button>
                                    @endforeach
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                </template>
                
                <!-- Empty State -->
                <tr x-show="displayedData.length === 0">
                    <td :colspan="{{ count($columns) + ($bulkActions ? 1 : 0) + (count($actions) > 0 ? 1 : 0) }}" class="px-4 py-16 text-center text-slate-400">
                        <div class="flex flex-col items-center gap-4">
                            <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div class="text-center">
                                <h3 class="text-lg font-medium text-slate-300 mb-1">No data found</h3>
                                <p class="text-sm text-slate-500">{{ $emptyMessage }}</p>
                            </div>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    @if($pagination)
    <!-- Pagination -->
    <div class="flex flex-col md:flex-row justify-between items-center mt-6 gap-4 bg-gradient-to-r from-slate-800/80 to-slate-700/80 border border-slate-600/50 rounded-lg px-4 py-3 shadow-lg backdrop-blur-sm">
        <div class="flex flex-col sm:flex-row items-center gap-3">
            <div class="text-slate-400 text-sm">
                Showing 
                <span x-text="totalRecords > 0 ? (currentPage - 1) * pageSize + 1 : 0"></span> - 
                <span x-text="Math.min(currentPage * pageSize, totalRecords)"></span> 
                of <span x-text="totalRecords"></span>
            </div>
            
            <!-- Rows per page -->
            <div class="flex items-center gap-2">
                <label class="text-slate-400 text-sm">Rows per page:</label>
                <select 
                    x-model="pageSize" 
                    @change="changePageSize"
                    class="border border-slate-600 rounded-lg px-2 py-1 text-sm text-slate-50 bg-slate-700 focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} focus:border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all"
                >
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button 
                @click="goToPage(currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm"
            >
                Prev
            </button>
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="goToPage(page)" 
                    :class="page === currentPage ? 'bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} text-white border-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} shadow-lg shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25' : 'bg-slate-700/60 text-slate-300 hover:bg-slate-600/80 hover:border-slate-500'"
                    class="px-3 py-1.5 rounded-full border border-slate-600/50 text-sm transition-all duration-200"
                    x-text="page"
                ></button>
            </template>
            <button 
                @click="goToPage(currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-3 py-1.5 bg-slate-700/60 border border-slate-600/50 rounded-full text-slate-300 disabled:opacity-40 disabled:cursor-not-allowed hover:bg-slate-600/80 hover:text-slate-100 hover:border-slate-500 transition-all duration-200 text-sm"
            >
                Next
            </button>
        </div>
    </div>
    @endif
</div>