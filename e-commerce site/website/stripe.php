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
    // Ensure required POST parameters are set
    if (!isset($_POST['productId'], $_POST['quantity'], $_POST['orderPrice'], $_POST['paymentMethod'], $_POST['price'])) {
        throw new Exception("Missing required parameters.");
    }

    $productId = intval($_POST['productId']);
    $quantity = intval($_POST['quantity']);
    $orderPrice = floatval($_POST['orderPrice']);
    $paymentMethod = $_POST['paymentMethod'];
    $unitPrice= floatval($_POST['price']);

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    $userId = $_SESSION['user_id'];

    // Stripe API key
    $stripe_secretkey = "sk_test_51QrgH2QY8VhNwNVQ6uxdgfjjlq8VEh130It95WLDBJkZxVd6rxrSICPIWACd3oVB5Xk9GU9TfbZqwQKyqpLjjqhR00KtKWidMG";
    \Stripe\Stripe::setApiKey($stripe_secretkey);

    // Create a Stripe Checkout Session
    $checkout_session = \Stripe\Checkout\Session::create([
        "payment_method_types" => ["card"],
        "mode" => "payment",
        "line_items" => [
            [
                "quantity" => $quantity,
                "price_data" => [
                    "currency" => "lkr",
                    "unit_amount" => $unitPrice * 100, // Stripe uses cents, so multiply by 100
                    "product_data" => [
                        "name" => "Product ID: $productId"
                    ]
                ]
            ]
        ],
        "success_url" => "http://localhost/dashboard/e-commerce%20site/website/index.php",
        "cancel_url" => "http://localhost/dashboard/e-commerce%20site/website/index.php"

        //productId=$productId&quantity=$quantity&orderPrice=$orderPrice&productImage=" . urlencode($productImage) . "&paymentMethod=$paymentMethod
    ]);

    // Redirect to Stripe Checkout
    http_response_code(303);
    $_SESSION['modal_message'] = [
        "message" => "Your order has been successfully placed!",
        "type" => "success"
    ];
    header("Location: " . $checkout_session->url);
    exit();
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Stripe-specific errors
    error_log("Stripe API Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Payment processing error. Please try again."]);
} catch (Exception $e) {
    // General errors
    error_log("General Error: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
