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
        $items = Inventory::orderBy('item_name')->paginate(15);
        return view('admin.inventory.index', compact('items'));
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


