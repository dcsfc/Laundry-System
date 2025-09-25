@extends('layouts.sidebar')

@section('title', 'Modern Table Headers Demo')

@section('content')
<div class="container mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-50 mb-2">Modern Table Headers Demo</h1>
        <p class="text-slate-400">Professional SaaS-style table headers with clean typography and modern interactions</p>
    </div>

    <!-- Color Scheme Examples -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
        <!-- Primary Theme -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-slate-50 mb-4">Primary Theme</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="modern-table-header primary shadow">
                        <tr>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Name</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Email</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Status</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-700">
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">John Doe</td>
                            <td class="px-6 py-4 text-slate-300">john@example.com</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">Active</span></td>
                        </tr>
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">Jane Smith</td>
                            <td class="px-6 py-4 text-slate-300">jane@example.com</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">Pending</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Success Theme -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-slate-50 mb-4">Success Theme</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="modern-table-header success shadow">
                        <tr>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Product</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Price</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Stock</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-700">
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">Premium Plan</td>
                            <td class="px-6 py-4 text-slate-300">$99/month</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">In Stock</span></td>
                        </tr>
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">Basic Plan</td>
                            <td class="px-6 py-4 text-slate-300">$29/month</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-green-500/20 text-green-400 rounded-full text-xs">In Stock</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Purple Theme -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-slate-50 mb-4">Purple Theme</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="modern-table-header purple glass">
                        <tr>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Task</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Priority</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Due Date</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-700">
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">Design Review</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-red-500/20 text-red-400 rounded-full text-xs">High</span></td>
                            <td class="px-6 py-4 text-slate-300">2024-01-15</td>
                        </tr>
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">Code Review</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-yellow-500/20 text-yellow-400 rounded-full text-xs">Medium</span></td>
                            <td class="px-6 py-4 text-slate-300">2024-01-20</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Indigo Theme with Active Sort -->
        <div class="bg-slate-800 border border-slate-700 rounded-xl p-6">
            <h3 class="text-xl font-semibold text-slate-50 mb-4">Indigo Theme (Active Sort)</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="modern-table-header indigo shadow">
                        <tr>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">User ID</span>
                                    <button class="sort-button active">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up active" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Role</span>
                                    <button class="sort-button">
                                        <div class="sort-icons">
                                            <svg class="sort-icon sort-icon-up" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 15l7-7 7 7"/>
                                            </svg>
                                            <svg class="sort-icon sort-icon-down" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </button>
                                </div>
                            </th>
                            <th>
                                <div class="header-content">
                                    <span class="header-text">Actions</span>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-slate-700">
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">#001</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-indigo-500/20 text-indigo-400 rounded-full text-xs">Admin</span></td>
                            <td class="px-6 py-4">
                                <button class="px-3 py-1 bg-indigo-500/20 text-indigo-400 rounded text-xs hover:bg-indigo-500/30 transition-colors">Edit</button>
                            </td>
                        </tr>
                        <tr class="border-b border-slate-600">
                            <td class="px-6 py-4 text-slate-300">#002</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 bg-emerald-500/20 text-emerald-400 rounded-full text-xs">Staff</span></td>
                            <td class="px-6 py-4">
                                <button class="px-3 py-1 bg-indigo-500/20 text-indigo-400 rounded text-xs hover:bg-indigo-500/30 transition-colors">Edit</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Features Showcase -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-8 mb-8">
        <h2 class="text-2xl font-bold text-slate-50 mb-6">Key Features</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-indigo-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Clean Typography</h3>
                    <p class="text-slate-400 text-sm">Inter font family with consistent spacing and optimal readability</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Accessibility First</h3>
                    <p class="text-slate-400 text-sm">WCAG compliant with proper focus states and ARIA labels</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-purple-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zM21 5a2 2 0 00-2-2h-4a2 2 0 00-2 2v12a4 4 0 004 4h4a2 2 0 002-2V5z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Responsive Design</h3>
                    <p class="text-slate-400 text-sm">Adapts seamlessly to all screen sizes and devices</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-yellow-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Theme Support</h3>
                    <p class="text-slate-400 text-sm">Light and dark mode compatibility with smooth transitions</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-red-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Modern Interactions</h3>
                    <p class="text-slate-400 text-sm">Smooth hover effects and micro-animations for better UX</p>
                </div>
            </div>
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 bg-cyan-500/20 rounded-lg flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-slate-50 mb-1">Customizable</h3>
                    <p class="text-slate-400 text-sm">Easy theming with CSS custom properties and color schemes</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Implementation Code -->
    <div class="bg-slate-800 border border-slate-700 rounded-xl p-8">
        <h2 class="text-2xl font-bold text-slate-50 mb-6">Implementation</h2>
        <div class="bg-slate-900 rounded-lg p-6 overflow-x-auto">
            <pre class="text-slate-300 text-sm"><code>&lt;!-- Include the CSS --&gt;
&lt;link rel="stylesheet" href="{{ asset('css/table-headers.css') }}"&gt;

&lt;!-- Use in your table --&gt;
&lt;thead class="modern-table-header primary shadow"&gt;
    &lt;tr&gt;
        &lt;th&gt;
            &lt;div class="header-content"&gt;
                &lt;span class="header-text"&gt;Column Name&lt;/span&gt;
                &lt;button class="sort-button"&gt;
                    &lt;div class="sort-icons"&gt;
                        &lt;svg class="sort-icon sort-icon-up"&gt;...&lt;/svg&gt;
                        &lt;svg class="sort-icon sort-icon-down"&gt;...&lt;/svg&gt;
                    &lt;/div&gt;
                &lt;/button&gt;
            &lt;/div&gt;
        &lt;/th&gt;
    &lt;/tr&gt;
&lt;/thead&gt;</code></pre>
        </div>
    </div>
</div>

@push('styles')
<link rel="stylesheet" href="{{ asset('css/table-headers.css') }}">
@endpush
@endsection
