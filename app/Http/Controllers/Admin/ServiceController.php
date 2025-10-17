<?php

namespace App\Http\Controllers\Admin;

use App\Models\Service;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServiceController extends Controller
{
    /**
     * Display a listing of services
     */
    public function index()
    {
        $services = Service::orderBy('name')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => $service->name,
                    'description' => $service->description ?? 'No description',
                    'price' => $service->price ? number_format((float)$service->price, 2) : 'N/A',
                    'status' => $service->is_active ? 'Active' : 'Inactive',
                    'status_color' => $service->is_active ? 'green' : 'red',
                    'created_at' => $service->created_at->format('M j, Y'),
                    'updated_at' => $service->updated_at->format('M j, Y g:i A'),
                ];
            })->toArray();

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Service Name', 'sortable' => true, 'searchable' => true],
            ['key' => 'description', 'label' => 'Description', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'view', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'edit', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'toggle_status', 'label' => 'Toggle Status', 'icon' => 'toggle', 'color' => 'green'],
            ['key' => 'delete', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red'],
        ];

        $description = 'Manage laundry services, pricing, and availability';

        return view('admin.services.index', compact('services', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new service
     */
    public function create()
    {
        return view('admin.services.create');
    }

    /**
     * Store a newly created service
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        Service::create($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service created successfully.');
    }

    /**
     * Display the specified service
     */
    public function show(Service $service)
    {
        return view('admin.services.show', compact('service'));
    }

    /**
     * Show the form for editing the specified service
     */
    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    /**
     * Update the specified service
     */
    public function update(Request $request, Service $service)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $service->update($validated);

        return redirect()->route('admin.services.index')
            ->with('success', 'Service updated successfully.');
    }

    /**
     * Remove the specified service
     */
    public function destroy(Service $service)
    {
        $service->delete();

        return redirect()->route('admin.services.index')
            ->with('success', 'Service deleted successfully.');
    }

    /**
     * Toggle service active status
     */
    public function toggleStatus(Service $service)
    {
        $service->update(['is_active' => !$service->is_active]);

        return response()->json([
            'success' => true,
            'message' => 'Service status updated successfully.',
            'is_active' => $service->is_active
        ]);
    }
}


