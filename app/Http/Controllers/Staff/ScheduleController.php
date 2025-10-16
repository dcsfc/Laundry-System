<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Traits\ScheduleDataFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    use ScheduleDataFormatter;
    /**
     * Display pending schedules that need staff approval
     */
    public function index(Request $request)
    {
        // Get active schedules (exclude completed and cancelled by default)
        $allSchedules = Order::with(['customer', 'service', 'approvedBy'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                // Compose date strings (without time)
                $dropoff = $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A';
                $pickup = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A';

                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'customer_phone' => $order->customer->phone_number ?? 'N/A',
                    'dropoff' => $dropoff ?: 'N/A',
                    'pickup' => $pickup ?: 'N/A',
                    'status' => $order->status, // Use consistent status field
                    'status_display' => $this->formatStatusDisplay($order->status),
                    'weight' => $order->weight,
                    'price' => $order->total_price !== null ? number_format((float)$order->total_price, 2) : null,
                    'updated_at' => $order->updated_at ? $order->updated_at->format('M j, Y g:i A') : null,
                    'notes' => $order->notes ?? '',
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'actions' => $this->getActionsForStatus($order->status, $order),
                ];
            })->toArray();

        // Get pending schedules count for statistics
        $pendingCount = Order::where('status', 'pending')->count();

        // Define columns for all schedules table
        $scheduleColumns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_phone', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'dropoff', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'status_display', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'weight', 'label' => 'Weight', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        // Define default actions (will be overridden by dynamic actions per row)
        $scheduleActions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'fas fa-eye'],
        ];

        // If this is the fetch endpoint or an AJAX/JSON request, return JSON
        if ($request->expectsJson() || $request->isMethod('post')) {
            return response()->json([
                'success' => true,
                'schedules' => $allSchedules,
            ]);
        }

        return view('staff.schedules.index', compact(
            'allSchedules',
            'scheduleColumns',
            'scheduleActions',
            'pendingCount'
        ));
    }

    /**
     * Approve a schedule
     */
    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Check if order is still pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule has already been processed.'
            ], 400);
        }

        // Update status to confirmed when approved
        $order->update([
            'status' => 'confirmed',
            'staff_id' => Auth::id(), // Assign to current staff member
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule approved successfully! Customer has been notified.',
            'order' => $order
        ]);
    }

    /**
     * Reject a schedule
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $order = Order::findOrFail($id);
        
        // Check if order is still pending
        if ($order->status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule has already been processed.'
            ], 400);
        }

        // Update status to cancelled when rejected
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->rejection_reason,
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule rejected. Customer has been notified with the reason.',
            'order' => $order
        ]);
    }

    /**
     * View schedule details
     */
    public function show($id)
    {
        $order = Order::with(['customer', 'service', 'approvedBy'])
            ->findOrFail($id);

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'customer_name' => $order->customer->name ?? 'N/A',
                'customer_email' => $order->customer->email ?? 'N/A',
                'customer_phone' => $order->customer->phone_number ?? 'N/A',
                'service_name' => $order->service->name ?? 'General Laundry',
                'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                'dropoff_time' => $order->dropoff_time ?? 'N/A',
                'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                'pickup_time' => $order->pickup_time ?? 'N/A',
                'approval_status' => ucfirst($order->approval_status),
                'status' => ucfirst(str_replace('_', ' ', $order->status)),
                'notes' => $order->notes ?? '',
                'rejection_reason' => $order->rejection_reason ?? '',
                'created_at' => $order->created_at->format('M j, Y g:i A'),
                'approved_at' => $order->approved_at ? Carbon::parse($order->approved_at)->format('M j, Y g:i A') : null,
                'approved_by_name' => $order->approvedBy->name ?? null,
            ]
        ]);
    }

    /**
     * Update schedule status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,ready_for_pickup,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        
        // Check if the status transition is valid
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['ready_for_pickup', 'cancelled'],
            'ready_for_pickup' => ['completed', 'cancelled'],
            'completed' => [], // No transitions from completed
            'cancelled' => [] // No transitions from cancelled
        ];

        if (!in_array($request->status, $validTransitions[$order->status] ?? [])) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status transition from ' . $order->status . ' to ' . $request->status
            ], 400);
        }

        // Update the order status
        $order->update([
            'status' => $request->status,
            'updated_at' => now()
        ]);

        // If confirming, also update approval status
        if ($request->status === 'confirmed') {
            $order->update([
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'staff_id' => Auth::id()
            ]);
        }

        // If cancelling, also update approval status
        if ($request->status === 'cancelled') {
            $order->update([
                'approval_status' => 'rejected', // Mark as rejected since it's no longer pending approval
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Schedule status updated successfully to ' . ucfirst(str_replace('_', ' ', $request->status)),
            'order' => $order
        ]);
    }

    /**
     * Set weight/price and stay in confirmed status
     */
    public function setPricing(Request $request, $id)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $order = Order::findOrFail($id);

        if ($order->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Pricing can only be set when the schedule is Confirmed.'
            ], 400);
        }

        $order->update([
            'weight' => $request->weight,
            'total_price' => $request->price,
            'status' => 'confirmed', // STAY in confirmed, don't auto-move to processing
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Weight and price saved successfully.',
            'order' => $order
        ]);
    }

    /**
     * Cancel a schedule (for approved and beyond)
     */
    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);
        
        $order = Order::findOrFail($id);
        
        // Can only cancel if approved or beyond
        if ($order->status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Use Reject for pending schedules.'
            ], 400);
        }
        
        if (in_array($order->status, ['completed', 'cancelled'])) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot cancel a completed or already cancelled schedule.'
            ], 400);
        }
        
        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule cancelled successfully.',
            'order' => $order
        ]);
    }

    /**
     * Start processing a confirmed schedule
     */
    public function startProcessing(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'confirmed') {
            return response()->json([
                'success' => false,
                'message' => 'Can only start processing on confirmed schedules.'
            ], 400);
        }
        
        if (!$order->weight || !$order->total_price) {
            return response()->json([
                'success' => false,
                'message' => 'Please set weight and price before processing.'
            ], 400);
        }
        
        $order->update([
            'status' => 'processing',
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule moved to processing.',
            'order' => $order
        ]);
    }

    /**
     * Mark schedule as ready for pickup
     */
    public function markReadyForPickup(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'processing') {
            return response()->json([
                'success' => false,
                'message' => 'Can only mark as ready from processing status.'
            ], 400);
        }
        
        $order->update([
            'status' => 'ready_for_pickup',
            'updated_at' => now()
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule marked as ready for pickup.',
            'order' => $order
        ]);
    }

    /**
     * Mark schedule as completed
     */
    public function markCompleted(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        if ($order->status !== 'ready_for_pickup') {
            return response()->json([
                'success' => false,
                'message' => 'Can only complete orders that are ready for pickup.'
            ], 400);
        }
        
        $order->update([
            'status' => 'completed',
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule marked as completed.',
            'order' => $order
        ]);
    }

    /**
     * Get actions for a specific status
     */
    private function getActionsForStatus($status, $order = null)
    {
        // Status-based actions using consistent status values
        switch ($status) {
            case 'pending':
                return [
                    ['key' => 'approve', 'label' => 'Approve', 'icon' => 'fas fa-check', 'color' => 'green'],
                    ['key' => 'reject', 'label' => 'Reject', 'icon' => 'fas fa-times', 'color' => 'red'],
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
                
            case 'confirmed':
                // Determine if price/weight is already set
                $hasPricing = $order && $order->weight && $order->total_price;
                
                if ($hasPricing) {
                    // If price/weight is set, show: Start Processing, Cancel, View
                    return [
                        ['key' => 'start_processing', 'label' => 'Start Processing', 'icon' => 'fas fa-play', 'color' => 'green'],
                        ['key' => 'cancel', 'label' => 'Cancel', 'icon' => 'fas fa-ban', 'color' => 'red'],
                        ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                    ];
                } else {
                    // If no price/weight set, show: Add Price/Weight, Cancel, View
                    return [
                        ['key' => 'add_price', 'label' => 'Add Price/Weight', 'icon' => 'fas fa-balance-scale', 'color' => 'blue'],
                        ['key' => 'cancel', 'label' => 'Cancel', 'icon' => 'fas fa-ban', 'color' => 'red'],
                        ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                    ];
                }
                
            case 'processing':
                return [
                    ['key' => 'mark_ready', 'label' => 'Ready for Pickup', 'icon' => 'fas fa-box', 'color' => 'green'],
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
                
            case 'ready_for_pickup':
                return [
                    ['key' => 'mark_completed', 'label' => 'Mark Completed', 'icon' => 'fas fa-check-circle', 'color' => 'green'],
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
                
            case 'completed':
            case 'cancelled':
                return [
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
                
            default:
                return [
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
        }
    }

    /**
     * Format status for display
     */
    private function formatStatusDisplay($status)
    {
        $statusMap = [
            'pending' => 'Pending',
            'confirmed' => 'Approved',
            'processing' => 'Processing',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];
        
        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Get all schedules (including completed and cancelled) for filtering
     */
    public function getAllSchedules(Request $request)
    {
        $allSchedules = Order::with(['customer', 'service', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                // Compose date strings (without time)
                $dropoff = $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A';
                $pickup = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A';

                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'customer_phone' => $order->customer->phone_number ?? 'N/A',
                    'dropoff' => $dropoff ?: 'N/A',
                    'pickup' => $pickup ?: 'N/A',
                    'status' => $order->status,
                    'status_display' => $this->formatStatusDisplay($order->status),
                    'weight' => $order->weight,
                    'price' => $order->total_price !== null ? number_format((float)$order->total_price, 2) : null,
                    'updated_at' => $order->updated_at ? $order->updated_at->format('M j, Y g:i A') : null,
                    'notes' => $order->notes ?? '',
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                    'actions' => $this->getActionsForStatus($order->status, $order),
                ];
            })->toArray();

        return response()->json([
            'success' => true,
            'schedules' => $allSchedules,
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'pending_count' => Order::where('status', 'pending')->count(),
            'approved_today' => Order::where('status', 'confirmed')
                ->whereDate('updated_at', today())
                ->count(),
            'rejected_today' => Order::where('status', 'cancelled')
                ->whereDate('updated_at', today())
                ->count(),
            'total_processed_today' => Order::whereIn('status', ['processing', 'ready_for_pickup', 'completed'])
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
