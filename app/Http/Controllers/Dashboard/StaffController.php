<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Controllers\Controller;

class StaffController extends Controller
{
    /**
     * Display the staff dashboard with real data
     */
    public function dashboard()
    {
        $currentUser = Auth::user();
        
        // Get real data from database for KPI cards
        $weeklyRevenue = $this->getWeeklyRevenue($currentUser->id);
        $pendingLaundry = $this->getPendingLaundry($currentUser->id);
        $completedToday = $this->getCompletedToday($currentUser->id);
        $assignedSchedules = $this->getAssignedSchedules($currentUser->id);
        
        // Get growth percentages
        $weeklyGrowth = $this->getWeeklyGrowth($currentUser->id);
        $dailyGrowth = $this->getDailyGrowth($currentUser->id);
        
        // Get due today count
        $dueToday = $this->getDueToday($currentUser->id);
        
        // Get afternoon schedules
        $afternoonSchedules = $this->getAfternoonSchedules($currentUser->id);
        
        // Get today's tasks (assigned orders)
        $todaysTasks = $this->getTodaysTasks($currentUser->id);
        
        // Get inventory status
        $inventoryItems = $this->getInventoryStatus();
        
        // Get chart data
        $performanceData = $this->getPerformanceData($currentUser->id);
        $taskDistributionData = $this->getTaskDistributionData($currentUser->id);
        $sparklineData = $this->getSparklineData($currentUser->id);

        return view('dashboard.staff', compact(
            'weeklyRevenue',
            'pendingLaundry',
            'completedToday',
            'assignedSchedules',
            'weeklyGrowth',
            'dailyGrowth',
            'dueToday',
            'afternoonSchedules',
            'todaysTasks',
            'inventoryItems',
            'performanceData',
            'taskDistributionData',
            'sparklineData'
        ));
    }

    /**
     * Get weekly revenue for the staff member
     */
    private function getWeeklyRevenue($staffId)
    {
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        // Try to get revenue from payments table first
        $revenue = Payment::whereHas('order', function($query) use ($staffId) {
            $query->where('staff_id', $staffId);
        })->where('payment_status', 'paid')
        ->whereBetween('paid_at', [$startOfWeek, $endOfWeek])
        ->sum('amount');
        
        // If no revenue from payments, try to get from orders table
        if ($revenue == 0) {
            $revenue = Order::where('staff_id', $staffId)
                ->where('payment_status', 'paid')
                ->whereBetween('updated_at', [$startOfWeek, $endOfWeek])
                ->sum('total_price');
        }
        
        return $revenue;
    }

    /**
     * Get pending laundry count for the staff member
     */
    private function getPendingLaundry($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->count();
    }

    /**
     * Get completed orders today for the staff member
     */
    private function getCompletedToday($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->where('status', 'completed')
            ->whereDate('updated_at', Carbon::today())
            ->count();
    }

    /**
     * Get assigned schedules count for the staff member
     */
    private function getAssignedSchedules($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->count();
    }

