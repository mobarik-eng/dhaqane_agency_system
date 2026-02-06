<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$pageInfo = [
    'title' => 'Travel Bookings',
    'subtitle' => 'Manage Flights and Visas'
];
$currentPage = 'travel';

// Fetch Bookings with Customer Names
$stmt = $pdo->query("
    SELECT t.*, c.name as customer_name, c.passport_no 
    FROM travel_bookings t 
    JOIN customers c ON t.customer_id = c.id 
    ORDER BY t.created_at DESC
");
$bookings = $stmt->fetchAll();

include '../../includes/header.php';
?>

<div class="card">
    <div class="card-header">
        <h3>Booking List</h3>
        <a href="add.php" class="btn btn-primary"><i class="fas fa-plus"></i> New Booking</a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Customer</th>
                        <th>Type</th>
                        <th>Description</th>
                        <th>Date</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($bookings) > 0): ?>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td>#
                                    <?php echo $booking['id']; ?>
                                </td>
                                <td>
                                    <strong>
                                        <?php echo htmlspecialchars($booking['customer_name']); ?>
                                    </strong><br>
                                    <small>
                                        <?php echo htmlspecialchars($booking['passport_no']); ?>
                                    </small>
                                </td>
                                <td><span class="badge badge-warning">
                                        <?php echo htmlspecialchars($booking['type']); ?>
                                    </span></td>
                                <td>
                                    <?php echo htmlspecialchars($booking['description']); ?>
                                </td>
                                <td>
                                    <?php echo $booking['flight_date'] ? date('M d, Y', strtotime($booking['flight_date'])) : 'N/A'; ?>
                                </td>
                                <td>$
                                    <?php echo number_format($booking['amount'], 2); ?>
                                </td>
                                <td>
                                    <?php
                                    $statusClass = 'badge-warning';
                                    if ($booking['status'] == 'Confirmed')
                                        $statusClass = 'badge-success';
                                    if ($booking['status'] == 'Cancelled')
                                        $statusClass = 'badge-danger';
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>">
                                        <?php echo $booking['status']; ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="edit.php?id=<?php echo $booking['id']; ?>" class="btn btn-primary"
                                        style="padding: 5px 10px; font-size: 0.8rem;"><i class="fas fa-edit"></i></a>
                                    <a href="delete.php?id=<?php echo $booking['id']; ?>" class="btn delete-btn"
                                        style="background:var(--danger-color); color:#fff; padding: 5px 10px; font-size: 0.8rem;"><i
                                            class="fas fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" style="text-align:center;">No bookings found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../../includes/footer.php'; ?>