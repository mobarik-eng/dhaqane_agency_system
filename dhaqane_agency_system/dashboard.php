<?php
require_once 'includes/auth_check.php';
require_once 'config/db.php';

$pageInfo = [
    'title' => 'Dashboard',
    'subtitle' => 'System Overview'
];
$currentPage = 'dashboard';

// Fetch stats
$totalCustomers = $pdo->query("SELECT COUNT(*) FROM customers")->fetchColumn();
$pendingBookings = $pdo->query("SELECT COUNT(*) FROM travel_bookings WHERE status = 'Pending'")->fetchColumn();
$pendingCargo = $pdo->query("SELECT COUNT(*) FROM cargo_shipments WHERE status = 'Pending'")->fetchColumn();
$totalRevenue = $pdo->query("SELECT SUM(amount) FROM payments")->fetchColumn() ?: 0;

// Chart Data: Monthly Income
$months = [];
$income = [];
for ($i = 5; $i >= 0; $i--) {
    $month = date('M', strtotime("-$i months"));
    $monthNum = date('n', strtotime("-$i months"));
    $months[] = $month;
    $stmt = $pdo->prepare("SELECT SUM(amount) FROM payments WHERE MONTH(payment_date) = ? AND YEAR(payment_date) = YEAR(CURRENT_DATE())");
    $stmt->execute([$monthNum]);
    $income[] = $stmt->fetchColumn() ?: 0;
}

include 'includes/header.php';
?>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-info">
            <h3><?php echo $totalCustomers; ?></h3>
            <p>Total Customers</p>
        </div>
        <div class="stat-icon"><i class="fas fa-users"></i></div>
    </div>
    <div class="stat-card orange">
        <div class="stat-info">
            <h3><?php echo $pendingBookings; ?></h3>
            <p>Pending Tickets</p>
        </div>
        <div class="stat-icon"><i class="fas fa-plane-departure"></i></div>
    </div>
    <div class="stat-card red">
        <div class="stat-info">
            <h3><?php echo $pendingCargo; ?></h3>
            <p>Cargo Pending</p>
        </div>
        <div class="stat-icon"><i class="fas fa-shipping-fast"></i></div>
    </div>
    <?php if($_SESSION['role'] == 'admin'): ?>
    <div class="stat-card green">
        <div class="stat-info">
            <h3>$<?php echo number_format($totalRevenue, 2); ?></h3>
            <p>Total Revenue</p>
        </div>
        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
    </div>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 30px;">
    <?php if($_SESSION['role'] == 'admin'): ?>
    <div class="card">
        <div class="card-header">
            <h3>Revenue Overview</h3>
        </div>
        <div class="card-body">
            <canvas id="revenueChart"></canvas>
        </div>
    </div>
    <?php else: ?>
    <!-- Staff View: Maybe a list of recent bookings or just wider quick actions -->
    <div class="card">
         <div class="card-header">
            <h3>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h3>
         </div>
         <div class="card-body">
            <p>Use the Quick Actions sidebar to manage bookings and shipments.</p>
         </div>
    </div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">
            <h3>Quick Actions</h3>
        </div>
        <div class="card-body">
            <a href="modules/travel/add.php" class="btn btn-primary"
                style="display:block; margin-bottom:10px; text-align:center;">
                <i class="fas fa-plus"></i> New Booking
            </a>
            <a href="modules/cargo/add.php" class="btn btn-primary"
                style="display:block; margin-bottom:10px; text-align:center; background: var(--secondary-color);">
                <i class="fas fa-box"></i> New Shipment
            </a>
            <a href="modules/customers/add.php" class="btn"
                style="display:block; text-align:center; background:#eee; color:#333;">
                <i class="fas fa-user-plus"></i> Add Customer
            </a>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($months); ?>,
            datasets: [{
                label: 'Income ($)',
                data: <?php echo json_encode($income); ?>,
                borderColor: '#0056b3',
                backgroundColor: 'rgba(0, 86, 179, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>

<?php include 'includes/footer.php'; ?>