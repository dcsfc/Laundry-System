@props([
    'columns' => [],
    'data' => [],
    'tableData' => [],
    'actions' => [],
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'bulkActions' => false,
    'exportable' => false,
    'pageSize' => 10,
    'emptyMessage' => 'No data available',
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'colorScheme' => 'blue',
])

@php
$actualData = !empty($tableData) ? $tableData : $data;

$columns = collect($columns)->map(function($column) {
    if (is_string($column)) {
        return ['key' => $column, 'label' => ucfirst($column), 'sortable' => true];
    }
    return array_merge(['key' => 'id', 'label' => 'Column', 'sortable' => true], $column);
})->toArray();

$actions = collect($actions)->map(function($action) {
    if (is_string($action)) {
        return ['key' => $action, 'label' => ucfirst($action), 'icon' => 'default'];
    }
    return array_merge(['key' => 'action', 'label' => 'Action', 'icon' => 'default'], $action);
})->toArray();

if (is_null($actualData)) {
    $actualData = [];
} elseif (!is_array($actualData)) {
    $actualData = collect($actualData)->toArray();
}
@endphp

@push('styles')
<link rel="stylesheet" href="{{ asset('css/data-table.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/data-table.js') }}"></script>
@endpush

<div class="data-table-wrapper" 
     x-data="dataTable(@js($actualData), @js($columns), @js($actions), {{ $pageSize }})"
     x-init="init()">

    <!-- Header -->
    <div class="data-table-header">
        <div class="header-content">
            <div class="header-left">
                <div class="header-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
                    </svg>
                </div>
                <div class="header-text">
                    <h2 class="table-title">{{ $title }}</h2>
                    <p class="table-description">{{ $description }}</p>
                </div>
            </div>

            <div class="header-actions">
                @if($bulkActions)
                <div class="bulk-actions" x-show="bulkSelectedItems.size > 0" x-transition>
                    <span class="bulk-count" x-text="`${bulkSelectedItems.size} selected`"></span>
                    <button class="btn btn-danger btn-sm">Delete</button>
                </div>
                @endif

                <button class="btn btn-primary" @click="addNew()">
                    <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add New
                </button>

                @if($exportable)
                <button class="btn btn-secondary" @click="exportData('csv')">Export</button>
                @endif
            </div>
        </div>
    </div>

    <!-- Filters -->
    @if($searchable)
    <div class="table-filters">
        <div class="filters-content">
            <div class="search-group">
                <div class="search-input-wrapper">
                    <div class="search-icon">
                        <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" 
                           class="search-input" 
                           placeholder="Search..." 
                           x-model="searchQuery"
                           @input="search()">
                </div>
            </div>

            <select class="filter-select" x-model="statusFilter" @change="applyFilters()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>

            <select class="filter-select" x-model="roleFilter" @change="applyFilters()">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>

            <button class="btn btn-secondary btn-sm" @click="clearAllFilters()">Clear</button>
        </div>
    </div>
    @endif

    <!-- Table -->
    <div class="table-scroll-container">
        <div class="table-scroll-wrapper">
            <table class="data-table">
                <thead class="table-head">
                    <tr>
                        @if($bulkActions)
                        <th class="checkbox-cell">
                            <div class="checkbox-wrapper">
                                <input type="checkbox" x-model="selectAll" @change="toggleSelectAll()">
                                <div class="checkbox-custom"></div>
                            </div>
                        </th>
                        @endif

                        @foreach($columns as $column)
                        <th class="table-header-cell">
                            @if($sortable && ($column['sortable'] ?? true))
                            <button class="sort-button" @click="sort('{{ $column['key'] }}')">
                                <span>{{ $column['label'] }}</span>
                                <div class="sort-arrows">
                                    <svg class="sort-arrow" 
                                         :class="{ 'active': sortColumn === '{{ $column['key'] }}' && sortDirection === 'asc' }" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"></path>
                                    </svg>
                                    <svg class="sort-arrow" 
                                         :class="{ 'active': sortColumn === '{{ $column['key'] }}' && sortDirection === 'desc' }" 
                                         fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
                                    </svg>
                                </div>
                            </button>
                            @else
                            {{ $column['label'] }}
                            @endif
                        </th>
                        @endforeach

                        @if(count($actions) > 0)
                        <th class="actions-header">Actions</th>
                        @endif
                    </tr>
                </thead>

                <tbody class="table-body">
                    <!-- Loading -->
                    <template x-if="isLoading">
                        <template x-for="i in pageSize" :key="i">
                            <tr class="loading-row">
                                @if($bulkActions)
                                <td class="checkbox-cell"><div class="skeleton skeleton-checkbox"></div></td>
                                @endif
                                @foreach($columns as $column)
                                <td class="data-cell"><div class="skeleton skeleton-text"></div></td>
                                @endforeach
                                @if(count($actions) > 0)
                                <td class="actions-cell"><div class="skeleton skeleton-actions"></div></td>
                                @endif
                            </tr>
                        </template>
                    </template>

                    <!-- CLEAN: Data rows with fixed portal triggers -->
                    <template x-if="!isLoading && paginatedData.length > 0">
                        <template x-for="(row, index) in paginatedData" :key="row.id || index">
                            <tr class="data-row">
                                @if($bulkActions)
                                <td class="checkbox-cell">
                                    <div class="checkbox-wrapper">
                                        <input type="checkbox" 
                                               :checked="bulkSelectedItems.has(row.id)"
                                               @change="toggleItemSelection(row.id)">
                                        <div class="checkbox-custom"></div>
                                    </div>
                                </td>
                                @endif

                                @foreach($columns as $column)
                                <td class="data-cell">
                                    @if($column['key'] === 'status')
                                    <span class="status-badge" 
                                          :class="`status-${row.{{ $column['key'] }}}`"
                                          x-text="row.{{ $column['key'] }} || 'N/A'"></span>
                                    @elseif(in_array($column['key'], ['created_at', 'updated_at']))
                                    <span class="cell-text date-text" 
                                          x-text="formatDate(row.{{ $column['key'] }})"></span>
                                    @else
                                    <span class="cell-text" 
                                          x-text="getNestedValue(row, '{{ $column['key'] }}') || 'N/A'"></span>
                                    @endif
                                </td>
                                @endforeach

                                @if(count($actions) > 0)
                                <td class="actions-cell">
    <!-- BULLETPROOF: Action trigger tied to exact row -->
    <div class="actions-dropdown">
        <button class="actions-trigger" 
                :class="{ 'active': activeMenuRow === (row.id || index) }"
                @click.stop="openActionMenu(row.id || index, $event.currentTarget, row)">
            <svg fill="currentColor" viewBox="0 0 20 20" width="16" height="16">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"></path>
            </svg>
        </button>
    </div>
