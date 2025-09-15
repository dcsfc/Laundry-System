@extends('layouts.sidebar')

@section('title', 'My Schedules')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">My Schedules</h1>
                    <p class="text-slate-400">Manage your laundry drop-off and pickup appointments.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('customer.schedules.create') }}" 
                       class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-plus mr-2"></i>Schedule Laundry
                    </a>
                    <button class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-calendar-alt mr-2"></i>Calendar View
                    </button>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-300 mb-2">Search Schedules</label>
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
                        <option value="confirmed">Confirmed</option>
                        <option value="pending">Pending</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>

                <!-- Date Filter -->
                <div>
                    <label class="block text-sm font-medium text-slate-300 mb-2">Date Range</label>
                    <select class="w-full bg-slate-700 border border-slate-600 rounded-lg px-4 py-2 text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                        <option value="">All Dates</option>
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Schedules Table -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-700">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Schedule ID</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Service</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Drop-off</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Pickup</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Staff</th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-slate-300 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        @forelse($schedules as $schedule)
                            <tr class="hover:bg-slate-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-200">#{{ $schedule['id'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $schedule['created_at'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $schedule['service_type'] }}</div>
                                    @if($schedule['notes'])
                                        <div class="text-xs text-slate-400 mt-1">{{ Str::limit($schedule['notes'], 30) }}</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $schedule['dropoff_date'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $schedule['dropoff_time'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $schedule['pickup_date'] }}</div>
                                    <div class="text-xs text-slate-400">{{ $schedule['pickup_time'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'Scheduled' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                            'Confirmed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                            'Pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                            'Cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30'
                                        ];
                                        $statusColor = $statusColors[$schedule['status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusColor }}">
                                        {{ $schedule['status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-slate-200">{{ $schedule['staff_assigned'] }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('customer.schedules.show', $schedule['id']) }}" 
                                           class="text-emerald-400 hover:text-emerald-300 transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($schedule['status'] !== 'Cancelled')
                                            <button class="text-blue-400 hover:text-blue-300 transition-colors" title="Edit Schedule">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        @endif
                                        @if($schedule['status'] === 'Scheduled' || $schedule['status'] === 'Pending')
                                            <button class="text-red-400 hover:text-red-300 transition-colors" title="Cancel Schedule">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i class="fas fa-calendar-alt text-4xl text-slate-500 mb-4"></i>
                                        <h3 class="text-lg font-medium text-slate-300 mb-2">No schedules found</h3>
                                        <p class="text-slate-400 mb-4">You haven't scheduled any laundry appointments yet.</p>
                                        <a href="{{ route('customer.schedules.create') }}" 
                                           class="bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                                            <i class="fas fa-plus mr-2"></i>Schedule Your First Appointment
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if($schedules->count() > 0)
            <div class="mt-6 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    Showing {{ $schedules->count() }} of {{ $schedules->count() }} schedules
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

        <!-- Quick Stats -->
        @if($schedules->count() > 0)
            <div class="mt-8 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-calendar-check text-emerald-400"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-200">{{ $schedules->where('status', 'Confirmed')->count() }}</div>
                            <div class="text-sm text-slate-400">Confirmed</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-blue-400"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-200">{{ $schedules->where('status', 'Scheduled')->count() }}</div>
                            <div class="text-sm text-slate-400">Scheduled</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-yellow-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-hourglass-half text-yellow-400"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-200">{{ $schedules->where('status', 'Pending')->count() }}</div>
                            <div class="text-sm text-slate-400">Pending</div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-4">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-red-500/20 rounded-lg flex items-center justify-center mr-3">
                            <i class="fas fa-times text-red-400"></i>
                        </div>
                        <div>
                            <div class="text-2xl font-bold text-slate-200">{{ $schedules->where('status', 'Cancelled')->count() }}</div>
                            <div class="text-sm text-slate-400">Cancelled</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
