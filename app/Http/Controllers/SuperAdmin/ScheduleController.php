<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
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
            ['label' => 'View', 'onclick' => 'viewSchedule'],
            ['label' => 'Edit', 'onclick' => 'editSchedule'],
            ['label' => 'Update Status', 'onclick' => 'updateScheduleStatus'],
            ['label' => 'Delete', 'onclick' => 'deleteSchedule']
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
        
        // Sample schedules data filtered for the current customer
        // In a real application, you would query: Schedule::where('customer_id', $currentUser->id)->get()
        $allSchedules = collect([
            [
                'id' => 1,
                'customer_id' => 1, // This would be the current user's ID
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
                'created_at' => '2024-01-14 10:30:00'
            ],
            [
                'id' => 2,
                'customer_id' => 2, // Different customer
                'customer_name' => 'Jose Garcia',
                'customer_phone' => '+639123456790',
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
                'customer_id' => 1, // This would be the current user's ID
                'customer_name' => 'Maria Santos',
                'customer_phone' => '+639123456789',
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
                'customer_id' => 3, // Different customer
                'customer_name' => 'Ana Dela Cruz',
                'customer_phone' => '+639123456791',
                'service_type' => 'Wash & Fold',
                'dropoff_date' => '2024-01-18',
                'dropoff_time' => '11:00 AM',
                'pickup_date' => '2024-01-20',
                'pickup_time' => '03:00 PM',
                'status' => 'Cancelled',
                'staff_assigned' => 'Mike Johnson',
                'notes' => 'Customer requested cancellation',
                'created_at' => '2024-01-17 16:45:00'
            ],
            [
                'id' => 5,
                'customer_id' => 1, // This would be the current user's ID
                'customer_name' => 'Maria Santos',
                'customer_phone' => '+639123456789',
                'service_type' => 'Dry Clean',
                'dropoff_date' => '2024-01-20',
                'dropoff_time' => '02:00 PM',
                'pickup_date' => '2024-01-22',
                'pickup_time' => '04:00 PM',
                'status' => 'Scheduled',
                'staff_assigned' => 'Mike Johnson',
                'notes' => 'Delicate items, cold water only',
                'created_at' => '2024-01-19 11:30:00'
            ]
        ]);

        // Filter schedules for the current customer only
        // In a real application, this would be: Schedule::where('customer_id', $currentUser->id)->get()
        $schedules = $allSchedules->filter(function ($schedule) use ($currentUser) {
            return $schedule['customer_id'] == $currentUser->id;
        })->values();

        return view('customer.schedules.index', compact('schedules'));
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
