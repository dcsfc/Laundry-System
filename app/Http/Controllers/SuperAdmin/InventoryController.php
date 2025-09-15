<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Sample inventory data for the reusable data table component
        $inventory = collect([
            [
                'id' => 1,
                'item_name' => 'Ariel Powder Detergent',
                'quantity' => 25,
                'unit' => 'kilos',
                'threshold' => 5,
                'status' => 'In Stock',
                'last_updated' => '2024-01-15 10:30:00'
            ],
            [
                'id' => 2,
                'item_name' => 'Downy Fabric Softener',
                'quantity' => 15,
                'unit' => 'bottles',
                'threshold' => 3,
                'status' => 'In Stock',
                'last_updated' => '2024-01-14 14:20:00'
            ],
            [
                'id' => 3,
                'item_name' => 'Tide Liquid Detergent',
                'quantity' => 8,
                'unit' => 'bottles',
                'threshold' => 5,
                'status' => 'Low Stock',
                'last_updated' => '2024-01-13 09:15:00'
            ],
            [
                'id' => 4,
                'item_name' => 'Breeze Powder Detergent',
                'quantity' => 0,
                'unit' => 'kilos',
                'threshold' => 10,
                'status' => 'Out of Stock',
                'last_updated' => '2024-01-12 16:45:00'
            ],
            [
                'id' => 5,
                'item_name' => 'Surf Powder Detergent',
                'quantity' => 12,
                'unit' => 'kilos',
                'threshold' => 4,
                'status' => 'In Stock',
                'last_updated' => '2024-01-11 11:20:00'
            ],
            [
                'id' => 6,
                'item_name' => 'Comfort Fabric Softener',
                'quantity' => 6,
                'unit' => 'bottles',
                'threshold' => 3,
                'status' => 'Low Stock',
                'last_updated' => '2024-01-10 15:30:00'
            ],
            [
                'id' => 7,
                'item_name' => 'Zonrox Bleach',
                'quantity' => 20,
                'unit' => 'bottles',
                'threshold' => 5,
                'status' => 'In Stock',
                'last_updated' => '2024-01-09 08:45:00'
            ],
            [
                'id' => 8,
                'item_name' => 'Joy Dishwashing Liquid',
                'quantity' => 18,
                'unit' => 'bottles',
                'threshold' => 4,
                'status' => 'In Stock',
                'last_updated' => '2024-01-08 13:15:00'
            ],
            [
                'id' => 9,
                'item_name' => 'Mr. Clean All-Purpose Cleaner',
                'quantity' => 7,
                'unit' => 'bottles',
                'threshold' => 3,
                'status' => 'Low Stock',
                'last_updated' => '2024-01-07 16:20:00'
            ],
            [
                'id' => 10,
                'item_name' => 'Clorox Disinfectant',
                'quantity' => 14,
                'unit' => 'bottles',
                'threshold' => 4,
                'status' => 'In Stock',
                'last_updated' => '2024-01-06 10:10:00'
            ]
        ]);

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'item_name', 'label' => 'Item Name', 'sortable' => true],
            ['key' => 'quantity', 'label' => 'Quantity', 'sortable' => true],
            ['key' => 'unit', 'label' => 'Unit', 'sortable' => true],
            ['key' => 'threshold', 'label' => 'Threshold', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'last_updated', 'label' => 'Last Updated', 'sortable' => true]
        ];

        // Define actions for the data table
        $actions = [
            ['label' => 'View', 'onclick' => 'viewItem'],
            ['label' => 'Edit', 'onclick' => 'editItem'],
            ['label' => 'Update Stock', 'onclick' => 'updateStock'],
            ['label' => 'Delete', 'onclick' => 'deleteItem']
        ];

        $description = 'Manage laundry supplies, detergents, and cleaning products inventory';
        
        return view('superadmin.inventory.index', compact('inventory', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('superadmin.inventory.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validation and storage logic will go here
        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return view('superadmin.inventory.show', compact('id'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('superadmin.inventory.edit', compact('id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Update logic will go here
        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Delete logic will go here
        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item deleted successfully');
    }
}
