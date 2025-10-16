@props([
    'inventory' => [],
    'colorScheme' => 'sky'
])

@php
    $columns = [
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'item_name', 'label' => 'Item Name'],
        ['key' => 'quantity', 'label' => 'Quantity'],
        ['key' => 'unit', 'label' => 'Unit'],
        ['key' => 'threshold', 'label' => 'Threshold'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'updated_at', 'label' => 'Last Updated'],
    ];
    
    $actions = [
        ['label' => 'View', 'url' => '#'],
        ['label' => 'Edit', 'url' => '#'],
        ['label' => 'Update Stock', 'url' => '#'],
        ['label' => 'Delete', 'url' => '#'],
    ];
@endphp

<x-data-table
    :columns="$columns"
    :items="$inventory"
    :actions="$actions"
    :color-scheme="$colorScheme"
    title="Inventory Management"
    description="Manage supplies, track stock levels, and monitor thresholds"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="12"
    empty-message="No inventory items found. Add your first item to get started."
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle inventory-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                window.location.href = `/admin/inventory/${rowId}`;
                break;
            case 'edit':
                window.location.href = `/admin/inventory/${rowId}/edit`;
                break;
            case 'update stock':
                // Open stock update modal
                console.log('Updating stock for item:', rowId);
                break;
            case 'delete':
                if (confirm('Are you sure you want to delete this inventory item?')) {
                    // Handle delete action
                    console.log('Deleting inventory item:', rowId);
                }
                break;
        }
    });
    
    // Handle modal form submission for inventory
    container.addEventListener('datatable:form:submit', function(e) {
        const { formData } = e.detail;
        
        // Create inventory form fields
        const formContent = document.getElementById('modal-form-content');
        formContent.innerHTML = `
            <div class="grid grid-cols-1 gap-6">
                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Item Name <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="item_name"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter item name"
                        required
                    >
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="form-input-premium">
                        <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                            </svg>
                            Quantity <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="quantity"
                            min="0"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                            placeholder="0"
                            required
                        >
                    </div>

                    <div class="form-input-premium">
                        <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 0h10m-9 0a2 2 0 00-2 2v14a2 2 0 002 2h10a2 2 0 002-2V6a2 2 0 00-2-2M9 4a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            Unit <span class="text-red-400">*</span>
                        </label>
                        <select 
                            name="unit"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                            required
                        >
                            <option value="">Select unit</option>
                            <option value="pieces">Pieces</option>
                            <option value="kg">Kilograms</option>
                            <option value="liters">Liters</option>
                            <option value="bottles">Bottles</option>
                            <option value="boxes">Boxes</option>
                            <option value="rolls">Rolls</option>
                            <option value="packs">Packs</option>
                        </select>
                    </div>

                    <div class="form-input-premium">
                        <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                            </svg>
                            Threshold
                        </label>
                        <input 
                            type="number" 
                            name="threshold"
                            min="0"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                            placeholder="0"
                        >
                    </div>
                </div>
            </div>
        `;
    });
});
</script>
@endpush

