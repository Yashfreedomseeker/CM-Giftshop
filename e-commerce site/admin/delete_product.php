<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    if (isset($_POST['productId'])) {
        $pid = $_POST['productId'];
        
        $stmt = $conn->prepare("DELETE FROM product WHERE productId = ?");
        $stmt->bind_param("i", $pid);

        if ($stmt->execute()) {
            echo "Product deleted successfully!";
        } else {
            echo "Error deleting Product.";
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