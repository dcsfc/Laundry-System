<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get real payments data from database with relationships
        $payments = Payment::with(['order.customer', 'recordedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'customer_name' => $payment->order->customer->name ?? 'N/A',
                    'amount' => number_format($payment->amount, 2),
                    'payment_method' => ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    'payment_status' => ucfirst($payment->payment_status),
                    'reference_number' => $payment->reference_number ?? 'N/A',
                    'paid_at' => $payment->paid_at ? Carbon::parse($payment->paid_at)->format('M j, Y g:i A') : 'Not paid',
                    'recorded_by' => $payment->recordedBy->name ?? 'System',
                    'created_at' => $payment->created_at->format('M j, Y g:i A')
                ];
            });

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
            ['key' => 'viewPayment', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'editPayment', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'updatePaymentStatus', 'label' => 'Update Status', 'icon' => 'toggle', 'color' => 'green'],
            ['key' => 'deletePayment', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red']
        ];

        $description = 'Monitor payment transactions, methods, and payment status for laundry services';
        
        return view('superadmin.payments.index', compact('payments', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $orders = Order::with('customer')
            ->where('payment_status', 'unpaid')
            ->get();
        
        return view('superadmin.payments.create', compact('orders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'required|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100'
        ]);

        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'reference_number' => $request->reference_number,
            'recorded_by' => Auth::id(),
            'paid_at' => $request->payment_status === 'paid' ? now() : null
        ]);

        // Update order payment status if payment is marked as paid
        if ($request->payment_status === 'paid') {
            $order = Order::find($request->order_id);
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method
            ]);
        }

        return redirect()->route('superadmin.payments.index')->with('success', 'Payment recorded successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::with(['order.customer', 'recordedBy'])->findOrFail($id);
        
        return view('superadmin.payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $payment = Payment::with(['order.customer'])->findOrFail($id);
        $orders = Order::with('customer')->get();
        
        return view('superadmin.payments.edit', compact('payment', 'orders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'required|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100'
        ]);

        $payment = Payment::findOrFail($id);
        $oldStatus = $payment->payment_status;
        
        $payment->update([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'reference_number' => $request->reference_number,
            'paid_at' => $request->payment_status === 'paid' ? now() : null
        ]);

        // Update order payment status if payment status changed to paid
        if ($oldStatus !== 'paid' && $request->payment_status === 'paid') {
            $order = Order::find($request->order_id);
            $order->update([
                'payment_status' => 'paid',
                'payment_method' => $request->payment_method
            ]);
        }

        return redirect()->route('superadmin.payments.index')->with('success', 'Payment updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return redirect()->route('superadmin.payments.index')->with('success', 'Payment deleted successfully');
    }
}
