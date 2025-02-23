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

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable detailed error reporting

try {
    $userId = $_SESSION['user_id']; // Get logged-in user ID
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $sql = "SELECT o.orderId, o.orderDate, o.status, o.totalamount,
                   od.productId, od.quantity, od.orderPrice,
                   p.productName, p.image, p.price
            FROM `orders` o
            JOIN orderDetails od ON o.orderId = od.orderId
            JOIN product p ON od.productId = p.productId
            WHERE o.uid = ?
            AND o.orderId LIKE '%$search%' OR od.productId LIKE '%$search%' OR od.quantity LIKE '%$search%' OR o.orderDate LIKE '%$search%' OR o.status LIKE '%$search%' OR o.totalamount LIKE '%$search%' OR od.orderprice LIKE '%$search%' OR p.productName LIKE '%$search%'
            ORDER BY o.orderDate DESC, o.orderId ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[$row['orderId']]['orderDetails'][] = $row;
        $orders[$row['orderId']]['orderInfo'] = [
            'orderId' => $row['orderId'],
            'orderDate' => $row['orderDate'],
            'status' => $row['status'],
            'totalamount' => $row['totalamount']
        ];
    }

    if (!empty($orders)) {
        foreach ($orders as $orderId => $orderData) {
            $orderInfo = $orderData['orderInfo'];
            $products = $orderData['orderDetails'];
            $rowspan = count($products); // Number of products in this order

            foreach ($products as $index => $product) {
                echo "<tr>";

                // Merge Order Details Only for the First Row
                if ($index === 0) {
                    echo "<td rowspan='{$rowspan}'>{$orderInfo['orderId']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['orderDate']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['status']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['totalamount']}</td>";
                }

                // Product Details
                echo "<td>{$product['productName']}</td>
                      <td><img src='{$product['image']}' alt='Product Image' width='80' height='80'></td>
                      <td>{$product['price']}</td>
                      <td>{$product['quantity']}</td>
                      <td>{$product['orderPrice']}</td>";

                // Action Buttons (Only in the first row)
                // if ($index === 0) {
                //     echo "<td rowspan='{$rowspan}'>
                //               <button class='btn btn-success pay-order w-75' data-id='{$orderInfo['orderId']}'>
                //                   <i class='fas fa-trash-alt'></i> Delete
                //               </button>
                //           </td>";
                // }

                echo "</tr>";
            }
        }
    } else {
        echo "<tr><td colspan='10'>No orders found.</td></tr>";
    }

    $stmt->close();
} catch (Exception $e) {
    echo "<tr><td colspan='10'>Error: " . $e->getMessage() . "</td></tr>";
    error_log("Database Error: " . $e->getMessage()); // Log error for debugging
}

$conn->close();
?>
