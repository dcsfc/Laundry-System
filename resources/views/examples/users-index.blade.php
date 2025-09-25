@extends('layouts.sidebar')

@section('title', 'User Management')

@push('styles')
    @vite('resources/css/datatable.css')
@endpush

@push('scripts')
    @vite('resources/js/datatable.js')
    @vite('resources/js/datatable-renderer.js')
@endpush

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-slate-50 mb-2">User Management</h1>
                    <p class="text-slate-400">Manage system users, roles, and permissions</p>
                </div>
                <div class="flex items-center gap-3">
                    <button class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg transition-colors">
                        <i class="fas fa-download mr-2"></i>Export
                    </button>
                    <button class="px-4 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-lg transition-colors">
                        <i class="fas fa-upload mr-2"></i>Import
                    </button>
                </div>
            </div>
        </div>

        <!-- Data Table -->
        <x-tables.users-table 
            :users="$users" 
            :roles="$roles" 
            color-scheme="sky"
        />
    </div>
@endsection

