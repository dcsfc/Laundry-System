<!-- Schedule Table Row -->
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
        @elseif(isset($column['type']) && $column['type'] === 'badge')
        <span 
            class="status-badge" 
            :class="{
                'status-pending': row.status === 'pending',
                'status-pending-approval': row.status === 'pending_approval',
                'status-approved': row.status === 'approved',
                'status-confirmed': row.status === 'confirmed',
                'status-processing': row.status === 'processing',
                'status-in-progress': row.status === 'in_progress',
                'status-ready-for-pickup': row.status === 'ready_for_pickup',
                'status-completed': row.status === 'completed',
                'status-cancelled': row.status === 'cancelled',
                'status-rejected': row.status === 'rejected'
            }"
            x-text="row.{{ $column['key'] }} || 'N/A'"
        ></span>
        @elseif(isset($column['type']) && $column['type'] === 'date')
        <span class="cell-text date-text" x-text="formatDate(row.{{ $column['key'] }})"></span>
        @else
        <span class="cell-text" x-text="row.{{ $column['key'] }} || 'N/A'"></span>
        @endif
    </td>
    @endforeach
    
    <!-- Actions Column -->
    <td class="actions-cell">
        <div class="flex items-center gap-1.5 whitespace-nowrap">
            <template x-if="row.approval_status === 'Pending'">
                <div class="flex items-center gap-1.5">
                    <button 
                        class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-medium rounded transition-colors duration-200"
                        @click="updateStatus(row.id, 'approved')"
                        title="Approve this schedule"
                    >
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Approve
                    </button>
                    <button 
                        class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-medium rounded transition-colors duration-200"
                        @click="updateStatus(row.id, 'cancelled')"
                        title="Reject this schedule"
                    >
                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        Reject
                    </button>
                </div>
            </template>
            
            <template x-if="row.status === 'approved'">
                <button 
                    class="inline-flex items-center px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-medium rounded transition-colors duration-200"
                    @click="openPricingModal(row)"
                    title="Add weight and price"
                >
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                    </svg>
                    Add Weight & Price
                </button>
            </template>
            
            <template x-if="row.status === 'processing'">
                <button 
                    class="inline-flex items-center px-2 py-1 bg-yellow-600 hover:bg-yellow-700 text-white text-xs font-medium rounded transition-colors duration-200"
                    @click="updateStatus(row.id, 'ready_for_pickup')"
                    title="Mark as ready for pickup"
                >
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 4a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1V8zm8 0a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1V8zm0 4a1 1 0 011-1h4a1 1 0 011 1v2a1 1 0 01-1 1h-4a1 1 0 01-1-1v-2z" clip-rule="evenodd"></path>
                    </svg>
                    Ready
                </button>
            </template>
            
            <template x-if="row.status === 'ready_for_pickup'">
                <button 
                    class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium rounded transition-colors duration-200"
                    @click="updateStatus(row.id, 'completed')"
                    title="Complete this schedule"
                >
                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Complete
                </button>
            </template>
            
            <template x-if="row.status === 'completed' || row.status === 'cancelled'">
                <span class="inline-flex items-center px-2 py-1 bg-gray-100 text-gray-600 text-xs font-medium rounded">
                    <template x-if="row.status === 'completed'">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Done
                        </div>
                    </template>
                    <template x-if="row.status === 'cancelled'">
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            Cancelled
                        </div>
                    </template>
                </span>
            </template>
            
            <!-- View Details Button (Always Available) -->
            <button 
                class="inline-flex items-center px-2 py-1 bg-slate-600 hover:bg-slate-700 text-white text-xs font-medium rounded transition-colors duration-200"
                @click="viewSchedule(row.id)"
                title="View schedule details"
            >
                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"></path>
                    <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </td>
</tr>
