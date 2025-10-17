<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Traits\ScheduleDataFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    use ScheduleDataFormatter;

    /**
     * Display a listing of schedules for staff to manage
     */
    public function index()
    {
        // Get all orders/schedules with relationships
        $orders = Order::with(['customer', 'staff', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Count pending schedules (pending status - awaiting staff approval)
        $pendingCount = Order::where('status', 'pending')->count();

        // Format schedules data for the data table
        $allSchedules = $orders->map(function ($order) {
            // Get status-specific actions for this row (pass order for conditional logic)
            $rowActions = $this->getActionsForStatus($order->status, $order);
            
            return [
                'id' => $order->id,
                'customer_id' => $order->customer_id,
                'customer_name' => $order->customer ? $order->customer->name : 'N/A',
                'customer_phone' => $order->customer ? $order->customer->phone_number : 'N/A',
                'service_type' => $order->service ? $order->service->name : 'N/A',
                'dropoff_date' => $order->dropoff_date ? $order->dropoff_date->format('Y-m-d') : 'N/A',
                'dropoff_time' => $order->dropoff_time ?? 'N/A',
                'pickup_date' => $order->pickup_date ? $order->pickup_date->format('Y-m-d') : 'N/A',
                'pickup_time' => $order->pickup_time ?? 'N/A',
                'status' => $this->getStatusDisplay($order->status),
                'raw_status' => $order->status, // Keep raw status for debugging
                'staff_assigned' => $order->staff ? $order->staff->name : 'Unassigned',
                'total_price' => $order->total_price ? '₱' . number_format($order->total_price, 2) : 'Not set',
                'weight' => $order->weight ? $order->weight . ' kg' : 'Not set',
                'payment_status' => ucfirst($order->payment_status),
                'notes' => $order->notes ?? '',
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'actions' => $rowActions, // Attach row-specific actions
            ];
        })->toArray();

        // Define columns for the data table
        $scheduleColumns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_phone', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'service_type', 'label' => 'Service', 'sortable' => true],
            ['key' => 'dropoff_date', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup_date', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'weight', 'label' => 'Weight', 'sortable' => true],
            ['key' => 'total_price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'payment_status', 'label' => 'Payment', 'sortable' => true],
        ];

        // Define a placeholder action to enable the actions column
        // Actual actions are per-row in the 'actions' field of each row
        $scheduleActions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'eye', 'color' => 'blue']
        ];

        // If it's an AJAX request (like from refreshData), return JSON
        if (request()->expectsJson() || request()->ajax() || request()->header('X-Requested-With') === 'XMLHttpRequest') {
            return response()->json([
                'success' => true,
                'data' => $allSchedules,
                'columns' => $scheduleColumns,
                'actions' => $scheduleActions,
                'stats' => [
                    'pending_count' => $pendingCount
                ]
            ]);
        }
        
        return view('staff.schedules.index', compact('allSchedules', 'scheduleColumns', 'scheduleActions', 'pendingCount'));
    }

    /**
     * Get statistics for staff schedules dashboard
     */
    public function getStats()
    {
        $today = now()->toDateString();

        $stats = [
            'pending_count' => Order::where('status', 'pending')->count(),
            'approved_today' => Order::whereDate('approved_at', $today)
                ->whereNotNull('approved_at')
                ->count(),
            'rejected_today' => Order::whereDate('updated_at', $today)
                ->where('status', 'cancelled')
                ->whereNotNull('rejection_reason')
                ->count(),
            'total_processed_today' => Order::whereDate('updated_at', $today)
                ->whereIn('status', ['confirmed', 'processing', 'ready_for_pickup', 'completed'])
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    /**
     * Get all schedules (API endpoint)
     */
    public function getAllSchedules()
    {
        $orders = Order::with(['customer', 'staff', 'service'])
            ->orderBy('created_at', 'desc')
            ->get();

        $schedules = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'customer_name' => $order->customer ? $order->customer->name : 'N/A',
                'customer_phone' => $order->customer ? $order->customer->phone_number : 'N/A',
                'service_type' => $order->service ? $order->service->name : 'N/A',
                'dropoff_date' => $order->dropoff_date ? $order->dropoff_date->format('Y-m-d') : 'N/A',
                'dropoff_time' => $order->dropoff_time ?? 'N/A',
                'pickup_date' => $order->pickup_date ? $order->pickup_date->format('Y-m-d') : 'N/A',
                'pickup_time' => $order->pickup_time ?? 'N/A',
                'status' => $order->status,
                'total_price' => $order->total_price,
                'weight' => $order->weight,
                'notes' => $order->notes ?? '',
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    /**
     * Display the specified schedule
     */
    public function show(Order $schedule)
    {
        $schedule->load(['customer', 'staff', 'service']);
        
        // Always return JSON for AJAX requests or when Accept header includes JSON
        if (request()->expectsJson() || 
            request()->ajax() || 
            request()->header('X-Requested-With') === 'XMLHttpRequest' ||
            request()->header('Accept') === 'application/json' ||
            str_contains(request()->header('Accept', ''), 'application/json')) {
            
            try {
                // Get updated actions for this order
                $rowActions = $this->getActionsForStatus($schedule->status, $schedule);
                
                return response()->json([
                    'success' => true,
                    'order' => [
                        'id' => $schedule->id,
                        'status' => $this->getStatusDisplay($schedule->status),
                        'raw_status' => $schedule->status,
                        'total_price' => $schedule->total_price ? '₱' . number_format($schedule->total_price, 2) : 'Not set',
                        'weight' => $schedule->weight ? $schedule->weight . ' kg' : 'Not set',
                        'actions' => $rowActions,
                        'payment_status' => ucfirst($schedule->payment_status),
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching schedule data: ' . $e->getMessage()
                ], 500);
            }
        }
        
        return view('staff.schedules.show', compact('schedule'));
    }

    /**
     * Approve a schedule
     */
    public function approve(Order $schedule)
    {
        try {
            // Check if order is in pending status
            if ($schedule->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending orders can be approved'
                ], 400);
            }

            $schedule->update([
                'status' => 'confirmed',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'staff_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule approved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to approve schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reject a schedule
     */
    public function reject(Request $request, Order $schedule)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        try {
            // Check if order is in pending status
            if ($schedule->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending orders can be rejected'
                ], 400);
            }

            $schedule->update([
                'status' => 'cancelled',
                'rejection_reason' => $request->rejection_reason,
                'approved_by' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule rejected successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reject schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update schedule status
     */
    public function updateStatus(Request $request, Order $schedule)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,ready_for_pickup,completed,cancelled'
        ]);

        try {
            $schedule->update([
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Set pricing for a schedule
     */
    public function setPricing(Request $request, Order $schedule)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0.1',
            'price' => 'required|numeric|min:0.01'
        ]);

        try {
            $schedule->update([
                'weight' => $request->weight,
                'total_price' => $request->price,
                'status' => 'confirmed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pricing updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update pricing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel a schedule
     */
    public function cancel(Order $schedule)
    {
        try {
            $schedule->update([
                'status' => 'cancelled'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule cancelled successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Start processing a schedule
     */
    public function startProcessing(Order $schedule)
    {
        try {
            // Check if order has pricing set
            if (!$schedule->total_price) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please set pricing before starting processing'
                ], 400);
            }

            $schedule->update([
                'status' => 'processing',
                'staff_id' => Auth::id()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Processing started successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to start processing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark schedule as ready for pickup
     */
    public function markReadyForPickup(Order $schedule)
    {
        try {
            $schedule->update([
                'status' => 'ready_for_pickup'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule marked as ready for pickup'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as ready: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark schedule as completed
     */
    public function markCompleted(Order $schedule)
    {
        try {
            $schedule->update([
                'status' => 'completed'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Schedule marked as completed'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to mark as completed: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get display text for order status
     */
    private function getStatusDisplay($status)
    {
        $statusMap = [
            'pending' => 'Pending Approval',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get available actions based on order status
     * This defines the complete workflow for staff
     * 
     * @param string $status The order status
     * @param Order|null $order The order instance (needed for conditional logic)
     * @return array
     */
    private function getActionsForStatus($status, $order = null)
    {
        // View is always available
        $actions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'eye', 'color' => 'blue']
        ];

        switch ($status) {
            case 'pending':
                // Staff can approve or reject pending orders
                $actions[] = ['key' => 'approve', 'label' => 'Approve', 'icon' => 'check', 'color' => 'green'];
                $actions[] = ['key' => 'reject', 'label' => 'Reject', 'icon' => 'times', 'color' => 'red'];
                break;

            case 'confirmed':
                // Staff can add pricing (only if not already set)
                if (!$order || !$order->weight || !$order->total_price || $order->total_price <= 0) {
                    $actions[] = ['key' => 'add_price', 'label' => 'Set Price', 'icon' => 'edit', 'color' => 'blue'];
                }
                
                // Start Processing button only appears if BOTH weight AND price are set
                if ($order && $order->weight && $order->total_price && $order->total_price > 0) {
                    $actions[] = ['key' => 'start_processing', 'label' => 'Start Processing', 'icon' => 'toggle', 'color' => 'green'];
                }
                
                $actions[] = ['key' => 'cancel', 'label' => 'Cancel', 'icon' => 'times', 'color' => 'red'];
                break;

            case 'processing':
                // Staff can mark as ready for pickup (no pricing changes allowed)
                $actions[] = ['key' => 'mark_ready', 'label' => 'Mark Ready', 'icon' => 'check', 'color' => 'green'];
                break;

            case 'ready_for_pickup':
                // Staff can mark as completed when customer picks up
                $actions[] = ['key' => 'mark_completed', 'label' => 'Mark Completed', 'icon' => 'check', 'color' => 'green'];
                break;

            case 'completed':
            case 'cancelled':
                // No actions available for completed/cancelled orders (view only)
                break;
        }

        return $actions;
    }
}

