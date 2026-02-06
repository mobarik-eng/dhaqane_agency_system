<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Cargo Payments',
    'subtitle' => 'Shipment Financials'
];
$currentPage = 'cargo_payments';

// Fetch Cargo Payments
$stmt = $pdo->prepare("SELECT * FROM payments WHERE related_type = 'Cargo' ORDER BY payment_date DESC");
$stmt->execute();
$payments = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Cargo Payments</h3>
        <a href="add_payment.php" class="btn btn-primary"><i class="fas fa-plus"></i> Record Payment</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tracking ID (Ref)</th>
                        <th>Amount</th>
                        <th>Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($payments) > 0): ?>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td>#
                                    <?php echo $payment['id']; ?>
                                </td>
                                <td>
                                    <?php echo $payment['related_id']; ?>
                                </td>
                                <!-- We store ID in related_id generally, but cargo uses tracking_no visually. DB stores int ID. -->
                                <td style="font-weight:bold; color:var(--success-color);">$
                                    <?php echo number_format($payment['amount'], 2); ?>
                                </td>
                                <td>
                                    <?php echo $payment['payment_method']; ?>
                                </td>
                                <td>
                                    <?php echo date('M d, Y H:i', strtotime($payment['payment_date'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align:center;">No payments recorded.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>