<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CustomerController extends Controller
{
    /**
     * Display the customer dashboard with real data
     */
    public function dashboard()
    {
        $currentUser = Auth::user();
        
        // Get real data from database
        $totalOrders = Order::where('customer_id', $currentUser->id)->count();
        $completedOrders = Order::where('customer_id', $currentUser->id)->where('status', 'completed')->count();
        $pendingOrders = Order::where('customer_id', $currentUser->id)->whereIn('status', ['scheduled', 'priced', 'in_progress'])->count();
        $totalSpent = Payment::whereHas('order', function($query) use ($currentUser) {
            $query->where('customer_id', $currentUser->id);
        })->where('payment_status', 'paid')->sum('amount');

        // Calculate completion rate
        $completionRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100) : 0;

        // Get recent orders (last 3)
        $recentOrders = Order::where('customer_id', $currentUser->id)
            ->with(['staff'])
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'service_type' => 'Wash & Fold', // This would come from order_items or services table
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'total_price' => number_format($order->total_price ?? 0, 2),
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'payment_status' => ucfirst($order->payment_status),
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'staff_name' => $order->staff->name ?? 'Unassigned'
                ];
            });

        // Get upcoming schedules (orders that are scheduled or in progress)
        $upcomingSchedules = Order::where('customer_id', $currentUser->id)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->with(['staff'])
            ->orderBy('dropoff_date', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'service_type' => 'Wash & Fold', // This would come from order_items or services table
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('Y-m-d') : 'N/A',
                    'dropoff_time' => '09:00 AM', // This would come from a time field or be calculated
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('Y-m-d') : 'N/A',
                    'pickup_time' => '05:00 PM', // This would come from a time field or be calculated
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
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
        $lastMonthSpent = Payment::whereHas('order', function($query) use ($currentUser) {
            $query->where('customer_id', $currentUser->id);
        })->where('payment_status', 'paid')
        ->whereMonth('paid_at', $lastMonth->month)
        ->whereYear('paid_at', $lastMonth->year)
        ->sum('amount');
        
        $thisMonthSpent = Payment::whereHas('order', function($query) use ($currentUser) {
            $query->where('customer_id', $currentUser->id);
        })->where('payment_status', 'paid')
        ->whereMonth('paid_at', $thisMonth->month)
        ->whereYear('paid_at', $thisMonth->year)
        ->sum('amount');
        
        $monthlyGrowth = $lastMonthSpent > 0 ? round((($thisMonthSpent - $lastMonthSpent) / $lastMonthSpent) * 100) : 0;

        return view('dashboard.customer', compact(
            'totalOrders',
            'completedOrders', 
            'pendingOrders',
            'totalSpent',
            'completionRate',
            'recentOrders',
            'upcomingSchedules',
            'monthlyGrowth',
            'monthlyOrderGrowth'
        ));
    }
}
