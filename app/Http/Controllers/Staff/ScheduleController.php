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
        // Get all schedules (orders) for staff management
        $pendingSchedules = Order::with(['customer', 'service', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($order) {
                // Compose friendly datetime strings
                $dropoff = null;
                if ($order->dropoff_date || $order->dropoff_time) {
                    $dateStr = $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : '';
                    $timeRaw = $order->dropoff_time;
                    $timeStr = '';
                    if (!empty($timeRaw)) {
                        try {
                            $timeStr = Carbon::parse($timeRaw)->format('g:i A');
                        } catch (\Exception $e) {
                            $timeStr = (string) $timeRaw; // fallback
                        }
                    }
                    $dropoff = trim($dateStr . (strlen($timeStr) ? ' • ' . $timeStr : '')) ?: 'N/A';
                }

                $pickup = null;
                if ($order->pickup_date || $order->pickup_time) {
                    $dateStr = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : '';
                    $timeRaw = $order->pickup_time;
                    $timeStr = '';
                    if (!empty($timeRaw)) {
                        try {
                            $timeStr = Carbon::parse($timeRaw)->format('g:i A');
                        } catch (\Exception $e) {
                            $timeStr = (string) $timeRaw; // fallback
                        }
                    }
                    $pickup = trim($dateStr . (strlen($timeStr) ? ' • ' . $timeStr : '')) ?: 'N/A';
                }

                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_email' => $order->customer->email ?? 'N/A',
                    'customer_phone' => $order->customer->phone_number ?? 'N/A',
                    'dropoff' => $dropoff ?: 'N/A',
                    'pickup' => $pickup ?: 'N/A',
                    'approval_status' => ucfirst($order->approval_status),
                    // Normalize status for robust client-side filtering
                    'status' => strtolower(trim((string) $order->status)),
                    'status_display' => ucfirst(str_replace('_', ' ', (string) $order->status)),
                    'weight' => $order->getAttribute('weight'),
                    'price' => $order->total_price !== null ? number_format((float)$order->total_price, 2) : null,
                    'approved_by' => optional($order->approvedBy)->name,
                    'approved_at' => $order->approved_at ?? null,
                    'updated_at' => $order->updated_at ? $order->updated_at->format('M j, Y g:i A') : null,
                    'notes' => $order->notes ?? '',
                    'created_at' => $order->created_at->format('M j, Y g:i A'),
                ];
            });

        // Get approved schedules for reference
        $approvedSchedules = Order::with(['customer', 'service'])
            ->where('approval_status', 'approved')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'service_name' => $order->service->name ?? 'General Laundry',
                    'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : 'N/A',
                    'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : 'N/A',
                    'status' => ucfirst(str_replace('_', ' ', $order->status)),
                    'approved_at' => $order->approved_at ? Carbon::parse($order->approved_at)->format('M j, Y g:i A') : 'N/A',
                ];
            });

        // Define columns for schedules table in requested layout
        $pendingColumns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_phone', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'dropoff', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'status_display', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'weight', 'label' => 'Weight', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'approved_by', 'label' => 'Approved By', 'sortable' => true],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        // Define actions for pending schedules
        $pendingActions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'fas fa-eye'],
            ['key' => 'approve', 'label' => 'Approve', 'icon' => 'fas fa-check'],
            ['key' => 'reject', 'label' => 'Reject', 'icon' => 'fas fa-times'],
        ];

        // If this is the fetch endpoint or an AJAX/JSON request, return JSON
        if ($request->expectsJson() || $request->isMethod('post')) {
            return response()->json([
                'success' => true,
                'schedules' => $pendingSchedules,
            ]);
        }

        return view('staff.schedules.index', compact(
            'pendingSchedules',
            'approvedSchedules',
            'pendingColumns',
            'pendingActions'
        ));
    }

    /**
     * Approve a schedule
     */
    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        // Check if order is still pending
        if ($order->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule has already been processed.'
            ], 400);
        }

        // Update approval status and order status
        $order->update([
            'approval_status' => 'approved',
            'status' => 'confirmed', // Set order status to confirmed when approved
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'staff_id' => Auth::id(), // Assign to current staff member
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
        if ($order->approval_status !== 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'This schedule has already been processed.'
            ], 400);
        }

        // Update approval status
        $order->update([
            'approval_status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
            'rejection_reason' => $request->rejection_reason,
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

        return response()->json([
            'success' => true,
            'message' => 'Schedule status updated successfully to ' . ucfirst(str_replace('_', ' ', $request->status)),
            'order' => $order
        ]);
    }

    /**
     * Set weight/price and move to processing
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
            'status' => 'in_progress', // Changed from 'processing' to 'in_progress' to match status mapping
            'updated_at' => now()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Weight and price saved. Schedule moved to Processing.',
            'order' => $order
        ]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'pending_count' => Order::where('approval_status', 'pending')->count(),
            'approved_today' => Order::where('approval_status', 'approved')
                ->whereDate('approved_at', today())
                ->count(),
            'rejected_today' => Order::where('approval_status', 'rejected')
                ->whereDate('approved_at', today())
                ->count(),
            'total_processed_today' => Order::whereIn('approval_status', ['approved', 'rejected'])
                ->whereDate('approved_at', today())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }
}
