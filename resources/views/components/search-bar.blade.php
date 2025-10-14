<div class="table-filters">
    <div class="filters-content">
        <div class="search-group">
            <div class="search-input-wrapper">
                <div class="search-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" class="search-input" placeholder="Search name or email..." x-model="searchQuery" @input="search()">
            </div>
        </div>

        <select class="filter-select" x-model="statusFilter" @change="applyFilters()">
            <option value="">All Status</option>
            @foreach(($statuses ?? ['active','inactive']) as $status)
                <option value="{{ $status }}">{{ ucfirst($status) }}</option>
            @endforeach
        </select>

        <select class="filter-select" x-model="roleFilter" @change="applyFilters()">
            <option value="">All Roles</option>
            @foreach(($roles ?? []) as $role)
                <option value="{{ $role }}">{{ ucfirst($role) }}</option>
            @endforeach
        </select>

        <button class="btn btn-secondary btn-sm" @click="clearAllFilters()">Clear</button>
    </div>
</div>


