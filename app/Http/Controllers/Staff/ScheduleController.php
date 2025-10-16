<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use App\Traits\ScheduleDataFormatter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ScheduleController extends Controller
{
    use ScheduleDataFormatter;

    public function index(Request $request)
    {
        $allSchedules = Order::with(['customer', 'service', 'approvedBy'])
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => $this->mapOrderToArray($order))
            ->toArray();

        $scheduleColumns = [
            ['key' => 'id', 'label' => 'ID', 'sortable' => true],
            ['key' => 'customer_name', 'label' => 'Customer', 'sortable' => true],
            ['key' => 'customer_phone', 'label' => 'Phone', 'sortable' => true],
            ['key' => 'dropoff', 'label' => 'Drop-off', 'sortable' => true],
            ['key' => 'pickup', 'label' => 'Pickup', 'sortable' => true],
            ['key' => 'status_display', 'label' => 'Status', 'sortable' => true, 'type' => 'badge'],
            ['key' => 'weight', 'label' => 'Weight', 'sortable' => true],
            ['key' => 'price', 'label' => 'Price', 'sortable' => true],
            ['key' => 'updated_at', 'label' => 'Last Updated', 'sortable' => true],
        ];

        if ($request->expectsJson() || $request->isMethod('post')) {
            return response()->json([
                'success' => true,
                'schedules' => $allSchedules,
            ]);
        }

        return view('staff.schedules.index', compact(
            'allSchedules',
            'scheduleColumns',
            'pendingCount'
        ))->with('pendingCount', Order::where('status', 'pending')->count());
    }

    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['pending'], 'This schedule has already been processed.')) {
            return $error;
        }

        $order->update([
            'status' => 'confirmed',
            'staff_id' => Auth::id(),
            'updated_at' => now(),
        ]);

        return $this->successResponse('Schedule approved successfully! Customer has been notified.', $order);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['pending'], 'This schedule has already been processed.')) {
            return $error;
        }

        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->rejection_reason,
            'updated_at' => now(),
        ]);

        return $this->successResponse('Schedule rejected. Customer has been notified with the reason.', $order);
    }

    public function show($id)
    {
        $order = Order::with(['customer', 'service', 'approvedBy'])->findOrFail($id);

        return $this->successResponse(null, [
            'id' => $order->id,
            'customer_name' => $order->customer->name ?? 'N/A',
            'customer_email' => $order->customer->email ?? 'N/A',
            'customer_phone' => $order->customer->phone_number ?? 'N/A',
            'service_name' => $order->service->name ?? 'General Laundry',
            'dropoff_date' => $this->formatDate($order->dropoff_date),
            'dropoff_time' => $order->dropoff_time ?? 'N/A',
            'pickup_date' => $this->formatDate($order->pickup_date),
            'pickup_time' => $order->pickup_time ?? 'N/A',
            'approval_status' => ucfirst($order->approval_status),
            'status' => ucfirst(str_replace('_', ' ', $order->status)),
            'notes' => $order->notes ?? '',
            'rejection_reason' => $order->rejection_reason ?? '',
            'created_at' => $order->created_at->format('M j, Y g:i A'),
            'approved_at' => $this->formatDate($order->approved_at, 'M j, Y g:i A'),
            'approved_by_name' => $order->approvedBy->name ?? null,
        ], 'order');
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,in_progress,ready_for_pickup,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);

        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['in_progress', 'cancelled'],
            'in_progress' => ['ready_for_pickup', 'cancelled'],
            'ready_for_pickup' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => []
        ];

        if (!in_array($request->status, $validTransitions[$order->status] ?? [])) {
            return $this->errorResponse('Invalid status transition from ' . $order->status . ' to ' . $request->status);
        }

        $updates = ['status' => $request->status, 'updated_at' => now()];

        $statusUpdates = [
            'confirmed' => [
                'approval_status' => 'approved',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
                'staff_id' => Auth::id()
            ],
            'cancelled' => [
                'approval_status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]
        ];

        if (isset($statusUpdates[$request->status])) {
            $updates = array_merge($updates, $statusUpdates[$request->status]);
        }

        $order->update($updates);

        return $this->successResponse('Schedule status updated successfully to ' . ucfirst(str_replace('_', ' ', $request->status)), $order);
    }

    public function setPricing(Request $request, $id)
    {
        $request->validate([
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0'
        ]);

        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['confirmed'], 'Pricing can only be set when the schedule is Confirmed.')) {
            return $error;
        }

        // STAY in confirmed, don't auto-move to processing
        $order->update([
            'weight' => $request->weight,
            'total_price' => $request->price,
            'status' => 'confirmed',
            'updated_at' => now()
        ]);

        return $this->successResponse('Weight and price saved successfully.', $order);
    }

    public function cancel(Request $request, $id)
    {
        $request->validate([
            'cancellation_reason' => 'nullable|string|max:500'
        ]);

        $order = Order::findOrFail($id);

        if ($error = $this->cannotBeCancelled($order)) {
            return $error;
        }

        $order->update([
            'status' => 'cancelled',
            'cancellation_reason' => $request->cancellation_reason,
            'updated_at' => now()
        ]);

        return $this->successResponse('Schedule cancelled successfully.', $order);
    }

    public function startProcessing(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['confirmed'], 'Can only start processing on confirmed schedules.')) {
            return $error;
        }

        if (!$order->weight || !$order->total_price) {
            return $this->errorResponse('Please set weight and price before processing.');
        }

        $order->update([
            'status' => 'processing',
            'updated_at' => now()
        ]);

        return $this->successResponse('Schedule moved to processing.', $order);
    }

    public function markReadyForPickup(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['processing'], 'Can only mark as ready from processing status.')) {
            return $error;
        }

        $order->update([
            'status' => 'ready_for_pickup',
            'updated_at' => now()
        ]);

        return $this->successResponse('Schedule marked as ready for pickup.', $order);
    }

    public function markCompleted(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($error = $this->validateOrderStatus($order, ['ready_for_pickup'], 'Can only complete orders that are ready for pickup.')) {
            return $error;
        }

        $order->update([
            'status' => 'completed',
            'updated_at' => now()
        ]);

        return $this->successResponse('Schedule marked as completed.', $order);
    }

    public function getAllSchedules(Request $request)
    {
        $allSchedules = Order::with(['customer', 'service', 'approvedBy'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => $this->mapOrderToArray($order))
            ->toArray();

        return response()->json([
            'success' => true,
            'schedules' => $allSchedules,
        ]);
    }

    public function getStats()
    {
        $stats = [
            'pending_count' => Order::where('status', 'pending')->count(),
            'approved_today' => Order::where('status', 'confirmed')
                ->whereDate('updated_at', today())
                ->count(),
            'rejected_today' => Order::where('status', 'cancelled')
                ->whereDate('updated_at', today())
                ->count(),
            'total_processed_today' => Order::whereIn('status', ['processing', 'ready_for_pickup', 'completed'])
                ->whereDate('updated_at', today())
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'stats' => $stats
        ]);
    }

    private function mapOrderToArray(Order $order): array
    {
        return [
            'id' => $order->id,
            'customer_id' => $order->customer_id,
            'customer_name' => $order->customer->name ?? 'N/A',
            'customer_email' => $order->customer->email ?? 'N/A',
            'customer_phone' => $order->customer->phone_number ?? 'N/A',
            'dropoff' => $this->formatDate($order->dropoff_date),
            'pickup' => $this->formatDate($order->pickup_date),
            'status' => $order->status,
            'status_display' => $this->formatStatusDisplay($order->status),
            'weight' => $order->weight,
            'price' => $order->total_price !== null ? number_format((float)$order->total_price, 2) : null,
            'updated_at' => $order->updated_at ? $order->updated_at->format('M j, Y g:i A') : null,
            'notes' => $order->notes ?? '',
            'created_at' => $order->created_at->format('M j, Y g:i A'),
            'actions' => $this->getActionsForStatus($order->status, $order),
        ];
    }

    private function formatDate($date, string $format = 'M j, Y'): string
    {
        if (!$date) {
            return 'N/A';
        }

        return Carbon::parse($date)->format($format);
    }

    private function validateOrderStatus(Order $order, array $requiredStatuses, string $errorMessage)
    {
        if (!in_array($order->status, $requiredStatuses)) {
            return $this->errorResponse($errorMessage);
        }

        return null;
    }

    private function cannotBeCancelled(Order $order)
    {
        if ($order->status === 'pending') {
            return $this->errorResponse('Use Reject for pending schedules.');
        }

        if (in_array($order->status, ['completed', 'cancelled'])) {
            return $this->errorResponse('Cannot cancel a completed or already cancelled schedule.');
        }

        return null;
    }

    private function successResponse(string $message = null, $data = null, string $dataKey = 'order')
    {
        $response = ['success' => true];

        if ($message) {
            $response['message'] = $message;
        }

        if ($data !== null) {
            $response[$dataKey] = $data;
        }

        return response()->json($response);
    }

    private function errorResponse(string $message, int $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message
        ], $code);
    }

    private function getActionsForStatus($status, $order = null): array
    {
        $baseActions = [
            'pending' => [
                ['key' => 'approve', 'label' => 'Approve', 'icon' => 'fas fa-check', 'color' => 'green'],
                ['key' => 'reject', 'label' => 'Reject', 'icon' => 'fas fa-times', 'color' => 'red'],
                ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ],
            'processing' => [
                ['key' => 'mark_ready', 'label' => 'Ready for Pickup', 'icon' => 'fas fa-box', 'color' => 'green'],
                ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ],
            'ready_for_pickup' => [
                ['key' => 'mark_completed', 'label' => 'Mark Completed', 'icon' => 'fas fa-check-circle', 'color' => 'green'],
                ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ],
            'completed' => [
                ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ],
            'cancelled' => [
                ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
            ],
        ];

        if ($status === 'confirmed') {
            $hasPricing = $order && $order->weight && $order->total_price;
            return $hasPricing
                ? [
                    ['key' => 'start_processing', 'label' => 'Start Processing', 'icon' => 'fas fa-play', 'color' => 'green'],
                    ['key' => 'cancel', 'label' => 'Cancel', 'icon' => 'fas fa-ban', 'color' => 'red'],
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ]
                : [
                    ['key' => 'add_price', 'label' => 'Add Price/Weight', 'icon' => 'fas fa-balance-scale', 'color' => 'blue'],
                    ['key' => 'cancel', 'label' => 'Cancel', 'icon' => 'fas fa-ban', 'color' => 'red'],
                    ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
                ];
        }

        return $baseActions[$status] ?? [
            ['key' => 'view', 'label' => 'View', 'icon' => 'fas fa-eye', 'color' => 'blue'],
        ];
    }

    private function formatStatusDisplay($status): string
    {
        $statusMap = [
            'pending' => 'Pending',
            'confirmed' => 'Approved',
            'processing' => 'Processing',
            'ready_for_pickup' => 'Ready for Pickup',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled'
        ];

        return $statusMap[$status] ?? ucfirst(str_replace('_', ' ', $status));
    }
}
