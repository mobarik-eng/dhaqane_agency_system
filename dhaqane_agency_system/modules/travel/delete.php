<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM travel_bookings WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error deleting booking: " . $e->getMessage());
    }
}

header("Location: index.php");
exit();
?>