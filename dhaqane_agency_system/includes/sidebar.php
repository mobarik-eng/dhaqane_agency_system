<div class="sidebar">
    <div class="sidebar-header">
        <?php if (file_exists($_SERVER['DOCUMENT_ROOT'] . '/dhaqane_agency_system/assets/img/logo.jpg')): ?>
            <img src="/dhaqane_agency_system/assets/img/logo.jpg"
                style="width: 50px; height: auto; display: block; margin: 0 auto 10px;">
        <?php endif; ?>
        <h2>Dhaqane <span>System</span></h2>
    </div>
    <ul class="sidebar-menu">
        <li>
            <a href="/dhaqane_agency_system/dashboard.php"
                class="<?php echo $currentPage == 'dashboard' ? 'active' : ''; ?>">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/customers/index.php"
                class="<?php echo $currentPage == 'customers' ? 'active' : ''; ?>">
                <i class="fas fa-users"></i> Customers
            </a>
        </li>

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin'): ?>
            <li>
                <a href="/dhaqane_agency_system/modules/employees/index.php"
                    class="<?php echo $currentPage == 'employees' ? 'active' : ''; ?>">
                    <i class="fas fa-user-tie"></i> Employees
                </a>
            </li>
        <?php endif; ?>

        <li>
            <div
                style="padding: 10px 25px; color: #7f8c8d; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">
                Travel</div>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/travel/index.php"
                class="<?php echo $currentPage == 'travel' ? 'active' : ''; ?>">
                <i class="fas fa-plane"></i> Bookings
            </a>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/travel/payments.php"
                class="<?php echo $currentPage == 'travel_payments' ? 'active' : ''; ?>">
                <i class="fas fa-file-invoice-dollar"></i> Travel Payments
            </a>
        </li>
        <li>
            <div
                style="padding: 10px 25px; color: #7f8c8d; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">
                Cargo</div>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/cargo/index.php"
                class="<?php echo $currentPage == 'cargo' ? 'active' : ''; ?>">
                <i class="fas fa-box"></i> Shipments
            </a>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/cargo/payments.php"
                class="<?php echo $currentPage == 'cargo_payments' ? 'active' : ''; ?>">
                <i class="fas fa-hand-holding-usd"></i> Cargo Payments
            </a>
        </li>
        <li>
            <div
                style="padding: 10px 25px; color: #7f8c8d; font-size: 0.8rem; text-transform: uppercase; font-weight: bold;">
                Analysis</div>
        </li>
        <li>
            <a href="/dhaqane_agency_system/modules/reports/index.php"
                class="<?php echo $currentPage == 'reports' ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i> Reports
            </a>
        </li>
    </ul>
</div>