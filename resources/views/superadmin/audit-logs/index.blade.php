@extends('layouts.sidebar')

@section('title', 'Audit Logs')


@section('content')
<div class="min-h-screen bg-slate-900">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <div>
                <h1 class="text-3xl font-bold text-white">Audit Logs</h1>
                <p class="text-slate-400">System activity monitoring</p>
            </div>
        </div>


        <!-- Audit Logs Content -->
        @if($auditLogs->count() > 0)
            <div class="space-y-3">
                @foreach($auditLogs as $log)
                    <x-audit-log-item :log="$log" />
                @endforeach
            </div>
            
            <!-- Modern Pagination -->
            <div class="mt-8 flex items-center justify-between">
                <div class="text-sm text-slate-400">
                    Showing {{ $auditLogs->firstItem() }} to {{ $auditLogs->lastItem() }} of {{ $auditLogs->total() }} results
                </div>
                
                <div class="flex items-center space-x-2">
                    <!-- Previous Button -->
                    @if ($auditLogs->onFirstPage())
                        <span class="px-3 py-2 text-slate-500 bg-slate-800 border border-slate-700 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </span>
                    @else
                        <a href="{{ $auditLogs->previousPageUrl() }}" class="px-3 py-2 text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                            <i class="fas fa-chevron-left text-sm"></i>
                        </a>
                    @endif

                    <!-- Page Numbers -->
                    @foreach ($auditLogs->getUrlRange(1, $auditLogs->lastPage()) as $page => $url)
                        @if ($page == $auditLogs->currentPage())
                            <span class="px-3 py-2 text-white bg-indigo-600 border border-indigo-600 rounded-lg font-medium">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}" class="px-3 py-2 text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach

                    <!-- Next Button -->
                    @if ($auditLogs->hasMorePages())
                        <a href="{{ $auditLogs->nextPageUrl() }}" class="px-3 py-2 text-slate-300 bg-slate-800 border border-slate-700 rounded-lg hover:bg-slate-700 hover:text-white transition-colors">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </a>
                    @else
                        <span class="px-3 py-2 text-slate-500 bg-slate-800 border border-slate-700 rounded-lg cursor-not-allowed">
                            <i class="fas fa-chevron-right text-sm"></i>
                        </span>
                    @endif
                </div>
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-12">
                <div class="w-16 h-16 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl text-slate-400"></i>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">No Audit Logs Found</h3>
                <p class="text-slate-400 mb-6">No audit logs match your current filters.</p>
                <a href="{{ route('superadmin.audit-logs.index') }}" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded font-medium">
                    Clear Filters
                </a>
            </div>
        @endif
    </div>
</div>
@endsection