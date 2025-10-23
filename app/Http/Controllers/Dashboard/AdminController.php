<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard with real data
     */
    public function dashboard()
    {
        $currentUser = Auth::user();
        
        // Get real data from database for KPI cards
        $totalUsers = User::count();
        $totalRevenue = $this->getTotalRevenue();
        $completedLaundry = Order::where('status', 'completed')->count();
        $pendingLaundry = Order::whereIn('status', ['scheduled', 'priced', 'in_progress'])->count();
        
        // Get growth percentages
        $revenueGrowth = $this->getRevenueGrowth();
        $userGrowth = $this->getUserGrowth();
        
        // Get recent orders
        $recentOrders = $this->getRecentOrders();
        
        // Get inventory status
        $inventoryItems = $this->getInventoryStatus();
        
        // Get chart data
        $revenueData = $this->getRevenueData();
        $orderData = $this->getOrderData();
        $sparklineData = $this->getSparklineData();

        return view('dashboard.admin', compact(
            'totalUsers',
            'totalRevenue',
            'completedLaundry',
            'pendingLaundry',
            'revenueGrowth',
            'userGrowth',
            'recentOrders',
            'inventoryItems',
            'revenueData',
            'orderData',
            'sparklineData'
        ));
    }

    /**
     * Get total revenue from all orders
     */
    private function getTotalRevenue()
    {
        // Try to get revenue from payments table first
        $revenue = Payment::where('payment_status', 'paid')->sum('amount');
        
        // If no revenue from payments, try to get from orders table
        if ($revenue == 0) {
            $revenue = Order::where('payment_status', 'paid')
                ->whereNotNull('total_price')
                ->sum('total_price');
        }
        
        return $revenue;
    }

    /**
     * Get revenue growth percentage
     */
    private function getRevenueGrowth()
    {
        $thisMonth = Payment::where('payment_status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->month)
            ->whereYear('paid_at', Carbon::now()->year)
            ->sum('amount');
            
        $lastMonth = Payment::where('payment_status', 'paid')
            ->whereMonth('paid_at', Carbon::now()->subMonth()->month)
            ->whereYear('paid_at', Carbon::now()->subMonth()->year)
            ->sum('amount');
        
        // If no payment data, try orders table
        if ($thisMonth == 0 && $lastMonth == 0) {
            $thisMonth = Order::where('payment_status', 'paid')
                ->whereNotNull('total_price')
                ->whereMonth('updated_at', Carbon::now()->month)
                ->whereYear('updated_at', Carbon::now()->year)
                ->sum('total_price');
                
            $lastMonth = Order::where('payment_status', 'paid')
                ->whereNotNull('total_price')
                ->whereMonth('updated_at', Carbon::now()->subMonth()->month)
                ->whereYear('updated_at', Carbon::now()->subMonth()->year)
                ->sum('total_price');
        }
        
        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : 0;
    }

    /**
     * Get user growth percentage
     */
    private function getUserGrowth()
    {
        $thisMonth = User::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
            
        $lastMonth = User::whereMonth('created_at', Carbon::now()->subMonth()->month)
            ->whereYear('created_at', Carbon::now()->subMonth()->year)
            ->count();
            
        return $lastMonth > 0 ? round((($thisMonth - $lastMonth) / $lastMonth) * 100) : 0;
    }

    /**
     * Get recent orders
     */
    private function getRecentOrders()
    {
        return Order::with(['customer', 'staff', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'Unknown Customer',
                    'staff_name' => $order->staff->name ?? 'Unassigned',
                    'service_name' => $order->service->name ?? 'Laundry Service',
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'total_price' => $order->total_price ?? 0,
                    'created_at' => $order->created_at->format('M j, Y g:i A')
                ];
            });
    }

    /**
     * Get inventory status
     */
    private function getInventoryStatus()
    {
        try {
            return Inventory::orderBy('quantity', 'asc')
                ->limit(5)
                ->get()
                ->map(function ($item) {
                $status = 'good';
                $statusText = 'Good stock level';
                $iconClass = 'fas fa-box text-emerald-500';
                $bgClass = 'bg-emerald-500/20';
                
                if ($item->quantity <= $item->threshold) {
                    $status = 'low';
                    $statusText = 'Low stock - reorder needed';
                    $iconClass = 'fas fa-exclamation-triangle text-amber-400';
                    $bgClass = 'bg-amber-500/20';
                } elseif ($item->quantity <= ($item->threshold * 1.5)) {
                    $status = 'warning';
                    $statusText = 'Monitor stock level';
                    $iconClass = 'fas fa-box text-sky-400';
                    $bgClass = 'bg-sky-500/20';
                }
                
                return [
                    'name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'status' => $status,
                    'status_text' => $statusText,
                    'icon_class' => $iconClass,
                    'bg_class' => $bgClass
                ];
            });
        } catch (\Exception $e) {
            // Return empty collection if inventory table doesn't exist or has issues
            return collect([]);
        }
    }

    /**
     * Get revenue data for the chart (last 6 months)
     */
    private function getRevenueData()
    {
        $months = [];
        $data = [];
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $revenue = Payment::where('payment_status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
                
            // If no payment data, try orders table
            if ($revenue == 0) {
                $revenue = Order::where('payment_status', 'paid')
                    ->whereNotNull('total_price')
                    ->whereMonth('updated_at', $date->month)
                    ->whereYear('updated_at', $date->year)
                    ->sum('total_price');
            }
                
            $data[] = $revenue;
        }

        return [
            'categories' => $months,
            'data' => $data
        ];
    }

    /**
     * Get order data for the chart (last 6 months)
     */
    private function getOrderData()
    {
        $months = [];
        $completed = [];
        $pending = [];
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $completed[] = Order::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $pending[] = Order::whereIn('status', ['scheduled', 'priced', 'in_progress'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return [
            'categories' => $months,
            'completed' => $completed,
            'pending' => $pending
        ];
    }

    /**
     * Get sparkline data for KPI cards
     */
    private function getSparklineData()
    {
        $sparklineData = [];
        
        // Get last 6 months of data for sparklines
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Revenue sparkline
            $revenue = Payment::where('payment_status', 'paid')
                ->whereMonth('paid_at', $date->month)
                ->whereYear('paid_at', $date->year)
                ->sum('amount');
                
            // If no payment data, try orders table
            if ($revenue == 0) {
                $revenue = Order::where('payment_status', 'paid')
                    ->whereNotNull('total_price')
                    ->whereMonth('updated_at', $date->month)
                    ->whereYear('updated_at', $date->year)
                    ->sum('total_price');
            }
            $sparklineData['revenue'][] = $revenue;
            
            // Users sparkline
            $sparklineData['users'][] = User::whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Completed orders sparkline
            $sparklineData['completed'][] = Order::where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Pending orders sparkline
            $sparklineData['pending'][] = Order::whereIn('status', ['scheduled', 'priced', 'in_progress'])
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
        }

        return $sparklineData;
    }
}
















