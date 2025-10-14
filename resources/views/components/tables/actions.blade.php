<!-- Action Menu -->
<div class="actions-dropdown">
    <button 
        type="button" 
        class="actions-trigger"
        :class="{ 'active': activeMenuRow === (row.id || index) }"
        @click.stop="openActionMenu(row.id || index, $event.currentTarget, row)"
    >
        <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
            <path d="M6 10a2 2 0 11-4 0 2 2 0 014 0zM12 10a2 2 0 11-4 0 2 2 0 014 0zM16 12a2 2 0 100-4 2 2 0 000 4z"/>
        </svg>
    </button>
</div>
