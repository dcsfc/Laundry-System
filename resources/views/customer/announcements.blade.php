@extends('layouts.sidebar')

@section('title', 'Announcements')

@section('content')
    <div class="container">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">Announcements</h1>
                    <p class="text-slate-400">Stay updated with the latest news and updates from Latino Laundry.</p>
                </div>
                <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-200 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Back to Dashboard
                </a>
            </div>
        </div>

        <!-- Announcements List -->
        <div class="space-y-6">
            @forelse($announcements as $announcement)
                <div id="announcement-{{ $announcement->id }}" class="bg-slate-800 border border-slate-700 rounded-xl p-6 hover:shadow-lg transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <!-- Icon -->
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-600 rounded-lg flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-bullhorn text-white text-lg"></i>
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <h3 class="text-xl font-semibold text-slate-50 mb-1">{{ $announcement->title }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-slate-400">
                                        <span class="flex items-center">
                                            <i class="fas fa-user mr-1"></i>
                                            {{ $announcement->createdBy->name ?? 'System' }}
                                        </span>
                                        <span class="flex items-center">
                                            <i class="fas fa-clock mr-1"></i>
                                            {{ $announcement->created_at->format('M d, Y \a\t g:i A') }}
                                        </span>
                                        @if($announcement->is_pinned)
                                            <span class="flex items-center text-emerald-400">
                                                <i class="fas fa-thumbtack mr-1"></i>
                                                Pinned
                                            </span>
                                        @endif
                                    </div>
                                </div>
                                
                                <!-- Type Badge -->
                                @if($announcement->type)
                                    <span class="px-3 py-1 text-xs font-medium rounded-full {{ $announcement->type_badge_class }}">
                                        {{ $announcement->formatted_type }}
                                    </span>
                                @endif
                            </div>
                            
                            <!-- Message -->
                            <div class="prose prose-invert max-w-none">
                                <p class="text-slate-300 leading-relaxed whitespace-pre-line">{{ $announcement->message }}</p>
                            </div>
                            
                            <!-- Link (if provided) -->
                            @if($announcement->link)
                                <div class="mt-4">
                                    <a href="{{ $announcement->link }}" target="_blank" class="inline-flex items-center text-emerald-400 hover:text-emerald-300 font-medium transition-colors">
                                        <i class="fas fa-external-link-alt mr-2"></i>
                                        Learn More
                                    </a>
                                </div>
                            @endif
                            
                            <!-- Expiry Info -->
                            @if($announcement->expires_at)
                                <div class="mt-4 pt-4 border-t border-slate-700">
                                    <p class="text-sm text-slate-500">
                                        <i class="fas fa-calendar-times mr-1"></i>
                                        Expires: {{ $announcement->expires_at->format('M d, Y \a\t g:i A') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bullhorn text-4xl text-slate-500"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-300 mb-2">No announcements yet</h3>
                    <p class="text-slate-400 mb-6">We'll post important updates and news here when they're available.</p>
                    <a href="{{ route('customer.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg font-medium transition-colors">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Back to Dashboard
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($announcements->hasPages())
            <div class="mt-8">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>

    <script>
        // Handle anchor scrolling when page loads
        document.addEventListener('DOMContentLoaded', function() {
            const hash = window.location.hash;
            if (hash) {
                setTimeout(() => {
                    const element = document.querySelector(hash);
                    if (element) {
                        element.scrollIntoView({ 
                            behavior: 'smooth',
                            block: 'start'
                        });
                        // Add a subtle highlight effect
                        element.style.borderColor = '#10B981';
                        setTimeout(() => {
                            element.style.borderColor = '';
                        }, 2000);
                    }
                }, 100);
            }
        });
    </script>
@endsection
