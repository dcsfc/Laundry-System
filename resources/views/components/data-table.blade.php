@props([
    'columns' => [],
    'items' => [],  // Renamed from 'data' to 'items' to avoid conflict with data-* attributes
    'actions' => [],
    'bulkActions' => false,
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10,
    'pageSizeOptions' => [10, 25, 50, 100],
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'emptyMessage' => 'No data found',
    'emptyDescription' => 'Start by adding your first item to the system.',
    'colorScheme' => 'sky',
    'showRoleFilter' => false,
    'availableRoles' => [],
    'customClass' => 'bg-slate-800 text-slate-200',
    'hoverEffects' => true,
    'alternatingRows' => true,
    'stickyHeader' => true,
    'addButton' => false,
    'addButtonLabel' => 'Add New Item',
    'addButtonAction' => 'addItem',
    'formType' => 'default'
])

@php
    // CRITICAL: Ensure items are properly handled
    // Process the items
    if (is_array($items)) {
        $tableData = $items;
    } elseif ($items instanceof \Illuminate\Support\Collection) {
        $tableData = $items->toArray();
    } else {
        $tableData = [];
    }
    
    // Color scheme mapping
    $colorClasses = [
        'sky' => [
            'primary' => 'from-sky-600 to-cyan-600',
            'accent' => 'sky-400',
            'hover' => 'sky-500',
            'bg' => 'sky-500/20',
            'border' => 'sky-500/30'
        ],
        'indigo' => [
            'primary' => 'from-indigo-600 to-purple-600',
            'accent' => 'indigo-400',
            'hover' => 'indigo-500',
            'bg' => 'indigo-500/20',
            'border' => 'indigo-500/30'
        ],
        'emerald' => [
            'primary' => 'from-emerald-500 to-teal-600',
            'accent' => 'emerald-400',
            'hover' => 'emerald-500',
            'bg' => 'emerald-500/20',
            'border' => 'emerald-500/30'
        ]
    ];
    
    $colors = $colorClasses[$colorScheme] ?? $colorClasses['sky'];
@endphp

<!-- DEBUG INFO -->
<!-- RECEIVED $items: type={{ gettype($items) }}, count={{ is_countable($items) ? count($items) : 'N/A' }} -->
<!-- PROCESSED $tableData: count={{ count($tableData) }} -->
<!-- First item: {{ count($tableData) > 0 ? substr(json_encode($tableData[0]), 0, 200) : 'EMPTY' }} -->
<!-- END DEBUG -->

<div class="table-container {{ $customClass }}" 
     x-data="dataTable({{ json_encode($tableData) }}, {{ json_encode($columns) }}, {{ json_encode($actions) }}, {{ $pageSize }})"
     data-color-scheme="{{ $colorScheme }}"
     data-datatable>

    <!-- Header -->
    <div class="table-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <i class="fas fa-table text-{{ $colors['accent'] }}"></i>
                </div>
                <div class="header-text">
                    <h2 class="table-title">{{ $title }}</h2>
                    <p class="table-description">{{ $description }}</p>
                </div>
            </div>

            @if($addButton)
            <div class="header-actions">
                <button class="btn btn-primary bg-gradient-to-r {{ $colors['primary'] }} hover:opacity-90" 
                        @click="{{ $addButtonAction }}()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    {{ $addButtonLabel }}
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Search and Filters -->
    @if($searchable)
        @include('components.tables.search-filters', [
            'showRoleFilter' => $showRoleFilter,
            'availableRoles' => $availableRoles,
            'colorScheme' => $colorScheme
        ])
    @endif

    <!-- Table -->
    <div class="table-scroll-container">
        <table class="data-table">
            <thead class="table-head">
                <tr>
                    <!-- Headers with sorting -->
                    @foreach($columns as $column)
                    <th class="table-header-cell">
                        @if($sortable)
                        <button type="button" class="sort-button" @click="sort('{{ $column['key'] }}')">
                            {{ $column['label'] }}
                            @include('components.tables.sort-arrows', ['column' => $column['key']])
                        </button>
                        @else
                        {{ $column['label'] }}
                        @endif
                    </th>
                    @endforeach
                    
                    @if($actions && count($actions) > 0)
                    <th class="actions-header">Actions</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                <!-- Loading State - only show when no data exists AND loading -->
                <template x-if="isLoading && paginatedData.length === 0">
                    @include('components.tables.loading-row', [
                        'columns' => $columns, 
                        'bulkActions' => $bulkActions, 
                        'actions' => $actions
                    ])
                </template>

                <!-- Data Rows -->
                <template x-for="(row, index) in paginatedData" :key="row.id || index">
                    @include('components.tables.row', [
                        'columns' => $columns, 
                        'actions' => $actions, 
                        'bulkActions' => $bulkActions,
                        'colorScheme' => $colorScheme
                    ])
                </template>
            </tbody>

            <!-- Empty State -->
            <tfoot x-show="paginatedData.length === 0 && !isLoading">
                <tr>
                    <td :colspan="{{ count($columns) + (count($actions) > 0 ? 1 : 0) }}" class="empty-content">
                        <div class="empty-icon">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3>{{ $emptyMessage }}</h3>
                        <p>{{ $emptyDescription }}</p>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>

    <!-- Pagination -->
    @if($pagination)
        @include('components.tables.pagination', [
            'itemName' => strtolower($title),
            'pageSizeOptions' => $pageSizeOptions,
            'colorScheme' => $colorScheme
        ])
    @endif
</div>

{{-- The dataTable function is loaded from resources/js/modules/table/tables-modular.js --}}

