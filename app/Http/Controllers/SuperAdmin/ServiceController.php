<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        // Sample service data
        $services = [
            [
                'id' => 1,
                'name' => 'Wash & Fold',
                'description' => 'Complete wash and fold service for regular clothes',
                'price' => 15.00,
                'category' => 'Basic',
                'status' => 'Active',
                'created_at' => '2024-01-15'
            ],
            [
                'id' => 2,
                'name' => 'Dry Cleaning',
                'description' => 'Professional dry cleaning for delicate garments',
                'price' => 25.00,
                'category' => 'Premium',
                'status' => 'Active',
                'created_at' => '2024-01-20'
            ],
            [
                'id' => 3,
                'name' => 'Ironing Service',
                'description' => 'Professional ironing and pressing service',
                'price' => 12.00,
                'category' => 'Basic',
                'status' => 'Active',
                'created_at' => '2024-02-01'
            ],
            [
                'id' => 4,
                'name' => 'Express Service',
                'description' => 'Same-day wash and fold service',
                'price' => 20.00,
                'category' => 'Express',
                'status' => 'Inactive',
                'created_at' => '2024-02-10'
            ],
            [
                'id' => 5,
                'name' => 'Bulk Laundry',
                'description' => 'Large quantity laundry service for businesses',
                'price' => 8.00,
                'category' => 'Bulk',
                'status' => 'Active',
                'created_at' => '2024-02-15'
            ],
            [
                'id' => 6,
                'name' => 'Stain Removal',
                'description' => 'Specialized stain removal service',
                'price' => 18.00,
                'category' => 'Premium',
                'status' => 'Active',
                'created_at' => '2024-02-20'
            ],
            [
                'id' => 7,
                'name' => 'Alterations',
                'description' => 'Clothing alterations and repairs',
                'price' => 35.00,
                'category' => 'Premium',
                'status' => 'Active',
                'created_at' => '2024-02-25'
            ],
            [
                'id' => 8,
                'name' => 'Wedding Dress Care',
                'description' => 'Specialized wedding dress cleaning and preservation',
                'price' => 150.00,
                'category' => 'Premium',
                'status' => 'Active',
                'created_at' => '2024-03-01'
            ],
        ];

        $columns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'name', 'label' => 'Service Name', 'sortable' => true, 'searchable' => true],
            ['key' => 'description', 'label' => 'Description', 'sortable' => true, 'searchable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'category', 'label' => 'Category', 'sortable' => true],
            ['key' => 'status', 'label' => 'Status', 'sortable' => true],
            ['key' => 'created_at', 'label' => 'Created', 'sortable' => true],
        ];

        $actions = [
            ['key' => 'viewService', 'label' => 'View', 'icon' => 'view', 'color' => 'blue'],
            ['key' => 'editService', 'label' => 'Edit', 'icon' => 'edit', 'color' => 'yellow'],
            ['key' => 'toggleServiceStatus', 'label' => 'Toggle Status', 'icon' => 'toggle', 'color' => 'green'],
            ['key' => 'deleteService', 'label' => 'Delete', 'icon' => 'delete', 'color' => 'red']
        ];

        return view('superadmin.services.index', compact('services', 'columns', 'actions'));
    }
}