<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get real inventory data from database
        $inventory = Inventory::orderBy('created_at', 'desc')
            ->get()
            ->map(function ($item) {
                // Determine status based on quantity and threshold
                $status = 'In Stock';
                if ($item->quantity == 0) {
                    $status = 'Out of Stock';
                } elseif ($item->quantity <= $item->threshold) {
                    $status = 'Low Stock';
                }

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'threshold' => $item->threshold,
                    'status' => $status,
                    'last_updated' => $item->updated_at->format('M j, Y g:i A')
                ];
            });

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
        $request->validate([
            'item_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'required|integer|min:0'
        ]);

        Inventory::create([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'threshold' => $request->threshold
        ]);

        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $item = Inventory::findOrFail($id);
        
        // Determine status
        $status = 'In Stock';
        if ($item->quantity == 0) {
            $status = 'Out of Stock';
        } elseif ($item->quantity <= $item->threshold) {
            $status = 'Low Stock';
        }
        
        $item->status = $status;
        
        return view('superadmin.inventory.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item = Inventory::findOrFail($id);
        
        return view('superadmin.inventory.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'required|integer|min:0'
        ]);

        $item = Inventory::findOrFail($id);
        
        $item->update([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'threshold' => $request->threshold
        ]);

        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();

        return redirect()->route('superadmin.inventory.index')->with('success', 'Inventory item deleted successfully');
    }
}
