// Toast Notification Functions
function showToast(message, type = 'success', duration = 4000) {
    const container = document.getElementById('toast-container');
    const toastId = 'toast-' + Date.now();
    
    const icons = {
        success: 'fas fa-check-circle',
        error: 'fas fa-exclamation-circle',
        warning: 'fas fa-exclamation-triangle',
        info: 'fas fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.id = toastId;
    toast.className = `toast toast-${type} max-w-sm w-full shadow-lg rounded-lg pointer-events-auto overflow-hidden`;
    
    toast.innerHTML = `
        <div class="p-4">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <i class="${icons[type]} text-white text-lg"></i>
                </div>
                <div class="ml-3 flex-1 pt-0.5">
                    <p class="text-sm font-medium text-white">${message}</p>
                </div>
                <div class="ml-4 flex-shrink-0 flex">
                    <button onclick="hideToast('${toastId}')" class="rounded-md inline-flex text-white hover:text-gray-200 focus:outline-none focus:ring-2 focus:ring-white focus:ring-opacity-50">
                        <span class="sr-only">Close</span>
                        <i class="fas fa-times text-sm"></i>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    container.appendChild(toast);
    
    // Show toast with animation
    setTimeout(() => {
        toast.classList.add('show');
    }, 100);
    
    // Auto hide after duration
    setTimeout(() => {
        hideToast(toastId);
    }, duration);
}

function hideToast(toastId) {
    const toast = document.getElementById(toastId);
    if (toast) {
        toast.classList.remove('show');
        toast.classList.add('hide');
        setTimeout(() => {
            toast.remove();
        }, 300);
    }
}

// View Schedule function
function viewSchedule(scheduleId) {
    // Find the schedule data from the table
    const scheduleRow = document.querySelector(`button[onclick="viewSchedule(${scheduleId})"]`).closest('tr');
    
    if (!scheduleRow) {
        showToast('Schedule not found', 'error');
        return;
    }
    
    // Extract data from the table row
    const scheduleData = extractScheduleDataFromRow(scheduleRow, scheduleId);
    
    // Populate and show the modal
    populateViewModal(scheduleData);
    openViewModal();
}

// Extract schedule data from table row
function extractScheduleDataFromRow(row, scheduleId) {
    const cells = row.cells;
    
    // Extract service type
    const serviceType = cells[2].querySelector('.text-sm.font-medium')?.textContent || 'N/A';
    
    // Extract dropoff info
    const dropoffDate = cells[3].querySelector('.text-sm.font-medium')?.textContent || 'N/A';
    const dropoffTime = cells[3].querySelector('.text-xs.text-slate-400')?.textContent || 'N/A';
    
    // Extract pickup info  
    const pickupDate = cells[4].querySelector('.text-sm.font-medium')?.textContent || 'N/A';
    const pickupTime = cells[4].querySelector('.text-xs.text-slate-400')?.textContent || 'N/A';
    
    // Extract weight
    const weightText = cells[5].querySelector('.text-sm.font-medium')?.textContent || '-';
    const weight = weightText === '-' ? null : weightText.replace(' kg', '');
    
    // Extract price
    const priceText = cells[6].querySelector('.text-sm.font-medium')?.textContent || '-';
    const totalPrice = priceText === '-' ? null : priceText.replace('₱', '');
    
    // Extract creation date
    const createdDate = cells[0].querySelector('.text-xs.text-slate-400')?.textContent || new Date().toLocaleDateString();
    
    // Determine status based on available data
    let status = 'Scheduled';
    if (weight && totalPrice) {
        status = 'In Progress';
    } else if (weight) {
        status = 'Confirmed';
    }
    
    return {
        id: scheduleId,
        service_type: serviceType,
        dropoff_date: dropoffDate,
        dropoff_time: dropoffTime,
        pickup_date: pickupDate,
        pickup_time: pickupTime,
        weight: weight,
        total_price: totalPrice,
        status: status,
        created_at: createdDate,
        updated_at: new Date().toLocaleString()
    };
}

// Populate view modal with real data
function populateViewModal(schedule) {
    // Set basic info
    document.getElementById('view_schedule_id').textContent = schedule.id;
    document.getElementById('view_service_type').textContent = schedule.service_type || 'N/A';
    
    // Format dates and times (already formatted from table)
    document.getElementById('view_dropoff_info').textContent = `${schedule.dropoff_date} at ${schedule.dropoff_time}`;
    document.getElementById('view_pickup_info').textContent = `${schedule.pickup_date} at ${schedule.pickup_time}`;
    
    // Weight and price
    document.getElementById('view_weight').textContent = schedule.weight ? `${schedule.weight} kg` : 'TBD';
    document.getElementById('view_price').textContent = schedule.total_price ? `₱${parseFloat(schedule.total_price).toFixed(2)}` : 'TBD';
    
    // Generate status timeline based on real status
    generateStatusTimeline(schedule.status, schedule);
}

// Generate status timeline like food delivery apps
function generateStatusTimeline(currentStatus, schedule) {
    const timeline = document.getElementById('status-timeline');
    timeline.innerHTML = '';
    
    // Define all possible statuses in order
    const allStatuses = [
        { 
            key: 'pending', 
            label: 'Pending', 
            description: 'Awaiting for staff confirmation',
            icon: 'fas fa-clock',
            timestamp: schedule.created_at
        },
        { 
            key: 'confirmed', 
            label: 'Confirmed', 
            description: 'Staff confirmed the schedule',
            icon: 'fas fa-check-circle',
            timestamp: schedule.updated_at
        },
        { 
            key: 'in_progress', 
            label: 'In Progress', 
            description: 'Staff doing the laundry',
            icon: 'fas fa-spinner',
            timestamp: schedule.updated_at
        },
        { 
            key: 'ready', 
            label: 'Ready to Pick Up', 
            description: 'Customer can pickup the finished laundry',
            icon: 'fas fa-hand-holding',
            timestamp: schedule.updated_at
        }
    ];
    
    // Map current status to our status keys
    const statusMap = {
        'Pending': 'pending',
        'Scheduled': 'pending', 
        'Confirmed': 'confirmed',
        'In Progress': 'in_progress',
        'Ready for Pickup': 'ready',
        'Completed': 'ready',
        'Cancelled': 'pending'
    };
    
    const currentStatusKey = statusMap[currentStatus] || 'pending';
    const currentIndex = allStatuses.findIndex(s => s.key === currentStatusKey);
    
    allStatuses.forEach((status, index) => {
        const statusItem = document.createElement('div');
        statusItem.className = 'status-item';
        
        let statusClass = 'pending';
        let iconClass = 'status-icon pending';
        
        if (index < currentIndex) {
            statusClass = 'completed';
            iconClass = 'status-icon completed';
        } else if (index === currentIndex) {
            statusClass = 'current';
            iconClass = 'status-icon current';
        }
        
        statusItem.innerHTML = `
            <div class="${iconClass}">
                <i class="${status.icon}"></i>
            </div>
            <div class="flex-1">
                <div class="text-sm font-medium text-white">${status.label}</div>
                <div class="text-xs text-slate-400">${status.description}</div>
            </div>
        `;
        
        timeline.appendChild(statusItem);
    });
}

// View Modal functions
function openViewModal() {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
    
    // Blur the sidebar and add overlay
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'blur(8px) brightness(0.3)';
        sidebar.style.transition = 'filter 0.3s ease';
        sidebar.style.pointerEvents = 'none';
    }
}

function closeViewModal() {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
    
    // Remove blur from sidebar and restore functionality
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'none';
        sidebar.style.pointerEvents = 'auto';
    }
}

// Edit Schedule function
function editSchedule(scheduleId) {
    // Find the schedule data from the table
    const scheduleRow = document.querySelector(`button[onclick="editSchedule(${scheduleId})"]`).closest('tr');
    
    // Extract service name from the service cell (remove any extra text)
    const serviceCell = scheduleRow.cells[1];
    const serviceName = serviceCell.querySelector('h5') ? serviceCell.querySelector('h5').textContent.trim() : serviceCell.textContent.trim();
    
    // Extract dates and times (assuming format: "2024-01-15 9:00 AM")
    const dropoffText = scheduleRow.cells[2].textContent.trim();
    const pickupText = scheduleRow.cells[3].textContent.trim();
    
    const scheduleData = {
        id: scheduleId,
        service: serviceName,
        dropoff_date: dropoffText.split(' ')[0],
        dropoff_time: dropoffText.split(' ').slice(1).join(' '),
        pickup_date: pickupText.split(' ')[0],
        pickup_time: pickupText.split(' ').slice(1).join(' ')
    };
    
    // Open edit modal with pre-filled data
    openEditModal(scheduleData);
}

// Cancel Schedule function
function cancelSchedule(scheduleId) {
    // Open delete confirmation modal
    openDeleteModal(scheduleId);
}

// Enhanced Modal functions with animations
function openScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
    
    // Blur the sidebar and add overlay
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'blur(8px) brightness(0.3)';
        sidebar.style.transition = 'filter 0.3s ease';
        sidebar.style.pointerEvents = 'none';
    }
}

function closeScheduleModal() {
    const modal = document.getElementById('scheduleModal');
    const modalContent = document.getElementById('modalContent');
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('scheduleForm').reset();
    }, 300);
    
    // Remove blur from sidebar and restore functionality
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'none';
        sidebar.style.pointerEvents = 'auto';
    }
}

// Edit Modal functions
function openEditModal(scheduleData) {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    
    // Pre-fill the form with existing data
    document.getElementById('edit_schedule_id').value = scheduleData.id;
    document.getElementById('edit_dropoff_date').value = scheduleData.dropoff_date;
    document.getElementById('edit_dropoff_time').value = scheduleData.dropoff_time;
    document.getElementById('edit_pickup_date').value = scheduleData.pickup_date;
    document.getElementById('edit_pickup_time').value = scheduleData.pickup_time;
    
    // Set the current service as selected
    const currentService = scheduleData.service;
    const serviceRadios = document.querySelectorAll('input[name="edit_service_id"]');
    serviceRadios.forEach(radio => {
        const serviceCard = radio.closest('label');
        const serviceNameElement = serviceCard.querySelector('h5');
        if (serviceNameElement && serviceNameElement.textContent.trim() === currentService) {
            radio.checked = true;
        }
    });
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
    
    // Blur the sidebar and add overlay
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'blur(8px) brightness(0.3)';
        sidebar.style.transition = 'filter 0.3s ease';
        sidebar.style.pointerEvents = 'none';
    }
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
        document.getElementById('editScheduleForm').reset();
    }, 300);
    
    // Remove blur from sidebar and restore functionality
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'none';
        sidebar.style.pointerEvents = 'auto';
    }
}

// Delete Modal functions
function openDeleteModal(scheduleId) {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    // Set the schedule ID for deletion
    document.getElementById('delete_schedule_id').value = scheduleId;
    
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate modal entrance
    setTimeout(() => {
        modalContent.style.transform = 'scale(1)';
        modalContent.style.opacity = '1';
    }, 10);
    
    // Blur the sidebar and add overlay
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'blur(8px) brightness(0.3)';
        sidebar.style.transition = 'filter 0.3s ease';
        sidebar.style.pointerEvents = 'none';
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('deleteModalContent');
    
    // Animate modal exit
    modalContent.style.transform = 'scale(0.95)';
    modalContent.style.opacity = '0';
    
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 300);
    
    // Remove blur from sidebar and restore functionality
    const sidebar = document.querySelector('.sidebar');
    if (sidebar) {
        sidebar.style.filter = 'none';
        sidebar.style.pointerEvents = 'auto';
    }
    
    // Clear cancellation reason field
    const cancellationReasonField = document.getElementById('cancellation_reason');
    if (cancellationReasonField) {
        cancellationReasonField.value = '';
        // Reset character counter
        const charCount = document.getElementById('char_count');
        if (charCount) {
            charCount.textContent = '0';
        }
    }
}

// Submit Edit Schedule
function submitEditSchedule() {
    const form = document.getElementById('editScheduleForm');
    const formData = new FormData(form);
    const scheduleId = document.getElementById('edit_schedule_id').value;
    
    // Basic validation
    if (!formData.get('edit_service_id') || !formData.get('dropoff_date') || !formData.get('dropoff_time') || !formData.get('pickup_date') || !formData.get('pickup_time')) {
        showToast('Please select a service and fill in all required fields', 'warning');
        return;
    }

    // Check if pickup date is after dropoff date
    const dropoffDate = new Date(formData.get('dropoff_date') + 'T00:00:00');
    const pickupDate = new Date(formData.get('pickup_date') + 'T00:00:00');
    
    if (pickupDate <= dropoffDate) {
        showToast('Pickup date must be after dropoff date', 'warning');
        return;
    }

    // Send AJAX request to update the schedule
    fetch(`/customer/schedules/${scheduleId}/update`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            service_id: formData.get('edit_service_id'),
            dropoff_date: formData.get('dropoff_date'),
            dropoff_time: formData.get('dropoff_time'),
            pickup_date: formData.get('pickup_date'),
            pickup_time: formData.get('pickup_time')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Schedule updated successfully!', 'success');
            closeEditModal();
            setTimeout(() => {
                location.reload(); // Refresh the page to show updated data
            }, 1000);
        } else {
            showToast('Error updating schedule: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error updating schedule. Please try again.', 'error');
    });
}

// Confirm Delete Schedule
function confirmDeleteSchedule() {
    const scheduleId = document.getElementById('delete_schedule_id').value;
    const cancellationReason = document.getElementById('cancellation_reason').value;
    
    // Send AJAX request to cancel the schedule
    fetch(`/customer/schedules/${scheduleId}/cancel`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            cancellation_reason: cancellationReason
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast('Schedule cancelled successfully!', 'success');
            closeDeleteModal();
            setTimeout(() => {
                location.reload(); // Refresh the page to show updated status
            }, 1000);
        } else {
            showToast('Error cancelling schedule: ' + (data.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error cancelling schedule. Please try again.', 'error');
    });
}

// Form submission
function submitSchedule() {
    const form = document.getElementById('scheduleForm');
    const formData = new FormData(form);
    
    // Basic validation
    if (!formData.get('service_id') || !formData.get('dropoff_date') || !formData.get('dropoff_time') || !formData.get('pickup_date') || !formData.get('pickup_time')) {
        showToast('Please select a service and fill in all required fields', 'warning');
        return;
    }

    // Check if pickup date is after dropoff date
    const dropoffDate = new Date(formData.get('dropoff_date') + 'T00:00:00');
    const pickupDate = new Date(formData.get('pickup_date') + 'T00:00:00');
    
    if (pickupDate <= dropoffDate) {
        showToast('Pickup date must be after drop-off date', 'warning');
        return;
    }

    // Show loading state
    const submitBtn = document.querySelector('button[onclick="submitSchedule()"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Scheduling...';
    submitBtn.disabled = true;

    // Submit to actual API endpoint
    fetch(window.scheduleStoreRoute, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            showToast('Schedule created!', 'success');
            closeScheduleModal();
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showToast(data.message || 'Error creating schedule. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error creating schedule. Please check all fields and try again.', 'error');
    })
    .finally(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
}

// Initialize event listeners when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Close modal when clicking outside
    const scheduleModal = document.getElementById('scheduleModal');
    if (scheduleModal) {
        scheduleModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeScheduleModal();
            }
        });
    }

    // Close view modal when clicking outside
    const viewModal = document.getElementById('viewModal');
    if (viewModal) {
        viewModal.addEventListener('click', function(e) {
            if (e.target === this) {
                closeViewModal();
            }
        });
    }

    // Auto-set pickup date when dropoff date changes
    const dropoffDateInput = document.querySelector('input[name="dropoff_date"]');
    if (dropoffDateInput) {
        dropoffDateInput.addEventListener('change', function() {
            if (!this.value) return;
            
            const dropoffDate = new Date(this.value + 'T00:00:00');
            const pickupDate = new Date(dropoffDate);
            pickupDate.setDate(pickupDate.getDate() + 1);
            
            const pickupInput = document.querySelector('input[name="pickup_date"]');
            const year = pickupDate.getFullYear();
            const month = String(pickupDate.getMonth() + 1).padStart(2, '0');
            const day = String(pickupDate.getDate()).padStart(2, '0');
            const formattedDate = `${year}-${month}-${day}`;
            
            pickupInput.min = formattedDate;
            
            // Auto-set pickup date if not already set
            if (!pickupInput.value) {
                pickupInput.value = formattedDate;
            }
        });
    }
    
    // Initialize character counter for cancellation reason
    const cancellationReasonField = document.getElementById('cancellation_reason');
    const charCount = document.getElementById('char_count');
    if (cancellationReasonField && charCount) {
        cancellationReasonField.addEventListener('input', function() {
            charCount.textContent = this.value.length;
        });
    }
});
