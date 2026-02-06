<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Edit Booking',
    'subtitle' => 'Update Flight or Visa Record'
];
$currentPage = 'travel';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM travel_bookings WHERE id = ?");
$stmt->execute([$id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "Booking not found.";
    exit();
}

// Fetch Customers
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
            $sql = "UPDATE travel_bookings SET 
                    customer_id = :customer_id, 
                    type = :type, 
                    description = :description, 
                    flight_date = :flight_date, 
                    amount = :amount, 
                    status = :status 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':flight_date', $flight_date);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $success = "Booking updated successfully!";
            // Reset Booking Data for display
            $stmt2 = $pdo->prepare("SELECT * FROM travel_bookings WHERE id = ?");
            $stmt2->execute([$id]);
            $booking = $stmt2->fetch();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Edit Booking #
            <?php echo $booking['id']; ?>
        </h3>
        <a href="index.php" class="btn" style="background: #ddd; color: #333;"><i class="fas fa-arrow-left"></i>
            Back</a>
    </div>
    <div class="card-body">
        <?php if ($error): ?>
            <div class="badge badge-danger" style="display:block; padding: 15px; margin-bottom: 20px; font-size:1rem;">
                <?php echo $error; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Customer *</label>
                <select name="customer_id" class="form-control" required>
                    <option value="">-- Select Customer --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $booking['customer_id'] ? 'selected' : ''; ?>>
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
                        <option value="Flight" <?php echo $booking['type'] == 'Flight' ? 'selected' : ''; ?>>Flight
                            Ticket</option>
                        <option value="Visa" <?php echo $booking['type'] == 'Visa' ? 'selected' : ''; ?>>Visa Service
                        </option>
                        <option value="Hotel" <?php echo $booking['type'] == 'Hotel' ? 'selected' : ''; ?>>Hotel Booking
                        </option>
                        <option value="Other" <?php echo $booking['type'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Travel Date</label>
                    <input type="date" name="flight_date" class="form-control"
                        value="<?php echo $booking['flight_date']; ?>">
                </div>
            </div>

            <div class="form-group">
                <label>Description / Routings</label>
                <textarea name="description" class="form-control"
                    rows="3"><?php echo htmlspecialchars($booking['description']); ?></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Amount ($) *</label>
                    <input type="number" step="0.01" name="amount" class="form-control"
                        value="<?php echo $booking['amount']; ?>" required>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control">
                        <option value="Pending" <?php echo $booking['status'] == 'Pending' ? 'selected' : ''; ?>>Pending
                        </option>
                        <option value="Confirmed" <?php echo $booking['status'] == 'Confirmed' ? 'selected' : ''; ?>>
                            Confirmed</option>
                        <option value="Cancelled" <?php echo $booking['status'] == 'Cancelled' ? 'selected' : ''; ?>>
                            Cancelled</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Booking</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>