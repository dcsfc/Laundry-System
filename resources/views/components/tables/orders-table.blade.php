@props([
    'orders' => [],
    'colorScheme' => 'sky'
])

@php
    $columns = [
        ['key' => 'id', 'label' => 'Order ID'],
        ['key' => 'customer_name', 'label' => 'Customer'],
        ['key' => 'dropoff_date', 'label' => 'Drop-off'],
        ['key' => 'pickup_date', 'label' => 'Pickup'],
        ['key' => 'total_price', 'label' => 'Total'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'payment_status', 'label' => 'Payment'],
        ['key' => 'created_at', 'label' => 'Created At'],
    ];
    
    $actions = [
        ['label' => 'View', 'url' => '#'],
        ['label' => 'Edit', 'url' => '#'],
        ['label' => 'Update Status', 'url' => '#'],
        ['label' => 'Print', 'url' => '#'],
    ];
@endphp

<x-data-table
    :columns="$columns"
    :data="$orders"
    :actions="$actions"
    :color-scheme="$colorScheme"
    title="Order Management"
    description="Manage laundry orders, schedules, and payments"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="15"
    empty-message="No orders found. Orders will appear here once customers start scheduling."
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle order-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                window.location.href = `/admin/orders/${rowId}`;
                break;
            case 'edit':
                window.location.href = `/admin/orders/${rowId}/edit`;
                break;
            case 'update status':
                // Open status update modal
                console.log('Updating status for order:', rowId);
                break;
            case 'print':
                // Print order details
                window.print();
                break;
        }
    });
    
    // Handle modal form submission for orders
    container.addEventListener('datatable:form:submit', function(e) {
        const { formData } = e.detail;
        
        // Create order form fields
        const formContent = document.getElementById('modal-form-content');
        formContent.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer <span class="text-red-400">*</span>
                    </label>
                    <select 
                        name="customer_id"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        required
                    >
                        <option value="">Select customer</option>
                        <!-- Customer options will be populated dynamically -->
                    </select>
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Drop-off Date <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="dropoff_date"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        required
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        Pickup Date <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="date" 
                        name="pickup_date"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        required
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                        </svg>
                        Estimated Price
                    </label>
                    <input 
                        type="number" 
                        name="total_price"
                        step="0.01"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="0.00"
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select 
                        name="status"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                    >
                        <option value="scheduled">🟡 Scheduled</option>
                        <option value="priced">🟠 Priced</option>
                        <option value="in_progress">🔵 In Progress</option>
                        <option value="completed">🟢 Completed</option>
                        <option value="cancelled">🔴 Cancelled</option>
                    </select>
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        Notes
                    </label>
                    <textarea 
                        name="notes"
                        rows="3"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Special instructions or notes..."
                    ></textarea>
                </div>
            </div>
        `;
    });
});
</script>
@endpush

