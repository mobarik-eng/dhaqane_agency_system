<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Add Employee',
    'subtitle' => 'Create User Account'
];
$currentPage = 'employees';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../dashboard.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $phone = trim($_POST['phone']);
    $role = $_POST['role'];
    $password = $_POST['password'];

    // File Upload
    // File Upload
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], '../../uploads/employees/' . $new_filename)) {
                $photo = $new_filename;
            } else {
                $error = "Failed to upload photo.";
            }
        } else {
            $error = "Invalid file type. Only JPG/PNG.";
        }
    }
    // If error is 4 (No file uploaded), we just ignore it for ADD, photo remains empty string


    if (empty($username) || empty($password) || empty($fullname)) {
        $error = "Username, Password and Full Name are required.";
    } else {
        // Check username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->rowCount() > 0) {
            $error = "Username already exists.";
        } else {
            try {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, password, role, fullname, phone, photo) VALUES (:username, :password, :role, :fullname, :phone, :photo)");
                $stmt->execute([
                    ':username' => $username,
                    ':password' => $hash,
                    ':role' => $role,
                    ':fullname' => $fullname,
                    ':phone' => $phone,
                    ':photo' => $photo
                ]);
                $success = "Employee added successfully!";
            } catch (PDOException $e) {
                $error = "Error: " . $e->getMessage();
            }
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Employee Details</h3>
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

        <form method="POST" action="" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Full Name *</label>
                    <input type="text" name="fullname" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Username (Login ID) *</label>
                    <input type="text" name="username" class="form-control" required>
                </div>
                <div class="form-group">
                    <label>Password *</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="staff">Staff</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Employee</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>