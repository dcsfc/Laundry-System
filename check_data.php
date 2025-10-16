<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Check pending orders
$pendingCount = \App\Models\Order::where('approval_status', 'pending')->count();
echo "=== DATABASE CHECK ===\n";
echo "Pending orders count: $pendingCount\n\n";

if ($pendingCount > 0) {
    $orders = \App\Models\Order::with('customer')
        ->where('approval_status', 'pending')
        ->get();
    
    foreach ($orders as $order) {
        echo "Order ID: {$order->id}\n";
        echo "Customer: {$order->customer->name}\n";
        echo "Approval Status: {$order->approval_status}\n";
        echo "Order Status: {$order->status}\n";
        echo "Created: {$order->created_at}\n";
        echo "---\n";
    }
} else {
    echo "NO PENDING ORDERS FOUND!\n";
    echo "\nAll orders:\n";
    $allOrders = \App\Models\Order::with('customer')->get();
    foreach ($allOrders as $order) {
        echo "Order ID: {$order->id} - {$order->customer->name} - approval: {$order->approval_status} - status: {$order->status}\n";
    }
}

