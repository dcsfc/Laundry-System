@extends('layouts.sidebar')

@section('title', 'Staff Dashboard')

@section('content')
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Staff Dashboard</h1>
                <p class="text-slate-400">Manage your daily tasks, inventory, schedules, and track your performance.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Weekly Revenue Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-emerald-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Weekly Revenue</p>
                        <p class="text-slate-50 text-2xl font-bold">₱{{ number_format($weeklyRevenue ?? 14250) }}</p>
                        <p class="text-emerald-500 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+15% from last week
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 via-teal-400 to-cyan-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-peso-sign text-white text-lg"></i>
                    </div>
                </div>
                <div id="revenueSparkline" class="h-12"></div>
            </div>

            <!-- Pending Laundry Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-amber-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Pending Laundry</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $pendingLaundry ?? 12 }}</p>
                        <p class="text-amber-500 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-clock mr-1 text-xs"></i>3 due today
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-amber-500 via-orange-400 to-red-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-clock text-white text-lg"></i>
                    </div>
                </div>
                <div id="pendingSparkline" class="h-12"></div>
            </div>

            <!-- Completed Laundry Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-sky-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Completed Today</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $completedToday ?? 8 }}</p>
                        <p class="text-emerald-500 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+2 from yesterday
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-sky-500 via-cyan-400 to-indigo-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                </div>
                <div id="completedSparkline" class="h-12"></div>
            </div>

            <!-- Assigned Schedules Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-indigo-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">My Schedules</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ $assignedSchedules ?? 5 }}</p>
                        <p class="text-indigo-500 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-calendar mr-1 text-xs"></i>2 this afternoon
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 via-purple-400 to-pink-500 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-alt text-white text-lg"></i>
                    </div>
                </div>
                <div id="schedulesSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Weekly Performance Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Weekly Performance</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-sky-400 focus:border-sky-400">
                            <option>This week</option>
                            <option>Last week</option>
                            <option>This month</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-sky-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Orders completed</span>
                        </div>
                    </div>
                </div>
                <div id="performanceChart" class="h-72"></div>
            </div>

            <!-- Task Distribution -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Task Distribution</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-sky-500 hover:bg-sky-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-emerald-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Current tasks</span>
                        </div>
                    </div>
                </div>
                <div id="taskDistributionChart" class="h-72"></div>
            </div>
        </div>

        <!-- Staff Management Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Today's Tasks -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Today's Tasks</h3>
                    <a href="#" class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-emerald-500">
                        <div class="w-2 h-2 bg-emerald-500 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1234 - Complete</h4>
                            <p class="text-slate-400 text-sm">Maria Santos - 5 items, ₱2,250</p>
                            <p class="text-slate-500 text-xs mt-1">Due: 2:00 PM</p>
                        </div>
                        <span class="px-2 py-1 bg-emerald-500/20 text-emerald-500 text-xs rounded-full">Completed</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-amber-400">
                        <div class="w-2 h-2 bg-amber-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1235 - In Progress</h4>
                            <p class="text-slate-400 text-sm">Jose Garcia - 3 items, ₱1,425</p>
                            <p class="text-slate-500 text-xs mt-1">Due: 4:30 PM</p>
                        </div>
                        <span class="px-2 py-1 bg-amber-500/20 text-amber-400 text-xs rounded-full">In Progress</span>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-sky-400">
                        <div class="w-2 h-2 bg-sky-400 rounded-full mt-2"></div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Order #1236 - Scheduled</h4>
                            <p class="text-slate-400 text-sm">Ana Dela Cruz - 7 items, ₱3,100</p>
                            <p class="text-slate-500 text-xs mt-1">Due: 6:00 PM</p>
                        </div>
                        <span class="px-2 py-1 bg-sky-500/20 text-sky-400 text-xs rounded-full">Scheduled</span>
                    </div>
                </div>
            </div>

            <!-- Inventory Status -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Inventory Status</h3>
                    <button class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        Manage
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Ariel Powder Detergent</h4>
                            <p class="text-slate-400 text-sm">15 kilos remaining</p>
                            <p class="text-slate-500 text-xs mt-1">Good stock level</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-exclamation-triangle text-amber-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Downy Fabric Softener</h4>
                            <p class="text-slate-400 text-sm">3 bottles remaining</p>
                            <p class="text-slate-500 text-xs mt-1">Low stock - reorder needed</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-sky-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-box text-sky-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Plastic Hangers</h4>
                            <p class="text-slate-400 text-sm">45 pieces available</p>
                            <p class="text-slate-500 text-xs mt-1">Adequate supply</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
            <h3 class="text-xl font-semibold text-slate-50 mb-6">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-sky-500 hover:border-sky-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-sky-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-plus text-sky-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Update Status</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Mark order complete</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-boxes text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Check Inventory</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">View supplies</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-amber-500 hover:border-amber-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-calendar-alt text-amber-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">View Schedule</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Check assignments</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-indigo-500 hover:border-indigo-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-chart-line text-indigo-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Weekly Report</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">View performance</p>
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

        // Performance Chart with enhanced animations
        const performanceOptions = {
            series: [{
                name: 'Orders Completed',
                data: [5, 7, 8, 6, 9, 8, 8]
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
            colors: ['#38BDF8'],
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
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
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
                        return val + ' orders'
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#38BDF8'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Task Distribution Chart with enhanced styling
        const taskDistributionOptions = {
            series: [8, 12, 3, 2],
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
            labels: ['Completed', 'Pending', 'In Progress', 'Overdue'],
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
                                label: 'Total Tasks',
                                color: '#F8FAFC',
                                fontSize: '14px',
                                fontWeight: 600,
                                fontFamily: 'Inter, sans-serif',
                                formatter: function (w) {
                                    return '25'
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
                        return val.toLocaleString() + ' tasks (' + percentage + '%)';
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
            const performanceChart = new ApexCharts(document.querySelector("#performanceChart"), performanceOptions);
            performanceChart.render();

            const taskDistributionChart = new ApexCharts(document.querySelector("#taskDistributionChart"), taskDistributionOptions);
            taskDistributionChart.render();

            // Sparkline charts for KPI cards
            const revenueSparkline = new ApexCharts(document.querySelector("#revenueSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [12500, 13000, 13500, 14000, 14250]
                }],
                colors: ['#22C55E']
            });
            revenueSparkline.render();

            const pendingSparkline = new ApexCharts(document.querySelector("#pendingSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [15, 14, 13, 12, 12]
                }],
                colors: ['#FBBF24']
            });
            pendingSparkline.render();

            const completedSparkline = new ApexCharts(document.querySelector("#completedSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [6, 7, 8, 8, 8]
                }],
                colors: ['#38BDF8']
            });
            completedSparkline.render();

            const schedulesSparkline = new ApexCharts(document.querySelector("#schedulesSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [3, 4, 5, 5, 5]
                }],
                colors: ['#8B5CF6']
            });
            schedulesSparkline.render();

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
