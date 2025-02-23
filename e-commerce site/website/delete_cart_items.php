<?php
session_name("customer_session");
session_start();
if (isset($_SESSION['modal_message'])) {
    $modalMessage = $_SESSION['modal_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showMessageModal('{$modalMessage['message']}', '{$modalMessage['type']}');
        });
    </script>";
    unset($_SESSION['modal_message']); // Clear the session message after showing
}

require 'dbcon.php';

try {
    if (isset($_POST['itemId'])) {
        $itemid = $_POST['itemId'];
        
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE itemId = ?");
        $stmt->bind_param("s", $itemid);

        if ($stmt->execute()) {
            echo "Item deleted successfully!";
        } else {
            echo "Error deleting Item.";
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