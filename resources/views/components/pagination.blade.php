<div class="table-pagination" x-show="totalPages > 1">
    <div class="pagination-content">
        <div class="pagination-info">
            Showing <strong x-text="startRecord"></strong> to <strong x-text="endRecord"></strong>
            of <strong x-text="totalRecords"></strong>
        </div>

        <div class="pagination-controls">
            <div class="page-size">
                <select class="filter-select" x-model.number="pageSize" @change="currentPage = 1; applyFilters()">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>

            <button class="pagination-btn" @click="previousPage()" :disabled="currentPage === 1">Previous</button>

            <div class="page-numbers">
                <template x-for="page in getPageNumbers()" :key="page">
                    <template x-if="page === '...'">
                        <span class="page-dots">...</span>
                    </template>
                    <template x-if="page !== '...'">
                        <button class="page-btn" :class="{ 'active': page === currentPage }" @click="goToPage(page)" x-text="page"></button>
                    </template>
                </template>
            </div>

            <button class="pagination-btn" @click="nextPage()" :disabled="currentPage === totalPages">Next</button>
        </div>
    </div>
</div>


