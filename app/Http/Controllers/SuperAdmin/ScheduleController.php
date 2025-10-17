<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Traits\ScheduleDataFormatter;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    use ScheduleDataFormatter;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sample schedules data for the reusable data table component
        $schedules = collect([
            [
                'id' => 1,
                'customer_name' => 'John Doe',
                'customer_phone' => '+1234567890',
                'service_type' => 'Wash & Fold',
                'dropoff_date' => '2024-01-15',
                'dropoff_time' => '09:00 AM',
                'pickup_date' => '2024-01-17',
                'pickup_time' => '05:00 PM',
                'status' => 'Confirmed',
                'staff_assigned' => 'Sarah Wilson',
                'notes' => 'Regular customer, prefer gentle cycle',
                'created_at' => '2024-01-14 10:30:00'
            ],
            [
                'id' => 2,
                'customer_name' => 'Jane Smith',
                'customer_phone' => '+1234567891',
                'service_type' => 'Dry Clean',
                'dropoff_date' => '2024-01-16',
                'dropoff_time' => '10:30 AM',
                'pickup_date' => '2024-01-18',
                'pickup_time' => '04:30 PM',
                'status' => 'Pending',
                'staff_assigned' => 'Mike Johnson',
                'notes' => 'Wedding dress, handle with care',
                'created_at' => '2024-01-15 14:20:00'
            ],
            [
                'id' => 3,
                'customer_name' => 'Mike Johnson',
                'customer_phone' => '+1234567892',
                'service_type' => 'Wash & Iron',
                'dropoff_date' => '2024-01-17',
                'dropoff_time' => '08:00 AM',
                'pickup_date' => '2024-01-19',
                'pickup_time' => '06:00 PM',
                'status' => 'Confirmed',
                'staff_assigned' => 'Sarah Wilson',
                'notes' => 'Business shirts, starch preferred',
                'created_at' => '2024-01-16 09:15:00'
            ],
            [
                'id' => 4,
                'customer_name' => 'Sarah Wilson',
                'customer_phone' => '+1234567893',
                'service_type' => 'Wash & Fold',
                'dropoff_date' => '2024-01-18',
                'dropoff_time' => '11:00 AM',
                'pickup_date' => '2024-01-20',
                'pickup_time' => '03:00 PM',
                'status' => 'Cancelled',
                'staff_assigned' => 'Mike Johnson',
                'notes' => 'Customer requested cancellation',
                'created_at' => '2024-01-17 16:45:00'
            ]
        ]);

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_phone', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'service_type', 'label' => 'Service', 'sortable' => true],
            ['key' => 'dropoff_date', 'label' => 'Drop-off Date', 'sortable' => true],
            ['key' => 'dropoff_time', 'label' => 'Drop-off Time', 'sortable' => true],
            ['key' => 'pickup_date', 'label' => 'Pickup Date', 'sortable' => true],
            ['key' => 'pickup_time', 'label' => 'Pickup Time', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'staff_assigned', 'label' => 'Staff', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'viewSchedule', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'editSchedule', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'updateScheduleStatus', 'label' => 'Update Status', 'icon' => 'toggle', 'color' => 'green'],
            ['key' => 'deleteSchedule', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red']
        ];

        return view('superadmin.schedules.index', compact('schedules', 'columns', 'actions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.schedules.create');
    }

    /**
     * Create a new schedule for customer (from modal)
     */
    public function customerScheduleCreate(\Illuminate\Http\Request $request)
    {
        $currentUser = auth()->user();
        
        $request->validate([
            'service_id' => 'required|exists:services,id',
            'dropoff_date' => 'required|date|after_or_equal:today',
            'dropoff_time' => 'required',
            'pickup_date' => 'required|date|after:dropoff_date',
            'pickup_time' => 'required',
            'notes' => 'nullable|string|max:1000',
        ]);

        // Create new order/schedule with selected service
        $order = \App\Models\Order::create([
            'customer_id' => $currentUser->id,
            'service_id' => $request->service_id,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
            'status' => 'scheduled', // Order status
            'approval_status' => 'pending', // Approval workflow: pending -> approved/rejected
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storage logic will go here
        return redirect()->route('superadmin.schedules.index')->with('success', 'Schedule created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('superadmin.schedules.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('superadmin.schedules.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic will go here
        return redirect()->route('superadmin.schedules.index')->with('success', 'Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete logic will go here
        return redirect()->route('superadmin.schedules.index')->with('success', 'Schedule deleted successfully');
    }

    /**
     * Display customer's schedules (filtered by customer_id)
     */
    public function customerSchedules()
    {
        $currentUser = auth()->user();
        
        // Get active schedules data from orders table (exclude cancelled and completed)
        $schedules = \App\Models\Order::where('customer_id', $currentUser->id)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->with(['service', 'staff', 'customer', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->paginate(5)
            ->through(function ($order) {
                return [
                    'id' => $order->id,
                    'customer_id' => $order->customer_id,
                    'customer_name' => trim(str_replace('User', '', $order->customer->name ?? $currentUser->name ?? 'Unknown')),
                    'customer_phone' => $order->customer->phone_number ?? $currentUser->phone_number ?? 'N/A',
                    'service_type' => $order->service->name ?? 'Laundry Service',
                    'dropoff_date' => $order->dropoff_date ? \Carbon\Carbon::parse($order->dropoff_date)->format('Y-m-d') : 'N/A',
                    'dropoff_time' => $order->dropoff_time ? \Carbon\Carbon::parse($order->dropoff_time)->format('g:i A') : '09:00 AM',
                    'pickup_date' => $order->pickup_date ? \Carbon\Carbon::parse($order->pickup_date)->format('Y-m-d') : 'N/A',
                    'pickup_time' => $order->pickup_time ? \Carbon\Carbon::parse($order->pickup_time)->format('g:i A') : '05:00 PM',
                    'status' => $this->getStatusDisplay($order->status, $order->approval_status),
                    'approval_status' => $order->approval_status,
                    'staff_assigned' => $order->staff->name ?? 'Unassigned',
                    'approved_by_name' => $order->approvedBy->name ?? null,
                    'approved_at' => $order->approved_at ?? null,
                    'notes' => $order->notes ?? '',
                    'weight' => $order->weight ?? null, // Add weight field
                    'total_price' => $order->total_price ?? null, // Add price field
                    'created_at' => $order->created_at->format('Y-m-d H:i:s')
                ];
            });

        // Get total counts for metrics cards (not paginated)
        $allSchedules = \App\Models\Order::where('customer_id', $currentUser->id)
            ->whereNotIn('status', ['cancelled']) // Only exclude cancelled, include completed
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'status' => $this->getStatusDisplay($order->status, $order->approval_status),
                    'approval_status' => $order->approval_status
                ];
            });

        $services = \App\Models\Service::where('is_active', true)->get();
        
        return view('customer.schedules.index', compact('schedules', 'services', 'allSchedules'));
    }

    /**
     * Get display text for order status based on approval workflow
     */
    private function getStatusDisplay($status, $approvalStatus = null)
    {
        // If approval status is rejected, show rejected
        if ($approvalStatus === 'rejected') {
            return 'Rejected';
        }
        
        // If approval status is pending, show "Pending Approval" (regardless of order status)
        if ($approvalStatus === 'pending') {
            return 'Pending Approval';
        }
        
        // For approved orders, show the actual order status
        $statusMap = [
            'pending' => 'Pending',
            'scheduled' => 'Confirmed', 
            'priced' => 'Confirmed',
            'in_progress' => 'In Progress',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'canceled' => 'Cancelled',
            // Legacy statuses for backward compatibility
            'approved' => 'Confirmed',
            'processing' => 'In Progress',
            'confirmed' => 'Confirmed'
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }

    /**
     * Cancel a customer schedule
     */
    public function customerScheduleCancel($id, Request $request)
    {
        $currentUser = auth()->user();
        
        // Find the order and verify ownership
        $order = \App\Models\Order::where('id', $id)
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
     * Update a customer schedule
     */
    public function customerScheduleUpdate($id, \Illuminate\Http\Request $request)
    {
        $currentUser = auth()->user();
        
        // Find the order and verify ownership
        $order = \App\Models\Order::where('id', $id)
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
            'dropoff_time' => 'required',
            'pickup_date' => 'required|date|after:dropoff_date',
            'pickup_time' => 'required',
        ]);
        
        // Update the order
        $order->update([
            'service_id' => $request->service_id,
            'dropoff_date' => $request->dropoff_date,
            'dropoff_time' => $request->dropoff_time,
            'pickup_date' => $request->pickup_date,
            'pickup_time' => $request->pickup_time,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully.'
        ]);
    }

    /**
     * Show customer's specific schedule details
     */
    public function customerScheduleShow($id)
    {
        $currentUser = auth()->user();
        
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
}
