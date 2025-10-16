<?php

namespace App\Http\Controllers\Customer;

use App\Models\Order;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class ScheduleController extends Controller
{
    /**
     * Display customer's schedules
     */
    public function index()
    {
        $currentUser = Auth::user();
        
        // Get only active schedules (exclude completed/cancelled)
        $schedules = Order::where('customer_id', $currentUser->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['service', 'staff', 'customer'])
            ->orderBy('dropoff_date', 'desc')
            ->paginate(10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_name' => $order->customer->name ?? 'N/A',
                    'customer_phone' => $order->customer->phone_number ?? 'N/A',
                    'service_type' => $order->service->name ?? 'General Laundry',
                    'dropoff_date' => $order->dropoff_date,
                    'dropoff_time' => $order->dropoff_time,
                    'pickup_date' => $order->pickup_date,
                    'pickup_time' => $order->pickup_time,
                    'weight' => $order->weight,
                    'total_price' => $order->total_price,
                    'status' => $this->getStatusDisplay($order->status),
                    'staff_assigned' => $order->staff->name ?? 'Unassigned',
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                ];
            });

        // Get all schedules including completed/cancelled for history
        $allSchedules = Order::where('customer_id', $currentUser->id)
            ->with(['service', 'staff', 'customer'])
            ->orderBy('dropoff_date', 'desc')
            ->paginate(10)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $this->getStatusDisplay($order->status),
                ];
            });

        $services = Service::where('is_active', true)->get();
        
        return view('customer.schedules.index', compact('schedules', 'services', 'allSchedules'));
    }

    /**
     * Create a new schedule
     */
    public function store(Request $request)
    {
        $currentUser = Auth::user();
        
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'dropoff_date' => 'required|date|after_or_equal:today',
            'pickup_date' => 'required|date|after:dropoff_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create new order/schedule with selected service
        $order = Order::create([
            'customer_id' => $currentUser->id,
            'service_id' => $request->service_id,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => '09:00:00', // Default drop-off time
            'pickup_date' => $request->pickup_date,
            'pickup_time' => '17:00:00', // Default pickup time
            'status' => 'pending', // Use consistent status system
            'notes' => $request->notes,
            'created_by' => $currentUser->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully! Your request is pending staff approval. We will contact you once confirmed.',
            'order_id' => $order->id
        ]);
    }

    /**
     * Show customer's specific schedule details
     */
    public function show($id)
    {
        $currentUser = Auth::user();
        
        // Sample schedule data - in real app, verify ownership: Schedule::where('id', $id)->where('customer_id', $currentUser->id)->firstOrFail()
        $schedule = [
            'id' => $id,
            'customer_id' => $currentUser->id,
            'customer_name' => 'Maria Santos',
            'customer_phone' => '+639123456789',
            'service_type' => 'Wash & Fold',
            'dropoff_date' => '2024-01-15',
            'dropoff_time' => '09:00 AM',
            'pickup_date' => '2024-01-17',
            'pickup_time' => '05:00 PM',
            'status' => 'Confirmed',
            'staff_assigned' => 'Sarah Wilson',
            'notes' => 'Regular customer, prefer gentle cycle',
            'created_at' => '2024-01-14 10:30:00',
            'estimated_completion' => '2024-01-17 05:00 PM',
            'special_instructions' => 'Handle delicate items with care'
        ];

        // Verify the schedule belongs to the current customer
        if ($schedule['customer_id'] != $currentUser->id) {
            abort(403, 'Unauthorized access to this schedule.');
        }

        return view('customer.schedules.show', compact('schedule'));
    }

    /**
     * Update a customer schedule
     */
    public function update($id, Request $request)
    {
        $currentUser = Auth::user();
        
        // Find the order and verify ownership
        $order = Order::where('id', $id)
            ->where('customer_id', $currentUser->id)
            ->first();
            
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found or you do not have permission to update it.'
            ], 404);
        }
        
        // Check if the order can be updated (only pending or scheduled orders)
        if (!in_array($order->status, ['pending', 'scheduled'])) {
            return response()->json([
                'success' => false,
                'message' => 'This schedule cannot be updated. Only pending or scheduled orders can be modified.'
            ], 400);
        }
        
        // Validate the request
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'dropoff_date' => 'required|date|after_or_equal:today',
            'pickup_date' => 'required|date|after:dropoff_date',
        ]);
        
        // Update the order
        $order->update([
            'service_id' => $request->service_id,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => '09:00:00', // Default drop-off time
            'pickup_date' => $request->pickup_date,
            'pickup_time' => '17:00:00', // Default pickup time
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully.'
        ]);
    }

    /**
     * Cancel a customer schedule
     */
    public function cancel($id, Request $request)
    {
        $currentUser = Auth::user();
        
        // Find the order and verify ownership
        $order = Order::where('id', $id)
            ->where('customer_id', $currentUser->id)
            ->first();
            
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Schedule not found or you do not have permission to cancel it.'
            ], 404);
        }
        
        // Check if the order can be cancelled (only pending or scheduled orders)
        if (!in_array($order->status, ['pending', 'scheduled'])) {
            return response()->json([
                'success' => false,
                'message' => 'This schedule cannot be cancelled. Only pending or scheduled orders can be cancelled.'
            ], 400);
        }
        
        // Get cancellation reason from request
        $cancellationReason = $request->input('cancellation_reason');
        
        // Update the order status to cancelled and store the reason
        $order->update([
            'status' => 'cancelled',
            'approval_status' => 'rejected', // Mark as rejected since it's no longer pending approval
            'notes' => $cancellationReason ? "Cancelled by customer: " . $cancellationReason : "Cancelled by customer"
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule cancelled successfully.'
        ]);
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

