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
require __DIR__ . "/vendor/autoload.php";

header('Content-Type: application/json');

try {
    // Retrieve the POST data
    $cartItems = json_decode($_POST['cartItems'], true);
    $totalAmount = $_POST['totalAmount'];
    $paymentMethod = $_POST['paymentMethod'];

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    if (!is_array($cartItems) || count($cartItems) === 0) {
        throw new Exception("No items found for checkout.");
    }

    $userId = $_SESSION['user_id'];
    $orderId = uniqid("ORD");
    $orderDate = date('Y-m-d H:i:s');

    // Store order details in session to be used after payment
    $_SESSION['pending_order'] = [
        'orderId' => $orderId,
        'userId' => $userId,
        'totalAmount' => $totalAmount,
        'paymentMethod' => $paymentMethod,
        'orderDate' => $orderDate,
        'items' => $cartItems
    ];

    // Initialize Stripe
    \Stripe\Stripe::setApiKey("sk_test_51QrgH2QY8VhNwNVQ6uxdgfjjlq8VEh130It95WLDBJkZxVd6rxrSICPIWACd3oVB5Xk9GU9TfbZqwQKyqpLjjqhR00KtKWidMG");

    // Create line items for Stripe
    $line_items = [];
    foreach ($cartItems as $item) {
        $line_items[] = [
            "quantity" => $item['quantity'],
            "price_data" => [
                "currency" => "lkr",
                "unit_amount" => intval($item['price']* 100), // Stripe uses cents
                "product_data" => [
                    "name" => "Product ID: " . $item['productId']
                ]
            ]
        ];
    }

    // Create Stripe Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "mode" => "payment",
        "line_items" => $line_items,
        "success_url" => "http://localhost/dashboard/e-commerce%20site/website/success.php?session_id={CHECKOUT_SESSION_ID}",
        "cancel_url" => "http://localhost/dashboard/e-commerce%20site/website/index.php"
    ]);

    // Redirect to Stripe Checkout
    header("Location: " . $checkout_session->url);
    $_SESSION['modal_message'] = [
        "message" => "Your order has been successfully placed!",
        "type" => "success"
    ];
    exit();

} catch (\Stripe\Exception\ApiErrorException $e) {
    error_log("Stripe API Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Payment processing error."]);
} catch (Exception $e) {
    error_log("General Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
