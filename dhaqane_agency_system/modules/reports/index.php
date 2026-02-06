<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Reports',
    'subtitle' => 'Business Analytics'
];
$currentPage = 'reports';

// Total Income
$stmt = $pdo->query("SELECT SUM(amount) FROM payments");
$totalIncome = $stmt->fetchColumn() ?: 0;

// Income by Method
$stmt = $pdo->query("SELECT payment_method, SUM(amount) as total FROM payments GROUP BY payment_method");
$incomeByMethod = $stmt->fetchAll();

// Monthly Travelers
$stmt = $pdo->query("SELECT COUNT(*) FROM travel_bookings WHERE MONTH(created_at) = MONTH(CURRENT_DATE())");
$monthlyTravelers = $stmt->fetchColumn();

// Monthly Cargo Weight
$stmt = $pdo->query("SELECT SUM(weight) FROM cargo_shipments WHERE MONTH(created_at) = MONTH(CURRENT_DATE())");
$monthlyCargo = $stmt->fetchColumn() ?: 0;

include '../../includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card green">
        <div class="stat-info">
            <h3>$
                <?php echo number_format($totalIncome, 2); ?>
            </h3>
            <p>Total Income</p>
        </div>
        <div class="stat-icon"><i class="fas fa-wallet"></i></div>
    </div>
    <div class="stat-card">
        <div class="stat-info">
            <h3>
                <?php echo $monthlyTravelers; ?>
            </h3>
            <p>Travelers This Month</p>
        </div>
        <div class="stat-icon"><i class="fas fa-user-check"></i></div>
    </div>
    <div class="stat-card blue">
        <div class="stat-info">
            <h3>
                <?php echo $monthlyCargo; ?> kg
            </h3>
            <p>Cargo This Month</p>
        </div>
        <div class="stat-icon"><i class="fas fa-weight-hanging"></i></div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Income by Payment Method</h3>
        <button onclick="window.print()" class="btn btn-primary"><i class="fas fa-print"></i> Print Report</button>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Payment Method</th>
                    <th>Total Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($incomeByMethod as $row): ?>
                    <tr>
                        <td>
                            <?php echo $row['payment_method']; ?>
                        </td>
                        <td>$
                            <?php echo number_format($row['total'], 2); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>