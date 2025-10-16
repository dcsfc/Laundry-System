<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\User;
use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index()
    {
        $orders = Order::with(['customer', 'service', 'staff'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        $services = Service::where('is_active', true)->get();
        $staff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();

        return view('admin.orders.create', compact('customers', 'services', 'staff'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'staff_id' => 'nullable|exists:users,id',
            'dropoff_date' => 'required|date',
            'pickup_date' => 'required|date|after_or_equal:dropoff_date',
            'dropoff_time' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'total_price' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid',
            'payment_method' => 'nullable|in:cash,gcash,credit_card,paypal',
            'status' => 'nullable|in:scheduled,priced,in_progress,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $validated['created_by'] = auth()->id();
        
        Order::create($validated);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order created successfully.');
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['customer', 'service', 'staff', 'payment']);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $customers = User::whereHas('role', function($query) {
            $query->where('name', 'customer');
        })->get();
        
        $services = Service::where('is_active', true)->get();
        $staff = User::whereHas('role', function($query) {
            $query->where('name', 'staff');
        })->get();

        return view('admin.orders.edit', compact('order', 'customers', 'services', 'staff'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:users,id',
            'service_id' => 'nullable|exists:services,id',
            'staff_id' => 'nullable|exists:users,id',
            'dropoff_date' => 'required|date',
            'pickup_date' => 'required|date|after_or_equal:dropoff_date',
            'dropoff_time' => 'nullable|string',
            'pickup_time' => 'nullable|string',
            'total_price' => 'nullable|numeric|min:0',
            'payment_status' => 'nullable|in:unpaid,paid',
            'payment_method' => 'nullable|in:cash,gcash,credit_card,paypal',
            'status' => 'nullable|in:scheduled,priced,in_progress,completed,canceled',
            'notes' => 'nullable|string',
        ]);

        $order->update($validated);

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        $order->delete();

        return redirect()->route('admin.orders.index')
            ->with('success', 'Order deleted successfully.');
    }
}


