<!-- Dashboard Announcements Widget -->
<div class="bg-slate-800 rounded-xl border border-slate-700 overflow-hidden" x-data="announcementsWidget()">
    <!-- Header -->
    <div class="px-6 py-4 border-b border-slate-700">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-lg font-semibold text-white">What's New</h2>
                <p class="text-sm text-slate-400">Latest announcements and updates</p>
            </div>
            <button 
                @click="toggleExpanded()"
                class="p-2 hover:bg-slate-700 rounded-lg transition-colors"
            >
                <svg 
                    class="w-5 h-5 text-slate-400 transition-transform duration-200" 
                    :class="{ 'rotate-180': isExpanded }"
                    fill="none" 
                    stroke="currentColor" 
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="p-6">
        @if($announcements->count() > 0)
            <div class="space-y-4">
                @foreach($announcements->take($isExpanded ? 10 : 5) as $announcement)
                <div 
                    class="announcement-item bg-slate-700/50 rounded-lg p-4 border border-slate-600/50 hover:border-slate-500 transition-all duration-200 fade-in"
                    x-show="!isDismissed({{ $announcement->id }})"
                >
                    <div class="flex items-start gap-3">
                        <!-- Type Badge -->
                        <div class="flex-shrink-0">
                            <span class="px-2 py-1 rounded-full text-xs font-medium border {{ $announcement->type_badge_class }}">
                                {{ $announcement->formatted_type }}
                            </span>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <h3 class="text-sm font-medium text-white truncate">{{ $announcement->title }}</h3>
                                @if($announcement->is_pinned)
                                <span class="text-orange-400 text-xs">📌</span>
                                @endif
                            </div>
                            
                            <p class="text-xs text-slate-300 mb-2 line-clamp-2">{{ $announcement->short_message }}</p>
                            
                            <div class="flex items-center justify-between text-xs text-slate-400">
                                <span>{{ $announcement->created_at->diffForHumans() }}</span>
                                
                                <div class="flex items-center gap-2">
                                    @if($announcement->link)
                                    <a 
                                        href="{{ $announcement->link }}" 
                                        target="_blank"
                                        class="text-indigo-400 hover:text-indigo-300 transition-colors"
                                    >
                                        Learn more
                                    </a>
                                    @endif
                                    
                                    <button 
                                        @click="dismissAnnouncement({{ $announcement->id }})"
                                        class="text-slate-500 hover:text-slate-300 transition-colors"
                                        title="Dismiss"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Show More/Less Button -->
            @if($announcements->count() > 5)
            <div class="mt-4 text-center">
                <button 
                    @click="toggleExpanded()"
                    class="text-sm text-indigo-400 hover:text-indigo-300 transition-colors"
                >
                    <span x-text="isExpanded ? 'Show Less' : 'Show More'"></span>
                </button>
            </div>
            @endif
        @else
            <div class="text-center py-8">
                <div class="w-12 h-12 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
                <p class="text-slate-400 text-sm">No announcements at the moment</p>
            </div>
        @endif
    </div>
</div>

@push('styles')
@vite(['resources/css/announcements.css'])
@endpush

@push('scripts')
@vite(['resources/js/modules/announcements/index.js'])
@endpush
