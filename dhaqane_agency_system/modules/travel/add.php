<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'New Booking',
    'subtitle' => 'Create Flight or Visa Record'
];
$currentPage = 'travel';

// Fetch Customers for Dropdown
$customers = $pdo->query("SELECT id, name, passport_no FROM customers ORDER BY name ASC")->fetchAll();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_POST['customer_id'];
    $type = $_POST['type'];
    $description = trim($_POST['description']);
    $flight_date = !empty($_POST['flight_date']) ? $_POST['flight_date'] : null;
    $amount = $_POST['amount'];
    $status = $_POST['status'];

    if (empty($customer_id) || empty($amount)) {
        $error = "Customer and Amount are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO travel_bookings (customer_id, type, description, flight_date, amount, status) VALUES (:customer_id, :type, :description, :flight_date, :amount, :status)");
            $stmt->execute([
                ':customer_id' => $customer_id,
                ':type' => $type,
                ':description' => $description,
                ':flight_date' => $flight_date,
                ':amount' => $amount,
                ':status' => $status
            ]);
            $success = "Booking created successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Booking Details</h3>
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
            <div class="form-group">
                <label>Customer *</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['id']; ?>">
                            <?php echo $c['name']; ?> (
                            <?php echo $c['passport_no']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Service Type *</label>
                    <select name="type" class="form-control" required>
                        <option value="Flight">Flight Ticket</option>
                        <option value="Visa">Visa Service</option>
                        <option value="Hotel">Hotel Booking</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Travel Date</label>
                    <input type="date" name="flight_date" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Description / Routings</label>
                <textarea name="description" class="form-control" rows="3"
                    placeholder="e.g. MGQ -> DXB -> IST"></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending">Pending</option>
                        <option value="Confirmed">Confirmed</option>
                        <option value="Cancelled">Cancelled</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Booking</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>