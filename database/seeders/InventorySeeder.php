<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $inventory = [
            [
                'item_name' => 'Ariel Liquid Detergent',
                'price' => 20.00,
                'quantity' => 50,
                'unit' => 'bottles',
                'threshold' => 10,
            ],
            [
                'item_name' => 'Downy Fabcon',
                'price' => 10.00,
                'quantity' => 30,
                'unit' => 'bottles',
                'threshold' => 8,
            ],
            [
                'item_name' => 'Zonrox Colorsafe',
                'price' => 15.00,
                'quantity' => 25,
                'unit' => 'bottles',
                'threshold' => 5,
            ],
        ];

        foreach ($inventory as $item) {
            Inventory::create($item);
        }
    }
}
