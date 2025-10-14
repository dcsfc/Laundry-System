<!-- Sort Arrows -->
<div class="sort-arrows">
    <svg class="sort-arrow" 
         :class="{ 'active': sortColumn === '<?php echo e($column); ?>' && sortDirection === 'asc' }" 
         fill="currentColor" viewBox="0 0 20 20">
        <path d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"></path>
    </svg>
    <svg class="sort-arrow" 
         :class="{ 'active': sortColumn === '<?php echo e($column); ?>' && sortDirection === 'desc' }" 
         fill="currentColor" viewBox="0 0 20 20">
        <path d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"></path>
    </svg>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/tables/sort-arrows.blade.php ENDPATH**/ ?>