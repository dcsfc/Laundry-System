<!-- Table Row -->
<tr class="data-row" :class="{ 'opacity-50 pointer-events-none': rowLoadingId === row.id }">
    
    <!-- Data Columns -->
    @foreach($columns as $column)
    <td class="data-cell" :class="{ 'relative': rowLoadingId === row.id }">
        @if(isset($column['type']) && $column['type'] === 'status')
        <span 
            class="status-badge" 
            :class="{ 
                'status-active': (row.{{ $column['key'] }} || '').toLowerCase() === 'active', 
                'status-inactive': (row.{{ $column['key'] }} || '').toLowerCase() !== 'active' 
            }"
            x-text="(row.{{ $column['key'] }} || 'inactive').charAt(0).toUpperCase() + (row.{{ $column['key'] }} || 'inactive').slice(1)"
        ></span>
        @elseif(isset($column['type']) && $column['type'] === 'date')
        <span class="cell-text date-text" x-text="formatDate(row.{{ $column['key'] }})"></span>
        @elseif(isset($column['type']) && $column['type'] === 'badge')
        <span 
            class="status-badge" 
            :class="'status-' + (row.{{ $column['key'] }} || 'default').toLowerCase().replace(/\s+/g, '-')"
            x-text="row.{{ $column['key'] }} || 'N/A'"
        ></span>
        @else
        <span class="cell-text" x-text="row.{{ $column['key'] }} || 'N/A'"></span>
        @endif
    </td>
    @endforeach
    
    <!-- Actions Column -->
    @if($actions && count($actions) > 0)
    <td class="actions-cell">
        <!-- Show spinner when this specific row is loading -->
        <div x-show="rowLoadingId === row.id" class="flex items-center justify-center">
            <svg class="animate-spin h-5 w-5 text-sky-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
        <!-- Show actions when row is not loading -->
        <div x-show="rowLoadingId !== row.id">
            @include('components.tables.actions', ['actions' => $actions])
        </div>
    </td>
    @endif
</tr>
