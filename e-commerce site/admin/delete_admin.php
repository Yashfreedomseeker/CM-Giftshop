<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    if (isset($_POST['adminId'])) {
        $adminid = $_POST['adminId'];
        
        $stmt = $conn->prepare("DELETE FROM admin WHERE adminId = ?");
        $stmt->bind_param("s", $adminid);

        if ($stmt->execute()) {
            echo "Admin user deleted successfully!";
        } else {
            echo "Error deleting Admin.";
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