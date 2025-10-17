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
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'service_name' => $order->service->name ?? 'General Laundry',
                    'staff_name' => $order->staff->name ?? 'Unassigned',
                    'dropoff_date' => $order->dropoff_date ? \Carbon\Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'payment_status' => ucfirst($order->payment_status ?? 'Unpaid'),
                    'total_price' => $order->total_price ? number_format((float)$order->total_price, 2) : 'N/A',
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                ];
            })->toArray();

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true, 'searchable' => true],
            ['key' => 'service_name', 'label' => 'Service', 'sortable' => true],
            ['key' => 'staff_name', 'label' => 'Staff', 'sortable' => true],
            ['key' => 'dropoff_date', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup_date', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'payment_status', 'label' => 'Payment', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'total_price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'edit', 'label' => 'Edit Order', 'icon' => 'edit', 'color' => 'yellow'],
        ];

        return view('admin.orders.index', compact('orders', 'columns', 'actions'));
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


