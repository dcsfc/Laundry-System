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
                'name' => 'Wash & Fold',
                'description' => 'Standard wash and fold service',
                'price' => 50.00,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Wash & Press',
                'description' => 'Wash and press service with ironing',
                'price' => 75.00,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Dry Clean',
                'description' => 'Professional dry cleaning service',
                'price' => 100.00,
                'is_active' => true,
            ],
            [
                'id' => 4,
                'name' => 'Express Service',
                'description' => 'Same-day or next-day service',
                'price' => 80.00,
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
