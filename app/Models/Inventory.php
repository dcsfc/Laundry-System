<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';

    protected $fillable = [
        'item_name',
        'price',
        'quantity',
        'unit',
        'threshold'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'threshold' => 'integer',
        'price' => 'decimal:2'
    ];

    /**
     * Check if the item is low in stock
     */
    public function isLowStock(): bool
    {
        return $this->quantity <= $this->threshold;
    }

    /**
     * Get the stock status
     */
    public function getStockStatus(): string
    {
        if ($this->quantity <= $this->threshold) {
            return 'low';
        } elseif ($this->quantity <= ($this->threshold * 1.5)) {
            return 'warning';
        }
        
        return 'good';
    }

    /**
     * Get the stock status text
     */
    public function getStockStatusText(): string
    {
        return match($this->getStockStatus()) {
            'low' => 'Low stock - reorder needed',
            'warning' => 'Monitor stock level',
            default => 'Good stock level'
        };
    }
}
