<?php
session_name("customer_session");
session_start(); // Start session at the beginning
include 'dbcon.php'; // Make sure the database connection is included

try {
    // Ensure that cart_id is available in the session
    if (!isset($_SESSION['cart_id'])) {
        throw new Exception("User's cart_id is not available in the session.");
    }

    // Get the cart_id from the session
    $cart_id = $_SESSION['cart_id'];

    // Get the product details and quantity from the AJAX request
    if (!isset($_POST['productId'], $_POST['quantity'], $_POST['image'])) {
        throw new Exception("Missing required product details.");
    }

    $productId = $_POST['productId'];
    $quantity = $_POST['quantity'];
    $image = $_POST['image'];

    // Generate a unique item_id for the cart item
    $item_id = uniqid('item_'); // Generate a random unique item ID

    // Insert the product into the cart_items table
    $query = "INSERT INTO cart_items (itemId, cartId, productId, quantity, image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);

    if (!$stmt) {
        throw new Exception("Failed to prepare the query.");
    }

    // Bind parameters and execute the query
    $stmt->bind_param("sssis", $item_id, $cart_id, $productId, $quantity, $image);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute the query: " . $stmt->error);
    }

    // Success
    echo json_encode(["success" => true, "message" => "Item added to cart successfully."]);

} catch (Exception $e) {
    // Handle any errors or exceptions
    echo json_encode(["success" => false, "message" => "Error: " . $e->getMessage()]);
} finally {
    // Close the statement and connection
    if (isset($stmt)) {
        $stmt->close();
    }
    $conn->close();
}
?>
