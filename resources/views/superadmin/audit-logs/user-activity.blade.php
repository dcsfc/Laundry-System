@extends('layouts.sidebar')

@section('title', 'User Activity - ' . $user->name)

@section('content')
<div class="container">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-user text-white"></i>
                </div>
                <div>
                    <h1 class="text-3xl font-bold text-slate-50">User Activity</h1>
                    <p class="text-slate-400">Activity history for {{ $user->name }}</p>
                </div>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('superadmin.audit-logs.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-600 hover:bg-slate-700 text-white text-sm font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 focus:ring-offset-slate-800 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to All Logs
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-slate-800 border border-slate-700 rounded-lg p-6 mb-6">
        <div class="flex items-center space-x-4">
            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                <i class="fas fa-user text-2xl text-white"></i>
            </div>
            <div class="flex-1">
                <h3 class="text-xl font-semibold text-slate-50">{{ $user->name }}</h3>
                <p class="text-slate-400">{{ $user->email }}</p>
                <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->role->name === 'superadmin') bg-purple-500/20 text-purple-400 border border-purple-500/30
                        @elseif($user->role->name === 'administrator') bg-blue-500/20 text-blue-400 border border-blue-500/30
                        @elseif($user->role->name === 'staff') bg-green-500/20 text-green-400 border border-green-500/30
                        @else bg-slate-500/20 text-slate-400 border border-slate-500/30
                        @endif">
                        {{ ucfirst($user->role->name) }}
                    </span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($user->status === 'active') bg-green-500/20 text-green-400 border border-green-500/30
                        @else bg-red-500/20 text-red-400 border border-red-500/30
                        @endif">
                        {{ ucfirst($user->status) }}
                    </span>
                    <span class="text-sm text-slate-400">
                        Member since {{ $user->created_at->format('M d, Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Activity Logs -->
    @if($auditLogs->count() > 0)
        <div class="space-y-4">
            @foreach($auditLogs as $log)
                <x-audit-log-item :log="$log" />
            @endforeach
        </div>
        
        <!-- Pagination -->
        <div class="mt-6">
            <div class="text-slate-300">
                {{ $auditLogs->links() }}
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-12">
            <div class="mx-auto w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mb-4">
                <i class="fas fa-history text-2xl text-slate-400"></i>
            </div>
            <h3 class="text-lg font-medium text-white mb-2">No Activity Found</h3>
            <p class="text-slate-400 mb-6">This user has no recorded activity yet.</p>
        </div>
    @endif
</div>
@endsection
