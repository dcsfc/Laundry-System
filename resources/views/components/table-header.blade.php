@props([
    'columns' => [],
    'actions' => false,
    'sortable' => true,
    'colorScheme' => 'gray'
])

<thead class="bg-slate-700 border-b border-slate-600">
    <tr>
        @foreach($columns as $column)
        <th 
            scope="col" 
            class="px-6 py-4 text-left text-sm font-semibold text-slate-200 tracking-wide uppercase select-none"
        >
            @if($sortable && isset($column['sortable']) && $column['sortable'])
            <button 
                type="button" 
                class="flex items-center gap-2 text-slate-300 hover:text-white focus:outline-none"
                @click="sort('{{ $column['key'] ?? $column }}')"
            >
                <span>{{ $column['label'] ?? $column }}</span>

                <!-- Sorting icons container -->
                <div class="flex flex-col">
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="w-3 h-3"
                        :class="{
                            'text-blue-500': sortColumn === '{{ $column['key'] ?? $column }}' && sortDirection === 'asc',
                            'text-slate-400': sortColumn !== '{{ $column['key'] ?? $column }}' || sortDirection !== 'asc'
                        }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                    </svg>

                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="w-3 h-3 -mt-1"
                        :class="{
                            'text-blue-500': sortColumn === '{{ $column['key'] ?? $column }}' && sortDirection === 'desc',
                            'text-slate-400': sortColumn !== '{{ $column['key'] ?? $column }}' || sortDirection !== 'desc'
                        }"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </button>
            @else
            <span>{{ $column['label'] ?? $column }}</span>
            @endif
        </th>
        @endforeach
        @if($actions)
        <th 
            scope="col" 
            class="px-6 py-4 text-sm font-semibold text-slate-200 uppercase"
        >
            Actions
        </th>
        @endif
    </tr>
</thead>
