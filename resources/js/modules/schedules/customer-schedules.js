// Customer Schedules JavaScript
// Enhanced with proper error handling and null checks

document.addEventListener('DOMContentLoaded', function() {
    console.log('Customer schedules page loaded');
    
    // Initialize character counter for cancellation reason
    const cancellationReason = document.getElementById('cancellation_reason');
    const charCount = document.getElementById('char_count');
    
    if (cancellationReason && charCount) {
        cancellationReason.addEventListener('input', function() {
            const current = this.value.length;
            const maxLength = 200;
            charCount.textContent = `${current}/${maxLength}`;
            
            if (current > maxLength * 0.8) {
                charCount.classList.add('text-red-500');
            } else {
                charCount.classList.remove('text-red-500');
            }
        });
    }
    
    // Initialize form validation
    initializeFormValidation();
});

// Toast notification function
function showToast(message, type = 'info') {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `fixed top-4 right-4 z-[10000] px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
    
    // Set colors based on type
    switch(type) {
        case 'success':
            toast.classList.add('bg-green-500', 'text-white');
            break;
        case 'error':
            toast.classList.add('bg-red-500', 'text-white');
            break;
        case 'warning':
            toast.classList.add('bg-yellow-500', 'text-black');
            break;
        default:
            toast.classList.add('bg-blue-500', 'text-white');
    }
    
    toast.textContent = message;
    document.body.appendChild(toast);
    
    // Animate in
    setTimeout(() => {
        toast.classList.remove('translate-x-full');
    }, 100);
    
    // Remove after 3 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 300);
    }, 3000);
}

// Enhanced Modal functions with animations
function openScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    if (!modal || !modalContent) {
        console.error('Modal elements not found:', { modal, modalContent });
        showToast('Modal not found. Please refresh the page.', 'error');
        return;
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Add blur effect to sidebar
    const sidebar = document.querySelector('.sidebar, [class*="sidebar"], aside');
    if (sidebar) {
        sidebar.style.filter = 'blur(4px)';
        sidebar.style.transition = 'filter 0.3s ease';
    }
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
}

function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    if (!modal || !modalContent) {
        console.error('Modal elements not found for closing');
        return;
    }
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    // Remove blur effect from sidebar
    const sidebar = document.querySelector('.sidebar, [class*="sidebar"], aside');
    if (sidebar) {
        sidebar.style.filter = 'none';
    }
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openViewModal(scheduleId) {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    if (!modal || !modalContent) {
        console.error('View modal elements not found');
        showToast('View modal not found. Please refresh the page.', 'error');
        return;
    }
    
    // Get schedule data from the table row
    const schedule = getScheduleData(scheduleId);
    if (schedule) {
        populateViewModal(schedule);
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
}

function closeViewModal() {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    if (!modal || !modalContent) {
        console.error('View modal elements not found for closing');
        return;
    }
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openEditModal(scheduleId) {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    
    if (!modal || !modalContent) {
        console.error('Edit modal elements not found');
        showToast('Edit modal not found. Please refresh the page.', 'error');
        return;
    }
    
    // Get schedule data from the table row
    const schedule = getScheduleData(scheduleId);
    if (schedule) {
        populateEditModal(schedule);
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    
    if (!modal || !modalContent) {
        console.error('Edit modal elements not found for closing');
        return;
    }
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openDeleteModal(scheduleId) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    if (!modal || !modalContent) {
        console.error('Delete modal elements not found');
        showToast('Delete modal not found. Please refresh the page.', 'error');
        return;
    }
    
    // Set the schedule ID for deletion
    const deleteScheduleIdEl = document.getElementById('delete_schedule_id');
    if (deleteScheduleIdEl) {
        deleteScheduleIdEl.value = scheduleId;
    }
    
    // Add blur effect to sidebar
    const sidebar = document.querySelector('.sidebar, [class*="sidebar"], aside');
    if (sidebar) {
        sidebar.style.filter = 'blur(4px)';
        sidebar.style.transition = 'filter 0.3s ease';
    }
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    if (!modal || !modalContent) {
        console.error('Delete modal elements not found for closing');
        return;
    }
    
    // Remove blur effect from sidebar
    const sidebar = document.querySelector('.sidebar, [class*="sidebar"], aside');
    if (sidebar) {
        sidebar.style.filter = 'none';
    }
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

// Get schedule data from table row
function getScheduleData(scheduleId) {
    const row = document.querySelector(`tr[data-schedule-id="${scheduleId}"]`);
    if (!row) {
        console.error('Schedule row not found for ID:', scheduleId);
        return null;
    }
    
    const cells = row.querySelectorAll('td');
    if (cells.length < 8) {
        console.error('Invalid table row structure');
        return null;
    }
    
    // Extract data from table cells (columns: ID, Customer, Service, Drop-off, Pickup, Weight, Price, Actions)
    const serviceCell = cells[2]; // Service column
    const dropoffCell = cells[3]; // Drop-off column  
    const pickupCell = cells[4]; // Pickup column
    const weightCell = cells[5]; // Weight column
    const priceCell = cells[6]; // Price column
    
    // Extract service type from service cell
    const serviceType = serviceCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '-';
    
    // Extract dropoff info (date only)
    const dropoffDate = dropoffCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '-';
    
    // Extract pickup info (date only)
    const pickupDate = pickupCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '-';
    
    // Extract weight
    const weight = weightCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '-';
    
    // Extract price
    const price = priceCell.querySelector('.text-sm.font-medium')?.textContent.trim() || '-';
    
    console.log('Extracted schedule data:', {
        dropoffDate,
        pickupDate
    });

    return {
        id: scheduleId,
        service_id: serviceType,
        dropoff_date: dropoffDate,
        pickup_date: pickupDate,
        weight: weight,
        price: price,
        status: 'scheduled', // Default status - you might want to get this from the data
        payment_status: 'unpaid' // Default payment status - you might want to get this from the data
    };
}

// Populate view modal with schedule data
function populateViewModal(schedule) {
    const elements = {
        view_schedule_id: document.getElementById('view_schedule_id'),
        view_service_id: document.getElementById('view_service_id'),
        view_dropoff_info: document.getElementById('view_dropoff_info'),
        view_pickup_info: document.getElementById('view_pickup_info'),
        view_weight: document.getElementById('view_weight'),
        view_price: document.getElementById('view_price'),
        view_status: document.getElementById('view_status'),
        view_payment_status: document.getElementById('view_payment_status')
    };
    
    // Check if all elements exist
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Element not found: ${key}`);
            showToast(`View modal element not found: ${key}`, 'error');
            return;
        }
    }
    
    // Populate the modal with better formatting
    elements.view_schedule_id.textContent = schedule.id;
    elements.view_service_id.textContent = schedule.service_id;
    
    // Format dropoff info (date only)
    elements.view_dropoff_info.textContent = `${schedule.dropoff_date} at 9:00 AM`;
    
    // Format pickup info (date only)
    elements.view_pickup_info.textContent = `${schedule.pickup_date} at 5:00 PM`;
    
    elements.view_weight.textContent = schedule.weight;
    elements.view_price.textContent = schedule.price;
    elements.view_status.textContent = schedule.status.charAt(0).toUpperCase() + schedule.status.slice(1).replace('_', ' ');
    elements.view_payment_status.textContent = schedule.payment_status.charAt(0).toUpperCase() + schedule.payment_status.slice(1);
}

