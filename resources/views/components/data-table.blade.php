@props([
    'columns' => [],
    'data' => [],
    'tableData' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'emptyMessage' => 'No data available',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'colorScheme' => 'blue',
])

@php
    // Use tableData if provided, otherwise fall back to data
    $actualData = !empty($tableData) ? $tableData : $data;
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/data-table.js') }}"></script>
@endpush

@php
    // Ensure columns have proper structure
    $columns = collect($columns)->map(function($column) {
        if (is_string($column)) {
            return ['key' => $column, 'label' => ucfirst($column)];
        }
        return array_merge(['key' => 'id', 'label' => 'Column'], $column);
    })->toArray();
    
    // Ensure actions have proper structure
    $actions = collect($actions)->map(function($action) {
        if (is_string($action)) {
            return ['key' => $action, 'label' => ucfirst($action)];
        }
        // Handle actions with onclick property
        if (isset($action['onclick'])) {
            return [
                'key' => $action['onclick'],
                'label' => $action['label'] ?? ucfirst($action['onclick']),
                'onclick' => $action['onclick']
            ];
        }
        return array_merge(['key' => 'action', 'label' => 'Action'], $action);
    })->toArray();
    
    // Ensure data is properly formatted - more robust approach
    if (is_null($actualData)) {
        $actualData = [];
    } elseif (!is_array($actualData)) {
        $actualData = collect($actualData)->toArray();
    }
@endphp

@php
    // Get user role for color scheme
    $userRole = Auth::user()->role->name ?? 'customer';
    $isSuperAdmin = $userRole === 'superadmin';
    $isAdmin = $userRole === 'administrator';
    $isStaff = $userRole === 'staff';
    $isCustomer = $userRole === 'customer';
    
    // Define color schemes based on role
    if ($isSuperAdmin) {
        $primaryColor = 'indigo';
        $secondaryColor = 'purple';
        $accentColor = 'indigo';
        $gradientFrom = 'from-indigo-600';
        $gradientTo = 'to-purple-600';
        $gradientHoverFrom = 'from-indigo-500/20';
        $gradientHoverTo = 'to-purple-500/20';
        $shadowColor = 'indigo';
        $textColor = 'indigo';
    } elseif ($isAdmin || $isStaff) {
        $primaryColor = 'sky';
        $secondaryColor = 'cyan';
        $accentColor = 'sky';
        $gradientFrom = 'from-sky-600';
        $gradientTo = 'to-cyan-600';
        $gradientHoverFrom = 'from-sky-500/20';
        $gradientHoverTo = 'to-cyan-500/20';
        $shadowColor = 'sky';
        $textColor = 'sky';
    } else {
        $primaryColor = 'emerald';
        $secondaryColor = 'teal';
        $accentColor = 'emerald';
        $gradientFrom = 'from-emerald-500';
        $gradientTo = 'to-teal-600';
        $gradientHoverFrom = 'from-emerald-500/20';
        $gradientHoverTo = 'to-teal-500/20';
        $shadowColor = 'emerald';
        $textColor = 'emerald';
    }
