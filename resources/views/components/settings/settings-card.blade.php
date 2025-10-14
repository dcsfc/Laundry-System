@props([
    'title' => '',
    'description' => '',
    'icon' => 'fas fa-cog'
])

<div class="bg-slate-800/40 backdrop-blur-xl rounded-2xl shadow-2xl border border-slate-700/50 hover:border-slate-600/50 transition-all duration-300 hover:shadow-indigo-500/10">
    <div class="px-8 py-6 border-b border-slate-700/50 bg-gradient-to-r from-slate-800/20 to-slate-700/20 rounded-t-2xl">
        <div class="flex items-center gap-3">
            <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                <i class="{{ $icon }} text-white text-sm"></i>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-white">{{ $title }}</h3>
                <p class="text-sm text-slate-300">{{ $description }}</p>
            </div>
        </div>
    </div>
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
