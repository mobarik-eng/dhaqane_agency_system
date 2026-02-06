<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Cargo Shipments',
    'subtitle' => 'Manage Goods and Logistics'
];
$currentPage = 'cargo';

// Fetch Cargo with Sender Names
$stmt = $pdo->query("
    SELECT s.*, c.name as sender_name, c.phone as sender_phone
    FROM cargo_shipments s 
    JOIN customers c ON s.sender_id = c.id 
    ORDER BY s.created_at DESC
");
$shipments = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Shipment List</h3>
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Shipment</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Tracking #</th>
                        <th>Sender</th>
                        <th>Receiver</th>
                        <th>Weight</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($shipments) > 0): ?>
                        <?php foreach ($shipments as $shipment): ?>
                            <tr>
                                <td><strong>
                                        <?php echo htmlspecialchars($shipment['tracking_no']); ?>
                                    </strong></td>
                                <td>
                                    <?php echo htmlspecialchars($shipment['sender_name']); ?><br>
                                    <small>
                                        <?php echo htmlspecialchars($shipment['sender_phone']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($shipment['receiver_name']); ?><br>
                                    <small>
                                        <?php echo htmlspecialchars($shipment['receiver_phone']); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php echo $shipment['weight']; ?> kg
                                </td>
                                <td>$
                                    <?php echo number_format($shipment['total_amount'], 2); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'badge-warning';
                                    if ($shipment['status'] == 'Arrived')
                                        $statusClass = 'badge-success';
                                    if ($shipment['status'] == 'Delivered')
                                        $statusClass = 'badge-success';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $shipment['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $shipment['id']; ?>" class="btn btn-primary"
                                        style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $shipment['id']; ?>" class="btn delete-btn"
                                        style="background:var(--danger-color); color:#fff; padding: 5px 10px; font-size: 0.8rem;"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" style="text-align:center;">No shipments found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>