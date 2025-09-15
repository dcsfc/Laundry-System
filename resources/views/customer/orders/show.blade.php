@extends('layouts.sidebar')

@section('title', 'Order Details')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">Order Details</h1>
                    <p class="text-slate-400">View detailed information about your order.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('customer.orders.index') }}" 
                       class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Orders
                    </a>
                    @if($order['status'] !== 'Completed' && $order['status'] !== 'Canceled')
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Order
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Order Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Status Card -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Order Status</h3>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-2xl font-bold text-slate-50">Order #{{ $order['id'] }}</div>
                            <div class="text-slate-400">Placed on {{ $order['created_at'] }}</div>
                        </div>
                        @php
                            $statusColors = [
                                'Scheduled' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'In Progress' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                'Completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'Canceled' => 'bg-red-500/20 text-red-400 border-red-500/30'
                            ];
                            $statusColor = $statusColors[$order['status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $statusColor }}">
                            {{ $order['status'] }}
                        </span>
                    </div>
                    
                    <!-- Progress Timeline -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-slate-200">Order Placed</div>
                                <div class="text-xs text-slate-400">{{ $order['created_at'] }}</div>
                            </div>
                        </div>
                        
                        @if($order['status'] === 'In Progress' || $order['status'] === 'Completed')
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-200">Processing Started</div>
                                    <div class="text-xs text-slate-400">In Progress</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-slate-400 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-400">Processing</div>
                                    <div class="text-xs text-slate-500">Pending</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($order['status'] === 'Completed')
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-200">Completed</div>
                                    <div class="text-xs text-slate-400">Ready for pickup</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-slate-400 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-400">Completed</div>
                                    <div class="text-xs text-slate-500">Pending</div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Service Details -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Service Details</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-700">
                            <span class="text-slate-400">Service Type</span>
                            <span class="text-slate-200 font-medium">{{ $order['service_type'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-700">
                            <span class="text-slate-400">Items Count</span>
                            <span class="text-slate-200 font-medium">{{ $order['items_count'] ?? 'N/A' }} items</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-700">
                            <span class="text-slate-400">Total Price</span>
                            <span class="text-slate-200 font-medium text-lg">₱{{ number_format($order['total_price'], 2) }}</span>
                        </div>
                        @if(isset($order['notes']) && $order['notes'])
                            <div class="py-2">
                                <span class="text-slate-400 block mb-2">Special Instructions</span>
                                <span class="text-slate-200">{{ $order['notes'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Schedule Information -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Schedule</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-plus text-emerald-400"></i>
                            </div>
                            <div>
                                <div class="text-sm text-slate-400">Drop-off Date</div>
                                <div class="text-slate-200 font-medium">{{ $order['dropoff_date'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-teal-400"></i>
                            </div>
                            <div>
                                <div class="text-sm text-slate-400">Pickup Date</div>
                                <div class="text-slate-200 font-medium">{{ $order['pickup_date'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Payment</h3>
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Status</span>
                            @php
                                $paymentColors = [
                                    'Paid' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                    'Unpaid' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                ];
                                $paymentColor = $paymentColors[$order['payment_status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                            @endphp
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium border {{ $paymentColor }}">
                                {{ $order['payment_status'] }}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-slate-400">Amount</span>
                            <span class="text-slate-200 font-medium">₱{{ number_format($order['total_price'], 2) }}</span>
                        </div>
                        @if($order['payment_status'] === 'Unpaid')
                            <button class="w-full bg-emerald-500 hover:bg-emerald-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                <i class="fas fa-credit-card mr-2"></i>Pay Now
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Need Help?</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <span class="text-slate-300">+63 123 456 7890</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-envelope text-emerald-400"></i>
                            <span class="text-slate-300">support@laundry.com</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-clock text-emerald-400"></i>
                            <span class="text-slate-300">Mon-Fri 8AM-6PM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
