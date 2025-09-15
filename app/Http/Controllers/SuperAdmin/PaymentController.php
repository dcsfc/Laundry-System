<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sample payments data for the reusable data table component
        $payments = collect([
            [
                'id' => 1,
                'order_id' => 101,
                'customer_name' => 'Maria Santos',
                'amount' => 150.00,
                'payment_method' => 'Cash',
                'payment_status' => 'Paid',
                'reference_number' => 'CASH-001',
                'paid_at' => '2024-01-15 10:30:00',
                'recorded_by' => 'Staff Member'
            ],
            [
                'id' => 2,
                'order_id' => 102,
                'customer_name' => 'Jose Garcia',
                'amount' => 200.00,
                'payment_method' => 'GCash',
                'payment_status' => 'Paid',
                'reference_number' => 'GCASH-789456123',
                'paid_at' => '2024-01-14 14:20:00',
                'recorded_by' => 'Admin User'
            ],
            [
                'id' => 3,
                'order_id' => 103,
                'customer_name' => 'Ana Cruz',
                'amount' => 300.00,
                'payment_method' => 'Credit Card',
                'payment_status' => 'Pending',
                'reference_number' => 'CC-456789123',
                'paid_at' => null,
                'recorded_by' => 'Staff Member'
            ],
            [
                'id' => 4,
                'order_id' => 104,
                'customer_name' => 'Carlos Reyes',
                'amount' => 120.00,
                'payment_method' => 'PayPal',
                'payment_status' => 'Failed',
                'reference_number' => 'PP-987654321',
                'paid_at' => null,
                'recorded_by' => 'Admin User'
            ]
        ]);

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'order_id', 'label' => 'Order ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'amount', 'label' => 'Amount', 'sortable' => true],
            ['key' => 'payment_method', 'label' => 'Method', 'sortable' => true],
            ['key' => 'payment_status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'reference_number', 'label' => 'Reference', 'sortable' => true],
            ['key' => 'paid_at', 'label' => 'Paid At', 'sortable' => true],
            ['key' => 'recorded_by', 'label' => 'Recorded By', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['label' => 'View', 'onclick' => 'viewPayment'],
            ['label' => 'Edit', 'onclick' => 'editPayment'],
            ['label' => 'Update Status', 'onclick' => 'updatePaymentStatus'],
            ['label' => 'Delete', 'onclick' => 'deletePayment']
        ];

        $description = 'Monitor payment transactions, methods, and payment status for laundry services';
        
        return view('superadmin.payments.index', compact('payments', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.payments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storage logic will go here
        return redirect()->route('superadmin.payments.index')->with('success', 'Payment created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('superadmin.payments.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('superadmin.payments.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic will go here
        return redirect()->route('superadmin.payments.index')->with('success', 'Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete logic will go here
        return redirect()->route('superadmin.payments.index')->with('success', 'Payment deleted successfully');
    }
}
