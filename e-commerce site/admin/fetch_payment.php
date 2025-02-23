<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

try {
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $sql = "SELECT * FROM payments 
            WHERE paymentId LIKE '%$search%' 
            OR orderId LIKE '%$search%' 
            OR paymentDate LIKE '%$search%'
            OR amount LIKE '%$search%'";
            
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['paymentId']}</td>
                    <td>{$row['orderId']}</td>
                    <td>{$row['paymentDate']}</td>
                    <td>{$row['amount']}</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No payments users found.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='6'>Error: " . $e->getMessage() . "</td></tr>";
}

$conn->close();
?>
