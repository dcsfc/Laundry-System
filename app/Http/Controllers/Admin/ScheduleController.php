<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.schedules.index', compact('schedules', 'columns', 'actions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.schedules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storage logic will go here
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('admin.schedules.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.schedules.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic will go here
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete logic will go here
        return redirect()->route('admin.schedules.index')->with('success', 'Schedule deleted successfully');
    }
}
