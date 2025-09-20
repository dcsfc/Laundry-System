@props([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'emptyMessage' => 'No data found',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'customClass' => 'bg-slate-800 text-slate-200',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'showRoleFilter' => false,
    'availableRoles' => [],
    'colorScheme' => 'sky'
])

@vite('resources/js/app.js')

<!-- Reusable Data Table Component -->
<div 
    x-data="dataTable(@js($data), @js($actions), @js($columns), {{ $pageSize }}, '{{ $colorScheme }}')"
    x-init="init()"
    @keydown.escape.window="closeAllMenus(); if (showAddModal) closeAddModal();"
    class="data-table-container w-full bg-slate-900 text-slate-50 p-6 rounded-xl shadow-xl border border-slate-800 {{ $customClass }}"
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
            
            <!-- Add Button -->
            <button 
                @click="handleAction({}, 'add')" 
                class="px-4 py-2 bg-gradient-to-r from-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }} to-{{ $colorScheme === 'indigo' ? 'purple-500' : 'cyan-500' }} hover:from-{{ $colorScheme === 'indigo' ? 'indigo-600' : 'sky-600' }} hover:to-{{ $colorScheme === 'indigo' ? 'purple-600' : 'cyan-600' }} text-white rounded-lg text-sm font-medium shadow-lg transition-all duration-200 hover:shadow-{{ $colorScheme === 'indigo' ? 'indigo-500' : 'sky-500' }}/25 flex items-center gap-2"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New
            </button>
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
                <option value="pending">Pending</option>
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
                                    <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                                </span>
                            </template>
                            
                            <!-- Price/Amount -->
                            <template x-if="'{{ $column['key'] }}' === 'price' || '{{ $column['key'] }}' === 'amount' || '{{ $column['key'] }}' === 'total_price'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <!-- Description (truncate) -->
                            <template x-if="'{{ $column['key'] }}' === 'description' || '{{ $column['key'] }}' === 'message'">
                                <span class="truncate max-w-xs block" :title="row['{{ $column['key'] }}']" x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                            
                            <!-- Created By -->
                            <template x-if="'{{ $column['key'] }}' === 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row.created_by_name || row.created_by_user_name || 'System')"></span>
                            </template>
                            
                            <!-- Default -->
                            <template x-if="'{{ $column['key'] }}' !== 'status' && '{{ $column['key'] }}' !== 'payment_status' && '{{ $column['key'] }}' !== 'price' && '{{ $column['key'] }}' !== 'amount' && '{{ $column['key'] }}' !== 'total_price' && '{{ $column['key'] }}' !== 'description' && '{{ $column['key'] }}' !== 'message' && '{{ $column['key'] }}' !== 'created_by'">
                                <span x-text="formatValue('{{ $column['key'] }}', row['{{ $column['key'] }}'])"></span>
                            </template>
                        </td>
                        @endforeach
                        
                        @if(count($actions) > 0)
                        <!-- Action menu cell -->
                        <td class="px-4 py-3 text-center relative">
                            <button 
                                :id="'action-btn-' + row.id"
                                @click="toggleMenu(row.id)" 
                                class="p-2 rounded-full hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-{{ $colorScheme === 'indigo' ? 'indigo-400' : 'sky-400' }} transition-all duration-200"
                                aria-haspopup="true"
                                :aria-expanded="openMenuId === row.id"
                                aria-label="Row actions"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-slate-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M6 10a2 2 0 114 0 2 2 0 01-4 0zm-6 0a2 2 0 114 0 2 2 0 01-4 0zm12 0a2 2 0 114 0 2 2 0 01-4 0z"/>
                                </svg>
                            </button>

                            <!-- Desktop Dropdown Menu -->
                            <div 
                                x-show="!isMobile && openMenuId === row.id"
                                :id="'action-menu-' + row.id"
                                :style="menuPosition"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                @click.away="closeAllMenus()"
                                class="fixed z-[99999] w-44 bg-slate-800/95 backdrop-blur-sm border border-slate-700/50 rounded-lg shadow-xl py-1"
                                role="menu" 
                                aria-orientation="vertical"
                                aria-labelledby="'action-btn-' + row.id"
                                tabindex="-1"
                            >
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-3 w-full text-left px-4 py-2.5 text-sm text-slate-200 hover:bg-slate-700/80 hover:text-white transition-all duration-200 focus:outline-none focus:bg-slate-700/80 focus:text-white"
                                    role="menuitem"
                                    tabindex="-1"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                            </div>

                            <!-- Mobile Bottom Sheet -->
                            <div 
                                x-show="isMobile && openMenuId === row.id"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="transform translate-y-full"
                                x-transition:enter-end="transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="transform translate-y-0"
                                x-transition:leave-end="transform translate-y-full"
                                @click.away="closeAllMenus()"
                                class="fixed inset-x-0 bottom-0 z-[99999] bg-slate-900 rounded-t-2xl shadow-2xl p-4"
                                role="menu" 
                                aria-orientation="vertical"
                                aria-labelledby="'action-btn-' + row.id"
                            >
                                <!-- Drag Handle -->
                                <div class="w-12 h-1.5 bg-slate-700 rounded-full mx-auto mb-4"></div>
                                
                                <!-- Menu Items -->
                                @foreach($actions as $action)
                                <button 
                                    @click="handleAction(row, '{{ strtolower($action['label']) }}'); closeAllMenus()" 
                                    class="flex items-center gap-4 w-full text-left px-4 py-4 text-base text-slate-200 hover:bg-slate-800/50 rounded-lg transition-all duration-200 focus:outline-none focus:bg-slate-800/50"
                                    role="menuitem"
                                >
                                    <span x-html="getActionIcon('{{ $action['label'] }}')"></span>
                                    <span class="font-medium">{{ $action['label'] }}</span>
                                </button>
                                @endforeach
                                
                                <!-- Cancel Button -->
                                <button 
                                    @click="closeAllMenus()" 
                                    class="w-full mt-4 py-3 text-center text-slate-400 hover:text-slate-300 transition-colors duration-200"
                                >
                                    Cancel
                                </button>
                            </div>
                        </td>
                        @endif
                    </tr>
                </template>
                
                <!-- Empty State -->
                <tr x-show="displayedData.length === 0">
                    <td :colspan="{{ count($columns) + (count($actions) > 0 ? 1 : 0) }}" class="px-4 py-16 text-center text-slate-400">
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

    <!-- Add/Edit Modal -->
    <div 
        x-show="showAddModal"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 overflow-y-auto"
        style="display: none;"
    >
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black bg-opacity-50" @click="closeAddModal()"></div>
        
        <!-- Modal Container -->
        <div class="relative flex min-h-full items-center justify-center p-4">
            <div 
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 transform scale-95"
                x-transition:enter-end="opacity-100 transform scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 transform scale-100"
                x-transition:leave-end="opacity-0 transform scale-95"
                class="relative w-full max-w-2xl transform overflow-hidden rounded-xl bg-white dark:bg-gray-900 shadow-2xl"
            >
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            <span x-text="formData.id ? 'Edit Item' : 'Add New Item'"></span>
                        </h3>
                        <button 
                            @click="closeAddModal()"
                            class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Form Content -->
                <form @submit.prevent="submitForm()">
                    <div class="px-6 py-4">
                        <div class="space-y-4">
                            <!-- Dynamic form fields will be inserted here by parent components -->
                            <div id="modal-form-content">
                                <!-- Example field -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Name</label>
                                    <input 
                                        type="text" 
                                        x-model="formData.name"
                                        class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white"
                                        placeholder="Enter name"
                                    >
                                    <p x-show="formErrors.name" class="mt-1 text-sm text-red-600" x-text="formErrors.name"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Footer -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/50">
                        <div class="flex justify-end gap-3">
                            <button 
                                type="button"
                                @click="closeAddModal()"
                                class="px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-750"
                            >
                                Cancel
                            </button>
                            <button 
                                type="submit"
                                :disabled="isSubmitting"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed flex items-center gap-2"
                            >
                                <svg x-show="isSubmitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="isSubmitting ? 'Saving...' : (formData.id ? 'Update' : 'Create')"></span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    /* Scrollbar styling */
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

    /* Mobile menu body scroll prevention */
    .mobile-menu-open {
        overflow: hidden;
    }

    /* Modal body scroll prevention */
    .modal-open {
        overflow: hidden;
    }
</style>