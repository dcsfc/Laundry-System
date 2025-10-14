<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Log user actions with enhanced details
     */
    public static function logUserAction(string $action, string $description, ?int $userId = null, array $metadata = []): AuditLog
    {
        $user = $userId ? User::find($userId) : Auth::user();
        
        $enhancedMetadata = array_merge($metadata, [
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'user_email' => $user?->email,
            'user_role' => $user?->role?->name ?? 'Unknown',
            'action_time' => now()->toDateTimeString(),
            'session_id' => session()->getId(),
            'request_id' => request()->header('X-Request-ID', uniqid()),
        ]);

        return AuditLog::log($action, $description, $user?->id, $enhancedMetadata);
    }

    /**
     * Log system events (no user context)
     */
    public static function logSystemEvent(string $action, string $description, array $metadata = []): AuditLog
    {
        $enhancedMetadata = array_merge($metadata, [
            'system_event' => true,
            'action_time' => now()->toDateTimeString(),
            'request_id' => request()->header('X-Request-ID', uniqid()),
        ]);

        return AuditLog::logSystem($action, $description, $enhancedMetadata);
    }

    /**
     * Log user management actions
     */
    public static function logUserManagement(string $action, User $targetUser, array $changes = [], array $metadata = []): AuditLog
    {
        $description = self::buildUserManagementDescription($action, $targetUser, $changes);
        
        $enhancedMetadata = array_merge($metadata, [
            'target_user_id' => $targetUser->id,
            'target_user_name' => $targetUser->name,
            'target_user_email' => $targetUser->email,
            'target_user_role' => $targetUser->role?->name ?? 'Unknown',
            'changes' => $changes,
        ]);

        return self::logUserAction($action, $description, null, $enhancedMetadata);
    }

    /**
     * Log order/transaction actions
     */
    public static function logOrderAction(string $action, $order, array $changes = [], array $metadata = []): AuditLog
    {
        $description = self::buildOrderDescription($action, $order, $changes);
        
        $enhancedMetadata = array_merge($metadata, [
            'order_id' => $order->id,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer?->name ?? 'Unknown',
            'order_status' => $order->status,
            'order_total' => $order->total_price,
            'changes' => $changes,
        ]);

        return self::logUserAction($action, $description, null, $enhancedMetadata);
    }

    /**
     * Log payment actions
     */
    public static function logPaymentAction(string $action, $payment, array $metadata = []): AuditLog
    {
        $description = self::buildPaymentDescription($action, $payment);
        
        $enhancedMetadata = array_merge($metadata, [
            'payment_id' => $payment->id,
            'order_id' => $payment->order_id,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'payment_status' => $payment->payment_status,
            'reference_number' => $payment->reference_number,
        ]);

        return self::logUserAction($action, $description, null, $enhancedMetadata);
    }

    /**
     * Log inventory actions
     */
    public static function logInventoryAction(string $action, $inventory, array $changes = [], array $metadata = []): AuditLog
    {
        $description = self::buildInventoryDescription($action, $inventory, $changes);
        
        $enhancedMetadata = array_merge($metadata, [
            'inventory_id' => $inventory->id,
            'item_name' => $inventory->item_name,
            'quantity' => $inventory->quantity,
            'unit' => $inventory->unit,
            'threshold' => $inventory->threshold,
            'changes' => $changes,
        ]);

        return self::logUserAction($action, $description, null, $enhancedMetadata);
    }

    /**
     * Log service management actions
     */
    public static function logServiceAction(string $action, $service, array $changes = [], array $metadata = []): AuditLog
    {
        $description = self::buildServiceDescription($action, $service, $changes);
        
        $enhancedMetadata = array_merge($metadata, [
            'service_id' => $service->id,
            'service_name' => $service->name,
            'service_price' => $service->price,
            'service_status' => $service->is_active ? 'active' : 'inactive',
            'changes' => $changes,
        ]);

        return self::logUserAction($action, $description, null, $enhancedMetadata);
    }

    /**
     * Build user management description
     */
    private static function buildUserManagementDescription(string $action, User $user, array $changes): string
    {
        $baseDescription = match($action) {
            'USER_CREATED' => "Created new user '{$user->name}'",
            'USER_UPDATED' => "Updated user '{$user->name}'",
            'USER_DELETED' => "Deleted user '{$user->name}'",
            'USER_STATUS_CHANGED' => "Changed user '{$user->name}' status",
            default => "Performed action on user '{$user->name}'"
        };

        if (!empty($changes)) {
            $changeDetails = "\n" . implode("\n", $changes);
            return $baseDescription . $changeDetails;
        }

        return $baseDescription;
    }

    /**
     * Build order description
     */
    private static function buildOrderDescription(string $action, $order, array $changes): string
    {
        $customerName = $order->customer?->name ?? 'Unknown Customer';
        $baseDescription = match($action) {
            'ORDER_CREATED' => "Created new order #{$order->id} for '{$customerName}'",
            'ORDER_UPDATED' => "Updated order #{$order->id} for '{$customerName}'",
            'ORDER_STATUS_CHANGED' => "Changed order #{$order->id} status for '{$customerName}'",
            'ORDER_DELETED' => "Deleted order #{$order->id} for '{$customerName}'",
            default => "Performed action on order #{$order->id} for '{$customerName}'"
        };

        if (!empty($changes)) {
            $changeDetails = "\n" . implode("\n", $changes);
            return $baseDescription . $changeDetails;
        }

        return $baseDescription;
    }

    /**
     * Build payment description
     */
    private static function buildPaymentDescription(string $action, $payment): string
    {
        $orderId = $payment->order_id;
        $amount = number_format($payment->amount, 2);
        $method = ucfirst($payment->payment_method);

        return match($action) {
            'PAYMENT_CREATED' => "Created payment of ₱{$amount} via {$method} for order #{$orderId}",
            'PAYMENT_UPDATED' => "Updated payment of ₱{$amount} via {$method} for order #{$orderId}",
            'PAYMENT_COMPLETED' => "Completed payment of ₱{$amount} via {$method} for order #{$orderId}",
            'PAYMENT_FAILED' => "Payment of ₱{$amount} via {$method} failed for order #{$orderId}",
            default => "Performed action on payment of ₱{$amount} via {$method} for order #{$orderId}"
        };
    }

    /**
     * Build inventory description
     */
    private static function buildInventoryDescription(string $action, $inventory, array $changes): string
    {
        $baseDescription = match($action) {
            'INVENTORY_CREATED' => "Added new inventory item '{$inventory->item_name}'",
            'INVENTORY_UPDATED' => "Updated inventory item '{$inventory->item_name}'",
            'INVENTORY_DELETED' => "Deleted inventory item '{$inventory->item_name}'",
            'INVENTORY_LOW_STOCK' => "Low stock alert for '{$inventory->item_name}'",
            default => "Performed action on inventory item '{$inventory->item_name}'"
        };

        if (!empty($changes)) {
            $changeDetails = "\n" . implode("\n", $changes);
            return $baseDescription . $changeDetails;
        }

        return $baseDescription;
    }

    /**
     * Build service description
     */
    private static function buildServiceDescription(string $action, $service, array $changes): string
    {
        $baseDescription = match($action) {
            'SERVICE_CREATED' => "Created new service '{$service->name}'",
            'SERVICE_UPDATED' => "Updated service '{$service->name}'",
            'SERVICE_DELETED' => "Deleted service '{$service->name}'",
            'SERVICE_STATUS_CHANGED' => "Changed service '{$service->name}' status",
            default => "Performed action on service '{$service->name}'"
        };

        if (!empty($changes)) {
            $changeDetails = "\n" . implode("\n", $changes);
            return $baseDescription . $changeDetails;
        }

        return $baseDescription;
    }

}
