<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get real orders data from database with relationships
        $orders = Order::with(['customer', 'staff', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'service_type' => 'Wash & Fold', // This would come from order_items or services table
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'total_price' => number_format($order->total_price ?? 0, 2),
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'payment_status' => ucfirst($order->payment_status),
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'staff_name' => $order->staff->name ?? 'Unassigned',
                    'notes' => $order->notes ?? ''
                ];
            });

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
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        $staff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();
        
        $services = Service::where('is_active', true)->get();
        
        return view('superadmin.orders.create', compact('customers', 'staff', 'services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'staff_id' => 'nullable|exists:users,id',
            'dropoff_date' => 'required|date|after_or_equal:today',
            'pickup_date' => 'required|date|after:dropoff_date',
            'total_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order = Order::create([
            'customer_id' => $request->customer_id,
            'staff_id' => $request->staff_id,
            'dropoff_date' => $request->dropoff_date,
            'pickup_date' => $request->pickup_date,
            'total_price' => $request->total_price,
            'status' => 'scheduled',
            'payment_status' => 'unpaid',
            'notes' => $request->notes,
            'created_by' => Auth::id()
        ]);

        return redirect()->route('superadmin.orders.index')->with('success', 'Order created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = Order::with(['customer', 'staff', 'createdBy', 'payments'])->findOrFail($id);
        
        return view('superadmin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = Order::with(['customer', 'staff'])->findOrFail($id);
        
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        $staff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();
        
        return view('superadmin.orders.edit', compact('order', 'customers', 'staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'customer_id' => 'required|exists:users,id',
            'staff_id' => 'nullable|exists:users,id',
            'dropoff_date' => 'required|date',
            'pickup_date' => 'required|date|after:dropoff_date',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'required|in:scheduled,priced,in_progress,completed,canceled',
            'payment_status' => 'required|in:unpaid,paid',
            'payment_method' => 'nullable|in:cash,gcash,credit_card,paypal',
            'notes' => 'nullable|string|max:1000'
        ]);

        $order = Order::findOrFail($id);
        
        $order->update([
            'customer_id' => $request->customer_id,
            'staff_id' => $request->staff_id,
            'dropoff_date' => $request->dropoff_date,
            'pickup_date' => $request->pickup_date,
            'total_price' => $request->total_price,
            'status' => $request->status,
            'payment_status' => $request->payment_status,
            'payment_method' => $request->payment_method,
            'notes' => $request->notes
        ]);

        return redirect()->route('superadmin.orders.index')->with('success', 'Order updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('superadmin.orders.index')->with('success', 'Order deleted successfully');
    }

    /**
     * Display customer's order history (filtered by customer_id)
     */
    public function customerOrders()
    {
        $currentUser = auth()->user();
        
        // Get real orders data for the current customer
        $orders = Order::where('customer_id', $currentUser->id)
            ->with(['staff', 'payments'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $currentUser->name,
                    'customer_email' => $currentUser->email,
                    'service_type' => 'Wash & Fold', // This would come from order_items or services table
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'total_price' => number_format($order->total_price ?? 0, 2),
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'payment_status' => ucfirst($order->payment_status),
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'staff_name' => $order->staff->name ?? 'Unassigned',
                    'notes' => $order->notes ?? ''
                ];
            });

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Show customer's specific order details
     */
    public function customerOrderShow($id)
    {
        $currentUser = auth()->user();
        
        // Get real order data and verify ownership
        $order = Order::where('id', $id)
            ->where('customer_id', $currentUser->id)
            ->with(['staff', 'payments', 'customer'])
            ->firstOrFail();

        // Format the order data for the view
        $orderData = [
            'id' => $order->id,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer->name,
            'customer_email' => $order->customer->email,
            'service_type' => 'Wash & Fold', // This would come from order_items or services table
            'status' => ucfirst(str_replace('_', ' ', $order->status)),
            'total_price' => number_format($order->total_price ?? 0, 2),
            'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
            'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
            'payment_status' => ucfirst($order->payment_status),
            'payment_method' => ucfirst(str_replace('_', ' ', $order->payment_method ?? 'Not specified')),
            'created_at' => $order->created_at->format('M j, Y g:i A'),
            'staff_name' => $order->staff->name ?? 'Unassigned',
            'notes' => $order->notes ?? 'No special instructions',
            'payments' => $order->payments->map(function ($payment) {
                return [
                    'amount' => number_format($payment->amount, 2),
                    'method' => ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    'status' => ucfirst($payment->payment_status),
                    'paid_at' => $payment->paid_at ? Carbon::parse($payment->paid_at)->format('M j, Y g:i A') : 'Not paid',
                    'reference_number' => $payment->reference_number ?? 'N/A'
                ];
            })
        ];

        return view('customer.orders.show', compact('orderData'));
    }
}
