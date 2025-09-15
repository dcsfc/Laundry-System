<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders Management - Data Table Example</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 p-8">
    <div class="max-w-7xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-900 mb-8">Orders Management Example</h1>
        
        <x-data-table
            :columns="[
                ['key' => 'id', 'label' => 'Order ID', 'sortable' => true],
                ['key' => 'customer', 'label' => 'Customer', 'sortable' => true, 'searchable' => true],
                ['key' => 'product', 'label' => 'Product', 'sortable' => true, 'searchable' => true],
                ['key' => 'amount', 'label' => 'Amount', 'sortable' => true],
                ['key' => 'status', 'label' => 'Status', 'sortable' => true],
                ['key' => 'payment', 'label' => 'Payment', 'sortable' => true],
                ['key' => 'created_at', 'label' => 'Order Date', 'sortable' => true],
            ]"
            :description="'Track customer orders, service requests, and payment transactions'"
            :data="[
                ['id' => 'ORD-001', 'customer' => 'Maria Santos', 'product' => 'Wash & Fold Service', 'amount' => 150, 'status' => 'Processing', 'payment' => 'GCash', 'created_at' => '2024-01-15'],
                ['id' => 'ORD-002', 'customer' => 'Jose Garcia', 'product' => 'Dry Cleaning', 'amount' => 200, 'status' => 'Shipped', 'payment' => 'Cash', 'created_at' => '2024-01-20'],
                ['id' => 'ORD-003', 'customer' => 'Ana Cruz', 'product' => 'Ironing Service', 'amount' => 80, 'status' => 'Delivered', 'payment' => 'Bank Transfer', 'created_at' => '2024-02-01'],
                ['id' => 'ORD-004', 'customer' => 'Carlos Reyes', 'product' => 'Express Wash', 'amount' => 300, 'status' => 'Pending', 'payment' => 'GCash', 'created_at' => '2024-02-10'],
                ['id' => 'ORD-005', 'customer' => 'Liza Martinez', 'product' => 'Bulk Laundry', 'amount' => 120, 'status' => 'Cancelled', 'payment' => 'Cash', 'created_at' => '2024-02-15'],
            ]"
            :actions="[
                ['label' => 'View', 'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z', 'onclick' => 'viewOrder(row.id)'],
                ['label' => 'Edit', 'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z', 'onclick' => 'editOrder(row.id)'],
                ['label' => 'Invoice', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'onclick' => 'generateInvoice(row.id)'],
            ]"
            :bulk-actions="true"
            :show-filters="true"
            :page-size="50"
            :total-records="5"
            :current-page="1"
            :sort-key="'created_at'"
            :sort-direction="'desc'"
            :search-query="''"
            :empty-message="'No orders found'"
        />
    </div>

    <script>
        function viewOrder(id) {
            console.log('View order:', id);
            alert('View order: ' + id);
        }

        function editOrder(id) {
            console.log('Edit order:', id);
            alert('Edit order: ' + id);
        }

        function generateInvoice(id) {
            console.log('Generate invoice for order:', id);
            alert('Invoice generated for order: ' + id);
        }
    </script>
</body>
</html>
