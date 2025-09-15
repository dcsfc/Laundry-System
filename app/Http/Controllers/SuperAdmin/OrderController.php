<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sample orders data for the reusable data table component
        $orders = collect([
            [
                'id' => 1,
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria.santos@gmail.com',
                'service_type' => 'Wash & Fold',
                'status' => 'In Progress',
                'total_price' => 750.00,
                'dropoff_date' => 'Sept 9, 2024',
                'pickup_date' => 'Sept 11, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 9, 2024 10:30 AM'
            ],
            [
                'id' => 2,
                'customer_name' => 'Jose Garcia',
                'customer_email' => 'jose.garcia@yahoo.com',
                'service_type' => 'Dry Clean',
                'status' => 'Completed',
                'total_price' => 1000.00,
                'dropoff_date' => 'Sept 8, 2024',
                'pickup_date' => 'Sept 10, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 8, 2024 2:20 PM'
            ],
            [
                'id' => 3,
                'customer_name' => 'Ana Dela Cruz',
                'customer_email' => 'ana.delacruz@outlook.com',
                'service_type' => 'Wash & Iron',
                'status' => 'Scheduled',
                'total_price' => 1500.00,
                'dropoff_date' => 'Sept 12, 2024',
                'pickup_date' => 'Sept 14, 2024',
                'payment_status' => 'Unpaid',
                'created_at' => 'Sept 12, 2024 9:15 AM'
            ],
            [
                'id' => 4,
                'customer_name' => 'Roberto Santos',
                'customer_email' => 'roberto.santos@gmail.com',
                'service_type' => 'Wash & Fold',
                'status' => 'Completed',
                'total_price' => 600.00,
                'dropoff_date' => 'Sept 7, 2024',
                'pickup_date' => 'Sept 9, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 7, 2024 11:45 AM'
            ],
            [
                'id' => 5,
                'customer_name' => 'Carmen Reyes',
                'customer_email' => 'carmen.reyes@yahoo.com',
                'service_type' => 'Dry Clean',
                'status' => 'In Progress',
                'total_price' => 1200.00,
                'dropoff_date' => 'Sept 10, 2024',
                'pickup_date' => 'Sept 12, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 10, 2024 3:30 PM'
            ]
        ]);

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_email', 'label' => 'Email', 'sortable' => true],
            ['key' => 'service_type', 'label' => 'Service', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'total_price', 'label' => 'Total', 'sortable' => true],
            ['key' => 'dropoff_date', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup_date', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'payment_status', 'label' => 'Payment', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['label' => 'View', 'onclick' => 'viewOrder'],
            ['label' => 'Edit', 'onclick' => 'editOrder'],
            ['label' => 'Update Status', 'onclick' => 'updateOrderStatus'],
            ['label' => 'Delete', 'onclick' => 'deleteOrder']
        ];

        $description = 'Track and manage customer laundry orders, schedules, and payment status';
        
        return view('superadmin.orders.index', compact('orders', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.orders.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storage logic will go here
        return redirect()->route('superadmin.orders.index')->with('success', 'Order created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('superadmin.orders.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('superadmin.orders.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic will go here
        return redirect()->route('superadmin.orders.index')->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete logic will go here
        return redirect()->route('superadmin.orders.index')->with('success', 'Order deleted successfully');
    }

    /**
     * Display customer's order history (filtered by customer_id)
     */
    public function customerOrders()
    {
        $currentUser = auth()->user();
        
        // Sample orders data filtered for the current customer
        // In a real application, you would query: Order::where('customer_id', $currentUser->id)->get()
        $allOrders = collect([
            [
                'id' => 1,
                'customer_id' => 1, // This would be the current user's ID
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria.santos@gmail.com',
                'service_type' => 'Wash & Fold',
                'status' => 'In Progress',
                'total_price' => 750.00,
                'dropoff_date' => 'Sept 9, 2024',
                'pickup_date' => 'Sept 11, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 9, 2024 10:30 AM'
            ],
            [
                'id' => 2,
                'customer_id' => 2, // Different customer
                'customer_name' => 'Jose Garcia',
                'customer_email' => 'jose.garcia@yahoo.com',
                'service_type' => 'Dry Clean',
                'status' => 'Completed',
                'total_price' => 1000.00,
                'dropoff_date' => 'Sept 8, 2024',
                'pickup_date' => 'Sept 10, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 8, 2024 2:20 PM'
            ],
            [
                'id' => 3,
                'customer_id' => 1, // This would be the current user's ID
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria.santos@gmail.com',
                'service_type' => 'Wash & Iron',
                'status' => 'Scheduled',
                'total_price' => 1500.00,
                'dropoff_date' => 'Sept 12, 2024',
                'pickup_date' => 'Sept 14, 2024',
                'payment_status' => 'Unpaid',
                'created_at' => 'Sept 12, 2024 9:15 AM'
            ],
            [
                'id' => 4,
                'customer_id' => 3, // Different customer
                'customer_name' => 'Roberto Santos',
                'customer_email' => 'roberto.santos@gmail.com',
                'service_type' => 'Wash & Fold',
                'status' => 'Completed',
                'total_price' => 600.00,
                'dropoff_date' => 'Sept 7, 2024',
                'pickup_date' => 'Sept 9, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 7, 2024 11:45 AM'
            ],
            [
                'id' => 5,
                'customer_id' => 1, // This would be the current user's ID
                'customer_name' => 'Maria Santos',
                'customer_email' => 'maria.santos@gmail.com',
                'service_type' => 'Dry Clean',
                'status' => 'Completed',
                'total_price' => 1200.00,
                'dropoff_date' => 'Sept 10, 2024',
                'pickup_date' => 'Sept 12, 2024',
                'payment_status' => 'Paid',
                'created_at' => 'Sept 10, 2024 3:30 PM'
            ]
        ]);

        // Filter orders for the current customer only
        // In a real application, this would be: Order::where('customer_id', $currentUser->id)->get()
        $orders = $allOrders->filter(function ($order) use ($currentUser) {
            return $order['customer_id'] == $currentUser->id;
        })->values();

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show customer's specific order details
     */
    public function customerOrderShow($id)
    {
        $currentUser = auth()->user();
        
        // Sample order data - in real app, verify ownership: Order::where('id', $id)->where('customer_id', $currentUser->id)->firstOrFail()
        $order = [
            'id' => $id,
            'customer_id' => $currentUser->id,
            'service_type' => 'Wash & Fold',
            'status' => 'Completed',
            'total_price' => 750.00,
            'dropoff_date' => 'Sept 9, 2024',
            'pickup_date' => 'Sept 11, 2024',
            'payment_status' => 'Paid',
            'created_at' => 'Sept 9, 2024 10:30 AM',
            'items_count' => 5,
            'notes' => 'Please handle with care - delicate items included'
        ];

        // Verify the order belongs to the current customer
        if ($order['customer_id'] != $currentUser->id) {
            abort(403, 'Unauthorized access to this order.');
        }

        return view('customer.orders.show', compact('order'));
    }
}
