<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class PaymentController extends Controller
{
    /**
     * Display payment management page
     */
    public function index()
    {
        // Get all payments with related order information
        $payments = Payment::with(['order.customer', 'recordedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($payment) {
                // Determine payment status styling
                $statusClass = 'status-pending';
                switch ($payment->payment_status) {
                    case 'paid':
                        $statusClass = 'status-paid';
                        break;
                    case 'failed':
                        $statusClass = 'status-failed';
                        break;
                    default:
                        $statusClass = 'status-pending';
                }

                return [
                    'id' => $payment->id,
                    'order_id' => $payment->order_id,
                    'customer_name' => $payment->order->customer->name ?? 'N/A',
                    'amount' => $payment->amount,
                    'payment_method' => ucfirst(str_replace('_', ' ', $payment->payment_method)),
                    'payment_status' => ucfirst($payment->payment_status),
                    'status_class' => $statusClass,
                    'reference_number' => $payment->reference_number ?? 'N/A',
                    'recorded_by' => $payment->recordedBy->name ?? 'System',
                    'paid_at' => $payment->paid_at ? Carbon::parse($payment->paid_at)->format('M j, Y g:i A') : 'N/A',
                    'created_at' => $payment->created_at->format('M j, Y g:i A'),
                ];
            });

        // Define columns for payments table
        $columns = [
            ['key' => 'id', 'label' => 'Payment ID', 'sortable' => true],
            ['key' => 'order_id', 'label' => 'Order ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'amount', 'label' => 'Amount', 'sortable' => true],
            ['key' => 'payment_method', 'label' => 'Method', 'sortable' => true],
            ['key' => 'payment_status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'reference_number', 'label' => 'Reference', 'sortable' => true],
            ['key' => 'recorded_by', 'label' => 'Recorded By', 'sortable' => true],
            ['key' => 'paid_at', 'label' => 'Paid At', 'sortable' => true],
        ];

        // Define actions for payments
        $actions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'edit', 'label' => 'Edit Payment', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'delete', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red'],
        ];

        return view('staff.payments.index', compact(
            'payments',
            'columns',
            'actions'
        ));
    }

    /**
     * Show the form for creating a new payment
     */
    public function create()
    {
        // Get orders that need payment
        $orders = Order::where('payment_status', 'unpaid')
            ->with('customer')
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'display' => "Order #{$order->id} - {$order->customer->name} (₱{$order->total_price})"
                ];
            });

        return view('staff.payments.create', compact('orders'));
    }

    /**
     * Store a newly created payment
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'required|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $payment = Payment::create([
            'order_id' => $request->order_id,
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'reference_number' => $request->reference_number,
            'recorded_by' => Auth::id(),
            'paid_at' => $request->payment_status === 'paid' ? now() : null,
        ]);

        return redirect()->route('staff.payments.index')
            ->with('success', 'Payment recorded successfully!');
    }

    /**
     * Display the specified payment
     */
    public function show($id)
    {
        $payment = Payment::with(['order.customer', 'recordedBy'])->findOrFail($id);
        
        return response()->json([
            'success' => true,
            'payment' => [
                'id' => $payment->id,
                'order_id' => $payment->order_id,
                'customer_name' => $payment->order->customer->name ?? 'N/A',
                'amount' => $payment->amount,
                'payment_method' => $payment->payment_method,
                'payment_status' => $payment->payment_status,
                'reference_number' => $payment->reference_number,
                'recorded_by' => $payment->recordedBy->name ?? 'System',
                'paid_at' => $payment->paid_at ? Carbon::parse($payment->paid_at)->format('M j, Y g:i A') : 'N/A',
                'created_at' => $payment->created_at->format('M j, Y g:i A'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified payment
     */
    public function edit($id)
    {
        $payment = Payment::with(['order.customer'])->findOrFail($id);
        return view('staff.payments.edit', compact('payment'));
    }

    /**
     * Update the specified payment
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,gcash,credit_card,paypal',
            'payment_status' => 'required|in:pending,paid,failed',
            'reference_number' => 'nullable|string|max:100',
        ]);

        $payment = Payment::findOrFail($id);
        $payment->update([
            'amount' => $request->amount,
            'payment_method' => $request->payment_method,
            'payment_status' => $request->payment_status,
            'reference_number' => $request->reference_number,
            'paid_at' => $request->payment_status === 'paid' ? now() : null,
        ]);

        return redirect()->route('staff.payments.index')
            ->with('success', 'Payment updated successfully!');
    }

    /**
     * Remove the specified payment
     */
    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        $payment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Payment deleted successfully!'
        ]);
    }
}

