@props(['logs' => [], 'title' => 'Recent Activity', 'showViewAll' => true])

<div class="bg-gradient-to-br from-slate-800/50 to-slate-800 border border-slate-700/50 rounded-2xl p-6 hover:shadow-lg hover:shadow-slate-900/20 transition-all duration-300">
    <!-- Header -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                <i class="fas fa-shield-alt text-white"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                <p class="text-sm text-slate-400">System activity monitoring</p>
            </div>
        </div>
        @if($showViewAll)
            <a href="{{ route('superadmin.audit-logs.index') }}" class="text-indigo-400 hover:text-indigo-300 text-sm font-medium transition-colors">
                View all
            </a>
        @endif
    </div>

    <!-- Activity Feed -->
    <div class="space-y-4">
        @forelse($logs as $log)
            <div class="group bg-slate-800/30 border border-slate-700/30 rounded-xl p-4 hover:bg-slate-700/30 hover:border-slate-600/50 transition-all duration-200">
                <div class="flex items-start space-x-3">
                    <!-- Action Icon -->
                    <div class="flex-shrink-0">
                        <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm
                            @if(str_contains($log->action, 'CREATED')) bg-gradient-to-br from-emerald-500 to-emerald-600 text-white
                            @elseif(str_contains($log->action, 'DELETED')) bg-gradient-to-br from-red-500 to-red-600 text-white
                            @elseif(str_contains($log->action, 'UPDATED')) bg-gradient-to-br from-blue-500 to-blue-600 text-white
                            @elseif(str_contains($log->action, 'STATUS')) bg-gradient-to-br from-amber-500 to-amber-600 text-white
                            @elseif(str_contains($log->action, 'LOGIN')) bg-gradient-to-br from-indigo-500 to-indigo-600 text-white
                            @elseif(str_contains($log->action, 'PAYMENT')) bg-gradient-to-br from-green-500 to-green-600 text-white
                            @elseif(str_contains($log->action, 'ORDER')) bg-gradient-to-br from-purple-500 to-purple-600 text-white
                            @else bg-gradient-to-br from-slate-500 to-slate-600 text-white
                            @endif">
                            @if(str_contains($log->action, 'CREATED'))
                                <i class="fas fa-plus text-xs"></i>
                            @elseif(str_contains($log->action, 'DELETED'))
                                <i class="fas fa-trash text-xs"></i>
                            @elseif(str_contains($log->action, 'UPDATED'))
                                <i class="fas fa-edit text-xs"></i>
                            @elseif(str_contains($log->action, 'STATUS'))
                                <i class="fas fa-toggle-on text-xs"></i>
                            @elseif(str_contains($log->action, 'LOGIN'))
                                <i class="fas fa-sign-in-alt text-xs"></i>
                            @elseif(str_contains($log->action, 'PAYMENT'))
                                <i class="fas fa-credit-card text-xs"></i>
                            @elseif(str_contains($log->action, 'ORDER'))
                                <i class="fas fa-shopping-bag text-xs"></i>
                            @else
                                <i class="fas fa-cog text-xs"></i>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Content -->
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-1">
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 rounded-full text-xs font-semibold uppercase tracking-wide
                                    @if(str_contains($log->action, 'CREATED')) bg-emerald-500/20 text-emerald-400 border border-emerald-500/30
                                    @elseif(str_contains($log->action, 'DELETED')) bg-red-500/20 text-red-400 border border-red-500/30
                                    @elseif(str_contains($log->action, 'UPDATED')) bg-blue-500/20 text-blue-400 border border-blue-500/30
                                    @elseif(str_contains($log->action, 'STATUS')) bg-amber-500/20 text-amber-400 border border-amber-500/30
                                    @elseif(str_contains($log->action, 'LOGIN')) bg-indigo-500/20 text-indigo-400 border border-indigo-500/30
                                    @elseif(str_contains($log->action, 'PAYMENT')) bg-green-500/20 text-green-400 border border-green-500/30
                                    @elseif(str_contains($log->action, 'ORDER')) bg-purple-500/20 text-purple-400 border border-purple-500/30
                                    @else bg-slate-500/20 text-slate-400 border border-slate-500/30
                                    @endif">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </div>
                            <span class="text-xs text-slate-400 font-medium">
                                {{ $log->created_at->diffForHumans() }}
                            </span>
                        </div>
                        
                        <div class="text-sm text-slate-200 leading-relaxed mb-2">
                            {!! Str::limit(strip_tags($log->description), 100) !!}
                        </div>
                        
                        <div class="flex items-center justify-between text-xs text-slate-400">
                            <div class="flex items-center space-x-3">
                                <span class="flex items-center space-x-1">
                                    <div class="w-4 h-4 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center">
                                        @if($log->user)
                                            <span class="text-white font-semibold text-xs">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </span>
                                        @else
                                            <i class="fas fa-cog text-white text-xs"></i>
                                        @endif
                                    </div>
                                    <span class="font-medium">{{ $log->user ? $log->user->name : 'System' }}</span>
                                </span>
                                <span class="flex items-center space-x-1">
                                    <i class="fas fa-globe text-xs"></i>
                                    <span class="font-mono">{{ $log->ip_address ?? 'N/A' }}</span>
                                </span>
                            </div>
                            <span class="font-mono">{{ $log->created_at->format('H:i') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <div class="w-16 h-16 bg-slate-700/50 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-shield-alt text-2xl text-slate-400"></i>
                </div>
                <h4 class="text-lg font-medium text-white mb-2">No Recent Activity</h4>
                <p class="text-slate-400 text-sm">System activity will appear here</p>
            </div>
        @endforelse
    </div>

</div>
