<?php
    $userRole = Auth::user()->role->name ?? 'customer';
    $isSuperAdmin = $userRole === 'superadmin';
    $isAdmin = $userRole === 'administrator';
    $isStaff = $userRole === 'staff';
    $isCustomer = $userRole === 'customer';
    
    // Define color schemes based on role
    if ($isSuperAdmin) {
        $primaryColor = 'indigo';
        $secondaryColor = 'purple';
    } elseif ($isAdmin || $isStaff) {
        $primaryColor = 'sky';
        $secondaryColor = 'cyan';
    } else {
        $primaryColor = 'emerald';
        $secondaryColor = 'teal';
    }
?>
<style>
:root {
    --primary-color: <?php echo e($primaryColor === 'indigo' ? '#6366f1' : ($primaryColor === 'sky' ? '#0ea5e9' : '#10b981')); ?>;
    --secondary-color: <?php echo e($secondaryColor === 'purple' ? '#8b5cf6' : ($secondaryColor === 'cyan' ? '#06b6d4' : '#14b8a6')); ?>;
}

.footer-container {
    background: #0F172A;
    color: #F8FAFC;
    padding: 2rem 0;
    margin-top: auto;
    border-top: 1px solid #334155;
    margin-left: 240px; /* Account for sidebar width */
    transition: margin-left 0.3s ease; /* Smooth transition when sidebar collapses */
}

/* When sidebar is collapsed */
body.sidebar-collapsed .footer-container {
    margin-left: 80px;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .footer-container {
        margin-left: 0;
    }
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 2rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.footer-main {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
}


.footer-links {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
}

.footer-links a {
    color: #94A3B8;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.footer-links a:hover {
    color: var(--primary-color);
}

.footer-bottom {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 1rem;
    padding-top: 1rem;
    border-top: 1px solid #334155;
}

.footer-bottom p {
    margin: 0;
    color: #64748B;
    font-size: 0.875rem;
}


@media (max-width: 768px) {
    .footer-main {
        flex-direction: column;
        text-align: center;
    }
    
    .footer-links {
        justify-content: center;
    }
    
    .footer-bottom {
        flex-direction: column;
        text-align: center;
    }
}
</style>

<div class="footer-container">
    <div class="footer-content">
        <div class="footer-main">
            <div class="footer-links">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Support</a>
                <a href="#">Documentation</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 Laundry Management System. All rights reserved.</p>
        </div>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\Laundry Sytem\Thesis\resources\views/components/footer.blade.php ENDPATH**/ ?>