<!-- Search and Filters Section -->
<div class="bg-slate-800 border-b border-slate-700 px-6 py-4">
    <div class="flex items-center gap-3 overflow-visible">
        <!-- Search Input -->
        @if($searchable ?? true)
        <div class="flex-1 max-w-sm">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="m21 21-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input 
                    type="text" 
                    class="block w-full pl-10 pr-4 py-2 bg-slate-900 border border-slate-600 rounded-lg text-sm text-gray-200 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-200" 
                    placeholder="{{ $searchPlaceholder ?? 'Search...' }}" 
                    x-model="searchQuery"
                    @input="debouncedSearch()"
                >
            </div>
        </div>
        @endif
        
        <!-- Custom Filters -->
        @if(isset($filters) && count($filters) > 0)
            @foreach($filters as $filter)
            <div class="relative">
                <select 
                    class="appearance-none bg-slate-900 border border-slate-600 rounded-lg px-4 py-2 pr-8 text-sm font-medium text-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-200 hover:border-slate-500 min-w-[140px] cursor-pointer" 
                    x-model="{{ $filter['key'] }}" 
                    @change="setFilter('{{ $filter['key'] }}', $event.target.value)"
                >
                    <option value="" class="bg-slate-900 text-gray-200">{{ $filter['label'] ?? 'All ' . ucfirst($filter['key']) }}</option>
                    @foreach($filter['options'] as $value => $label)
                    <option value="{{ $value }}" class="bg-slate-900 text-gray-200">{{ $label }}</option>
                    @endforeach
                </select>
                <!-- Custom dropdown arrow -->
                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
            @endforeach
        @endif
        
        <!-- Clear Filters Button -->
        <button 
            type="button" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-slate-700 border border-slate-600 rounded-lg text-sm font-medium text-gray-200 hover:bg-slate-600 hover:border-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-500/40 focus:border-blue-500 transition-all duration-200" 
            @click="clearAllFilters()"
        >
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M6 18L18 6M6 6l12 12" />
            </svg>
            Clear Filters
        </button>
    </div>
</div>
