<!-- Pagination Section -->
<div class="table-pagination">
    <div class="pagination-content">
        <!-- Pagination Info -->
        <div class="pagination-info">
            <span>
                Showing <strong x-text="startRecord"></strong> to <strong x-text="endRecord"></strong> 
                of <strong x-text="totalRecords"></strong> <?php echo e($itemName ?? 'items'); ?>

            </span>
        </div>
        
        <!-- Page Size Selector -->
        <div class="rows-per-page">
            <label for="pageSize">Rows per page:</label>
            <select id="pageSize" x-model="pageSize" @change="changePageSize($event.target.value)">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
            </select>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="page-navigation">
            <!-- First Page Button -->
            <button 
                type="button" 
                class="pagination-btn" 
                @click="goToPage(1)" 
                :disabled="currentPage === 1"
                title="First page"
            >
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Previous Button -->
            <button 
                type="button" 
                class="pagination-btn" 
                @click="previousPage()" 
                :disabled="currentPage === 1"
                title="Previous page"
            >
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <!-- Page Numbers -->
            <div class="page-numbers">
                <template x-for="page in getPageNumbers()" :key="page">
                    <div>
                        <button 
                            x-show="page !== '...'" 
                            type="button" 
                            class="page-btn" 
                            :class="{ 'active': page === currentPage }"
                            @click="goToPage(page)"
                            x-text="page"
                        ></button>
                        <span x-show="page === '...'" class="page-dots">…</span>
                    </div>
                </template>
            </div>
            
            <!-- Next Button -->
            <button 
                type="button" 
                class="pagination-btn" 
                @click="nextPage()" 
                :disabled="currentPage === totalPages"
                title="Next page"
            >
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
            
            <!-- Last Page Button -->
            <button 
                type="button" 
                class="pagination-btn" 
                @click="goToPage(totalPages)" 
                :disabled="currentPage === totalPages"
                title="Last page"
            >
                <svg class="btn-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/pagination.blade.php ENDPATH**/ ?>