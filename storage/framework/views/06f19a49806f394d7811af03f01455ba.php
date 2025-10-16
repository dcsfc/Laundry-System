<?php $__env->startSection('title', 'Weekly Reports - Staff'); ?>

<?php $__env->startPush('styles'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/css/tables.css', 'resources/css/search-filters.css', 'resources/css/status-badges.css']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php echo app('Illuminate\Foundation\Vite')(['resources/js/modules/table/action-menu.js', 'resources/js/modules/table/tables-modular.js', 'resources/js/modules/notifications/modern-notifications.js']); ?>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<!-- Weekly Reports using Reusable Data Table -->
<?php if (isset($component)) { $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae = $attributes; } ?>
<?php $component = App\View\Components\DataTable::resolve(['columns' => $columns,'data' => $reports,'actions' => $actions,'bulkActions' => false,'searchable' => true,'sortable' => true,'pagination' => true,'pageSize' => 10,'emptyMessage' => 'No reports found','emptyDescription' => 'Generate your first weekly report to get started','hoverEffects' => true,'alternatingRows' => true,'stickyHeader' => true,'customClass' => 'bg-slate-800 text-slate-200','title' => 'Weekly Reports','description' => 'View and analyze your weekly performance reports','addButton' => true,'addButtonLabel' => 'Generate Report','addButtonAction' => 'generateReport','formType' => 'report','colorScheme' => 'sky'] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('data-table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\DataTable::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $attributes = $__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__attributesOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae)): ?>
<?php $component = $__componentOriginalb539fdd4bceece4a667dd360eb69c7ae; ?>
<?php unset($__componentOriginalb539fdd4bceece4a667dd360eb69c7ae); ?>
<?php endif; ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.querySelector('[data-datatable]');
    
    // Handle report-specific actions
    container.addEventListener('datatable:action', function(e) {
        const { rowId, action, row } = e.detail;
        
        switch(action) {
            case 'view':
                viewReport(row);
                break;
            case 'export':
                exportReport(row);
                break;
        }
    });
});

// Report Management Functions
function viewReport(row) {
    console.log('View report:', row);
    
    // Fetch detailed report information
    fetch(`/staff/reports/${row.id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const report = data.report;
                let reportDetails = `Weekly Report Details:\n\n` +
                      `Week Period: ${report.week_period}\n` +
                      `Total Orders: ${report.total_orders}\n` +
                      `Completed Orders: ${report.completed_orders}\n` +
                      `Total Revenue: ₱${report.total_revenue}\n\n`;
                
                if (report.orders && report.orders.length > 0) {
                    reportDetails += `Recent Orders:\n`;
                    report.orders.slice(0, 5).forEach(order => {
                        reportDetails += `• Order #${order.id} - ${order.customer_name} - ₱${order.total_price}\n`;
                    });
                    if (report.orders.length > 5) {
                        reportDetails += `... and ${report.orders.length - 5} more orders\n`;
                    }
                }
                
                alert(reportDetails);
            }
        })
        .catch(error => {
            console.error('Error fetching report details:', error);
            alert('Error loading report details');
        });
}

function exportReport(row) {
    console.log('Export report:', row);
    
    fetch(`/staff/reports/${row.id}/export`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Report export for ${data.week_period} would be downloaded here.\n\nNote: Export functionality needs to be implemented.`);
            } else {
                alert('Error exporting report');
            }
        })
        .catch(error => {
            console.error('Error exporting report:', error);
            alert('Error exporting report');
        });
}

function generateReport() {
    console.log('Generate new report');
    window.location.href = '/staff/reports/create';
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/staff/reports/index.blade.php ENDPATH**/ ?>