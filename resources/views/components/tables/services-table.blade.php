@props([
    'services' => [],
    'colorScheme' => 'sky'
])

@php
    $columns = [
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'name', 'label' => 'Service Name'],
        ['key' => 'description', 'label' => 'Description'],
        ['key' => 'price', 'label' => 'Price'],
        ['key' => 'is_active', 'label' => 'Status'],
        ['key' => 'created_at', 'label' => 'Created At'],
    ];
    
    $actions = [
        ['label' => 'View', 'url' => '#'],
        ['label' => 'Edit', 'url' => '#'],
        ['label' => 'Toggle Status', 'url' => '#'],
        ['label' => 'Delete', 'url' => '#'],
    ];
@endphp

<x-data-table
    :columns="$columns"
    :data="$services"
    :actions="$actions"
    :color-scheme="$colorScheme"
    title="Service Management"
    description="Manage laundry services, pricing, and availability"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="10"
    empty-message="No services found. Add your first service to get started."
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle service-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                window.location.href = `/admin/services/${rowId}`;
                break;
            case 'edit':
                window.location.href = `/admin/services/${rowId}/edit`;
                break;
            case 'toggle status':
                // Toggle service active status
                console.log('Toggling status for service:', rowId);
                break;
            case 'delete':
                if (confirm('Are you sure you want to delete this service?')) {
                    // Handle delete action
                    console.log('Deleting service:', rowId);
                }
                break;
        }
    });
    
    // Handle modal form submission for services
    container.addEventListener('datatable:form:submit', function(e) {
        const { formData } = e.detail;
        
        // Create service form fields
        const formContent = document.getElementById('modal-form-content');
        formContent.innerHTML = `
            <div class="grid grid-cols-1 gap-6">
                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Service Name <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter service name"
                        required
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        Description
                    </label>
                    <textarea 
                        name="description"
                        rows="4"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Describe the service details..."
                    ></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="form-input-premium">
                        <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                            <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                            </svg>
                            Price <span class="text-red-400">*</span>
                        </label>
                        <input 
                            type="number" 
                            name="price"
                            step="0.01"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                            placeholder="0.00"
                            required
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
                            name="is_active"
                            class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        >
                            <option value="1">🟢 Active</option>
                            <option value="0">🔴 Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
    });
});
</script>
@endpush

