<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management - Data Table Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Products Management Example</h1>
        
        <x-data-table
            :columns="[
                ['key' => 'id', 'label' => 'ID', 'sortable' => true],
                ['key' => 'name', 'label' => 'Product Name', 'sortable' => true, 'searchable' => true],
                ['key' => 'category', 'label' => 'Category', 'sortable' => true],
                ['key' => 'price', 'label' => 'Price', 'sortable' => true],
                ['key' => 'stock', 'label' => 'Stock', 'sortable' => true],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true],
                ['key' => 'created_at', 'label' => 'Created', 'sortable' => true],
            ]"
            :description="'Manage laundry services, pricing, and availability for customer orders'"
            :data="[
                ['id' => 1, 'name' => 'Wash & Fold Service', 'category' => 'Laundry Services', 'price' => 150, 'stock' => 25, 'status' => 'Active', 'created_at' => '2024-01-15'],
                ['id' => 2, 'name' => 'Dry Cleaning', 'category' => 'Laundry Services', 'price' => 200, 'stock' => 150, 'status' => 'Active', 'created_at' => '2024-01-20'],
                ['id' => 3, 'name' => 'Ironing Service', 'category' => 'Laundry Services', 'price' => 80, 'stock' => 0, 'status' => 'Out of Stock', 'created_at' => '2024-02-01'],
                ['id' => 4, 'name' => 'Express Wash', 'category' => 'Laundry Services', 'price' => 300, 'stock' => 12, 'status' => 'Active', 'created_at' => '2024-02-10'],
                ['id' => 5, 'name' => 'Bulk Laundry', 'category' => 'Laundry Services', 'price' => 120, 'stock' => 200, 'status' => 'Active', 'created_at' => '2024-02-15'],
            ]"
            :actions="[
                ['label' => 'View', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'onclick' => 'viewProduct(row.id)'],
                ['label' => 'Edit', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'onclick' => 'editProduct(row.id)'],
                ['label' => 'Duplicate', 'icon' => 'M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z', 'onclick' => 'duplicateProduct(row.id)'],
            ]"
            :bulk-actions="true"
            :show-filters="true"
            :page-size="25"
            :total-records="5"
            :current-page="1"
            :sort-key="'name'"
            :sort-direction="'asc'"
            :search-query="''"
            :empty-message="'No products found'"
        />
    </div>

    <script>
        function viewProduct(id) {
            console.log('View product:', id);
            alert('View product: ' + id);
        }

        function editProduct(id) {
            console.log('Edit product:', id);
            alert('Edit product: ' + id);
        }

        function duplicateProduct(id) {
            console.log('Duplicate product:', id);
            alert('Product ' + id + ' duplicated');
        }
    </script>
</body>
</html>
