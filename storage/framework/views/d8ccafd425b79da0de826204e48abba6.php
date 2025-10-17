<!-- Action Menu - Direct action buttons with SVG icons -->
<div class="actions-dropdown">
    <!-- Direct action buttons instead of dropdown -->
    <div class="direct-actions">
        <template x-for="action in (row.actions || actions)" :key="action.key">
            <button 
                type="button"
                class="action-button"
                :class="`action-${action.color || 'blue'}`"
                @click="handleAction(action.key, row)"
                :title="action.label"
                x-html="getActionIconSvg(action.icon)"
            >
            </button>
        </template>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/actions.blade.php ENDPATH**/ ?>