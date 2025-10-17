<?php

namespace App\Http\Controllers\Admin;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of inventory items
     */
    public function index()
    {
        $inventory = Inventory::orderBy('item_name')
            ->get()
            ->map(function ($item) {
                $status = 'In Stock';
                $statusColor = 'green';
                
                if ($item->threshold && $item->quantity <= $item->threshold) {
                    $status = 'Low Stock';
                    $statusColor = 'yellow';
                }
                
                if ($item->quantity == 0) {
                    $status = 'Out of Stock';
                    $statusColor = 'red';
                }
                
                return [
                    'id' => $item->id,
                    'item_name' => $item->item_name,
                    'price' => $item->price ? '₱' . number_format($item->price, 2) : 'N/A',
                    'quantity' => $item->quantity,
                    'unit' => $item->unit,
                    'threshold' => $item->threshold ?? 'N/A',
                    'status' => $status,
                    'status_color' => $statusColor,
                    'created_at' => $item->created_at->format('M j, Y'),
                    'updated_at' => $item->updated_at->format('M j, Y g:i A'),
                ];
            })->toArray();

        // Define columns for the data table
        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'item_name', 'label' => 'Item Name', 'sortable' => true, 'searchable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'quantity', 'label' => 'Quantity', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        // Define actions for the data table
        $actions = [
            ['key' => 'view', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'edit', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'update_stock', 'label' => 'Update Stock', 'icon' => 'settings', 'color' => 'green'],
            ['key' => 'delete', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red'],
        ];

        $description = 'Manage inventory items, track stock levels, and set low-stock alerts';

        return view('admin.inventory.index', compact('inventory', 'columns', 'actions', 'description'));
    }

    /**
     * Show the form for creating a new inventory item
     */
    public function create()
    {
        return view('admin.inventory.create');
    }

    /**
     * Store a newly created inventory item
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'nullable|integer|min:0',
        ]);

        Inventory::create($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item created successfully.');
    }

    /**
     * Display the specified inventory item
     */
    public function show(Inventory $inventory)
    {
        return view('admin.inventory.show', compact('inventory'));
    }

    /**
     * Show the form for editing the specified inventory item
     */
    public function edit(Inventory $inventory)
    {
        return view('admin.inventory.edit', compact('inventory'));
    }

    /**
     * Update the specified inventory item
     */
    public function update(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:100',
            'price' => 'nullable|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'threshold' => 'nullable|integer|min:0',
        ]);

        $inventory->update($validated);

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item updated successfully.');
    }

    /**
     * Remove the specified inventory item
     */
    public function destroy(Inventory $inventory)
    {
        $inventory->delete();

        return redirect()->route('admin.inventory.index')
            ->with('success', 'Inventory item deleted successfully.');
    }

    /**
     * Update stock quantity
     */
    public function updateStock(Request $request, Inventory $inventory)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer',
            'action' => 'required|in:add,subtract,set',
        ]);

        $newQuantity = match($validated['action']) {
            'add' => $inventory->quantity + $validated['quantity'],
            'subtract' => max(0, $inventory->quantity - $validated['quantity']),
            'set' => $validated['quantity'],
        };

        $inventory->update(['quantity' => $newQuantity]);

        return response()->json([
            'success' => true,
            'message' => 'Stock updated successfully.',
            'new_quantity' => $newQuantity
        ]);
    }
}


