<!-- Table Row -->
<tr class="data-row">
    
    <!-- Data Columns -->
    @foreach($columns as $column)
    <td class="data-cell">
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
        @include('components.tables.actions', ['actions' => $actions])
    </td>
    @endif
</tr>
