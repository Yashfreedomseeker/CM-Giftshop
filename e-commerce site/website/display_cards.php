<?php
session_name("customer_session");
session_start();
require 'dbcon.php';

// Ensure JSON response
header('Content-Type: application/json');
ob_clean();
ob_start();

try {
    $sql = "SELECT productId, catId, productName, description, price, stock, image FROM product";
    $result = $conn->query($sql);

    $products = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Fix image path
            $row['image'] = "/products/" . basename($row['image']); // Assuming images/ is at root
            $products[] = $row;
        }
    }

    // Output JSON data
    echo json_encode($products);
} catch (Exception $e) {
    echo json_encode(["error" => $e->getMessage()]);
}

$conn->close();
ob_end_flush();
?>
