<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Announcement;
use App\Helpers\StatusHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Display the customer dashboard with real data
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Get real data from database (using schedules/orders)
        $totalOrders = Order::where('customer_id', $currentUser->id)->count();
        $completedOrders = Order::where('customer_id', $currentUser->id)->where('status', 'completed')->count();
        $pendingOrders = Order::where('customer_id', $currentUser->id)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->where('approval_status', '!=', 'pending') // Exclude pending approval orders
            ->count();
        // Calculate total spent from orders table (orders with prices are considered spent)
        $totalSpent = Order::where('customer_id', $currentUser->id)
            ->whereNotNull('total_price')
            ->where('total_price', '>', 0)
            ->sum('total_price');

        // Calculate completion rate
        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;

        // Get recent schedules (last 3) - using orders table for schedules
        $recentOrders = Order::where('customer_id', $currentUser->id)
            ->with(['staff', 'service'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'service_type' => $order->service->name ?? 'Laundry Service',
                    'status' => $this->getStatusDisplay($order->status, $order->approval_status),
                    'total_price' => number_format($order->total_price ?? 0, 2),
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'payment_status' => ucfirst($order->payment_status),
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'staff_name' => $order->staff->name ?? 'Unassigned'
                ];
            });

        // Get upcoming schedules (orders that are active)
        $upcomingSchedules = Order::where('customer_id', $currentUser->id)
            ->whereIn('status', ['pending', 'confirmed', 'processing', 'ready_for_pickup'])
            ->with(['staff', 'service'])
            ->orderBy('dropoff_date', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'service_type' => $order->service->name ?? 'Laundry Service',
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('Y-m-d') : 'N/A',
                    'dropoff_time' => $order->dropoff_time ? Carbon::parse($order->dropoff_time)->format('g:i A') : '09:00 AM',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('Y-m-d') : 'N/A',
                    'pickup_time' => $order->pickup_time ? Carbon::parse($order->pickup_time)->format('g:i A') : '05:00 PM',
                    'status' => $this->getStatusDisplay($order->status),
                    'staff_assigned' => $order->staff->name ?? 'Unassigned',
                    'created_at' => $order->created_at->format('Y-m-d H:i:s')
                ];
            });

        // Calculate monthly growth
        $lastMonth = Carbon::now()->subMonth();
        $thisMonth = Carbon::now();
        
        $lastMonthOrders = Order::where('customer_id', $currentUser->id)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();
            
        $thisMonthOrders = Order::where('customer_id', $currentUser->id)
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->count();
            
        $monthlyOrderGrowth = $lastMonthOrders > 0 ? round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100) : 0;
        
        // Calculate monthly spending growth
        $lastMonthSpent = Order::where('customer_id', $currentUser->id)
            ->whereNotNull('total_price')
            ->where('total_price', '>', 0)
            ->whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum('total_price');
        
        $thisMonthSpent = Order::where('customer_id', $currentUser->id)
            ->whereNotNull('total_price')
            ->where('total_price', '>', 0)
            ->whereMonth('created_at', $thisMonth->month)
            ->whereYear('created_at', $thisMonth->year)
            ->sum('total_price');
        
        $monthlyGrowth = $lastMonthSpent > 0 ? round((($thisMonthSpent - $lastMonthSpent) / $lastMonthSpent) * 100) : 0;

        // Get announcements visible to customers
        $announcements = Announcement::with('createdBy')
            ->active()
            ->where(function($query) {
                $query->where('visible_to', 'customer')
                      ->orWhere('visible_to', 'all');
            })
            ->orderBy('is_pinned', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(3) // Show only 3 most recent
            ->get();

        // Get service usage data for chart
        $serviceUsageData = $this->getServiceUsageData($currentUser->id);
        
        // Get order history data for chart (last 6 months)
        $orderHistoryData = $this->getOrderHistoryData($currentUser->id);
        
        // Get sparkline data for KPI cards
        $sparklineData = $this->getSparklineData($currentUser->id);

        return view('dashboard.customer', compact(
            'totalOrders',
            'completedOrders', 
            'pendingOrders',
            'totalSpent',
            'completionRate',
            'recentOrders',
            'upcomingSchedules',
            'monthlyGrowth',
            'monthlyOrderGrowth',
            'announcements',
            'serviceUsageData',
            'orderHistoryData',
            'sparklineData'
        ));
    }

    /**
     * Get service usage data for the customer
     */
    private function getServiceUsageData($customerId)
    {
        // Get orders with services
        $serviceUsage = Order::where('customer_id', $customerId)
            ->leftJoin('services', 'orders.service_id', '=', 'services.id')
            ->selectRaw('COALESCE(services.name, "General Laundry") as service_name, COUNT(*) as count')
            ->groupBy('orders.service_id', 'services.name')
            ->orderBy('count', 'desc')
            ->get();

        $labels = $serviceUsage->pluck('service_name')->toArray();
        $data = $serviceUsage->pluck('count')->toArray();

        // If no data, provide default
        if (empty($labels)) {
            $labels = ['No Services Used'];
            $data = [0];
        }

        return [
            'labels' => $labels,
            'data' => $data,
            'total' => array_sum($data)
        ];
    }

    /**
     * Get order history data for the last 6 months
     */
    private function getOrderHistoryData($customerId)
    {
        $months = [];
        $data = [];
        
        // Get last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M');
            
            $count = Order::where('customer_id', $customerId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
                
            $data[] = $count;
        }

        return [
            'categories' => $months,
            'data' => $data
        ];
    }

    /**
     * Get sparkline data for KPI cards
     */
    private function getSparklineData($customerId)
    {
        $sparklineData = [];
        
        // Get last 6 months of data for sparklines
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            
            // Total orders sparkline
            $sparklineData['orders'][] = Order::where('customer_id', $customerId)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Completed orders sparkline
            $sparklineData['completed'][] = Order::where('customer_id', $customerId)
                ->where('status', 'completed')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Pending orders sparkline (exclude pending approval)
            $sparklineData['pending'][] = Order::where('customer_id', $customerId)
                ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
                ->where('approval_status', '!=', 'pending')
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->count();
            
            // Total spent sparkline
            $sparklineData['spent'][] = Order::where('customer_id', $customerId)
                ->whereNotNull('total_price')
                ->where('total_price', '>', 0)
                ->whereMonth('created_at', $date->month)
                ->whereYear('created_at', $date->year)
                ->sum('total_price');
        }

        return $sparklineData;
    }

    /**
     * Get display text for order status using consistent status system
     */
    private function getStatusDisplay($status)
    {
        $statusMap = [
            'pending' => 'Pending Approval',
            'confirmed' => 'Approved',
            'processing' => 'In Progress',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}

