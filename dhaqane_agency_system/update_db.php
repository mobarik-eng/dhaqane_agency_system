<?php
require_once 'config/db.php';

try {
    $pdo->exec("ALTER TABLE users ADD COLUMN fullname VARCHAR(100) AFTER username");
    $pdo->exec("ALTER TABLE users ADD COLUMN phone VARCHAR(20) AFTER fullname");
    $pdo->exec("ALTER TABLE users ADD COLUMN photo VARCHAR(255) AFTER phone");
    echo "Database updated successfully!";
} catch (PDOException $e) {
    echo "Update (might already exist): " . $e->getMessage();
}
?>