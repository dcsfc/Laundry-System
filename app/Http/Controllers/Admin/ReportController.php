<?php

namespace App\Http\Controllers\Admin;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{
    /**
     * Display sales reports
     */
    public function index(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Total revenue
        $totalRevenue = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->sum('total_price');

        // Total orders
        $totalOrders = Order::whereBetween('created_at', [$startDate, $endDate])->count();

        // Completed orders
        $completedOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', 'completed')
            ->count();

        // Pending orders
        $pendingOrders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->count();

        // Revenue by service
        $revenueByService = Order::selectRaw('services.name as service_name, SUM(orders.total_price) as revenue')
            ->join('services', 'orders.service_id', '=', 'services.id')
            ->whereBetween('orders.created_at', [$startDate, $endDate])
            ->where('orders.payment_status', 'paid')
            ->groupBy('services.id', 'services.name')
            ->get();

        // Orders trend (last 30 days)
        $ordersTrend = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $ordersTrend[] = [
                'date' => $date->format('M d'),
                'count' => Order::whereDate('created_at', $date)->count()
            ];
        }

        return view('admin.reports.index', compact(
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'pendingOrders',
            'revenueByService',
            'ordersTrend',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Generate sales report for specific period
     */
    public function sales(Request $request)
    {
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $orders = Order::with(['customer', 'service', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_price');

        return view('admin.reports.sales', compact('orders', 'totalRevenue', 'startDate', 'endDate'));
    }

    /**
     * Export report as CSV/PDF
     */
    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth());
        $endDate = $request->input('end_date', Carbon::now()->endOfMonth());

        $orders = Order::with(['customer', 'service', 'payment'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->where('payment_status', 'paid')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($format === 'csv') {
            return $this->exportAsCsv($orders, $startDate, $endDate);
        }

        // For PDF export, you would integrate a PDF library like dompdf or snappy
        return redirect()->back()->with('error', 'PDF export not implemented yet.');
    }

    /**
     * Export report as CSV
     */
    private function exportAsCsv($orders, $startDate, $endDate)
    {
        $filename = "sales_report_{$startDate}_to_{$endDate}.csv";
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');
            
            // Add headers
            fputcsv($file, ['Date', 'Order ID', 'Customer', 'Service', 'Amount', 'Payment Method', 'Status']);
            
            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->created_at->format('Y-m-d'),
                    $order->id,
                    $order->customer->name ?? 'N/A',
                    $order->service->name ?? 'N/A',
                    $order->total_price ?? 0,
                    $order->payment_method ?? 'N/A',
                    $order->status,
                ]);
            }
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}


