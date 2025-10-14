<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\AuditLogService;
use Symfony\Component\HttpFoundation\Response;

class AuditLogMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only log for authenticated users and successful responses
        if (auth()->check() && $response->getStatusCode() >= 200 && $response->getStatusCode() < 300) {
            $this->logRequest($request, $response);
        }

        return $response;
    }

    /**
     * Log the request based on route and method
     */
    private function logRequest(Request $request, Response $response): void
    {
        $route = $request->route();
        $routeName = $route?->getName();
        $method = $request->method();
        $path = $request->path();

        // Skip logging for certain routes
        if ($this->shouldSkipLogging($routeName, $path)) {
            return;
        }

        // Log based on route patterns
        if ($this->isUserManagementRoute($routeName, $path)) {
            $this->logUserManagementAction($request, $response);
        } elseif ($this->isOrderManagementRoute($routeName, $path)) {
            $this->logOrderManagementAction($request, $response);
        } elseif ($this->isPaymentRoute($routeName, $path)) {
            $this->logPaymentAction($request, $response);
        } elseif ($this->isInventoryRoute($routeName, $path)) {
            $this->logInventoryAction($request, $response);
        } elseif ($this->isServiceRoute($routeName, $path)) {
            $this->logServiceAction($request, $response);
        } elseif ($this->isSettingsRoute($routeName, $path)) {
            $this->logSettingsAction($request, $response);
        }
    }

    /**
     * Check if route should be skipped from logging
     */
    private function shouldSkipLogging(?string $routeName, string $path): bool
    {
        $skipPatterns = [
            'audit-logs',
            'api/',
            'ajax/',
            'export',
            'statistics',
            'dashboard',
            'profile',
        ];

        foreach ($skipPatterns as $pattern) {
            if (str_contains($path, $pattern) || str_contains($routeName ?? '', $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if this is a user management route
     */
    private function isUserManagementRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'users') || 
               str_contains($routeName ?? '', 'user') ||
               str_contains($path, 'roles');
    }

    /**
     * Check if this is an order management route
     */
    private function isOrderManagementRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'orders') || 
               str_contains($routeName ?? '', 'order');
    }

    /**
     * Check if this is a payment route
     */
    private function isPaymentRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'payments') || 
               str_contains($routeName ?? '', 'payment');
    }

    /**
     * Check if this is an inventory route
     */
    private function isInventoryRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'inventory') || 
               str_contains($routeName ?? '', 'inventory');
    }

    /**
     * Check if this is a service route
     */
    private function isServiceRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'services') || 
               str_contains($routeName ?? '', 'service');
    }

    /**
     * Check if this is a settings route
     */
    private function isSettingsRoute(?string $routeName, string $path): bool
    {
        return str_contains($path, 'settings') || 
               str_contains($routeName ?? '', 'setting');
    }

    /**
     * Log user management actions
     */
    private function logUserManagementAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST' => 'USER_CREATED',
            'PUT', 'PATCH' => 'USER_UPDATED',
            'DELETE' => 'USER_DELETED',
            default => 'USER_ACCESSED'
        };

        $description = $this->buildUserManagementDescription($request, $action);
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Log order management actions
     */
    private function logOrderManagementAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST' => 'ORDER_CREATED',
            'PUT', 'PATCH' => 'ORDER_UPDATED',
            'DELETE' => 'ORDER_DELETED',
            default => 'ORDER_ACCESSED'
        };

        $description = $this->buildOrderManagementDescription($request, $action);
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Log payment actions
     */
    private function logPaymentAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST' => 'PAYMENT_CREATED',
            'PUT', 'PATCH' => 'PAYMENT_UPDATED',
            'DELETE' => 'PAYMENT_DELETED',
            default => 'PAYMENT_ACCESSED'
        };

        $description = $this->buildPaymentDescription($request, $action);
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Log inventory actions
     */
    private function logInventoryAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST' => 'INVENTORY_CREATED',
            'PUT', 'PATCH' => 'INVENTORY_UPDATED',
            'DELETE' => 'INVENTORY_DELETED',
            default => 'INVENTORY_ACCESSED'
        };

        $description = $this->buildInventoryDescription($request, $action);
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Log service actions
     */
    private function logServiceAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST' => 'SERVICE_CREATED',
            'PUT', 'PATCH' => 'SERVICE_UPDATED',
            'DELETE' => 'SERVICE_DELETED',
            default => 'SERVICE_ACCESSED'
        };

        $description = $this->buildServiceDescription($request, $action);
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Log settings actions
     */
    private function logSettingsAction(Request $request, Response $response): void
    {
        $method = $request->method();
        $action = match($method) {
            'POST', 'PUT', 'PATCH' => 'SETTINGS_UPDATED',
            default => 'SETTINGS_ACCESSED'
        };

        $description = "Accessed system settings";
        
        AuditLogService::logUserAction(
            $action,
            $description,
            null,
            [
                'route' => $request->route()?->getName(),
                'method' => $method,
                'path' => $request->path(),
                'response_status' => $response->getStatusCode()
            ]
        );
    }

    /**
     * Build user management description
     */
    private function buildUserManagementDescription(Request $request, string $action): string
    {
        $path = $request->path();
        
        if (str_contains($path, 'users')) {
            return match($action) {
                'USER_CREATED' => 'Created a new user account',
                'USER_UPDATED' => 'Updated user account information',
                'USER_DELETED' => 'Deleted a user account',
                default => 'Accessed user management'
            };
        }
        
        if (str_contains($path, 'roles')) {
            return match($action) {
                'USER_CREATED' => 'Created a new user role',
                'USER_UPDATED' => 'Updated user role permissions',
                'USER_DELETED' => 'Deleted a user role',
                default => 'Accessed role management'
            };
        }

        return 'Performed user management action';
    }

    /**
     * Build order management description
     */
    private function buildOrderManagementDescription(Request $request, string $action): string
    {
        return match($action) {
            'ORDER_CREATED' => 'Created a new laundry order',
            'ORDER_UPDATED' => 'Updated laundry order details',
            'ORDER_DELETED' => 'Deleted a laundry order',
            default => 'Accessed order management'
        };
    }

    /**
     * Build payment description
     */
    private function buildPaymentDescription(Request $request, string $action): string
    {
        return match($action) {
            'PAYMENT_CREATED' => 'Created a new payment record',
            'PAYMENT_UPDATED' => 'Updated payment information',
            'PAYMENT_DELETED' => 'Deleted a payment record',
            default => 'Accessed payment management'
        };
    }

    /**
     * Build inventory description
     */
    private function buildInventoryDescription(Request $request, string $action): string
    {
        return match($action) {
            'INVENTORY_CREATED' => 'Added new inventory item',
            'INVENTORY_UPDATED' => 'Updated inventory item',
            'INVENTORY_DELETED' => 'Deleted inventory item',
            default => 'Accessed inventory management'
        };
    }

    /**
     * Build service description
     */
    private function buildServiceDescription(Request $request, string $action): string
    {
        return match($action) {
            'SERVICE_CREATED' => 'Created a new laundry service',
            'SERVICE_UPDATED' => 'Updated laundry service details',
            'SERVICE_DELETED' => 'Deleted a laundry service',
            default => 'Accessed service management'
        };
    }
}
