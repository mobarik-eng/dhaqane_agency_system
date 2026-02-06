<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Add New Customer',
    'subtitle' => 'Register a new client'
];
$currentPage = 'customers';

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $phone = trim($_POST['phone']);
    $passport = trim($_POST['passport']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    if (empty($name) || empty($phone)) {
        $error = "Name and Phone are required.";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO customers (name, phone, passport_no, email, address) VALUES (:name, :phone, :passport, :email, :address)");
            $stmt->execute([
                ':name' => $name,
                ':phone' => $phone,
                ':passport' => $passport,
                ':email' => $email,
                ':address' => $address
            ]);
            $success = "Customer added successfully!";
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Customer Details</h3>
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
                <label>Full Name *</label>
                <input type="text" name="name" class="form-control" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Phone Number *</label>
                    <input type="text" name="phone" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Passport Number</label>
                    <input type="text" name="passport" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control">
            </div>

            <div class="form-group">
                <label>Address</label>
                <textarea name="address" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Customer</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>