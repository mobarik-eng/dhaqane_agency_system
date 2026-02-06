<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Record Payment',
    'subtitle' => 'Add Income Transaction'
];
$currentPage = 'finance';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $related_type = $_POST['related_type'];
    $related_id = $_POST['related_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    if (empty($amount) || empty($related_id)) {
        $error = "Amount and Reference ID are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO payments (related_type, related_id, amount, payment_method) VALUES (:related_type, :related_id, :amount, :payment_method)");
            $stmt->execute([
                ':related_type' => $related_type,
                ':related_id' => $related_id,
                ':amount' => $amount,
                ':payment_method' => $payment_method
            ]);
            $success = "Payment recorded successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Payment Details</h3>
        <a href="index.php" class="btn" style="background: #ddd; color: #333;"><i class="fas fa-arrow-left"></i>
            Back</a>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="badge badge-danger" style="display:block; padding: 15px; margin-bottom: 20px; font-size:1rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="badge badge-success" style="display:block; padding: 15px; margin-bottom: 20px; font-size:1rem;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Payment For *</label>
                    <select name="related_type" class="form-control">
                        <option value="Travel">Travel Booking</option>
                        <option value="Cargo">Cargo Shipment</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Reference ID (Booking/Tracking ID) *</label>
                    <input type="number" name="related_id" class="form-control" placeholder="e.g. 12" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Payment Method</label>
                    <select name="payment_method" class="form-control">
                        <option value="Cash">Cash</option>
                        <option value="Bank Transfer">Bank Transfer</option>
                        <option value="Mobile Money">Mobile Money (Zaad/Eda/Sahal)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Payment</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>