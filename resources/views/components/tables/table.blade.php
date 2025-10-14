<!-- Main Table Structure -->
<div class="table-scroll-container">
    <table class="data-table">
        <thead class="table-head">
            <tr>
                <!-- Headers with sorting -->
                @foreach($columns as $column)
                <th class="table-header-cell">
                    @if($sortable ?? true)
                    <button type="button" class="sort-button" @click="sort('{{ $column['key'] }}')">
                        {{ $column['label'] }}
                        @include('components.tables.sort-arrows', ['column' => $column['key']])
                    </button>
                    @else
                    {{ $column['label'] }}
                    @endif
                </th>
                @endforeach
                
                @if($actions ?? false)
                <th class="actions-header">Actions</th>
                @endif
            </tr>
        </thead>

        <tbody>
            <!-- Loading State -->
            <template x-if="isLoading">
                @include('components.tables.loading-row', ['columns' => $columns, 'bulkActions' => $bulkActions ?? false, 'actions' => $actions ?? false])
            </template>

            <!-- Data Rows -->
            <template x-for="(row, index) in paginatedData" :key="row.id || index">
                @include('components.tables.row', ['columns' => $columns, 'actions' => $actions ?? [], 'bulkActions' => $bulkActions ?? false])
            </template>
        </tbody>

        <!-- Empty State -->
        <tfoot x-show="paginatedData.length === 0 && !isLoading">
            <tr>
                <td :colspan="{{ count($columns) + ($actions ? 1 : 0) }}" class="empty-content">
                    <div class="empty-icon">
                        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h3>{{ $emptyMessage ?? 'No data found' }}</h3>
                    <p>{{ $emptyDescription ?? 'Start by adding your first item to the system.' }}</p>
                </td>
            </tr>
        </tfoot>
    </table>
</div>
