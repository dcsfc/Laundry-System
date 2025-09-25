@extends('layouts.sidebar')

@section('title', 'User Management - Admin')

@section('content')
    <div class="container">
        <!-- User Management using Reusable Data Table -->
        <x-data-table
            :columns="$columns"
            :data="$data"
            :actions="$actions"
            :bulk-actions="false"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :page-size="10"
            :current-page="1"
            :empty-message="'No users found'"
            :hover-effects="true"
            :alternating-rows="true"
            :sticky-header="true"
            :custom-class="'bg-slate-800 text-slate-200'"
            :title="'User Management'"
            :description="$description"
            :add-button="true"
            :show-role-filter="true"
            :available-roles="$roles"
            :color-scheme="'sky'"
        />
    </div>

    <script>
        // User Management Functions for Data Table Component
        function viewUser(row) {
            console.log('Viewing user:', row);
            window.location.href = `/admin/users/${row.id}`;
        }

        function editUser(row) {
            console.log('Editing user:', row);
            window.location.href = `/admin/users/${row.id}/edit`;
        }

        function viewUserActivity(row) {
            console.log('Viewing user activity:', row);
            // Show user activity modal or redirect to activity page
            fetch(`/admin/users/${row.id}/activity`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showUserActivityModal(data.data, row.name);
                    } else {
                        alert('Error loading user activity');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading user activity');
                });
        }

        function deactivateUser(row) {
            const action = row.status === 'Active' ? 'deactivate' : 'activate';
            if (confirm(`Are you sure you want to ${action} ${row.name}?`)) {
                console.log(`${action} user:`, row);
                fetch(`/admin/users/${row.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert(`Error ${action}ing user`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert(`Error ${action}ing user`);
                });
            }
        }

        function showUserActivityModal(activityData, userName) {
            // Create modal HTML
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-slate-800 rounded-xl p-6 max-w-2xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-semibold text-slate-50">${userName} - Activity</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-slate-400 hover:text-slate-200">
                            <i class="fas fa-times text-xl"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        ${activityData.recent_orders ? `
                            <div>
                                <h4 class="text-slate-300 font-medium mb-2">Recent Orders (${activityData.recent_orders.length})</h4>
                                <div class="space-y-2">
                                    ${activityData.recent_orders.map(order => `
                                        <div class="bg-slate-700/30 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-200">Order #${order.id}</span>
                                                <span class="text-slate-400 text-sm">${order.created_at}</span>
                                            </div>
                                            <div class="text-slate-400 text-sm">Status: ${order.status}</div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                        ${activityData.assigned_orders ? `
                            <div>
                                <h4 class="text-slate-300 font-medium mb-2">Assigned Orders (${activityData.assigned_orders.length})</h4>
                                <div class="space-y-2">
                                    ${activityData.assigned_orders.map(order => `
                                        <div class="bg-slate-700/30 rounded-lg p-3">
                                            <div class="flex justify-between items-center">
                                                <span class="text-slate-200">Order #${order.id}</span>
                                                <span class="text-slate-400 text-sm">${order.status}</span>
                                            </div>
                                            <div class="text-slate-400 text-sm">Customer: ${order.customer_name}</div>
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        ` : ''}
                        <div class="text-center text-slate-400 text-sm">
                            Total Orders: ${activityData.total_orders || 0}
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        // Add user function
        function addUser() {
            window.location.href = '/admin/users/create';
        }
    </script>
@endsection
