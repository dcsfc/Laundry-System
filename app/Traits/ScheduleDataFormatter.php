<?php

namespace App\Traits;

use App\Models\Order;
use Carbon\Carbon;

trait ScheduleDataFormatter
{
    /**
     * Format order data consistently across all controllers
     */
    protected function formatOrderData(Order $order, $includeCustomerInfo = true)
    {
        // Compose friendly datetime strings
        $dropoff = null;
        if ($order->dropoff_date || $order->dropoff_time) {
            $dateStr = $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('M j, Y') : '';
            $timeRaw = $order->dropoff_time;
            $timeStr = '';
            if (!empty($timeRaw)) {
                try {
                    $timeStr = Carbon::parse($timeRaw)->format('g:i A');
                } catch (\Exception $e) {
                    $timeStr = (string) $timeRaw; // fallback
                }
            }
            $dropoff = trim($dateStr . (strlen($timeStr) ? ' • ' . $timeStr : '')) ?: 'N/A';
        }

        $pickup = null;
        if ($order->pickup_date || $order->pickup_time) {
            $dateStr = $order->pickup_date ? Carbon::parse($order->pickup_date)->format('M j, Y') : '';
            $timeRaw = $order->pickup_time;
            $timeStr = '';
            if (!empty($timeRaw)) {
                try {
                    $timeStr = Carbon::parse($timeRaw)->format('g:i A');
                } catch (\Exception $e) {
                    $timeStr = (string) $timeRaw; // fallback
                }
            }
            $pickup = trim($dateStr . (strlen($timeStr) ? ' • ' . $timeStr : '')) ?: 'N/A';
        }

        $baseData = [
            'id' => $order->id,
            'customer_id' => $order->customer_id,
            'service_type' => $order->service->name ?? 'Laundry Service',
            'dropoff_date' => $order->dropoff_date ? Carbon::parse($order->dropoff_date)->format('Y-m-d') : 'N/A',
            'dropoff_time' => $order->dropoff_time ? Carbon::parse($order->dropoff_time)->format('g:i A') : '09:00 AM',
            'pickup_date' => $order->pickup_date ? Carbon::parse($order->pickup_date)->format('Y-m-d') : 'N/A',
            'pickup_time' => $order->pickup_time ? Carbon::parse($order->pickup_time)->format('g:i A') : '05:00 PM',
            'dropoff' => $dropoff ?: 'N/A',
            'pickup' => $pickup ?: 'N/A',
            'approval_status' => $order->approval_status,
            'status' => strtolower(trim((string) $order->status)),
            'status_display' => ucfirst(str_replace('_', ' ', (string) $order->status)),
            'weight' => $order->weight ?? null,
            'total_price' => $order->total_price ?? null,
            'price' => $order->total_price !== null ? number_format((float)$order->total_price, 2) : null,
            'approved_by_name' => $order->approvedBy->name ?? null,
            'approved_by' => $order->approvedBy->name ?? null, // Alias for compatibility
            'approved_at' => $order->approved_at ?? null,
            'notes' => $order->notes ?? '',
            'created_at' => $order->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $order->updated_at ? $order->updated_at->format('M j, Y g:i A') : null,
        ];

        // Add customer-specific fields if requested
        if ($includeCustomerInfo) {
            $baseData = array_merge($baseData, [
                'customer_name' => $order->customer->name ?? 'N/A',
                'customer_email' => $order->customer->email ?? 'N/A',
                'customer_phone' => $order->customer->phone_number ?? 'N/A',
                'staff_assigned' => $order->staff->name ?? 'Unassigned',
            ]);
        }

        return $baseData;
    }

    /**
     * Get consistent status display based on approval workflow
     */
    protected function getStatusDisplay($status, $approvalStatus = null)
    {
        // If approval status is rejected, show rejected
        if ($approvalStatus === 'rejected') {
            return 'Rejected';
        }
        
        // If approval status is pending, only show "Pending Approval" if the order status is also pending
        if ($approvalStatus === 'pending' && $status === 'pending') {
            return 'Pending Approval';
        }
        
        // For all other cases (including pending approval with cancelled/completed status), show the actual order status
        $statusMap = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed', 
            'in_progress' => 'In Progress',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            // Legacy statuses for backward compatibility
            'approved' => 'Confirmed', // Map old 'approved' status to 'Confirmed'
            'processing' => 'In Progress', // Map old 'processing' status to 'In Progress'
            'scheduled' => 'Confirmed',
            'priced' => 'Confirmed',
            'canceled' => 'Cancelled'
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
