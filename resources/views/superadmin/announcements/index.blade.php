@extends('layouts.sidebar')

@section('title', 'Announcements Management')

@push('styles')
@vite(['resources/css/announcements.css'])
@endpush

@section('content')
<div class="container" x-data="announcementsManager()">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">Announcements Management</h1>
                <p class="text-slate-400">Create and manage system announcements</p>
            </div>
            <button 
                @click="openCreateModal()"
                class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors duration-200 shadow-lg hover:shadow-xl"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Create Announcement
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-slate-900 rounded-xl p-6 border border-indigo-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Total Announcements</p>
                    <p class="text-2xl font-bold text-white">{{ $announcements->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-indigo-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Active</p>
                    <p class="text-2xl font-bold text-green-400">{{ $announcements->where('is_active', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-green-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-indigo-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">Pinned</p>
                    <p class="text-2xl font-bold text-orange-400">{{ $announcements->where('is_pinned', true)->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-orange-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="bg-slate-900 rounded-xl p-6 border border-indigo-500/20">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-slate-400 text-sm">This Month</p>
                    <p class="text-2xl font-bold text-purple-400">{{ $announcements->where('created_at', '>=', now()->startOfMonth())->count() }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-600/20 rounded-lg flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements List -->
    <div class="bg-slate-900 rounded-xl border border-indigo-500/20">
        <div class="px-6 py-4 border-b border-indigo-500/20">
            <h2 class="text-xl font-semibold text-white">All Announcements</h2>
            <p class="text-slate-400 text-sm">Manage your system announcements</p>
        </div>
        
        <div class="p-6">
            @if($announcements->count() > 0)
                <div class="space-y-4">
                    @foreach($announcements as $announcement)
                    <div class="announcement-card bg-slate-900 rounded-xl p-6 border border-indigo-500/20 fade-in">
                        <div class="flex items-start justify-between mb-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-3">
                                    <span class="px-3 py-1 rounded-full text-xs font-medium border {{ $announcement->type_badge_class }}">
                                        {{ $announcement->formatted_type }}
                                    </span>
                                    
                                    @if($announcement->is_pinned)
                                    <span class="px-2 py-1 bg-orange-600/20 text-orange-400 text-xs font-medium rounded-full border border-orange-600/30">
                                        📌 Pinned
                                    </span>
                                    @endif
                                    
                                    @if($announcement->is_active)
                                    <span class="px-2 py-1 bg-green-600/20 text-green-400 text-xs font-medium rounded-full border border-green-600/30">
                                        Active
                                    </span>
                                    @else
                                    <span class="px-2 py-1 bg-red-600/20 text-red-400 text-xs font-medium rounded-full border border-red-600/30">
                                        Inactive
                                    </span>
                                    @endif
                                    
                                    <span class="text-xs text-slate-400">
                                        {{ $announcement->created_at->format('M j, Y') }}
                                    </span>
                                </div>
                                
                                <h3 class="text-lg font-semibold text-white mb-2">{{ $announcement->title }}</h3>
                                <p class="text-slate-300 mb-4 line-clamp-2">{{ $announcement->message }}</p>
                                
                                <div class="flex items-center justify-between text-sm text-slate-400">
                                    <div class="flex items-center gap-4">
                                        <span>By {{ $announcement->createdBy->name ?? 'Unknown' }}</span>
                                        <span>Visible to: {{ ucfirst($announcement->visible_to) }}</span>
                                        @if($announcement->expires_at)
                                        <span>Expires: {{ $announcement->expires_at->format('M j, Y') }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($announcement->link)
                                    <a href="{{ $announcement->link }}" target="_blank" class="text-slate-400 hover:text-slate-300 transition-colors">
                                        Learn More →
                                    </a>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Actions Dropdown -->
                            <div class="relative" x-data="{ open: false }" style="z-index: 1;">
                                <button 
                                    @click="open = !open"
                                    class="action-menu-button p-2 rounded-lg transition-colors relative z-10"
                                    style="outline: none; border: none; background: transparent;"
                                >
                                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                    </svg>
                                </button>
                                
                                <div 
                                    x-show="open"
                                    @click.away="open = false"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-slate-800 border border-indigo-500/20 rounded-lg shadow-xl dropdown-menu"
                                    style="display: none;"
                                >
                                    <div class="py-1">
                                        <button 
                                            @click="openEditModal({{ $announcement->id }})"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        <button 
                                            @click="toggleStatus({{ $announcement->id }})"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                            </svg>
                                            {{ $announcement->is_active ? 'Deactivate' : 'Activate' }}
                                        </button>
                                        
                                        <button 
                                            @click="togglePin({{ $announcement->id }})"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"/>
                                            </svg>
                                            {{ $announcement->is_pinned ? 'Unpin' : 'Pin' }}
                                        </button>
                                        
                                        <div class="border-t border-indigo-500/20 my-1"></div>
                                        
                                        <button 
                                            @click="deleteAnnouncement({{ $announcement->id }})"
                                            class="flex items-center gap-2 w-full px-4 py-2 text-sm text-red-400 hover:bg-red-900/20 transition-colors"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-12">
                    <div class="w-16 h-16 bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-white mb-2">No announcements yet</h3>
                    <p class="text-slate-400 mb-6">Create your first announcement to get started</p>
                    <button 
                        @click="openCreateModal()"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Create Announcement
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Create/Edit Modal -->
    @include('components.announcement-modal')

    <!-- Delete Confirmation Modal -->
    @include('components.delete-confirmation-modal')
</div>

@push('scripts')
@vite(['resources/js/modules/announcements/index.js'])
@endpush
@endsection