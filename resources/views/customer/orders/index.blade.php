@extends('layouts.sidebar')

@section('title', 'Order History')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">Order History</h1>
                    <p class="text-slate-400">Track your laundry orders and their current status.</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>New Order
                    </button>
                    <button class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Search Orders</label>
                    <div class="relative">
                        <input type="text" 
                               class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 pl-10 text-slate-200 placeholder-slate-400 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400"
                               placeholder="Search by service type, status...">
                        <i class="fas fa-search absolute left-3 top-3 text-slate-400"></i>
                    </div>
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Status</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                        <option value="">All Status</option>
                        <option value="scheduled">Scheduled</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="canceled">Canceled</option>
                    </select>
                </div>

                <!-- Payment Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Payment</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                        <option value="">All Payments</option>
                        <option value="paid">Paid</option>
                        <option value="unpaid">Unpaid</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Order ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Drop-off</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Pickup</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Payment</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-200">#{{ $order['id'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $order['created_at'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $order['service_type'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Scheduled' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                            'In Progress' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                            'Completed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'Canceled' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                        ];
                                        $statusColor = $statusColors[$order['status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                                        {{ $order['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-200">₱{{ number_format($order['total_price'], 2) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $order['dropoff_date'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $order['pickup_date'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $paymentColors = [
                                            'Paid' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'Unpaid' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                        ];
                                        $paymentColor = $paymentColors[$order['payment_status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $paymentColor }}">
                                        {{ $order['payment_status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('customer.orders.show', $order['id']) }}" 
                                           class="text-emerald-400 hover:text-emerald-300 transition-colors">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($order['status'] !== 'Completed' && $order['status'] !== 'Canceled')
                                            <button class="text-blue-400 hover:text-blue-300 transition-colors">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if($order['payment_status'] === 'Unpaid')
                                            <button class="text-yellow-400 hover:text-yellow-300 transition-colors">
                                                <i class="fas fa-credit-card"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-shopping-bag text-4xl text-slate-500 mb-4"></i>
                                        <h3 class="text-lg font-medium text-slate-300 mb-2">No orders found</h3>
                                        <p class="text-slate-400 mb-4">You haven't placed any orders yet.</p>
                                        <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Place Your First Order
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($orders->count() > 0)
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    Showing {{ $orders->count() }} of {{ $orders->count() }} orders
                </div>
                <div class="flex items-center gap-2">
                    <button class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg transition-colors disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <span class="px-3 py-2 bg-emerald-500 text-white rounded-lg">1</span>
                    <button class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg transition-colors disabled:opacity-50" disabled>
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            </div>
        @endif
    </div>
@endsection
