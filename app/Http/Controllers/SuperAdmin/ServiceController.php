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
            [
                'label' => 'View',
                'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z',
                'onclick' => 'viewService(row.id)'
            ],
            [
                'label' => 'Edit',
                'icon' => 'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z',
                'onclick' => 'editService(row.id)'
            ],
            [
                'label' => 'Toggle Status',
                'icon' => 'M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z',
                'onclick' => 'toggleServiceStatus(row.id, row.status)'
            ],
            [
                'label' => 'Delete',
                'icon' => 'M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16',
                'onclick' => 'deleteService(row.id)',
                'class' => 'text-red-600 hover:text-red-800'
            ],
        ];

        return view('superadmin.services.index', compact('services', 'columns', 'actions'));
    }
}