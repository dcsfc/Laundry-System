<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Display weekly reports page
     */
    public function index()
    {
        // Get weekly reports data (last 4 weeks)
        $reports = collect();
        
        for ($i = 3; $i >= 0; $i--) {
            $startOfWeek = Carbon::now()->subWeeks($i)->startOfWeek();
            $endOfWeek = Carbon::now()->subWeeks($i)->endOfWeek();
            
            // Get orders for this week
            $weeklyOrders = Order::where('staff_id', Auth::id())
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->get();
            
            // Get payments for this week
            $weeklyPayments = Payment::whereHas('order', function($query) use ($startOfWeek, $endOfWeek) {
                $query->where('staff_id', Auth::id())
                      ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            })->where('payment_status', 'paid')->get();
            
            $totalOrders = $weeklyOrders->count();
            $completedOrders = $weeklyOrders->where('status', 'completed')->count();
            $revenue = $weeklyPayments->sum('amount');
            
            // Determine status
            $status = 'completed';
            $statusText = 'Completed';
            $statusClass = 'status-completed';
            
            if ($totalOrders === 0) {
                $status = 'no-data';
                $statusText = 'No Data';
                $statusClass = 'status-no-data';
            } elseif ($completedOrders < $totalOrders) {
                $status = 'in-progress';
                $statusText = 'In Progress';
                $statusClass = 'status-in-progress';
            }

            $reports->push([
                'id' => $i + 1,
                'week_period' => $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y'),
                'total_orders' => $totalOrders,
                'completed_orders' => $completedOrders,
                'revenue' => $revenue,
                'status' => $status,
                'status_text' => $statusText,
                'status_class' => $statusClass,
                'created_at' => $startOfWeek->format('M j, Y'),
                'week_start' => $startOfWeek->toDateString(),
                'week_end' => $endOfWeek->toDateString(),
            ]);
        }

        // Define columns for reports table
        $columns = [
            ['key' => 'id', 'label' => 'Week #', 'sortable' => true],
            ['key' => 'week_period', 'label' => 'Week Period', 'sortable' => true],
            ['key' => 'total_orders', 'label' => 'Total Orders', 'sortable' => true],
            ['key' => 'completed_orders', 'label' => 'Completed', 'sortable' => true],
            ['key' => 'revenue', 'label' => 'Revenue', 'sortable' => true],
            ['key' => 'status_text', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'created_at', 'label' => 'Week Start', 'sortable' => true],
        ];

        // Define actions for reports
        $actions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'fas fa-eye'],
            ['key' => 'export', 'label' => 'Export Report', 'icon' => 'fas fa-download'],
        ];

        return view('staff.reports.index', compact(
            'reports',
            'columns',
            'actions'
        ));
    }

    /**
     * Show the form for creating a new report
     */
    public function create()
    {
        return view('staff.reports.create');
    }

    /**
     * Generate a new report
     */
    public function store(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        // This would typically generate a detailed report
        // For now, we'll just redirect back with a success message
        return redirect()->route('staff.reports.index')
            ->with('success', 'Report generated successfully!');
    }

    /**
     * Display the specified report details
     */
    public function show($id)
    {
        // Get report details for the specified week
        $weekOffset = $id - 1;
        $startOfWeek = Carbon::now()->subWeeks($weekOffset)->startOfWeek();
        $endOfWeek = Carbon::now()->subWeeks($weekOffset)->endOfWeek();
        
        // Get detailed data for this week
        $orders = Order::where('staff_id', Auth::id())
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->with(['customer', 'service'])
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'service_name' => $order->service->name ?? 'General Laundry',
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'total_price' => $order->total_price,
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                ];
            });

        $payments = Payment::whereHas('order', function($query) use ($startOfWeek, $endOfWeek) {
            $query->where('staff_id', Auth::id())
                  ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
        })->where('payment_status', 'paid')->get();

        return response()->json([
            'success' => true,
            'report' => [
                'week_period' => $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y'),
                'total_orders' => $orders->count(),
                'completed_orders' => $orders->where('status', 'Completed')->count(),
                'total_revenue' => $payments->sum('amount'),
                'orders' => $orders,
                'payments' => $payments->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'order_id' => $payment->order_id,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'paid_at' => $payment->paid_at ? Carbon::parse($payment->paid_at)->format('M j, Y g:i A') : 'N/A',
                    ];
                }),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified report
     */
    public function edit($id)
    {
        // Reports are typically read-only, but this could be used for custom date ranges
        return view('staff.reports.edit', compact('id'));
    }

    /**
     * Export report data
     */
    public function export($id)
    {
        // This would typically generate a PDF or Excel file
        // For now, we'll return a JSON response
        $weekOffset = $id - 1;
        $startOfWeek = Carbon::now()->subWeeks($weekOffset)->startOfWeek();
        $endOfWeek = Carbon::now()->subWeeks($weekOffset)->endOfWeek();
        
        return response()->json([
            'success' => true,
            'message' => 'Report export functionality would be implemented here',
            'week_period' => $startOfWeek->format('M j') . ' - ' . $endOfWeek->format('M j, Y'),
        ]);
    }
}

