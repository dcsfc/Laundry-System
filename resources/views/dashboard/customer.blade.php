@extends('layouts.sidebar')

@section('title', 'Customer Dashboard')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/status-badges.css') }}">
@endpush

@section('content')
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Customer Dashboard</h1>
                <p class="text-slate-400">Track your laundry schedules, manage pickups, and view your service history.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Schedules</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $totalOrders }}</p>
                        <p class="text-emerald-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ $monthlyOrderGrowth }} this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-shopping-bag text-white text-lg"></i>
                    </div>
                </div>
                <div id="ordersSparkline" class="h-12"></div>
            </div>

            <!-- Completed Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-teal-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Completed Schedules</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $completedOrders }}</p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-check mr-1 text-xs"></i>{{ $completionRate }}% completion rate
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <div id="completedSparkline" class="h-12"></div>
            </div>

            <!-- Pending Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">In Progress</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $pendingOrders }}</p>
                        <p class="text-emerald-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-clock mr-1 text-xs"></i>Processing now
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-spinner text-white text-lg"></i>
                    </div>
                </div>
                <div id="pendingSparkline" class="h-12"></div>
            </div>

            <!-- Total Spent Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-teal-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Spent</p>
                        <p class="text-slate-50 text-2xl font-bold">₱{{ number_format($totalSpent, 2) }}</p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ $monthlyGrowth }}% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                    </div>
                </div>
                <div id="spentSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Announcements Section -->
        <div class="mb-8">
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <!-- Header -->
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-slate-50">Important Announcements</h3>
                    </div>
                    @if($announcements && count($announcements) > 0)
                        <div class="flex items-center gap-2">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-sm text-emerald-400 font-medium">{{ count($announcements) }} announcement{{ count($announcements) !== 1 ? 's' : '' }}</span>
                        </div>
                    @endif
                </div>
                
                <!-- Announcements List -->
                @if($announcements && count($announcements) > 0)
                    <div class="space-y-3">
                        @foreach($announcements->take(3) as $announcement)
                            <div class="group bg-slate-700/30 border border-slate-600/50 rounded-lg p-4 hover:bg-slate-700/50 hover:border-slate-600/70 hover:-translate-y-0.5 transition-all duration-200 cursor-pointer" 
                                 onclick="openAnnouncementModal({{ $announcement->id }})">
                                <div class="flex items-start gap-3">
                                    <!-- Status Indicator -->
                                    <div class="flex-shrink-0 mt-1">
                                        @if($announcement->is_pinned)
                                            <div class="w-2 h-2 bg-amber-400 rounded-full"></div>
                                        @else
                                            <div class="w-2 h-2 bg-emerald-400 rounded-full opacity-60"></div>
                                        @endif
                                    </div>
                                    
                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Title and Badges -->
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <h4 class="text-slate-50 font-semibold text-sm leading-tight group-hover:text-emerald-400 transition-colors">
                                                {{ $announcement->title }}
                                            </h4>
                                            <div class="flex items-center gap-1 flex-shrink-0">
                                                @if($announcement->is_pinned)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-amber-500/20 text-amber-400 border border-amber-500/30">
                                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        Pinned
                                                    </span>
                                                @endif
                                                @if($announcement->type)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $announcement->type_badge_class }}">
                                                        {{ ucfirst($announcement->type) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Message Preview -->
                                        <p class="text-slate-400 text-xs leading-relaxed mb-3 line-clamp-2">
                                            {{ Str::limit($announcement->message, 120) }}
                                        </p>
                                        
                                        <!-- Meta Information -->
                                        <div class="flex items-center gap-3 text-xs text-slate-500">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                {{ $announcement->createdBy->name ?? 'System' }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <!-- Read More Indicator -->
                                    @if(strlen($announcement->message) > 120)
                                        <div class="flex-shrink-0 mt-1">
                                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-400 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    <!-- Footer -->
                    <div class="mt-6 pt-4 border-t border-slate-700">
                        <a href="{{ route('customer.announcements') }}" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 text-sm font-medium transition-colors group">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            View all announcements
                        </a>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="text-center py-8">
                        <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                            </svg>
                        </div>
                        <h4 class="text-slate-300 font-medium mb-2">No announcements yet</h4>
                        <p class="text-slate-500 text-sm">Check back soon for important updates and news.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Order History Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Order History</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400">
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>All time</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Order trend</span>
                        </div>
                    </div>
                </div>
                <div id="orderHistoryChart" class="h-72"></div>
            </div>

            <!-- Service Usage Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Service Usage</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-emerald-500 hover:bg-emerald-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-teal-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Service breakdown</span>
                        </div>
                    </div>
                </div>
                <div id="serviceUsageChart" class="h-72"></div>
            </div>
        </div>

        <!-- Activity Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Schedules</h3>
                    <a href="{{ route('customer.schedules.index') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentOrders as $order)
                        @php
                            // Use consistent status colors - simplified approach
                            $statusColors = \App\Helpers\StatusHelper::getStatusColor($order['status']);
                            $statusColor = $statusColors['badge'];
                            $dotColor = $statusColors['dot'];
                            
                            // Set border color based on status
                            $borderColor = 'border-slate-400'; // Default
                            if (str_contains($statusColor, 'yellow')) {
                                $borderColor = 'border-yellow-400';
                            } elseif (str_contains($statusColor, 'green')) {
                                $borderColor = 'border-green-400';
                            } elseif (str_contains($statusColor, 'blue')) {
                                $borderColor = 'border-blue-400';
                            } elseif (str_contains($statusColor, 'purple')) {
                                $borderColor = 'border-purple-400';
                            } elseif (str_contains($statusColor, 'red')) {
                                $borderColor = 'border-red-400';
                            } elseif (str_contains($statusColor, 'gray')) {
                                $borderColor = 'border-gray-400';
                            }
                        @endphp
                        <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 {{ $borderColor }}">
                            <div class="w-2 h-2 {{ $dotColor }} rounded-full mt-2"></div>
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">Order #{{ $order['id'] }} - {{ $order['status'] }}</h4>
                                <p class="text-slate-400 text-sm">{{ $order['service_type'] }} - ₱{{ $order['total_price'] }}</p>
                                <p class="text-slate-500 text-xs mt-1">{{ $order['created_at'] }}</p>
                            </div>
                            <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border {{ $statusColor }}">{{ $order['status'] }}</span>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-shopping-bag text-4xl text-slate-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-slate-300 mb-2">No recent orders</h3>
                            <p class="text-slate-400">You haven't placed any orders yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Upcoming Schedules -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Upcoming Schedules</h3>
                    <button class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        Schedule new
                    </button>
                </div>
                <div class="space-y-4">
                    @forelse($upcomingSchedules as $schedule)
                        @php
                            $statusColors = [
                                'Scheduled' => ['bg' => 'bg-emerald-500/20', 'icon' => 'fas fa-calendar-plus text-emerald-500'],
                                'Confirmed' => ['bg' => 'bg-teal-500/20', 'icon' => 'fas fa-calendar-check text-teal-500'],
                                'Pending' => ['bg' => 'bg-yellow-500/20', 'icon' => 'fas fa-clock text-yellow-500']
                            ];
                            $statusColor = $statusColors[$schedule['status']] ?? ['bg' => 'bg-slate-500/20', 'icon' => 'fas fa-calendar text-slate-500'];
                        @endphp
                        <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                            <div class="w-8 h-8 {{ $statusColor['bg'] }} rounded-full flex items-center justify-center">
                                <i class="{{ $statusColor['icon'] }} text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">{{ $schedule['service_type'] }} - {{ $schedule['status'] }}</h4>
                                <p class="text-slate-400 text-sm">Drop-off: {{ $schedule['dropoff_date'] }} at {{ $schedule['dropoff_time'] }}</p>
                                <p class="text-slate-500 text-xs mt-1">Pickup: {{ $schedule['pickup_date'] }} at {{ $schedule['pickup_time'] }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="fas fa-calendar-alt text-4xl text-slate-500 mb-4"></i>
                            <h3 class="text-lg font-medium text-slate-300 mb-2">No upcoming schedules</h3>
                            <p class="text-slate-400">You don't have any scheduled appointments.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-semibold text-slate-50 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">New Order</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Schedule laundry service</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-teal-500 hover:border-teal-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar text-teal-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Schedule Pickup</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Arrange pickup time</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-history text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Order History</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">View past orders</p>
                    </div>
                </button>

            </div>
        </div>
    </div>

    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
    <script>
        // Sparkline configurations for KPI cards
        const sparklineConfig = {
            chart: {
                type: 'area',
                sparkline: {
                    enabled: true
                },
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 600
                }
            },
            stroke: {
                curve: 'smooth',
                width: 1.5
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.2,
                    opacityTo: 0.05,
                    stops: [0, 100]
                }
            },
            tooltip: {
                enabled: false
            },
            grid: {
                show: false
            },
            xaxis: {
                labels: {
                    show: false
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                labels: {
                    show: false
                }
            }
        };

        // Order History Chart with enhanced animations
        const orderHistoryOptions = {
            series: [{
                name: 'Orders',
                data: @json($orderHistoryData['data'])
            }],
            chart: {
                type: 'line',
                height: 300,
                toolbar: {
                    show: false
                },
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800,
                    animateGradually: {
                        enabled: true,
                        delay: 150
                    }
                }
            },
            colors: ['#10B981'],
            stroke: {
                curve: 'smooth',
                width: 3,
                lineCap: 'round'
            },
            fill: {
                type: 'gradient',
                gradient: {
                    shadeIntensity: 1,
                    opacityFrom: 0.4,
                    opacityTo: 0.1,
                    stops: [0, 100]
                }
            },
            grid: {
                borderColor: 'rgba(148,163,184,0.2)',
                strokeDashArray: 4,
                xaxis: {
                    lines: {
                        show: true
                    }
                },
                yaxis: {
                    lines: {
                        show: true
                    }
                }
            },
            xaxis: {
                categories: @json($orderHistoryData['categories']),
                axisBorder: {
                    color: '#334155'
                },
                axisTicks: {
                    color: '#334155'
                },
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
            },
            yaxis: {
                labels: {
                    style: {
                        colors: '#94A3B8',
                        fontFamily: 'Inter, sans-serif'
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function (val) {
                        return val + " orders"
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#10B981'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Service Usage Chart with enhanced styling
        const serviceUsageOptions = {
            series: @json($serviceUsageData['data']),
            chart: {
                type: 'donut',
                height: 300,
                background: 'transparent',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 800
                }
            },
            colors: ['#10B981', '#14B8A6', '#059669', '#047857', '#065F46', '#064E3B'],
            labels: @json($serviceUsageData['labels']),
            legend: {
                position: 'bottom',
                fontSize: '12px',
                fontFamily: 'Inter, sans-serif',
                labels: {
                    colors: '#94A3B8'
                },
                markers: {
                    width: 8,
                    height: 8,
                    radius: 2
                }
            },
            plotOptions: {
                pie: {
                    donut: {
                        size: '70%',
                        labels: {
                            show: true,
                            total: {
                                show: true,
                                label: 'Total Orders',
                                color: '#F8FAFC',
                                fontSize: '14px',
                                fontWeight: 600,
                                fontFamily: 'Inter, sans-serif',
                                formatter: function (w) {
                                    return '{{ $serviceUsageData["total"] }}'
                                }
                            }
                        }
                    }
                }
            },
            tooltip: {
                theme: 'dark',
                style: {
                    fontSize: '12px',
                    fontFamily: 'Inter, sans-serif'
                },
                y: {
                    formatter: function (val, { seriesIndex, w }) {
                        const total = w.globals.seriesTotals.reduce((a, b) => a + b, 0);
                        const percentage = ((val / total) * 100).toFixed(1);
                        return val + ' orders (' + percentage + '%)';
                    }
                }
            },
            dataLabels: {
                enabled: false
            }
        };

        // Initialize charts when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Main charts
            const orderHistoryChart = new ApexCharts(document.querySelector("#orderHistoryChart"), orderHistoryOptions);
            orderHistoryChart.render();

            const serviceUsageChart = new ApexCharts(document.querySelector("#serviceUsageChart"), serviceUsageOptions);
            serviceUsageChart.render();

            // Sparkline charts for KPI cards
            const ordersSparkline = new ApexCharts(document.querySelector("#ordersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json($sparklineData['orders'])
                }],
                colors: ['#10B981']
            });
            ordersSparkline.render();

            const completedSparkline = new ApexCharts(document.querySelector("#completedSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json($sparklineData['completed'])
                }],
                colors: ['#14B8A6']
            });
            completedSparkline.render();

            const pendingSparkline = new ApexCharts(document.querySelector("#pendingSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json($sparklineData['pending'])
                }],
                colors: ['#10B981']
            });
            pendingSparkline.render();

            const spentSparkline = new ApexCharts(document.querySelector("#spentSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json($sparklineData['spent'])
                }],
                colors: ['#14B8A6']
            });
            spentSparkline.render();

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });

        // Announcement modal functionality
        function openAnnouncementModal(announcementId) {
            // Option 1: Redirect to announcements page with anchor (current implementation)
            window.location.href = `{{ route('customer.announcements') }}#announcement-${announcementId}`;
            
            // Option 2: Open modal (uncomment to use instead of redirect)
            // fetch(`/api/announcements/${announcementId}`)
            //     .then(response => response.json())
            //     .then(data => {
            //         showAnnouncementModal(data);
            //     })
            //     .catch(error => {
            //         console.error('Error fetching announcement:', error);
            //         // Fallback to redirect
            //         window.location.href = `{{ route('customer.announcements') }}#announcement-${announcementId}`;
            //     });
        }
    </script>

    <!-- Custom CSS for enhanced animations and micro-interactions -->
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
        
        * {
            font-family: 'Inter', sans-serif;
        }

        /* Enhanced hover effects for cards */
        .group:hover .group-hover\:scale-110 {
            transform: scale(1.1);
        }

        /* Smooth transitions for all interactive elements */
        .transition-all {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        ::-webkit-scrollbar-track {
            background: #0F172A;
        }

        ::-webkit-scrollbar-thumb {
            background: #334155;
            border-radius: 3px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #475569;
        }

        /* Focus states for accessibility */
        input:focus, button:focus, select:focus {
            outline: 2px solid #10B981;
            outline-offset: 2px;
        }

        /* Loading animation for charts */
        .chart-loading {
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: .5;
            }
        }

        /* Enhanced table row hover effects */
        .group:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        /* Status dot animations */
        .status-dot {
            animation: pulse-dot 2s infinite;
        }

        @keyframes pulse-dot {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        /* Line clamp utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Announcement card hover effects */
        .announcement-card {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .announcement-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
    </style>
@endsection
