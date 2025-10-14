@props(['log'])

<div class="bg-slate-800 border border-slate-700 rounded-lg p-4 hover:bg-slate-750 transition-colors duration-200">
    <div class="flex items-start space-x-3">
        <!-- Simple Action Icon -->
        <div class="flex-shrink-0">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white text-sm
                @if(str_contains($log->action, 'CREATED')) bg-green-500
                @elseif(str_contains($log->action, 'DELETED')) bg-red-500
                @elseif(str_contains($log->action, 'UPDATED')) bg-blue-500
                @elseif(str_contains($log->action, 'LOGIN')) bg-indigo-500
                @else bg-slate-500
                @endif">
                @if(str_contains($log->action, 'CREATED'))
                    <i class="fas fa-plus"></i>
                @elseif(str_contains($log->action, 'DELETED'))
                    <i class="fas fa-trash"></i>
                @elseif(str_contains($log->action, 'UPDATED'))
                    <i class="fas fa-edit"></i>
                @elseif(str_contains($log->action, 'LOGIN'))
                    <i class="fas fa-sign-in-alt"></i>
                @else
                    <i class="fas fa-cog"></i>
                @endif
            </div>
        </div>
        
        <!-- Content -->
        <div class="flex-1 min-w-0">
            <!-- Header -->
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-medium text-slate-300">
                    {{ str_replace('_', ' ', $log->action) }}
                </span>
                <span class="text-xs text-slate-500">
                    {{ $log->created_at->diffForHumans() }}
                </span>
            </div>
            
            <!-- Description -->
            <div class="mb-3">
                <div class="text-slate-200 text-sm">
                    {!! $log->description !!}
                </div>
            </div>
            
            <!-- Footer -->
            <div class="flex items-center justify-between text-xs text-slate-500">
                <span class="font-medium">{{ $log->user ? $log->user->name : 'System' }}</span>
                <span>{{ $log->created_at->format('M d, H:i') }}</span>
            </div>
        </div>
    </div>
</div>