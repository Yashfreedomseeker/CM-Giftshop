<?php
session_name("admin_session");
session_start();
require 'dbcon.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // error reporting

try {
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    $sql = "SELECT p.productId, c.catId, p.productName, p.description, p.price, p.stock, p.image 
            FROM product p 
            JOIN category c ON p.catId = c.catId 
            WHERE p.productName LIKE '%$search%' 
            OR c.categoryName LIKE '%$search%'
            OR p.description LIKE '%$search%'
            OR p.price LIKE '%$search%'
            OR p.stock LIKE '%$search%' 
            OR p.productId LIKE '%$search%'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['productId']}</td>
                    <td>{$row['catId']}</td>
                    <td>{$row['productName']}</td>
                    <td>{$row['description']}</td>
                    <td>{$row['price']}</td>
                    <td>{$row['stock']}</td>
                    <td><img src='{$row['image']}' alt='Product Image' width='80' height='80'></td>
                    <td>
                        <button class='btn btn-danger delete-product' data-id='{$row['productId']}'>
                            <i class='fas fa-trash-alt'></i> Delete
                        </button>
                    </td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='8'>No products found.</td></tr>";
    }
} catch (Exception $e) {
    echo "<tr><td colspan='8'>Error: " . $e->getMessage() . "</td></tr>";
    error_log("Database Error: " . $e->getMessage()); // Logs the error for debugging
}

$conn->close();
?>
