<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Edit Shipment',
    'subtitle' => 'Update Cargo Tracking'
];
$currentPage = 'cargo';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM cargo_shipments WHERE id = ?");
$stmt->execute([$id]);
$shipment = $stmt->fetch();

if (!$shipment) {
    echo "Shipment not found.";
    exit();
}

// Fetch Customers
$customers = $pdo->query("SELECT id, name, phone FROM customers ORDER BY name ASC")->fetchAll();

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $sender_id = $_POST['sender_id'];
    $receiver_name = trim($_POST['receiver_name']);
    $receiver_phone = trim($_POST['receiver_phone']);
    $weight = $_POST['weight'];
    $price_per_kg = $_POST['price_per_kg'];
    $total_amount = $_POST['total_amount'];
    $status = $_POST['status'];

    if (empty($sender_id) || empty($weight)) {
        $error = "Sender and Weight are required.";
    } else {
        try {
            $sql = "UPDATE cargo_shipments SET 
                    sender_id = :sender_id, 
                    receiver_name = :receiver_name, 
                    receiver_phone = :receiver_phone, 
                    weight = :weight, 
                    price_per_kg = :price_per_kg, 
                    total_amount = :total_amount, 
                    status = :status 
                    WHERE id = :id";

            $stmt = $pdo->prepare($sql);

            $stmt->bindParam(':sender_id', $sender_id);
            $stmt->bindParam(':receiver_name', $receiver_name);
            $stmt->bindParam(':receiver_phone', $receiver_phone);
            $stmt->bindParam(':weight', $weight);
            $stmt->bindParam(':price_per_kg', $price_per_kg);
            $stmt->bindParam(':total_amount', $total_amount);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':id', $id);

            $stmt->execute();

            $success = "Shipment updated successfully!";
            // Refresh
            $stmt2 = $pdo->prepare("SELECT * FROM cargo_shipments WHERE id = ?");
            $stmt2->execute([$id]);
            $shipment = $stmt2->fetch();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Edit Shipment #
            <?php echo $shipment['tracking_no']; ?>
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
        <?php if ($success): ?>
            <div class="badge badge-success" style="display:block; padding: 15px; margin-bottom: 20px; font-size:1rem;">
                <?php echo $success; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Sender (Customer) *</label>
                <select name="sender_id" class="form-control" required>
                    <option value="">-- Select Sender --</option>
                    <?php foreach ($customers as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $c['id'] == $shipment['sender_id'] ? 'selected' : ''; ?>>
                            <?php echo $c['name']; ?> (
                            <?php echo $c['phone']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Receiver Name</label>
                    <input type="text" name="receiver_name" class="form-control"
                        value="<?php echo htmlspecialchars($shipment['receiver_name']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Receiver Phone</label>
                    <input type="text" name="receiver_phone" class="form-control"
                        value="<?php echo htmlspecialchars($shipment['receiver_phone']); ?>" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Weight (kg) *</label>
                    <input type="number" step="0.01" name="weight" id="weight" class="form-control"
                        value="<?php echo $shipment['weight']; ?>" required oninput="calculateTotal()">
                </div>
                <div class="form-group">
                    <label>Price per Kg ($) *</label>
                    <input type="number" step="0.01" name="price_per_kg" id="price" class="form-control"
                        value="<?php echo $shipment['price_per_kg']; ?>" required oninput="calculateTotal()">
                </div>
                <div class="form-group">
                    <label>Total Amount ($)</label>
                    <input type="number" step="0.01" name="total_amount" id="total" class="form-control"
                        value="<?php echo $shipment['total_amount']; ?>" readonly>
                </div>
            </div>

            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control">
                    <option value="Pending" <?php echo $shipment['status'] == 'Pending' ? 'selected' : ''; ?>>Pending
                    </option>
                    <option value="Shipped" <?php echo $shipment['status'] == 'Shipped' ? 'selected' : ''; ?>>Shipped
                    </option>
                    <option value="Arrived" <?php echo $shipment['status'] == 'Arrived' ? 'selected' : ''; ?>>Arrived
                    </option>
                    <option value="Delivered" <?php echo $shipment['status'] == 'Delivered' ? 'selected' : ''; ?>>
                        Delivered</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Shipment</button>
        </form>
    </div>
</div>

<script>
    function calculateTotal() {
        var weight = parseFloat(document.getElementById('weight').value) || 0;
        var price = parseFloat(document.getElementById('price').value) || 0;
        var total = weight * price;
        document.getElementById('total').value = total.toFixed(2);
    }
</script>

<?php include '../../includes/footer.php'; ?>