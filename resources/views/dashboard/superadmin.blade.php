@extends('layouts.sidebar')

@section('title', 'Super Admin Dashboard')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="container">
        <!-- Dashboard Header -->
        <div class="mb-6">
            <div>
                <h1 class="text-3xl font-bold text-slate-50 mb-2">Dashboard</h1>
                <p class="text-slate-400">Monitor system health, user activity, and manage your laundry platform.</p>
            </div>
        </div>

        <!-- KPI Cards Row -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Users Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-indigo-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Total Users</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ number_format($totalUsers) }}</p>
                        <p class="text-indigo-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ round(($totalUsers / max(1, $totalUsers - 50)) * 100 - 100, 1) }}% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-users text-white text-lg"></i>
                    </div>
                </div>
                <div id="usersSparkline" class="h-12"></div>
            </div>

            <!-- Administrators Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-purple-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Administrators</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ number_format($admins) }}</p>
                        <p class="text-purple-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ $admins > 0 ? round(($admins / max(1, $admins - 1)) * 100 - 100, 1) : 0 }}% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-shield text-white text-lg"></i>
                    </div>
                </div>
                <div id="adminsSparkline" class="h-12"></div>
            </div>

            <!-- Staff Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-indigo-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Staff</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ number_format($staff) }}</p>
                        <p class="text-indigo-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ $staff > 0 ? round(($staff / max(1, $staff - 2)) * 100 - 100, 1) : 0 }}% this month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user-tie text-white text-lg"></i>
                    </div>
                </div>
                <div id="staffSparkline" class="h-12"></div>
            </div>

            <!-- Customers Card -->
            <div class="group bg-gradient-to-tr from-slate-800 to-slate-700 border border-slate-700 rounded-xl p-6 hover:shadow-lg hover:border-purple-400/40 transition-all duration-300">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <p class="text-slate-400 text-sm font-medium mb-1">Customers</p>
                        <p class="text-slate-50 text-2xl font-bold">{{ number_format($customers) }}</p>
                        <p class="text-purple-400 text-sm font-medium mt-1 flex items-center">
                            <i class="fas fa-arrow-up mr-1 text-xs"></i>+{{ $customers > 0 ? round(($customers / max(1, $customers - 50)) * 100 - 100, 1) : 0 }}% from last month
                        </p>
                    </div>
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-user text-white text-lg"></i>
                    </div>
                </div>
                <div id="customersSparkline" class="h-12"></div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- User Growth Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">User Growth</h3>
                    <div class="flex items-center gap-3">
                        <select class="bg-slate-700 border border-slate-600 rounded-lg px-3 py-1.5 text-sm text-slate-200 focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400">
                            <option>Last 6 months</option>
                            <option>Last year</option>
                            <option>All time</option>
                        </select>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-indigo-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Growth trend</span>
                        </div>
                    </div>
                </div>
                <div id="userGrowthChart" class="h-72"></div>
            </div>

            <!-- Role Distribution Chart -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Role Distribution</h3>
                    <div class="flex items-center gap-3">
                        <button class="bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1.5 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>Export
                        </button>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                            <span class="text-sm text-slate-400">Current users</span>
                        </div>
                    </div>
                </div>
                <div id="roleDistributionChart" class="h-72"></div>
            </div>
        </div>

        <!-- System Governance Panels -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Recent Announcements -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Recent Announcements</h3>
                    <a href="{{ route('superadmin.announcements.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentAnnouncements as $announcement)
                        <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors border-l-4 border-indigo-500">
                            <div class="w-2 h-2 bg-indigo-500 rounded-full mt-2"></div>
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">{{ $announcement->title }}</h4>
                                <p class="text-slate-400 text-sm">{{ Str::limit($announcement->message, 60) }}</p>
                                <p class="text-slate-500 text-xs mt-1">{{ $announcement->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 bg-indigo-500/20 text-indigo-500 text-xs rounded-full">Active</span>
                        </div>
                    @empty
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-bullhorn text-4xl mb-2"></i>
                                <p>No announcements yet</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Audit Log -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Audit Log</h3>
                    <button class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">User Login</h4>
                            <p class="text-slate-400 text-sm">Admin John Doe logged in from 192.168.1.100</p>
                            <p class="text-slate-500 text-xs mt-1">5 minutes ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-indigo-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-cog text-indigo-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Role Change</h4>
                            <p class="text-slate-400 text-sm">Jane Smith promoted to Administrator</p>
                            <p class="text-slate-500 text-xs mt-1">1 hour ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-red-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-shield-alt text-red-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Security Event</h4>
                            <p class="text-slate-400 text-sm">Failed login attempt from suspicious IP</p>
                            <p class="text-slate-500 text-xs mt-1">2 hours ago</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Activity Feed -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Latest Registered Users -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Latest Registered Users</h3>
                    <a href="{{ route('superadmin.users.index') }}" class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </a>
                </div>
                <div class="space-y-4">
                    @forelse($recentUsers as $user)
                        <div class="flex items-center gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors group">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background={{ $user->role->name === 'administrator' ? '38BDF8' : ($user->role->name === 'staff' ? '22C55E' : 'FBBF24') }}&color=fff&size=40&font-size=0.6" 
                                 alt="{{ $user->name }}" class="w-10 h-10 rounded-full ring-2 {{ $user->role->name === 'administrator' ? 'ring-sky-400' : ($user->role->name === 'staff' ? 'ring-emerald-500' : 'ring-amber-400') }}">
                            <div class="flex-1">
                                <h4 class="text-slate-50 font-medium">{{ $user->name }}</h4>
                                <p class="text-slate-400 text-sm">{{ $user->email }}</p>
                                <p class="text-slate-500 text-xs mt-1">Joined {{ $user->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="px-2 py-1 {{ $user->role->name === 'administrator' ? 'bg-indigo-500/20 text-indigo-400' : ($user->role->name === 'staff' ? 'bg-emerald-500/20 text-emerald-500' : 'bg-amber-500/20 text-amber-400') }} text-xs rounded-full">{{ ucfirst($user->role->name) }}</span>
                        </div>
                    @empty
                        <div class="flex items-center justify-center p-8 text-slate-400">
                            <div class="text-center">
                                <i class="fas fa-users text-4xl mb-2"></i>
                                <p>No users registered yet</p>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Role Changes Activity -->
            <div class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-semibold text-slate-50">Role Changes</h3>
                    <button class="text-sky-400 hover:text-sky-300 text-sm font-medium">
                        View all
                    </button>
                </div>
                <div class="space-y-4">
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-indigo-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-arrow-up text-indigo-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Promotion</h4>
                            <p class="text-slate-400 text-sm">Admin John promoted Staff Jane to Administrator</p>
                            <p class="text-slate-500 text-xs mt-1">1 hour ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-plus text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">New Assignment</h4>
                            <p class="text-slate-400 text-sm">Customer Mike assigned to Staff Robert</p>
                            <p class="text-slate-500 text-xs mt-1">3 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-700/30 rounded-lg hover:bg-slate-700/50 transition-colors">
                        <div class="w-8 h-8 bg-amber-500/20 rounded-full flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-amber-400 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-slate-50 font-medium">Role Transfer</h4>
                            <p class="text-slate-400 text-sm">Staff Sarah transferred to different department</p>
                            <p class="text-slate-500 text-xs mt-1">1 day ago</p>
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
                        <i class="fas fa-user-plus text-sky-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Add Administrator</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Create new admin account</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-indigo-500 hover:border-indigo-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-indigo-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-bullhorn text-indigo-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Send Announcement</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Notify all users</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-emerald-500 hover:border-emerald-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-cog text-emerald-500"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Manage Settings</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">System configuration</p>
                    </div>
                </button>

                <button class="group flex items-center gap-3 p-4 bg-slate-700/50 border border-slate-600 rounded-lg hover:bg-amber-500 hover:border-amber-400 transition-all duration-300 hover:scale-105">
                    <div class="w-10 h-10 bg-amber-500/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                        <i class="fas fa-download text-amber-400"></i>
                    </div>
                    <div class="text-left">
                        <h4 class="text-slate-50 font-medium group-hover:text-white">Export Report</h4>
                        <p class="text-slate-400 text-sm group-hover:text-slate-200">Download analytics</p>
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

        // User Growth Chart with enhanced animations
        const userGrowthOptions = {
            series: [{
                name: 'Users',
                data: @json(array_values($userGrowthData))
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
            colors: ['#6366F1'],
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
                categories: @json(array_keys($userGrowthData)),
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
                        return val.toLocaleString() + " users"
                    }
                }
            },
            markers: {
                size: 4,
                colors: ['#6366F1'],
                strokeColors: '#fff',
                strokeWidth: 2,
                hover: {
                    size: 6
                }
            }
        };

        // Role Distribution Chart with enhanced styling
        const roleDistributionOptions = {
            series: @json(array_values($roleDistribution)),
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
            colors: ['#6366F1', '#8B5CF6', '#A855F7'],
            labels: @json(array_keys($roleDistribution)),
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
                                label: 'Total Users',
                                color: '#F8FAFC',
                                fontSize: '14px',
                                fontWeight: 600,
                                fontFamily: 'Inter, sans-serif',
                                formatter: function (w) {
                                    return '{{ number_format($totalUsers) }}'
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
                        return val.toLocaleString() + ' users (' + percentage + '%)';
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
            const userGrowthChart = new ApexCharts(document.querySelector("#userGrowthChart"), userGrowthOptions);
            userGrowthChart.render();

            const roleDistributionChart = new ApexCharts(document.querySelector("#roleDistributionChart"), roleDistributionOptions);
            roleDistributionChart.render();

            // Sparkline charts for KPI cards
            const usersSparkline = new ApexCharts(document.querySelector("#usersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json(array_values($userGrowthData))
                }],
                colors: ['#6366F1']
            });
            usersSparkline.render();

            const adminsSparkline = new ApexCharts(document.querySelector("#adminsSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [{{ $admins > 0 ? $admins - 1 : 0 }}, {{ $admins > 0 ? $admins - 1 : 0 }}, {{ $admins > 0 ? $admins - 1 : 0 }}, {{ $admins > 0 ? $admins - 1 : 0 }}, {{ $admins > 0 ? $admins - 1 : 0 }}, {{ $admins }}]
                }],
                colors: ['#8B5CF6']
            });
            adminsSparkline.render();

            const staffSparkline = new ApexCharts(document.querySelector("#staffSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: [{{ $staff > 0 ? $staff - 2 : 0 }}, {{ $staff > 0 ? $staff - 1 : 0 }}, {{ $staff > 0 ? $staff - 1 : 0 }}, {{ $staff > 0 ? $staff - 1 : 0 }}, {{ $staff > 0 ? $staff - 1 : 0 }}, {{ $staff }}]
                }],
                colors: ['#A855F7']
            });
            staffSparkline.render();

            const customersSparkline = new ApexCharts(document.querySelector("#customersSparkline"), {
                ...sparklineConfig,
                series: [{
                    data: @json(array_values($userGrowthData))
                }],
                colors: ['#8B5CF6']
            });
            customersSparkline.render();


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