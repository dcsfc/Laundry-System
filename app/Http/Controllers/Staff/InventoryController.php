<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    /**
     * Display inventory management page
     */
    public function index()
    {
        // Get all inventory items
        $inventory = Inventory::orderBy('item_name', 'asc')
            ->get()
            ->map(function ($item) {
                // Determine stock status
                $status = 'good';
                $statusText = 'Good Stock';
                $statusClass = 'status-good';
                
                if ($item->quantity <= $item->threshold) {
                    $status = 'low';
                    $statusText = 'Low Stock';
                    $statusClass = 'status-low';
                } elseif ($item->quantity <= ($item->threshold * 1.5)) {
                    $status = 'warning';
                    $statusText = 'Monitor';
                    $statusClass = 'status-warning';
                }

                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'threshold' => $item->threshold,
                    'status' => $status,
                    'status_text' => $statusText,
                    'status_class' => $statusClass,
                    'created_at' => $item->created_at->format('M j, Y'),
                    'updated_at' => $item->updated_at->format('M j, Y g:i A'),
                ];
            });

        // Define columns for inventory table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'item_name', 'label' => 'Item Name', 'sortable' => true],
            ['key' => 'quantity', 'label' => 'Quantity', 'sortable' => true],
            ['key' => 'unit', 'label' => 'Unit', 'sortable' => true],
            ['key' => 'threshold', 'label' => 'Threshold', 'sortable' => true],
            ['key' => 'status_text', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        // Define actions for inventory
        $actions = [
            ['key' => 'view', 'label' => 'View Details', 'icon' => 'fas fa-eye'],
            ['key' => 'edit', 'label' => 'Edit Item', 'icon' => 'fas fa-edit'],
            ['key' => 'update_stock', 'label' => 'Update Stock', 'icon' => 'fas fa-boxes'],
            ['key' => 'delete', 'label' => 'Delete', 'icon' => 'fas fa-trash'],
        ];

        return view('staff.inventory.index', compact(
            'inventory',
            'columns',
            'actions'
        ));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create()
    {
        return view('staff.inventory.create');
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'required|integer|min:0',
        ]);

        Inventory::create([
            'item_name' => $request->item_name,
            'quantity' => $request->quantity,
            'unit' => $request->unit,
            'threshold' => $request->threshold,
        ]);

        return redirect()->route('staff.inventory.index')
            ->with('success', 'Inventory item created successfully!');
    }

    /**
     * Display the specified inventory item
     */
    public function show($id)
    {
        $item = Inventory::findOrFail($id);
        
        return response()->json([
            'success' => true,
            'item' => [
                'id' => $item->id,
                'item_name' => $item->item_name,
                'quantity' => $item->quantity,
                'unit' => $item->unit,
                'threshold' => $item->threshold,
                'created_at' => $item->created_at->format('M j, Y g:i A'),
                'updated_at' => $item->updated_at->format('M j, Y g:i A'),
            ]
        ]);
    }

    /**
     * Show the form for editing the specified inventory item
     */
    public function edit($id)
    {
        $item = Inventory::findOrFail($id);
        return view('staff.inventory.edit', compact('item'));
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'item_name' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'required|integer|min:0',
        ]);

        $item = Inventory::findOrFail($id);
        $item->update($request->all());

        return redirect()->route('staff.inventory.index')
            ->with('success', 'Inventory item updated successfully!');
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $item = Inventory::findOrFail($id);
        $item->update(['quantity' => $request->quantity]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully!',
            'item' => $item
        ]);
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy($id)
    {
        $item = Inventory::findOrFail($id);
        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Inventory item deleted successfully!'
        ]);
    }
}

