<?php

namespace App\Http\Controllers\Admin;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments
     */
    public function index()
    {
        $payments = Payment::with(['order.customer'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        $orders = Order::whereIn('payment_status', ['unpaid'])
            ->with('customer')
            ->get();

        return view('admin.payments.create', compact('orders'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'nullable|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $validated['recorded_by'] = auth()->id();
        $validated['paid_at'] = $validated['payment_status'] === 'paid' ? now() : null;

        Payment::create($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment recorded successfully.');
    }

    /**
     * Display the specified payment
     */
    public function show(Payment $payment)
    {
        $payment->load(['order.customer', 'recordedBy']);
        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit(Payment $payment)
    {
        $orders = Order::with('customer')->get();
        return view('admin.payments.edit', compact('payment', 'orders'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'required|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100',
        ]);

        if ($validated['payment_status'] === 'paid' && !$payment->paid_at) {
            $validated['paid_at'] = now();
        }

        $payment->update($validated);

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment updated successfully.');
    }

    /**
     * Remove the specified payment
     */
    public function destroy(Payment $payment)
    {
        $payment->delete();

        return redirect()->route('admin.payments.index')
            ->with('success', 'Payment deleted successfully.');
    }
}


