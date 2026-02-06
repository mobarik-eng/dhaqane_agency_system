<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        $stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        // Handle error (e.g. valid constraint)
        die("Error deleting customer: " . $e->getMessage());
    }
}

header("Location: index.php");
exit();
?>