    /**
     * Get weekly growth percentage
     */
    private function getWeeklyGrowth($staffId)
    {
        $thisWeek = $this->getWeeklyRevenue($staffId);
        $lastWeek = Payment::whereHas('order', function($query) use ($staffId) {
            $query->where('staff_id', $staffId);
        })->where('payment_status', 'paid')
        ->whereBetween('paid_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->sum('amount');
        
        // If no last week data, try from orders table
        if ($lastWeek == 0) {
            $lastWeek = Order::where('staff_id', $staffId)
                ->where('payment_status', 'paid')
                ->whereBetween('updated_at', [
                    Carbon::now()->subWeek()->startOfWeek(),
                    Carbon::now()->subWeek()->endOfWeek()
                ])->sum('total_price');
        }
        
        return $lastWeek > 0 ? round((($thisWeek - $lastWeek) / $lastWeek) * 100) : 0;
    }

    /**
     * Get daily growth percentage
     */
    private function getDailyGrowth($staffId)
    {
        $today = Order::where('staff_id', $staffId)
            ->where('status', 'completed')
            ->whereDate('updated_at', Carbon::today())
            ->count();
            
        $yesterday = Order::where('staff_id', $staffId)
            ->where('status', 'completed')
            ->whereDate('updated_at', Carbon::yesterday())
            ->count();
            
        return $yesterday > 0 ? round((($today - $yesterday) / $yesterday) * 100) : 0;
    }

    /**
     * Get orders due today
     */
    private function getDueToday($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->whereDate('pickup_date', Carbon::today())
            ->count();
    }

    /**
     * Get afternoon schedules
     */
    private function getAfternoonSchedules($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->whereDate('pickup_date', Carbon::today())
            ->whereTime('pickup_time', '>=', '12:00:00')
            ->count();
    }

    /**
     * Get today's tasks (assigned orders)
     */
    private function getTodaysTasks($staffId)
    {
        return Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->with(['customer', 'service'])
            ->orderBy('pickup_time', 'asc')
            ->limit(3)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'Unknown Customer',
                    'service_name' => $order->service->name ?? 'Laundry Service',
                    'total_price' => $order->total_price ?? 0,
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'pickup_time' => $order->pickup_time ? Carbon::parse($order->pickup_time)->format('g:i A') : 'N/A',
                    'item_count' => 1 // Default item count - would need to be tracked in the database
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
                ->limit(3)
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
     * Get performance data for the chart (last 7 days)
     */
    private function getPerformanceData($staffId)
    {
        $data = [];
        $categories = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $categories[] = $date->format('D');
            
            $count = Order::where('staff_id', $staffId)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date->toDateString())
                ->count();
                
            $data[] = $count;
        }

        return [
            'categories' => $categories,
            'data' => $data
        ];
    }

    /**
     * Get task distribution data
     */
    private function getTaskDistributionData($staffId)
    {
        $completed = Order::where('staff_id', $staffId)->where('status', 'completed')->count();
        $pending = Order::where('staff_id', $staffId)->where('status', 'scheduled')->count();
        $inProgress = Order::where('staff_id', $staffId)->where('status', 'in_progress')->count();
        $overdue = Order::where('staff_id', $staffId)
            ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
            ->where('pickup_date', '<', Carbon::today())
            ->count();

        return [
            'series' => [$completed, $pending, $inProgress, $overdue],
            'labels' => ['Completed', 'Pending', 'In Progress', 'Overdue'],
            'total' => $completed + $pending + $inProgress + $overdue
        ];
    }

    /**
     * Get sparkline data for KPI cards
     */
    private function getSparklineData($staffId)
    {
        $sparklineData = [];
        
        // Get last 5 days of data for sparklines
        for ($i = 4; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Revenue sparkline
            $revenue = Payment::whereHas('order', function($query) use ($staffId) {
                $query->where('staff_id', $staffId);
            })->where('payment_status', 'paid')
            ->whereDate('paid_at', $date->toDateString())
            ->sum('amount');
            $sparklineData['revenue'][] = $revenue;
            
            // Pending sparkline
            $pending = Order::where('staff_id', $staffId)
                ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
                ->whereDate('created_at', $date->toDateString())
                ->count();
            $sparklineData['pending'][] = $pending;

            // Completed sparkline
            $completed = Order::where('staff_id', $staffId)
                ->where('status', 'completed')
                ->whereDate('updated_at', $date->toDateString())
                ->count();
            $sparklineData['completed'][] = $completed;
            
            // Schedules sparkline
            $schedules = Order::where('staff_id', $staffId)
                ->whereIn('status', ['scheduled', 'priced', 'in_progress'])
                ->whereDate('created_at', $date->toDateString())
                ->count();
            $sparklineData['schedules'][] = $schedules;
        }

        return $sparklineData;
    }
}









