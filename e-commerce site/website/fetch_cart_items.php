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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // error reporting

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';
    $cartId = $_SESSION['cart_id'];

    // Use prepared statement to prevent SQL injection
    $sql = "SELECT ci.cartId, ci.itemId, ci.productId, ci.quantity, ci.image, p.price 
            FROM cart_items ci
            JOIN product p ON ci.productId = p.productId
            WHERE ci.cartId = ? 
            AND (ci.itemId LIKE ? OR ci.productId LIKE ? OR ci.quantity LIKE ?)";

    $stmt = $conn->prepare($sql);
    $searchTerm = "%$search%"; // Wildcard search
    $stmt->bind_param("isss", $cartId, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td><input type='checkbox' class='select-item' data-id='{$row['itemId']}' data-product='{$row['productId']}' data-quantity='{$row['quantity']}' data-price='{$row['price']}' data-image='{$row['image']}'></td>
                    <td>{$row['itemId']}</td>
                    <td>{$row['productId']}</td>
                    <td>{$row['quantity']}</td>
                    <td><img src='{$row['image']}' alt='Product Image' width='80' height='80'></td>
                    <td class='text-center'>
                        <button class='btn btn-danger delete-item mb-2 w-75'  data-id='{$row['itemId']}'>
                            <i class='fas fa-trash-alt'></i> Delete
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No products found.</td></tr>";
    }

    $stmt->close();
} catch (Exception $e) {
    echo "<tr><td colspan='8'>Error: " . $e->getMessage() . "</td></tr>";
    error_log("Database Error: " . $e->getMessage()); // Logs the error for debugging
}

$conn->close();
?>
