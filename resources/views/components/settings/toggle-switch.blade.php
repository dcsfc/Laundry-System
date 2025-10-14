@props([
    'title' => '',
    'description' => '',
    'model' => ''
])

<div class="flex items-center justify-between p-4 bg-slate-700/50 rounded-lg">
    <div class="flex-1">
        <h4 class="text-sm font-medium text-white">{{ $title }}</h4>
        @if($description)
            <p class="text-xs text-slate-300 mt-1">{{ $description }}</p>
        @endif
    </div>
    <label class="relative inline-flex items-center cursor-pointer">
        <input 
            type="checkbox" 
            x-model="{{ $model }}"
            @change="markAsChanged()"
            class="sr-only peer"
        >
        <div class="w-11 h-6 bg-slate-600 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-800 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-600 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
    </label>
</div>