// Populate edit modal with schedule data
function populateEditModal(schedule) {
    const elements = {
        edit_schedule_id: document.getElementById('edit_schedule_id'),
        edit_service_id: document.getElementById('edit_service_id'),
        edit_dropoff_date: document.getElementById('edit_dropoff_date'),
        edit_pickup_date: document.getElementById('edit_pickup_date')
    };
    
    // Check if all elements exist
    for (const [key, element] of Object.entries(elements)) {
        if (!element) {
            console.error(`Edit modal element not found: ${key}`);
            showToast(`Edit modal element not found: ${key}`, 'error');
            return;
        }
    }
    
    // Populate the modal with current values
    elements.edit_schedule_id.value = schedule.id;
    
    // Map service type to service ID
    const serviceMapping = {
        'Wash & Fold': '1',
        'Wash & Press': '2', 
        'Dry Clean': '3',
        'Express Service': '4'
    };
    elements.edit_service_id.value = serviceMapping[schedule.service_id] || '';
    
    // Parse dates from the schedule data
    const dropoffData = parseDateTime(schedule.dropoff_date);
    const pickupData = parseDateTime(schedule.pickup_date);
    
    elements.edit_dropoff_date.value = dropoffData.date;
    elements.edit_pickup_date.value = pickupData.date;
}

// Parse date from formatted string
function parseDateTime(dateString) {
    // Handle date format: "Oct 15, 2024"
    let formattedDate = '';
    if (dateString && dateString !== '-') {
        const date = new Date(dateString);
        if (!isNaN(date.getTime())) {
            formattedDate = date.toISOString().split('T')[0];
        }
    }
    
    return {
        date: formattedDate
    };
}

