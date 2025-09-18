@props([
    'columns' => [],
    'data' => [],
    'actions' => [],
    'title' => 'Data Table',
    'description' => 'Manage your data records',
    'searchable' => true,
    'sortable' => true,
    'pagination' => true,
    'pageSize' => 10
])

<div x-data="{
    data: @js($data),
    columns: @js($columns),
    actions: @js($actions),
    displayedData: [],
    searchTerm: '',
    currentPage: 1,
    pageSize: {{ $pageSize }},
    
    init() {
        console.log('Simple Data Table Init - Data:', this.data);
        console.log('Simple Data Table Init - Columns:', this.columns);
        this.displayedData = [...this.data];
        console.log('Simple Data Table Init - Displayed Data:', this.displayedData);
    },
    
    get filteredData() {
        if (!this.searchTerm) return this.displayedData;
        return this.displayedData.filter(row => 
            Object.values(row).some(value => 
                String(value).toLowerCase().includes(this.searchTerm.toLowerCase())
            )
        );
    },
    
    get paginatedData() {
        const start = (this.currentPage - 1) * this.pageSize;
        const end = start + this.pageSize;
        return this.filteredData.slice(start, end);
    },
    
    get totalPages() {
        return Math.ceil(this.filteredData.length / this.pageSize);
    },
    
    get totalRecords() {
        return this.filteredData.length;
    }
}" class="w-full bg-white rounded-lg shadow-lg p-6">
    <!-- Header -->
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-800 mb-2">{{ $title }}</h2>
        <p class="text-gray-600">{{ $description }}</p>
    </div>
    
    <!-- Search -->
    @if($searchable)
    <div class="mb-4">
        <input 
            x-model="searchTerm" 
            type="text" 
            placeholder="Search..." 
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        >
    </div>
    @endif
    
    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gray-50">
                    <template x-for="column in columns" :key="column.key">
                        <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <span x-text="column.label"></span>
                        </th>
                    </template>
                    @if(count($actions) > 0)
                    <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    @endif
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <template x-for="(row, index) in paginatedData" :key="row.id || index">
                    <tr class="hover:bg-gray-50">
                        <template x-for="column in columns" :key="column.key">
                            <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span x-text="row[column.key] || '-'"></span>
                            </td>
                        </template>
                        @if(count($actions) > 0)
                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div class="flex space-x-2">
                                <template x-for="action in actions" :key="action.label">
                                    <button 
                                        @click="console.log('Action clicked:', action.label, row)"
                                        class="text-blue-600 hover:text-blue-900 text-xs"
                                        x-text="action.label"
                                    ></button>
                                </template>
                            </div>
                        </td>
                        @endif
                    </tr>
                </template>
                
                <tr x-show="paginatedData.length === 0">
                    <td :colspan="columns.length + (actions.length > 0 ? 1 : 0)" class="px-4 py-8 text-center text-gray-500">
                        No data found
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    @if($pagination)
    <div class="flex justify-between items-center mt-6">
        <div class="text-sm text-gray-700">
            Showing <span x-text="totalRecords > 0 ? (currentPage - 1) * pageSize + 1 : 0"></span> - 
            <span x-text="Math.min(currentPage * pageSize, totalRecords)"></span> of <span x-text="totalRecords"></span>
        </div>
        <div class="flex items-center space-x-2">
            <button 
                @click="currentPage = Math.max(1, currentPage - 1)" 
                :disabled="currentPage === 1"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded disabled:opacity-50"
            >Prev</button>
            <template x-for="page in totalPages" :key="page">
                <button 
                    @click="currentPage = page" 
                    :class="page === currentPage ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'"
                    class="px-3 py-1 rounded"
                    x-text="page"
                ></button>
            </template>
            <button 
                @click="currentPage = Math.min(totalPages, currentPage + 1)" 
                :disabled="currentPage === totalPages"
                class="px-3 py-1 bg-gray-200 text-gray-700 rounded disabled:opacity-50"
            >Next</button>
        </div>
    </div>
    @endif
</div>
