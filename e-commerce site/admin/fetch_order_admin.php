<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // Enable detailed error reporting

try {
    $search = isset($_POST['search']) ? $_POST['search'] : '';

    $sql = "SELECT o.orderId, o.orderDate, o.status, o.totalamount, o.uid,
                   od.productId, od.quantity, od.orderPrice,
                   p.productName, p.price
            FROM `orders` o
            JOIN orderDetails od ON o.orderId = od.orderId
            JOIN product p ON od.productId = p.productId
            WHERE o.orderId LIKE ? OR od.productId LIKE ? OR od.quantity LIKE ? 
                  OR o.orderDate LIKE ? OR o.status LIKE ? OR o.totalamount LIKE ?
                  OR od.orderPrice LIKE ? OR p.productName LIKE ?
            ORDER BY o.uid, o.orderDate DESC, o.orderId ASC";

    $searchTerm = "%$search%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();

    $orders = [];
    while ($row = $result->fetch_assoc()) {
        $orders[$row['orderId']]['orderDetails'][] = $row;
        $orders[$row['orderId']]['orderInfo'] = [
            'userId' => $row['uid'],
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
            $rowspan = count($products);

            foreach ($products as $index => $product) {
                echo "<tr>";
                
                if ($index === 0) {
                    echo "<td rowspan='{$rowspan}'>{$orderInfo['userId']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['orderId']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['orderDate']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['status']}</td>
                          <td rowspan='{$rowspan}'>{$orderInfo['totalamount']}</td>";
                }

                echo "<td>{$product['productName']}</td>
                      <td>{$product['price']}</td>
                      <td>{$product['quantity']}</td>
                      <td>{$product['orderPrice']}</td>";
                
                echo "</tr>";
            }
        }
    } else {
        echo "<tr><td colspan='9'>No orders found.</td></tr>";
    }

    $stmt->close();
} catch (Exception $e) {
    echo "<tr><td colspan='9'>Error: " . $e->getMessage() . "</td></tr>";
    error_log("Database Error: " . $e->getMessage());
}

$conn->close();
?>
