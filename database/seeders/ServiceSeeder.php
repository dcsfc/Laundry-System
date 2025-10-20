<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            [
                'id' => 1,
                'name' => 'Full Service 5-7kg',
                'description' => 'Complete laundry service including wash, dry, and fold for 5-7kg load',
                'price' => 175.00,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Self Service Wash 5-7kg',
                'description' => 'Self-service washing only for 5-7kg load',
                'price' => 70.00,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Self Service Wash and Dry 5-7kg',
                'description' => 'Self-service washing and drying for 5-7kg load',
                'price' => 140.00,
                'is_active' => true,
            ],
        ];

        foreach ($services as $service) {
            Service::updateOrCreate(
                ['id' => $service['id']],
                $service
            );
        }
    }
}
