@props([
    'users' => [],
    'roles' => [],
    'colorScheme' => 'sky'
])

@php
    $columns = [
        ['key' => 'id', 'label' => 'ID'],
        ['key' => 'name', 'label' => 'Name'],
        ['key' => 'email', 'label' => 'Email'],
        ['key' => 'phone_number', 'label' => 'Phone'],
        ['key' => 'role_name', 'label' => 'Role'],
        ['key' => 'status', 'label' => 'Status'],
        ['key' => 'created_at', 'label' => 'Created At'],
    ];
    
    $actions = [
        ['label' => 'View', 'url' => '#'],
        ['label' => 'Edit', 'url' => '#'],
        ['label' => 'Delete', 'url' => '#'],
    ];
@endphp

<x-data-table
    :columns="$columns"
    :data="$users"
    :actions="$actions"
    :available-roles="$roles"
    :color-scheme="$colorScheme"
    title="User Management"
    description="Manage system users, roles, and permissions"
    :show-role-filter="true"
    :searchable="true"
    :sortable="true"
    :pagination="true"
    :page-size="10"
    empty-message="No users found. Add your first user to get started."
/>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle user-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                window.location.href = `/admin/users/${rowId}`;
                break;
            case 'edit':
                window.location.href = `/admin/users/${rowId}/edit`;
                break;
            case 'delete':
                if (confirm('Are you sure you want to delete this user?')) {
                    // Handle delete action
                    console.log('Deleting user:', rowId);
                }
                break;
        }
    });
    
    // Handle modal form submission
    container.addEventListener('datatable:form:submit', function(e) {
        const { formData } = e.detail;
        
        // Create user form fields
        const formContent = document.getElementById('modal-form-content');
        formContent.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Full Name <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="text" 
                        name="name"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter full name"
                        required
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                        Email Address
                    </label>
                    <input 
                        type="email" 
                        name="email"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter email address"
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        Phone Number
                    </label>
                    <input 
                        type="tel" 
                        name="phone_number"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter phone number"
                    >
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        User Role <span class="text-red-400">*</span>
                    </label>
                    <select 
                        name="role_id"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        required
                    >
                        <option value="">Select a role</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-input-premium">
                    <label class="block text-sm font-semibold text-slate-200 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Password <span class="text-red-400">*</span>
                    </label>
                    <input 
                        type="password" 
                        name="password"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 placeholder-slate-400 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                        placeholder="Enter password"
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
                        name="status"
                        class="w-full px-4 py-3 bg-slate-700/50 border border-slate-600/50 rounded-xl text-slate-100 transition-all duration-200 hover:border-slate-500 focus:ring-2 focus:ring-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}/50 focus:border-${container.dataset.colorScheme === 'indigo' ? 'indigo-400' : 'sky-400'}"
                    >
                        <option value="active">🟢 Active</option>
                        <option value="inactive">🔴 Inactive</option>
                        <option value="pending">🟡 Pending</option>
                    </select>
                </div>
            </div>
        `;
    });
});
</script>
@endpush

