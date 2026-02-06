<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Edit Employee',
    'subtitle' => 'Update Account'
];
$currentPage = 'employees';

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
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

    $photo = $user['photo']; // Default to existing photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $allowed = ['jpg', 'jpeg', 'png'];
        $filename = $_FILES['photo']['name'];
        $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
        if (in_array($ext, $allowed)) {
            $new_filename = uniqid() . '.' . $ext;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], '../../uploads/employees/' . $new_filename)) {
                $photo = $new_filename;
            }
        } else {
            $error = "Invalid file type. Only JPG/PNG.";
        }
    }

    if (empty($username) || empty($fullname)) {
        $error = "Username and Full Name are required.";
    } else {
        try {
            $sql = "UPDATE users SET fullname=:fullname, username=:username, phone=:phone, role=:role, photo=:photo";
            $params = [
                ':fullname' => $fullname,
                ':username' => $username,
                ':phone' => $phone,
                ':role' => $role,
                ':photo' => $photo,
                ':id' => $id
            ];

            if (!empty($password)) {
                $sql .= ", password=:password";
                $params[':password'] = password_hash($password, PASSWORD_DEFAULT);
            }

            $sql .= " WHERE id=:id";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $success = "Employee updated successfully!";

            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            $user = $stmt->fetch();
        } catch (PDOException $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Edit Employee</h3>
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
                    <input type="text" name="fullname" class="form-control"
                        value="<?php echo htmlspecialchars($user['fullname'] ?? ''); ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control"
                        value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Username (Login ID) *</label>
                    <input type="text" name="username" class="form-control"
                        value="<?php echo htmlspecialchars($user['username']); ?>" required>
                </div>
                <div class="form-group">
                    <label>Password (Leave blank to keep current)</label>
                    <input type="password" name="password" class="form-control" placeholder="New Password">
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="form-group">
                    <label>Role</label>
                    <select name="role" class="form-control">
                        <option value="staff" <?php echo $user['role'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
                        <option value="admin" <?php echo $user['role'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Photo</label>
                    <?php if (!empty($user['photo'])): ?>
                        <br><img src="../../uploads/employees/<?php echo $user['photo']; ?>"
                            style="width: 50px; height: 50px; border-radius: 5px; margin-bottom: 5px;">
                    <?php endif; ?>
                    <input type="file" name="photo" class="form-control" accept="image/*">
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Update Employee</button>
        </form>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>