@extends('layouts.sidebar')

@section('title', 'Schedule History')

@section('content')
    <div class="container">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-emerald-400 to-teal-400 bg-clip-text text-transparent mb-3">Schedule History</h1>
                    <p class="text-slate-400 text-lg">Your completed and cancelled laundry orders</p>
                </div>
                <div class="flex items-center gap-3">
                </div>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 mb-8 shadow-2xl">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <!-- Filter Tabs -->
                <div class="flex items-center gap-2">
                    <button onclick="filterSchedules('all')" 
                            id="filterAll"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all bg-slate-700 text-white">
                        All (<span id="filterAllCount">0</span>)
                    </button>
                    <button onclick="filterSchedules('completed')" 
                            id="filterCompleted"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white hover:bg-slate-700/50">
                        Completed (<span id="filterCompletedCount">0</span>)
                    </button>
                    <button onclick="filterSchedules('cancelled')" 
                            id="filterCancelled"
                            class="px-4 py-2 rounded-lg text-sm font-medium transition-all text-slate-400 hover:text-white hover:bg-slate-700/50">
                        Cancelled (<span id="filterCancelledCount">0</span>)
                    </button>
                </div>

                <!-- Search Box -->
                <div class="relative max-w-md">
                    <input type="text" 
                           id="searchInput"
                           placeholder="Search orders..." 
                           class="w-full bg-slate-700/50 border border-slate-600/50 rounded-lg px-4 py-2 pl-10 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 transition-all">
                    <div class="absolute left-3 top-1/2 transform -translate-y-1/2">
                        <i class="fas fa-search text-slate-400 text-sm"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Area -->
        <div id="contentArea">
            <!-- Desktop Table View -->
            <div class="hidden lg:block bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-700/50">
                        <thead class="bg-slate-700/50 border-b border-slate-600/50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-hashtag text-slate-400"></i>
                                        Order ID
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-concierge-bell text-slate-400"></i>
                                        Service
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-arrow-down text-slate-400"></i>
                                        Drop-off
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-arrow-up text-slate-400"></i>
                                        Pickup
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-info-circle text-slate-400"></i>
                                        Status
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-300 uppercase tracking-wider">
                                    <div class="flex items-center gap-2">
                                        <i class="fas fa-peso-sign text-slate-400"></i>
                                        Amount
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="desktopTableBody" class="bg-slate-800/50 divide-y divide-slate-700/50">
                            <!-- Table rows will be populated by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Mobile Card View -->
            <div id="mobileCardView" class="lg:hidden space-y-6">
                <!-- Mobile cards will be populated by JavaScript -->
            </div>

            <!-- Pagination -->
            <div id="paginationContainer" class="mt-8 flex items-center justify-between bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 shadow-2xl hidden">
                <div class="text-sm text-slate-300">
                    Showing <span id="paginationInfo" class="font-medium text-white">0 to 0 of 0</span> orders
                </div>
                <div class="flex items-center gap-2">
                    <button id="prevPageBtn" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-slate-700/50 disabled:hover:text-slate-300" disabled>
                        <i class="fas fa-chevron-left mr-1"></i>
                        Previous
                    </button>
                    <div id="pageNumbers" class="flex items-center gap-1">
                        <!-- Page numbers will be populated by JavaScript -->
                    </div>
                    <button id="nextPageBtn" class="px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:bg-slate-700/50 disabled:hover:text-slate-300" disabled>
                        Next
                        <i class="fas fa-chevron-right ml-1"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-12 shadow-2xl hidden">
            <div class="text-center max-w-md mx-auto">
                <div class="w-20 h-20 bg-gradient-to-br from-slate-500/20 to-slate-600/20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-500/30">
                    <i class="fas fa-history text-slate-400 text-3xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-white mb-3">No Order History</h3>
                <p class="text-slate-400 mb-6">You haven't completed or cancelled any orders yet. Your order history will appear here once you have orders.</p>
                <a href="{{ route('customer.schedules.index') }}" 
                   class="inline-flex items-center bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-600 hover:to-teal-600 text-white px-6 py-3 rounded-xl font-medium transition-all hover:shadow-lg hover:shadow-emerald-500/25">
                    <i class="fas fa-calendar-plus mr-2"></i>
                    Schedule New Order
                </a>
            </div>
        </div>
    </div>

    <script>
        // All schedule data from server
        const allSchedules = @json($allSchedules);
        console.log('All Schedules Data:', allSchedules);
        
        // Current state
        let currentFilter = 'all';
        let currentSearch = '';
        let currentPage = 1;
        const itemsPerPage = 5;
        
        // Initialize the page
        document.addEventListener('DOMContentLoaded', function() {
            updateFilterCounts(); // Set initial counts
            renderSchedules();
        });
        
        // Filter by status
        function filterSchedules(status) {
            currentFilter = status;
            currentPage = 1; // Reset to first page
            
            // Update button styles
            updateFilterButtons();
            
            // Re-render (counts should remain static)
            renderSchedules();
        }
        
        // Update filter button styles
        function updateFilterButtons() {
            const buttons = ['filterAll', 'filterCompleted', 'filterCancelled'];
            const activeClasses = ['bg-slate-700 text-white', 'bg-emerald-600 text-white', 'bg-red-600 text-white'];
            const inactiveClasses = 'text-slate-400 hover:text-white hover:bg-slate-700/50';
            
            buttons.forEach((buttonId, index) => {
                const button = document.getElementById(buttonId);
                if (currentFilter === ['all', 'completed', 'cancelled'][index]) {
                    button.className = `px-4 py-2 rounded-lg text-sm font-medium transition-all ${activeClasses[index]}`;
                } else {
                    button.className = `px-4 py-2 rounded-lg text-sm font-medium transition-all ${inactiveClasses}`;
                }
            });
        }
        
        // Update filter counts - always show total counts, not filtered counts
        function updateFilterCounts() {
            // Always count from the full dataset, regardless of current filter
            const allCount = allSchedules.length;
            const completedCount = allSchedules.filter(s => s.status === 'Completed').length;
            const cancelledCount = allSchedules.filter(s => s.status === 'Cancelled').length;
            
            // Update the count displays - these should always show the total counts
            document.getElementById('filterAllCount').textContent = allCount;
            document.getElementById('filterCompletedCount').textContent = completedCount;
            document.getElementById('filterCancelledCount').textContent = cancelledCount;
        }
        
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            currentSearch = e.target.value.toLowerCase();
            currentPage = 1; // Reset to first page
            renderSchedules();
        });
        
        // Get filtered schedules
        function getFilteredSchedules() {
            let filtered = allSchedules;
            
            // Apply status filter
            if (currentFilter !== 'all') {
                filtered = filtered.filter(schedule => schedule.status_raw === currentFilter);
            }
            
            // Apply search filter
            if (currentSearch) {
                filtered = filtered.filter(schedule => 
                    schedule.searchable_text.includes(currentSearch)
                );
            }
            
            return filtered;
        }
        
        // Render schedules
        function renderSchedules() {
            const filteredSchedules = getFilteredSchedules();
            const totalItems = filteredSchedules.length;
            const totalPages = Math.ceil(totalItems / itemsPerPage);
            
            console.log('Render Debug:', { 
                totalItems, 
                totalPages, 
                itemsPerPage, 
                filteredSchedulesLength: filteredSchedules.length,
                allSchedulesLength: allSchedules.length 
            });
            
            // Stats are updated once on page load, not on every render
            
            // Show/hide content
            if (totalItems === 0) {
                document.getElementById('contentArea').classList.add('hidden');
                document.getElementById('emptyState').classList.remove('hidden');
                return;
            } else {
                document.getElementById('contentArea').classList.remove('hidden');
                document.getElementById('emptyState').classList.add('hidden');
            }
            
            // Get current page data
            const startIndex = (currentPage - 1) * itemsPerPage;
            const endIndex = startIndex + itemsPerPage;
            const currentPageData = filteredSchedules.slice(startIndex, endIndex);
            
            // Render desktop table
            renderDesktopTable(currentPageData);
            
            // Render mobile cards
            renderMobileCards(currentPageData);
            
            // Render pagination
            renderPagination(totalItems, totalPages);
        }
        
        // updateStats function removed - counts should remain static
        
        // Render desktop table
        function renderDesktopTable(schedules) {
            const tbody = document.getElementById('desktopTableBody');
            tbody.innerHTML = '';
            
            schedules.forEach(schedule => {
                const row = document.createElement('tr');
                row.className = 'hover:bg-slate-700/30 transition-all duration-200 group';
                
                const statusColor = schedule.status === 'Completed' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30';
                
                row.innerHTML = `
                    <!-- Order ID -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-slate-700/50 flex items-center justify-center">
                                    <span class="text-xs font-medium text-slate-300">#${schedule.id}</span>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-white">Order #${schedule.id}</div>
                                <div class="text-xs text-slate-400">${schedule.reference_id}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Service -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-teal-500/20 flex items-center justify-center">
                                    <i class="fas fa-concierge-bell text-teal-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-white">${schedule.service_type}</div>
                                <div class="text-xs text-slate-400">Laundry Service</div>
                            </div>
                        </div>
                    </td>

                    <!-- Drop-off -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-blue-500/20 flex items-center justify-center">
                                    <i class="fas fa-arrow-down text-blue-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-white">${schedule.dropoff_date}</div>
                                ${schedule.dropoff_time ? `<div class="text-xs text-slate-400">${schedule.dropoff_time}</div>` : ''}
                            </div>
                        </div>
                    </td>

                    <!-- Pickup -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-emerald-500/20 flex items-center justify-center">
                                    <i class="fas fa-arrow-up text-emerald-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-white">${schedule.pickup_date}</div>
                                ${schedule.pickup_time ? `<div class="text-xs text-slate-400">${schedule.pickup_time}</div>` : ''}
                            </div>
                        </div>
                    </td>

                    <!-- Status -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full ${schedule.status === 'Completed' ? 'bg-emerald-500/20' : 'bg-red-500/20'} flex items-center justify-center">
                                    <i class="fas ${schedule.status === 'Completed' ? 'fa-check-circle' : 'fa-times-circle'} ${schedule.status === 'Completed' ? 'text-emerald-400' : 'text-red-400'} text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border ${statusColor}">
                                    <div class="w-2 h-2 rounded-full mr-2 ${schedule.status === 'Completed' ? 'bg-emerald-400' : 'bg-red-400'}"></div>
                                    ${schedule.status}
                                </span>
                            </div>
                        </div>
                    </td>

                    <!-- Amount -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8">
                                <div class="h-8 w-8 rounded-full bg-yellow-500/20 flex items-center justify-center">
                                    <i class="fas fa-peso-sign text-yellow-400 text-xs"></i>
                                </div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-white">₱${parseFloat(schedule.total_amount).toFixed(2)}</div>
                            </div>
                        </div>
                    </td>
                `;
                
                tbody.appendChild(row);
            });
        }
        
        // Render mobile cards
        function renderMobileCards(schedules) {
            const container = document.getElementById('mobileCardView');
            container.innerHTML = '';
            
            schedules.forEach(schedule => {
                const statusColor = schedule.status === 'Completed' ? 'bg-emerald-500/20 text-emerald-400 border-emerald-500/30' : 'bg-red-500/20 text-red-400 border-red-500/30';
                
                const card = document.createElement('div');
                card.className = 'group bg-gradient-to-br from-slate-800/90 to-slate-900/90 backdrop-blur-xl border border-slate-700/50 rounded-2xl p-6 hover:border-slate-600/50 transition-all hover:shadow-lg hover:shadow-slate-500/10';
                
                card.innerHTML = `
                    <!-- Order Header -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 rounded-xl flex items-center justify-center border border-indigo-500/30">
                                <i class="fas fa-receipt text-indigo-400 text-lg"></i>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-white">Order #${schedule.id}</h3>
                                <p class="text-sm text-slate-400">${schedule.reference_id}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-semibold border ${statusColor}">
                            <div class="w-2 h-2 rounded-full mr-2 ${schedule.status === 'Completed' ? 'bg-emerald-400' : 'bg-red-400'}"></div>
                            ${schedule.status}
                        </span>
                    </div>

                    <!-- Order Details -->
                    <div class="space-y-4">
                        <!-- Service Type -->
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center border border-blue-500/30">
                                <i class="fas fa-tshirt text-blue-400 text-sm"></i>
                            </div>
                            <div>
                                <div class="text-sm font-medium text-white">${schedule.service_type}</div>
                                <div class="text-xs text-slate-400">Service Type</div>
                            </div>
                        </div>

                        <!-- Dates -->
                        <div class="grid grid-cols-2 gap-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-500/20 rounded-lg flex items-center justify-center border border-blue-500/30">
                                    <i class="fas fa-arrow-down text-blue-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">${schedule.dropoff_date}</div>
                                    <div class="text-xs text-slate-400">Drop-off</div>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-500/20 rounded-lg flex items-center justify-center border border-emerald-500/30">
                                    <i class="fas fa-arrow-up text-emerald-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-sm font-medium text-white">${schedule.pickup_date}</div>
                                    <div class="text-xs text-slate-400">Pickup</div>
                                </div>
                            </div>
                        </div>

                        <!-- Price -->
                        <div class="flex items-center justify-between pt-4 border-t border-slate-700/50">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-500/20 rounded-lg flex items-center justify-center border border-green-500/30">
                                    <i class="fas fa-dollar-sign text-green-400 text-sm"></i>
                                </div>
                                <div>
                                    <div class="text-lg font-bold text-white">₱${parseFloat(schedule.total_amount).toFixed(2)}</div>
                                    <div class="text-xs text-slate-400">Total Amount</div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                container.appendChild(card);
            });
        }
        
        // Render pagination
        function renderPagination(totalItems, totalPages) {
            const container = document.getElementById('paginationContainer');
            const info = document.getElementById('paginationInfo');
            const prevBtn = document.getElementById('prevPageBtn');
            const nextBtn = document.getElementById('nextPageBtn');
            const pageNumbers = document.getElementById('pageNumbers');
            
            console.log('Pagination Debug:', { totalItems, totalPages, itemsPerPage });
            
            if (totalPages <= 1) {
                container.classList.add('hidden');
                console.log('Hiding pagination - only 1 page or less');
                return;
            }
            
            container.classList.remove('hidden');
            console.log('Showing pagination');
            
            // Update pagination info
            const startItem = (currentPage - 1) * itemsPerPage + 1;
            const endItem = Math.min(currentPage * itemsPerPage, totalItems);
            info.textContent = `${startItem} to ${endItem} of ${totalItems}`;
            
            // Update prev/next buttons
            prevBtn.disabled = currentPage === 1;
            nextBtn.disabled = currentPage === totalPages;
            
            prevBtn.className = prevBtn.disabled 
                ? 'px-4 py-2 bg-slate-700/50 text-slate-500 rounded-lg cursor-not-allowed opacity-50'
                : 'px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors';
                
            nextBtn.className = nextBtn.disabled 
                ? 'px-4 py-2 bg-slate-700/50 text-slate-500 rounded-lg cursor-not-allowed opacity-50'
                : 'px-4 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors';
            
            // Update page numbers
            pageNumbers.innerHTML = '';
            const maxVisiblePages = 5;
            let startPage = Math.max(1, currentPage - Math.floor(maxVisiblePages / 2));
            let endPage = Math.min(totalPages, startPage + maxVisiblePages - 1);
            
            if (endPage - startPage + 1 < maxVisiblePages) {
                startPage = Math.max(1, endPage - maxVisiblePages + 1);
            }
            
            for (let i = startPage; i <= endPage; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.textContent = i;
                pageBtn.className = i === currentPage 
                    ? 'px-3 py-2 bg-emerald-500/20 border border-emerald-500/50 text-emerald-400 rounded-lg font-medium'
                    : 'px-3 py-2 bg-slate-700/50 hover:bg-slate-600/50 text-slate-300 hover:text-white rounded-lg transition-colors';
                pageBtn.onclick = () => goToPage(i);
                pageNumbers.appendChild(pageBtn);
            }
        }
        
        // Go to specific page
        function goToPage(page) {
            currentPage = page;
            renderSchedules();
        }
        
        // Previous page
        document.getElementById('prevPageBtn').addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderSchedules();
            }
        });
        
        // Next page
        document.getElementById('nextPageBtn').addEventListener('click', function() {
            const filteredSchedules = getFilteredSchedules();
            const totalPages = Math.ceil(filteredSchedules.length / itemsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                renderSchedules();
            }
        });
    </script>
@endsection