// Generate status timeline
function generateStatusTimeline(currentStatus, schedule) {
    const timeline = document.getElementById('status-timeline');
    if (!timeline) {
        console.error('Status timeline element not found');
        return;
    }
    
    const statuses = [
        { key: 'scheduled', label: 'Scheduled', icon: 'fas fa-calendar-plus' },
        { key: 'priced', label: 'Priced', icon: 'fas fa-tag' },
        { key: 'in_progress', label: 'In Progress', icon: 'fas fa-spinner' },
        { key: 'completed', label: 'Completed', icon: 'fas fa-check-circle' },
        { key: 'canceled', label: 'Canceled', icon: 'fas fa-times-circle' }
    ];
    
    const currentIndex = statuses.findIndex(s => s.key === currentStatus);
    
    timeline.innerHTML = statuses.map((status, index) => {
        const isActive = index <= currentIndex;
        const isCurrent = index === currentIndex;
        const isCompleted = index < currentIndex;
        
        return `
            <div class="flex items-center ${index < statuses.length - 1 ? 'mb-4' : ''}">
                <div class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center ${
                    isActive ? 'bg-slate-700' : 'bg-slate-600/50'
                }">
                    <i class="${status.icon} text-sm ${
                        isActive ? 'text-white' : 'text-slate-400'
                    }"></i>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium ${
                        isActive ? 'text-white' : 'text-slate-400'
                    }">
                        ${status.label}
                    </p>
                    ${isCurrent ? `
                        <p class="text-xs text-slate-400 mt-1">Current</p>
                    ` : isCompleted ? `
                        <p class="text-xs text-green-400 mt-1">Completed</p>
                    ` : `
                        <p class="text-xs text-slate-500 mt-1">Pending</p>
                    `}
                </div>
            </div>
        `;
    }).join('');
}

// Form submission functions
function submitScheduleForm() {
    const form = document.getElementById('scheduleForm');
    if (!form) {
        console.error('Schedule form not found');
        showToast('Form not found. Please refresh the page.', 'error');
        return;
    }
    
    const formData = new FormData(form);
    
    // Validate required fields with better error messages
    const requiredFields = [
        { field: 'service_id', message: 'Please select a service type' },
        { field: 'dropoff_date', message: 'Please select a drop-off date' },
        { field: 'pickup_date', message: 'Please select a pickup date' }
    ];
    
    for (const { field, message } of requiredFields) {
        if (!formData.get(field)) {
            showToast(message, 'error');
            return;
        }
    }
    
    // Validate dates
    const dropoffDate = new Date(formData.get('dropoff_date'));
    const pickupDate = new Date(formData.get('pickup_date'));
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to start of day
    
    // Check if drop-off date is in the past
    if (dropoffDate < today) {
        showToast('Drop-off date cannot be in the past', 'error');
        return;
    }
    
    // Check if pickup date is before or same as drop-off date
    if (pickupDate <= dropoffDate) {
        showToast('Pickup date must be after the drop-off date', 'error');
        return;
    }
    
    // Check if pickup date is too far in the future (optional: 30 days limit)
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
    if (pickupDate > maxDate) {
        showToast('Pickup date cannot be more than 30 days from today', 'error');
        return;
    }
    
    // Submit form
    fetch(window.scheduleStoreRoute, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Schedule created successfully!', 'success');
            closeScheduleModal();
            form.reset();
            // Refresh the page to show new schedule
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to create schedule.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
}

function submitEditForm() {
    const form = document.getElementById('editScheduleForm');
    if (!form) {
        console.error('Edit form not found');
        showToast('Edit form not found. Please refresh the page.', 'error');
        return;
    }
    
    const formData = new FormData(form);
    
    // Validate required fields with better error messages
    const requiredFields = [
        { field: 'service_id', message: 'Please select a service type' },
        { field: 'dropoff_date', message: 'Please select a drop-off date' },
        { field: 'pickup_date', message: 'Please select a pickup date' }
    ];
    
    for (const { field, message } of requiredFields) {
        if (!formData.get(field)) {
            showToast(message, 'error');
            return;
        }
    }
    
    // Validate dates
    const dropoffDate = new Date(formData.get('dropoff_date'));
    const pickupDate = new Date(formData.get('pickup_date'));
    const today = new Date();
    today.setHours(0, 0, 0, 0); // Reset time to start of day
    
    // For editing, allow same day drop-off (more flexible than new schedule)
    if (dropoffDate < today) {
        showToast('Drop-off date cannot be in the past', 'error');
        return;
    }
    
    // Check if pickup date is before or same as drop-off date
    if (pickupDate <= dropoffDate) {
        showToast('Pickup date must be after the drop-off date', 'error');
        return;
    }
    
    // Check if pickup date is too far in the future (optional: 30 days limit)
    const maxDate = new Date();
    maxDate.setDate(maxDate.getDate() + 30);
    if (pickupDate > maxDate) {
        showToast('Pickup date cannot be more than 30 days from today', 'error');
        return;
    }
    
    // Additional validation for edit: check if schedule ID exists
    const scheduleId = formData.get('schedule_id');
    if (!scheduleId) {
        showToast('Schedule ID is missing. Please refresh and try again.', 'error');
        return;
    }
    
    // Time validation removed - using default times
    
    // Submit form
    const updateRoute = window.scheduleUpdateRoute.replace(':schedule', scheduleId);
    fetch(updateRoute, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Schedule updated successfully!', 'success');
            closeEditModal();
            // Refresh the page to show updated schedule
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to update schedule.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
}

function submitDeleteForm() {
    const form = document.getElementById('deleteForm');
    if (!form) {
        console.error('Delete form not found');
        showToast('Delete form not found. Please refresh the page.', 'error');
        return;
    }
    
    const formData = new FormData(form);
    
    // Validate schedule ID
    const scheduleId = formData.get('schedule_id');
    if (!scheduleId) {
        showToast('Schedule ID is missing. Please refresh and try again.', 'error');
        return;
    }
    
    // Validate cancellation reason (optional)
    const cancellationReason = formData.get('cancellation_reason');
    
    // Only validate length if reason is provided
    if (cancellationReason && cancellationReason.trim() !== '') {
        if (cancellationReason.trim().length < 10) {
            showToast('Please provide a more detailed reason (at least 10 characters) or leave it empty.', 'error');
            return;
        }
        
        if (cancellationReason.length > 200) {
            showToast('Cancellation reason is too long (maximum 200 characters).', 'error');
            return;
        }
    }
    
    // Submit form
    const deleteRoute = window.scheduleDeleteRoute.replace(':schedule', scheduleId);
    fetch(deleteRoute, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Schedule cancelled successfully!', 'success');
            closeDeleteModal();
            // Refresh the page to show updated schedule
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Failed to cancel schedule.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('An error occurred. Please try again.', 'error');
    });
}

// Initialize form validation
function initializeFormValidation() {
    // Add real-time validation for date fields
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            const today = new Date().toISOString().split('T')[0];
            const maxDate = new Date();
            maxDate.setDate(maxDate.getDate() + 30);
            const maxDateStr = maxDate.toISOString().split('T')[0];
            
            if (this.value < today) {
                showToast('Date cannot be in the past', 'error');
                this.value = today;
                return;
            }
            
            if (this.value > maxDateStr) {
                showToast('Date cannot be more than 30 days from today', 'error');
                this.value = maxDateStr;
                return;
            }
            
            // Validate pickup date is after dropoff date
            const form = this.closest('form');
            if (form) {
                const dropoffDate = form.querySelector('input[name="dropoff_date"]');
                const pickupDate = form.querySelector('input[name="pickup_date"]');
                
                if (dropoffDate && pickupDate && dropoffDate.value && pickupDate.value) {
                    if (pickupDate.value <= dropoffDate.value) {
                        showToast('Pickup date must be after the drop-off date', 'error');
                        pickupDate.value = '';
                    }
                }
            }
        });
    });
    
    // Time field validation removed - using default times
    
    // Add validation for service selection
    const serviceSelects = document.querySelectorAll('select[name="service_id"]');
    serviceSelects.forEach(select => {
        select.addEventListener('change', function() {
            if (!this.value) {
                showToast('Please select a service type', 'error');
            }
        });
    });
}