@endphp

    <div 
        x-data="dataTable({{ json_encode($actualData) }}, {{ json_encode($columns) }}, {{ json_encode($actions) }}, {{ $pageSize }})"
    x-init="init()"
        class="bg-slate-800 rounded-2xl shadow-2xl border border-slate-700 backdrop-blur-sm"
    >
    {{-- Premium SaaS Header --}}
    <div class="px-8 py-6 bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} border-b border-{{ $accentColor }}-500/20">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-xl flex items-center justify-center border border-white/20">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-2xl font-bold text-white tracking-tight whitespace-nowrap">{{ $title }}</h3>
                    <p class="text-sm text-white/80 mt-1 font-medium whitespace-nowrap">{{ $description }}</p>
                </div>
            </div>
            <button 
                @click="addNewRecord()"
                class="group inline-flex items-center px-6 py-3 bg-white/10 backdrop-blur-sm text-white text-sm font-semibold rounded-xl border border-white/20 hover:bg-white/20 hover:border-white/30 hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/25 transform hover:-translate-y-0.5 transition-all duration-300"
            >
                <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New
            </button>
        </div>
    </div>

    {{-- Premium Search and Filters --}}
    @if($searchable)
    <div class="px-8 py-6 bg-slate-700/50 border-b border-slate-600/50 backdrop-blur-sm">
        <div class="flex items-center space-x-4">
            {{-- Search Bar --}}
            <div class="flex-1 max-w-md">
                <div class="relative group">
                    <input 
                        type="text" 
                        x-model="searchQuery"
                        @input="search()"
                        placeholder="Search..."
                        class="w-full pl-12 pr-4 py-3.5 bg-slate-800/50 border border-slate-600 rounded-xl focus:ring-2 focus:ring-{{ $accentColor }}-500 focus:border-{{ $accentColor }}-500 text-slate-50 placeholder-slate-400 backdrop-blur-sm transition-all duration-300 text-sm font-medium"
                    >
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-slate-400 group-focus:text-{{ $accentColor }}-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button 
                        x-show="searchQuery"
                        @click="searchQuery = ''; search()"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-{{ $accentColor }}-400 transition-colors"
                    >
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Status Filter --}}
            <div class="relative">
                <select 
                    x-model="statusFilter"
                    @change="filterByStatus()"
                    class="appearance-none bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3.5 pr-10 text-slate-50 text-sm font-medium focus:ring-2 focus:ring-{{ $accentColor }}-500 focus:border-{{ $accentColor }}-500 transition-all duration-300 min-w-[140px]"
                >
                    <option value="">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>

            {{-- Role Filter --}}
            <div class="relative">
                <select 
                    x-model="roleFilter"
                    @change="filterByRole()"
                    class="appearance-none bg-slate-800/50 border border-slate-600 rounded-xl px-4 py-3.5 pr-10 text-slate-50 text-sm font-medium focus:ring-2 focus:ring-{{ $accentColor }}-500 focus:border-{{ $accentColor }}-500 transition-all duration-300 min-w-[140px]"
                >
                    <option value="">All Roles</option>
                    <option value="superadmin">Super Admin</option>
                    <option value="administrator">Administrator</option>
                    <option value="staff">Staff</option>
                    <option value="customer">Customer</option>
                </select>
                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- Modern SaaS Table --}}
    <div class="overflow-x-auto bg-slate-800 rounded-lg border border-slate-700 shadow-sm relative">
        <div class="py-2 px-4">
            <table class="min-w-full divide-y divide-slate-700">
            <thead class="bg-slate-700 border-b border-slate-600">
                <tr>
                    @foreach($columns as $column)
                    <th 
                        scope="col" 
                        class="px-6 py-4 text-left text-sm font-semibold text-slate-200 tracking-wide uppercase select-none whitespace-nowrap"
                    >
                        @if(isset($column['sortable']) && $column['sortable'])
                        <button 
                            type="button" 
                            class="flex items-center gap-2 text-slate-300 hover:text-white focus:outline-none"
                            @click="sort('{{ $column['key'] }}')"
                        >
                            <span>{{ $column['label'] }}</span>

                            <!-- Sorting icons container -->
                            <div class="flex flex-col">
                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="w-3 h-3"
                                    :class="{
                                        'text-blue-500': sortColumn === '{{ $column['key'] }}' && sortDirection === 'asc',
                                        'text-slate-400': sortColumn !== '{{ $column['key'] }}' || sortDirection !== 'asc'
                                    }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                </svg>

                                <svg 
                                    xmlns="http://www.w3.org/2000/svg" 
                                    class="w-3 h-3 -mt-1"
                                    :class="{
                                        'text-blue-500': sortColumn === '{{ $column['key'] }}' && sortDirection === 'desc',
                                        'text-slate-400': sortColumn !== '{{ $column['key'] }}' || sortDirection !== 'desc'
                                    }"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </button>
                        @else
                        <span>{{ $column['label'] }}</span>
                        @endif
                    </th>
                    @endforeach
                    @if(count($actions) > 0)
                        <th 
                            scope="col" 
                            class="px-6 py-4 text-sm font-semibold text-slate-200 uppercase whitespace-nowrap"
                        >
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-slate-800 divide-y divide-slate-700">
                <!-- Loading Skeleton -->
                <template x-if="isLoading">
                    <template x-for="i in 5" :key="'skeleton-' + i">
                        <tr class="border-b border-slate-700">
                            @foreach($columns as $column)
                            <td class="px-6 py-4">
                                <div class="h-4 bg-slate-700 rounded animate-pulse"></div>
                            </td>
                            @endforeach
                            @if(count($actions) > 0)
                            <td class="px-6 py-4">
                                <div class="w-8 h-8 bg-slate-700 rounded-lg animate-pulse"></div>
                            </td>
                            @endif
                        </tr>
                    </template>
                </template>
                
                <!-- Actual Data -->
                <template x-for="(row, index) in paginatedData" :key="row.id || index" x-show="!isLoading">
                    <tr class="hover:bg-slate-700/50 transition-colors duration-200 group">
                        @foreach($columns as $column)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                            <div class="flex items-center">
                                @if($column['key'] === 'status')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold"
                                          :class="{
                                              'bg-emerald-500/20 text-emerald-400 border border-emerald-500/30': row.status === 'Active',
                                              'bg-red-500/20 text-red-400 border border-red-500/30': row.status === 'Inactive',
                                              'bg-yellow-500/20 text-yellow-400 border border-yellow-500/30': row.status === 'pending'
                                          }"
                                          x-text="row.status"></span>
                                @elseif($column['key'] === 'role')
                                    <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-{{ $accentColor }}-500/20 text-{{ $accentColor }}-400 border border-{{ $accentColor }}-500/30"
                                          x-text="row.role"></span>
                                @elseif($column['key'] === 'created_at')
                                    <span class="text-slate-300 font-medium" x-text="new Date(row.created_at).toLocaleDateString()"></span>
                                @elseif($column['key'] === 'account_age')
                                    <span class="text-center block w-full text-slate-300 font-medium" x-text="row.account_age"></span>
                                @elseif($column['key'] === 'id')
                                    <span class="text-{{ $accentColor }}-400 font-bold" x-text="row.{{ $column['key'] ?? 'id' }}"></span>
                                @else
                                    <span class="text-slate-200 font-medium" x-text="row.{{ $column['key'] ?? 'id' }}"></span>
                                @endif
                            </div>
                        </td>
                        @endforeach
                        @if(count($actions) > 0)
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-100">
                            <div class="relative" 
                                 x-data="{ 
                                    open: false, 
                                    position: 'bottom-left',
                                    calculatePosition() {
                                        this.$nextTick(() => {
                                            const button = this.$refs.button;
                                            const menu = this.$refs.menu;
                                            if (!button || !menu) return;

                                            const rect = button.getBoundingClientRect();
                                            const viewportHeight = window.innerHeight;
                                            const viewportWidth = window.innerWidth;

                                            const menuHeight = menu.offsetHeight || 0;
                                            const menuWidth = menu.offsetWidth || 0;

                                            let verticalPos = 'bottom';
                                            let horizontalPos = 'left';

                                            if ((viewportHeight - rect.bottom) < menuHeight && rect.top > menuHeight) {
                                                verticalPos = 'top';
                                            }

                                            if (rect.left < (menuWidth + 20) && (viewportWidth - rect.right) > (menuWidth + 20)) {
                                                horizontalPos = 'right';
                                            }

                                            this.position = `${verticalPos}-${horizontalPos}`;
                                        });
                                    },
                                    init() {
                                        const reposition = () => { if (this.open) { this.calculatePosition(); } };
                                        window.addEventListener('resize', reposition, { passive: true });
                                        window.addEventListener('scroll', reposition, true);
                                        this.$nextTick(() => {
                                            const btn = this.$refs.button;
                                            if (btn) {
                                                const scroller = btn.closest('.overflow-x-auto');
                                                if (scroller) scroller.addEventListener('scroll', reposition, { passive: true });
                                            }
                                        });
                                    }
                                }"
                                @click.outside="open = false"
                            >
                                <!-- 3 Dots Button -->
                                <button 
                                    x-ref="button"
                                    @click="open = !open; if (open) $nextTick(() => calculatePosition())"
                                    class="inline-flex items-center justify-center w-8 h-8 text-slate-400 hover:text-slate-200 hover:bg-slate-600 rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-1"
                                >
                                    <svg class="w-4 h-4 group-hover/btn:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                                
                                <!-- Dropdown Menu -->
                                <div 
                                    x-ref="menu"
                                    x-show="open"
                                    x-transition:enter="transition ease-out duration-200"
                                    x-transition:enter-start="opacity-0 scale-95"
                                    x-transition:enter-end="opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-150"
                                    x-transition:leave-start="opacity-100 scale-100"
                                    x-transition:leave-end="opacity-0 scale-95"
                                    :class="{
                                        'absolute right-0 mt-2': position === 'bottom-right',
                                        'absolute right-0 mb-2 bottom-full': position === 'top-right',
                                        'absolute left-0 mt-2': position === 'bottom-left',
                                        'absolute left-0 mb-2 bottom-full': position === 'top-left'
                                    }"
                                    class="z-50 w-48 bg-slate-800 rounded-lg shadow-xl border border-slate-700 max-h-64 overflow-y-auto action-dropdown"
                                >
                                    <div class="py-2">
                                        @foreach($actions as $action)
                                        <button 
                                            @click="handleAction('{{ $action['key'] }}', row); open = false"
                                            class="w-full flex items-center px-4 py-3 text-sm text-slate-300 hover:text-white hover:bg-slate-700 transition-colors duration-200"
                                        >
                                            @if($action['key'] === 'viewUser')
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-{{ $accentColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            @elseif($action['key'] === 'editUser')
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-{{ $accentColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            @elseif($action['key'] === 'toggleUserStatus')
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-{{ $accentColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                                </svg>
                                            @elseif($action['key'] === 'deleteUser')
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            @else
                                                <svg class="w-4 h-4 mr-3 group-hover/action:scale-110 transition-transform text-{{ $accentColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                                </svg>
                                            @endif
                                            <span class="font-medium">{{ $action['label'] }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                </template>
            </tbody>
        </table>
        </div>
    </div>

    {{-- Premium Empty State --}}
    <div x-show="originalData.length === 0" class="text-center py-20">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-100 mb-3">No data available</h3>
        <p class="text-sm text-slate-400 mb-8 max-w-md mx-auto">{{ $emptyMessage }}</p>
        <button 
            @click="addNewRecord()"
            class="group inline-flex items-center px-6 py-3 bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} text-white text-sm font-semibold rounded-xl hover:shadow-lg hover:shadow-{{ $shadowColor }}-500/25 transform hover:-translate-y-0.5 transition-all duration-300"
        >
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Add First Record
        </button>
    </div>

    {{-- Premium No Results Found --}}
    <div x-show="originalData.length > 0 && filteredData.length === 0" class="text-center py-20">
        <div class="w-20 h-20 mx-auto mb-6 bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} rounded-2xl flex items-center justify-center shadow-2xl">
            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        <h3 class="text-xl font-bold text-slate-100 mb-3">No results found</h3>
        <p class="text-sm text-slate-400 mb-8 max-w-md mx-auto">Try adjusting your search or filter criteria</p>
        <button 
            @click="searchQuery = ''; search()"
            class="group inline-flex items-center px-6 py-3 bg-slate-700 text-slate-200 text-sm font-semibold rounded-xl border border-slate-600 hover:bg-slate-600 hover:border-{{ $accentColor }}-500/50 transition-all duration-300"
        >
            <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            Clear Search
        </button>
    </div>

    {{-- Premium Pagination --}}
    @if($pagination)
    <div class="px-8 py-6 bg-gradient-to-r from-slate-700 to-slate-600 border-t border-slate-600 backdrop-blur-sm">
        <div class="flex items-center justify-between">
            <div class="text-sm text-slate-300">
                Showing <span class="font-bold text-white" x-text="startRecord">0</span> to 
                <span class="font-bold text-white" x-text="endRecord">0</span> of 
                <span class="font-bold text-white" x-text="totalRecords">0</span> results
            </div>
            <div class="flex items-center space-x-2">
                <button 
                    @click="prevPage()"
                    :disabled="currentPage === 1"
                    class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-slate-800/50 border border-slate-600 rounded-xl hover:bg-slate-700 hover:text-white hover:border-{{ $accentColor }}-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                >
                    <svg class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Previous
                </button>
                
                <div class="flex items-center space-x-1">
                    <template x-for="page in getPageNumbers()" :key="page">
                        <button 
                            @click="goToPage(page)"
                            class="px-4 py-2.5 text-sm font-semibold rounded-xl transition-all duration-300"
                            :class="page === currentPage ? 
                                'bg-gradient-to-r {{ $gradientFrom }} {{ $gradientTo }} text-white shadow-lg shadow-{{ $shadowColor }}-500/25' : 
                                'text-slate-300 hover:bg-slate-700 hover:text-white hover:border hover:border-{{ $accentColor }}-500/50'"
                            x-text="page"
                        ></button>
                    </template>
                </div>
                
                <button 
                    @click="nextPage()"
                    :disabled="currentPage === totalPages"
                    class="group inline-flex items-center px-4 py-2.5 text-sm font-semibold text-slate-300 bg-slate-800/50 border border-slate-600 rounded-xl hover:bg-slate-700 hover:text-white hover:border-{{ $accentColor }}-500/50 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-300"
                >
                    Next
                    <svg class="w-4 h-4 ml-2 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

