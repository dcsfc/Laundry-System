@extends('layouts.sidebar')

@section('title', 'Customer Dashboard')

@section('content')
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Customer Dashboard</h1>
                <p class="text-slate-400">Track your laundry orders, schedule pickups, and view your transaction history.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Orders Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Orders</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $totalOrders ?? 12 }}</p>
                        <p class="text-emerald-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+3 this month
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
                        <p class="text-slate-400 text-sm font-medium mb-1">Completed</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $completedOrders ?? 8 }}</p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-check mr-1 text-xs"></i>67% completion rate
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
                        <p class="text-slate-50 text-2xl font-bold">{{ $pendingOrders ?? 3 }}</p>
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
                        <p class="text-slate-50 text-2xl font-bold">₱{{ number_format($totalSpent ?? 1250, 2) }}</p>
                        <p class="text-teal-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+15% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                    </div>
                </div>
                <div id="spentSparkline" class="h-12"></div>
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
                    <h3 class="text-xl font-semibold text-slate-50">Recent Orders</h3>
                    <a href="{{ route('customer.orders.index') }}" class="text-emerald-400 hover:text-emerald-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-emerald-500">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1234 - Completed</h4>
                            <p class="text-slate-400 text-sm">Wash & Fold Service - 5 items</p>
                            <p class="text-slate-500 text-xs mt-1">Picked up 2 hours ago</p>
                        </div>
                        <span class="px-2 py-1 bg-emerald-500/20 text-emerald-500 text-xs rounded-full">Completed</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-teal-400">
                        <div class="w-2 h-2 bg-teal-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1235 - In Progress</h4>
                            <p class="text-slate-400 text-sm">Dry Cleaning - 3 items</p>
                            <p class="text-slate-500 text-xs mt-1">Expected completion: Tomorrow</p>
                        </div>
                        <span class="px-2 py-1 bg-teal-500/20 text-teal-400 text-xs rounded-full">Processing</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-emerald-400">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1236 - Scheduled</h4>
                            <p class="text-slate-400 text-sm">Wash & Fold Service - 8 items</p>
                            <p class="text-slate-500 text-xs mt-1">Drop-off: Tomorrow 9 AM</p>
                        </div>
                        <span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 text-xs rounded-full">Scheduled</span>
                    </div>
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
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-plus text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Drop-off Scheduled</h4>
                            <p class="text-slate-400 text-sm">Tomorrow, 9:00 AM - 10:00 AM</p>
                            <p class="text-slate-500 text-xs mt-1">Order #1236</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-teal-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-calendar-check text-teal-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Pickup Available</h4>
                            <p class="text-slate-400 text-sm">Today, 2:00 PM - 6:00 PM</p>
                            <p class="text-slate-500 text-xs mt-1">Order #1235</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-bell text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Reminder</h4>
                            <p class="text-slate-400 text-sm">Order #1234 ready for pickup</p>
                            <p class="text-slate-500 text-xs mt-1">2 hours ago</p>
                        </div>
                    </div>
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
                data: [2, 3, 1, 4, 2, 3]
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
                categories: ['Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Jan'],
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
            series: [5, 3, 2, 2],
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
            colors: ['#10B981', '#14B8A6', '#059669', '#047857'],
            labels: ['Wash & Fold', 'Dry Cleaning', 'Ironing', 'Express'],
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
                                    return '12'
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
                    data: [8, 9, 10, 11, 11, 12]
                }],
                colors: ['#10B981']
            });
            ordersSparkline.render();

            const completedSparkline = new ApexCharts(document.querySelector("#completedSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [5, 6, 6, 7, 7, 8]
                }],
                colors: ['#14B8A6']
            });
            completedSparkline.render();

            const pendingSparkline = new ApexCharts(document.querySelector("#pendingSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [2, 3, 2, 4, 3, 3]
                }],
                colors: ['#10B981']
            });
            pendingSparkline.render();

            const spentSparkline = new ApexCharts(document.querySelector("#spentSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [1000, 1050, 1100, 1150, 1200, 1250]
                }],
                colors: ['#14B8A6']
            });
            spentSparkline.render();

            // Add smooth scroll behavior
            document.documentElement.style.scrollBehavior = 'smooth';
        });
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
    </style>
@endsection
