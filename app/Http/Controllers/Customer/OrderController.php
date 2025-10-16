<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class OrderController extends Controller
{
    /**
     * Display customer's order history
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        $orders = Order::where('customer_id', $currentUser->id)
            ->with(['service', 'staff', 'payment'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('customer.orders.index', compact('orders'));
    }

    /**
     * Display specific order details
     */
    public function show($id)
    {
        $currentUser = Auth::user();
        
        $order = Order::where('customer_id', $currentUser->id)
            ->where('id', $id)
            ->with(['service', 'staff', 'payment'])
            ->firstOrFail();

        return view('customer.orders.show', compact('order'));
    }
}


