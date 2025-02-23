<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    if (isset($_POST['uid'])) {
        $uid = $_POST['uid'];
        
        $stmt = $conn->prepare("DELETE FROM users WHERE uid = ?");
        $stmt->bind_param("i", $uid);

        if ($stmt->execute()) {
            echo "User deleted successfully!";
        } else {
            echo "Error deleting user.";
        }

        $stmt->close();
    } else {
        throw new Exception("Invalid request.");
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}

$conn->close();

?>
