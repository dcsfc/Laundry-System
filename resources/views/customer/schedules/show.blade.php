@extends('layouts.sidebar')

@section('title', 'Schedule Details')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">Schedule Details</h1>
                    <p class="text-slate-400">View detailed information about your laundry appointment.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('customer.schedules.index') }}" 
                       class="bg-slate-700 hover:bg-slate-600 text-slate-300 px-4 py-2 rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>Back to Schedules
                    </a>
                    @if($schedule['status'] !== 'Cancelled')
                        <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                            <i class="fas fa-edit mr-2"></i>Edit Schedule
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Schedule Information -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Schedule Status Card -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Schedule Status</h3>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <div class="text-2xl font-bold text-slate-50">Schedule #{{ $schedule['id'] }}</div>
                            <div class="text-slate-400">Created on {{ $schedule['created_at'] }}</div>
                        </div>
                        @php
                            $statusColors = [
                                'Scheduled' => 'bg-blue-500/20 text-blue-400 border-blue-500/30',
                                'Confirmed' => 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30',
                                'Pending' => 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30',
                                'Cancelled' => 'bg-red-500/20 text-red-400 border-red-500/30'
                            ];
                            $statusColor = $statusColors[$schedule['status']] ?? 'bg-slate-500/20 text-slate-400 border-slate-500/30';
                        @endphp
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border {{ $statusColor }}">
                            {{ $schedule['status'] }}
                        </span>
                    </div>
                    
                    <!-- Progress Timeline -->
                    <div class="space-y-4">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-white text-sm"></i>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-slate-200">Schedule Created</div>
                                <div class="text-xs text-slate-400">{{ $schedule['created_at'] }}</div>
                            </div>
                        </div>
                        
                        @if($schedule['status'] === 'Confirmed' || $schedule['status'] === 'Pending')
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-200">Schedule Confirmed</div>
                                    <div class="text-xs text-slate-400">Staff has confirmed your appointment</div>
                                </div>
                            </div>
                        @else
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-slate-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-clock text-slate-400 text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-400">Awaiting Confirmation</div>
                                    <div class="text-xs text-slate-500">Staff will confirm your appointment</div>
                                </div>
                            </div>
                        @endif
                        
                        @if($schedule['status'] === 'Cancelled')
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-red-500 rounded-full flex items-center justify-center">
                                    <i class="fas fa-times text-white text-sm"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-slate-200">Schedule Cancelled</div>
                                    <div class="text-xs text-slate-400">This appointment has been cancelled</div>
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
                            <span class="text-slate-200 font-medium">{{ $schedule['service_type'] }}</span>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-700">
                            <span class="text-slate-400">Staff Assigned</span>
                            <span class="text-slate-200 font-medium">{{ $schedule['staff_assigned'] }}</span>
                        </div>
                        @if(isset($schedule['estimated_completion']))
                            <div class="flex justify-between items-center py-2 border-b border-slate-700">
                                <span class="text-slate-400">Estimated Completion</span>
                                <span class="text-slate-200 font-medium">{{ $schedule['estimated_completion'] }}</span>
                            </div>
                        @endif
                        @if(isset($schedule['special_instructions']) && $schedule['special_instructions'])
                            <div class="py-2">
                                <span class="text-slate-400 block mb-2">Special Instructions</span>
                                <span class="text-slate-200">{{ $schedule['special_instructions'] }}</span>
                            </div>
                        @endif
                        @if($schedule['notes'])
                            <div class="py-2">
                                <span class="text-slate-400 block mb-2">Notes</span>
                                <span class="text-slate-200">{{ $schedule['notes'] }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Schedule Times -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Appointment Times</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-emerald-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-plus text-emerald-400"></i>
                            </div>
                            <div>
                                <div class="text-sm text-slate-400">Drop-off Date & Time</div>
                                <div class="text-slate-200 font-medium">{{ $schedule['dropoff_date'] }}</div>
                                <div class="text-slate-300 text-sm">{{ $schedule['dropoff_time'] }}</div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-teal-500/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-calendar-check text-teal-400"></i>
                            </div>
                            <div>
                                <div class="text-sm text-slate-400">Pickup Date & Time</div>
                                <div class="text-slate-200 font-medium">{{ $schedule['pickup_date'] }}</div>
                                <div class="text-slate-300 text-sm">{{ $schedule['pickup_time'] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Your Contact Info</h3>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-user text-emerald-400"></i>
                            <span class="text-slate-300">{{ $schedule['customer_name'] }}</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <i class="fas fa-phone text-emerald-400"></i>
                            <span class="text-slate-300">{{ $schedule['customer_phone'] }}</span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
                    <h3 class="text-xl font-semibold text-slate-50 mb-4">Actions</h3>
                    <div class="space-y-3">
                        @if($schedule['status'] === 'Scheduled' || $schedule['status'] === 'Pending')
                            <button class="w-full bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                <i class="fas fa-times mr-2"></i>Cancel Appointment
                            </button>
                        @endif
                        @if($schedule['status'] !== 'Cancelled')
                            <button class="w-full bg-blue-500 hover:bg-blue-600 text-white py-2 px-4 rounded-lg font-medium transition-colors">
                                <i class="fas fa-edit mr-2"></i>Reschedule
                            </button>
                        @endif
                        <button class="w-full bg-slate-700 hover:bg-slate-600 text-slate-300 py-2 px-4 rounded-lg font-medium transition-colors">
                            <i class="fas fa-print mr-2"></i>Print Details
                        </button>
                    </div>
                </div>

                <!-- Support -->
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