</td>

                                @endif
                            </tr>
                        </template>
                    </template>

                    <!-- Empty state -->
                    <template x-if="!isLoading && paginatedData.length === 0">
                        <tr>
                            <td :colspan="@if($bulkActions){{ count($columns) + 2 }}@else{{ count($columns) + (count($actions) > 0 ? 1 : 0) }}@endif">
                                <div class="empty-content">
                                    <div class="empty-icon">
                                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 009.586 13H7"></path>
                                        </svg>
                                    </div>
                                    <h3>No Data</h3>
                                    <p>{{ $emptyMessage }}</p>
                                </div>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    @if($pagination)
    <div class="table-pagination" x-show="totalPages > 1">
        <div class="pagination-content">
            <div class="pagination-info">
                Showing <strong x-text="startRecord"></strong> to <strong x-text="endRecord"></strong> 
                of <strong x-text="totalRecords"></strong>
            </div>

            <div class="pagination-controls">
                <button class="pagination-btn" @click="previousPage()" :disabled="currentPage === 1">Previous</button>

                <div class="page-numbers">
                    <template x-for="page in getPageNumbers()" :key="page">
                        <template x-if="page === '...'">
                            <span class="page-dots">...</span>
                        </template>
                        <template x-if="page !== '...'">
                            <button class="page-btn" 
                                    :class="{ 'active': page === currentPage }"
                                    @click="goToPage(page)"
                                    x-text="page"></button>
                        </template>
                    </template>
                </div>

                <button class="pagination-btn" @click="nextPage()" :disabled="currentPage === totalPages">Next</button>
            </div>
        </div>
    </div>
    @endif
</div>