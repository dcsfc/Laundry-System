<?php

namespace App\Helpers;

class StatusHelper
{
    /**
     * Get consistent status colors for both light and dark themes
     */
    public static function getStatusColors()
    {
        return [
            'pending' => [
                'bg' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                'dot' => 'bg-yellow-400',
                'badge' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'
            ],
            'approved' => [
                'bg' => 'bg-green-500/20 text-green-400 border-green-500/30',
                'dot' => 'bg-green-400',
                'badge' => 'bg-green-500/20 text-green-400 border-green-500/30'
            ],
            'confirmed' => [
                'bg' => 'bg-green-500/20 text-green-400 border-green-500/30',
                'dot' => 'bg-green-400',
                'badge' => 'bg-green-500/20 text-green-400 border-green-500/30'
            ],
            'processing' => [
                'bg' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                'dot' => 'bg-blue-400',
                'badge' => 'bg-blue-500/20 text-blue-400 border-blue-500/30'
            ],
            'in_progress' => [
                'bg' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                'dot' => 'bg-blue-400',
                'badge' => 'bg-blue-500/20 text-blue-400 border-blue-500/30'
            ],
            'ready_for_pickup' => [
                'bg' => 'bg-purple-500/20 text-purple-400 border-purple-500/30',
                'dot' => 'bg-purple-400',
                'badge' => 'bg-purple-500/20 text-purple-400 border-purple-500/30'
            ],
            'completed' => [
                'bg' => 'bg-gray-500/20 text-gray-400 border-gray-500/30',
                'dot' => 'bg-gray-400',
                'badge' => 'bg-gray-500/20 text-gray-400 border-gray-500/30'
            ],
            'cancelled' => [
                'bg' => 'bg-red-500/20 text-red-400 border-red-500/30',
                'dot' => 'bg-red-400',
                'badge' => 'bg-red-500/20 text-red-400 border-red-500/30'
            ],
            'canceled' => [
                'bg' => 'bg-red-500/20 text-red-400 border-red-500/30',
                'dot' => 'bg-red-400',
                'badge' => 'bg-red-500/20 text-red-400 border-red-500/30'
            ],
            'rejected' => [
                'bg' => 'bg-red-500/20 text-red-400 border-red-500/30',
                'dot' => 'bg-red-400',
                'badge' => 'bg-red-500/20 text-red-400 border-red-500/30'
            ],
            'pending_approval' => [
                'bg' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                'dot' => 'bg-yellow-400',
                'badge' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'
            ]
        ];
    }

    /**
     * Get status colors for a specific status
     */
    public static function getStatusColor($status)
    {
        $colors = self::getStatusColors();
        $normalizedStatus = strtolower(str_replace([' ', '_'], ['_', '_'], $status));
        
        // Handle special cases
        if ($normalizedStatus === 'pending_approval') {
            return $colors['pending_approval'];
        }
        
        return $colors[$normalizedStatus] ?? $colors['pending'];
    }

    /**
     * Get status display text
     */
    public static function getStatusDisplay($status)
    {
        $statusMap = [
            'pending' => 'Pending',
            'pending_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'confirmed' => 'Confirmed',
            'processing' => 'Processing',
            'in_progress' => 'In Progress',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'canceled' => 'Cancelled',
            'rejected' => 'Rejected'
        ];

        $normalizedStatus = strtolower(str_replace([' ', '_'], ['_', '_'], $status));
        return $statusMap[$normalizedStatus] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