// Event handlers for buttons
function viewSchedule(scheduleId) {
    openViewModal(scheduleId);
}

function editSchedule(scheduleId) {
    openEditModal(scheduleId);
}

function cancelSchedule(scheduleId) {
    openDeleteModal(scheduleId);
}

// Close modals when clicking outside
document.addEventListener('click', function(event) {
    if (event.target.classList.contains('fixed') && event.target.classList.contains('inset-0')) {
        // Close the modal
        const modal = event.target;
        if (modal.id === 'scheduleModal') {
            closeScheduleModal();
        } else if (modal.id === 'viewModal') {
            closeViewModal();
        } else if (modal.id === 'editModal') {
            closeEditModal();
        } else if (modal.id === 'deleteModal') {
            closeDeleteModal();
        }
    }
});

// Make functions globally available
window.openScheduleModal = openScheduleModal;
window.closeScheduleModal = closeScheduleModal;
window.openViewModal = openViewModal;
window.closeViewModal = closeViewModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.openDeleteModal = openDeleteModal;
window.closeDeleteModal = closeDeleteModal;
window.submitScheduleForm = submitScheduleForm;
window.submitEditForm = submitEditForm;
window.submitDeleteForm = submitDeleteForm;
window.viewSchedule = viewSchedule;
window.editSchedule = editSchedule;
window.cancelSchedule = cancelSchedule;

// Close modals with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        // Close any open modal
        const openModals = document.querySelectorAll('.fixed.inset-0:not(.hidden)');
        openModals.forEach(modal => {
            if (modal.id === 'scheduleModal') {
                closeScheduleModal();
            } else if (modal.id === 'viewModal') {
                closeViewModal();
            } else if (modal.id === 'editModal') {
                closeEditModal();
            } else if (modal.id === 'deleteModal') {
                closeDeleteModal();
            }
        });
    }
});