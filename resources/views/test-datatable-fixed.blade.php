<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fixed Data Table Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @vite('resources/js/app.js')
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Fixed Data Table Test</h1>
        
        <!-- Test Data Table Component -->
        <x-data-table 
            :columns="[
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'], 
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'created_at', 'label' => 'Created At']
            ]" 
            :data="[
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'status' => 'active', 'created_at' => '2024-01-15'],
                ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'status' => 'inactive', 'created_at' => '2024-01-20'],
                ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'status' => 'active', 'created_at' => '2024-02-01'],
                ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'status' => 'pending', 'created_at' => '2024-02-05'],
                ['id' => 5, 'name' => 'Charlie Wilson', 'email' => 'charlie@example.com', 'status' => 'active', 'created_at' => '2024-02-10'],
            ]" 
            :actions="[
                ['label' => 'View', 'onclick' => 'viewUser'],
                ['label' => 'Edit', 'onclick' => 'editUser'],
                ['label' => 'Delete', 'onclick' => 'deleteUser']
            ]"
            title="User Management"
            description="Manage your user accounts"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :pageSize="3"
            :showRoleFilter="true"
            :availableRoles="[
                (object)['name' => 'admin'],
                (object)['name' => 'user'],
                (object)['name' => 'staff']
            ]"
        />
    </div>

    <script>
        // Define action functions
        function viewUser(user) {
            alert('View user: ' + user.name);
        }
        
        function editUser(user) {
            alert('Edit user: ' + user.name);
        }
        
        function deleteUser(user) {
            if (confirm('Are you sure you want to delete ' + user.name + '?')) {
                alert('User deleted: ' + user.name);
            }
        }
    </script>
</body>
</html>