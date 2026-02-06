<?php
require_once 'config/db.php';

// The password we want to use
$new_password = 'admin123';

// Generate a secure hash
$new_hash = password_hash($new_password, PASSWORD_DEFAULT);

try {
    // Update the admin user
    $stmt = $pdo->prepare("UPDATE users SET password = :pass WHERE username = 'admin'");
    $stmt->execute([':pass' => $new_hash]);

    // Check if any row was actually updated
    if ($stmt->rowCount() > 0) {
        echo "<div style='font-family: Arial; padding: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>";
        echo "<h3>✅ Success!</h3>";
        echo "<p>Admin password has been reset.</p>";
        echo "<p><strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> admin123</p>";
        echo "<br><a href='index.php' style='background: #0056b3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a>";
        echo "</div>";
    } else {
        // Maybe the user doesn't exist? Let's try to insert if update failed (though rowCount might be 0 if pass is same)
        // But to be safe, let's just force recreate admin
        $stmt = $pdo->prepare("DELETE FROM users WHERE username = 'admin'");
        $stmt->execute();

        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES ('admin', :pass, 'admin')");
        $stmt->execute([':pass' => $new_hash]);

        echo "<div style='font-family: Arial; padding: 20px; background: #d4edda; color: #155724; border: 1px solid #c3e6cb; border-radius: 5px;'>";
        echo "<h3>✅ Admin Account Created/Reset!</h3>";
        echo "<p>You can now login with:</p>";
        echo "<p><strong>Username:</strong> admin<br>";
        echo "<strong>Password:</strong> admin123</p>";
        echo "<br><a href='index.php' style='background: #0056b3; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;'>Go to Login Page</a>";
        echo "</div>";
    }

} catch (PDOException $e) {
    echo "<div style='font-family: Arial; padding: 20px; background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px;'>";
    echo "<h3>❌ Error</h3>";
    echo "<p>" . $e->getMessage() . "</p>";
    echo "<p>Please ensure your database configuration in <code>config/db.php</code> is correct.</p>";
    echo "</div>";
}
?>