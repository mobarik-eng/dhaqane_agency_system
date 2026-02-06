<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Customers',
    'subtitle' => 'Manage Client Information'
];
$currentPage = 'customers';

// Fetch Customers
$stmt = $pdo->query("SELECT * FROM customers ORDER BY created_at DESC");
$customers = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Customer List</h3>
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> Add New Customer</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Passport No</th>
                        <th>Joined Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($customers) > 0): ?>
                        <?php foreach ($customers as $customer): ?>
                            <tr>
                                <td>#
                                    <?php echo $customer['id']; ?>
                                </td>
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($customer['name']); ?>
                                    </strong><br>
                                    <small>
                                        <?php echo htmlspecialchars($customer['email']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($customer['phone']); ?>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($customer['passport_no']); ?>
                                </td>
                                <td>
                                    <?php echo date('M d, Y', strtotime($customer['created_at'])); ?>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $customer['id']; ?>" class="btn btn-primary"
                                        style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $customer['id']; ?>" class="btn delete-btn"
                                        style="background:var(--danger-color); color:#fff; padding: 5px 10px; font-size: 0.8rem;"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" style="text-align:center;">No customers found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>