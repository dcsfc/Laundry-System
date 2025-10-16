<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of assigned orders
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        $orders = Order::where('staff_id', $currentUser->id)
            ->with(['customer', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('staff.orders.index', compact('orders'));
    }

    /**
     * Display the specified order
     */
    public function show(string $id)
    {
        $currentUser = Auth::user();
        
        $order = Order::where('staff_id', $currentUser->id)
            ->with(['customer', 'service'])
            ->findOrFail($id);
            
        return view('staff.orders.show', compact('order'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, string $id)
    {
        $currentUser = Auth::user();
        
        $order = Order::where('staff_id', $currentUser->id)->findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:scheduled,priced,in_progress,completed,canceled',
            'total_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string'
        ]);
        
        $order->update($request->only(['status', 'total_price', 'notes']));
        
        return redirect()->route('staff.orders.index')->with('success', 'Order updated successfully');
    }
}

