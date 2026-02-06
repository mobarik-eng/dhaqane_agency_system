<?php
require_once '../../includes/auth_check.php';
require_once '../../config/db.php';

// Only Admin
if ($_SESSION['role'] !== 'admin') {
    header("Location: ../../dashboard.php");
    exit();
}

$id = $_GET['id'] ?? null;

if ($id) {
    try {
        // Prevent deleting self or main admin if needed
        if ($id == $_SESSION['user_id']) {
            die("Cannot delete logged in user.");
        }

        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$id]);
    } catch (PDOException $e) {
        die("Error deleting user: " . $e->getMessage());
    }
}

header("Location: index.php");
exit();
?>