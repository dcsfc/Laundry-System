<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Data Table</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold mb-8 text-gray-800">Data Table Test</h1>
        
        <!-- Simple Alpine.js Test -->
        <div x-data="{ message: 'Alpine.js is working!' }" class="mb-8 p-4 bg-green-100 border border-green-400 rounded">
            <p x-text="message"></p>
        </div>
        
        <!-- Test Simple Data Table Component -->
        <x-simple-data-table 
            :columns="[
                ['key' => 'id', 'label' => 'ID'],
                ['key' => 'name', 'label' => 'Name'],
                ['key' => 'email', 'label' => 'Email'], 
                ['key' => 'status', 'label' => 'Status'],
                ['key' => 'created_at', 'label' => 'Created At']
            ]" 
            :data="[
                ['id' => 1, 'name' => 'Test User 1', 'email' => 'test1@example.com', 'status' => 'active', 'created_at' => '2024-01-01'],
                ['id' => 2, 'name' => 'Test User 2', 'email' => 'test2@example.com', 'status' => 'inactive', 'created_at' => '2024-01-02'],
                ['id' => 3, 'name' => 'Test User 3', 'email' => 'test3@example.com', 'status' => 'pending', 'created_at' => '2024-01-03']
            ]" 
            :actions="[
                ['label' => 'View'],
                ['label' => 'Edit'],
                ['label' => 'Delete']
            ]"
            title="Test Data Table"
            description="Testing the simple data table component"
            :searchable="true"
            :sortable="true"
            :pagination="true"
            :pageSize="10"
        />
    </div>
</body>
</html>