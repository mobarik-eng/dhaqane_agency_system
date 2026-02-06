<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Employees',
    'subtitle' => 'Manage Staff & Users'
];
$currentPage = 'employees';

// Only Admin can see this
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../dashboard.php");
    exit();
}

$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Employee List</h3>
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Employee</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Phone</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td>
                                <?php if (!empty($user['photo'])): ?>
                                    <img src="../../uploads/employees/<?php echo $user['photo']; ?>"
                                        style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <div
                                        style="width: 50px; height: 50px; background: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: #888;">
                                        N/A</div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['fullname'] ?? $user['username']); ?>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['username']); ?>
                            </td>
                            <td>
                                <span
                                    class="badge <?php echo $user['role'] == 'admin' ? 'badge-danger' : 'badge-success'; ?>">
                                    <?php echo ucfirst($user['role']); ?>
                                </span>
                            </td>
                            <td>
                                <?php echo htmlspecialchars($user['phone'] ?? '-'); ?>
                            </td>
                            <td>
                                <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-primary"
                                    style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                <?php if ($user['username'] !== 'admin' && $user['id'] !== $_SESSION['user_id']): ?>
                                    <a href="delete.php?id=<?php echo $user['id']; ?>" class="btn delete-btn"
                                        style="background:var(--danger-color); color:#fff; padding: 5px 10px; font-size: 0.8rem;"><i
                                            class="fas fa-trash"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>