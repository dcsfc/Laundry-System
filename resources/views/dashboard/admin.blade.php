@extends('layouts.sidebar')

@section('title', 'Admin Dashboard')

@section('content')
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Admin Dashboard</h1>
                <p class="text-slate-400">Manage operations, staff, schedules, inventory, and laundry services.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-sky-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Users</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $totalUsers ?? 1247 }}</p>
                        <p class="text-sky-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+12% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div id="usersSparkline" class="h-12"></div>
            </div>

            <!-- Total Revenue Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-cyan-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Revenue</p>
                        <p class="text-slate-50 text-2xl font-bold">₱{{ number_format($totalRevenue ?? 12500) }}</p>
                        <p class="text-cyan-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+18% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-dollar-sign text-white text-lg"></i>
                    </div>
                </div>
                <div id="revenueSparkline" class="h-12"></div>
            </div>

            <!-- Completed Laundry Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-sky-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Completed Laundry</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $completedLaundry ?? 320 }}</p>
                        <p class="text-sky-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+25 this week
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <div id="completedSparkline" class="h-12"></div>
            </div>

            <!-- Pending Laundry Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-cyan-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Pending Laundry</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $pendingLaundry ?? 45 }}</p>
                        <p class="text-cyan-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-down mr-1 text-xs"></i>-8 from yesterday
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-600 to-cyan-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div id="pendingSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Revenue Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Revenue Trend</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>All time</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Revenue trend</span>
                        </div>
                    </div>
                </div>
                <div id="revenueChart" class="h-72"></div>
            </div>

            <!-- Laundry Status Distribution -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Laundry Status</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-amber-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Current status</span>
                        </div>
                    </div>
                </div>
                <div id="laundryStatusChart" class="h-72"></div>
            </div>
        </div>

        <!-- Operations Management Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Orders -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Orders</h3>
                    <a href="#" class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-emerald-500">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1234 - Completed</h4>
                            <p class="text-slate-400 text-sm">Maria Santos - 5 items, ₱2,250</p>
                            <p class="text-slate-500 text-xs mt-1">2 hours ago</p>
                        </div>
                        <span class="px-2 py-1 bg-emerald-500/20 text-emerald-500 text-xs rounded-full">Completed</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-amber-400">
                        <div class="w-2 h-2 bg-amber-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1235 - In Progress</h4>
                            <p class="text-slate-400 text-sm">Jose Garcia - 3 items, ₱1,425</p>
                            <p class="text-slate-500 text-xs mt-1">4 hours ago</p>
                        </div>
                        <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-xs rounded-full">In Progress</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-sky-400">
                        <div class="w-2 h-2 bg-sky-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1236 - Scheduled</h4>
                            <p class="text-slate-400 text-sm">Ana Cruz - 7 items, ₱3,100</p>
                            <p class="text-slate-500 text-xs mt-1">6 hours ago</p>
                        </div>
                        <span class="px-2 py-1 bg-sky-500/20 text-sky-400 text-xs rounded-full">Scheduled</span>
                    </div>
                </div>
            </div>

            <!-- Staff Activity -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Staff Activity</h3>
                    <button class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Maria Santos</h4>
                            <p class="text-slate-400 text-sm">Completed 5 orders today</p>
                            <p class="text-slate-500 text-xs mt-1">Active now</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-indigo-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-indigo-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Jose Garcia</h4>
                            <p class="text-slate-400 text-sm">Processing 3 orders</p>
                            <p class="text-slate-500 text-xs mt-1">30 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-tie text-amber-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Ana Cruz</h4>
                            <p class="text-slate-400 text-sm">On break - 2 orders pending</p>
                            <p class="text-slate-500 text-xs mt-1">1 hour ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-semibold text-slate-50 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('admin.users.create') }}" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-sky-500 hover:border-sky-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-sky-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-plus text-sky-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Add User</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Create new staff or customer</p>
                    </div>
                </a>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-alt text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Manage Schedules</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">View & edit schedules</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-amber-500 hover:border-amber-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes text-amber-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Inventory</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Manage supplies</p>
                    </div>
                </button>

                <a href="{{ route('admin.users.index') }}" class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-indigo-500 hover:border-indigo-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-indigo-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">User Management</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Manage staff and customers</p>
                    </div>
                </a>
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

        // Revenue Chart with enhanced animations
        const revenueOptions = {
            series: [{
                name: 'Revenue',
                data: [42500, 46000, 49000, 52500, 56000, 62500]
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
            colors: ['#22C55E'],
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
                    },
                    formatter: function (val) {
                        return '₱' + val.toLocaleString()
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
                        return '₱' + val.toLocaleString()
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#22C55E'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Laundry Status Chart with enhanced styling
        const laundryStatusOptions = {
            series: [320, 45, 12, 8],
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
            colors: ['#22C55E', '#FBBF24', '#38BDF8', '#EF4444'],
            labels: ['Completed', 'Pending', 'In Progress', 'Cancelled'],
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
                                    return '385'
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
                        return val.toLocaleString() + ' orders (' + percentage + '%)';
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
            const revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueOptions);
            revenueChart.render();

            const laundryStatusChart = new ApexCharts(document.querySelector("#laundryStatusChart"), laundryStatusOptions);
            laundryStatusChart.render();

            // Sparkline charts for KPI cards
            const usersSparkline = new ApexCharts(document.querySelector("#usersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [1200, 1210, 1220, 1230, 1240, 1247]
                }],
                colors: ['#38BDF8']
            });
            usersSparkline.render();

            const revenueSparkline = new ApexCharts(document.querySelector("#revenueSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [50000, 52500, 55000, 57500, 60000, 62500]
                }],
                colors: ['#22C55E']
            });
            revenueSparkline.render();

            const completedSparkline = new ApexCharts(document.querySelector("#completedSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [280, 290, 300, 310, 315, 320]
                }],
                colors: ['#FBBF24']
            });
            completedSparkline.render();

            const pendingSparkline = new ApexCharts(document.querySelector("#pendingSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [60, 55, 50, 48, 47, 45]
                }],
                colors: ['#EF4444']
            });
            pendingSparkline.render();

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
            outline: 2px solid #38BDF8;
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
