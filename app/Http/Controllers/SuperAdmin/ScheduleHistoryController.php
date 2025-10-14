<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ScheduleHistoryController extends Controller
{
    /**
     * Display the schedule history page
     */
    public function index(Request $request)
    {
        $currentUser = auth()->user();
        
        // Get ALL schedule history (completed and cancelled orders) - no server-side filtering
        $orders = Order::where('customer_id', $currentUser->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['service', 'staff', 'customer'])
            ->orderBy('pickup_date', 'desc')
            ->get();
        
        // Transform ALL the data for client-side filtering
        $allSchedules = $orders->map(function ($order) use ($currentUser) {
            return [
                'id' => $order->id,
                'reference_id' => 'SCH-' . date('Ymd', strtotime($order->created_at)) . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT),
                'service_type' => $order->service->name ?? 'Laundry Service',
                'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                'dropoff_time' => $order->dropoff_time ? Carbon::parse($order->dropoff_time)->format('g:i A') : null,
                'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                'pickup_time' => $order->pickup_time ? Carbon::parse($order->pickup_time)->format('g:i A') : null,
                'status' => $this->getStatusDisplay($order->status),
                'status_raw' => $order->status,
                'cancelled_reason' => $order->notes ?? null,
                'total_amount' => $order->total_price ?? 0,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'staff_name' => $order->staff->name ?? 'Unassigned',
                'customer_name' => $currentUser->name,
                // Add searchable fields for instant filtering
                'searchable_text' => strtolower(implode(' ', [
                    $order->id,
                    $order->service->name ?? 'Laundry Service',
                    $this->getStatusDisplay($order->status),
                    $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('Y-m-d') : '',
                    $order->pickup_date ? Carbon::parse($order->pickup_date)->format('Y-m-d') : '',
                    'SCH-' . date('Ymd', strtotime($order->created_at)) . '-' . str_pad($order->id, 4, '0', STR_PAD_LEFT)
                ]))
            ];
        });

        // Get counts for filter tabs
        $allCount = $orders->count();
        $completedCount = $orders->where('status', 'completed')->count();
        $cancelledCount = $orders->where('status', 'cancelled')->count();

        return view('customer.schedules.history', compact(
            'allSchedules', 
            'allCount',
            'completedCount', 
            'cancelledCount'
        ));
    }

    /**
     * Get display text for order status
     */
    private function getStatusDisplay($status)
    {
        $statusMap = [
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'canceled' => 'Cancelled', // Legacy support
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
