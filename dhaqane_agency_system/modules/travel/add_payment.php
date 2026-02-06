<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Travel Payment',
    'subtitle' => 'Add Income'
];
$currentPage = 'travel_payments';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $related_id = $_POST['related_id'];
    $amount = $_POST['amount'];
    $payment_method = $_POST['payment_method'];

    if (empty($amount) || empty($related_id)) {
        $error = "Amount and Reference ID are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO payments (related_type, related_id, amount, payment_method) VALUES ('Travel', :related_id, :amount, :payment_method)");
            $stmt->execute([
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

// Fetch Pending Bookings to suggest?
$bookings = $pdo->query("SELECT id, amount, status FROM travel_bookings WHERE status != 'Cancelled' ORDER BY id DESC LIMIT 20")->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Record Travel Payment</h3>
        <a href="payments.php" class="btn" style="background: #ddd; color: #333;"><i class="fas fa-arrow-left"></i>
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
                    <label>Booking Reference (Select or Type ID) *</label>
                    <input type="text" name="related_id" list="booking_list" class="form-control" required
                        placeholder="Booking ID">
                    <datalist id="booking_list">
                        <?php foreach ($bookings as $b): ?>
                            <option value="<?php echo $b['id']; ?>">Booking #
                                <?php echo $b['id']; ?> ($
                                <?php echo $b['amount']; ?>)
                            </option>
                        <?php endforeach; ?>
                    </datalist>
                </div>

                <div class="form-group">
                    <label>Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label>Payment Method</label>
                <select name="payment_method" class="form-control">
                    <option value="Cash">Cash</option>
                    <option value="Bank Transfer">Bank Transfer</option>
                    <option value="Mobile Money">Mobile Money (Zaad/Eda/Sahal)</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Payment</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>