<?php
session_name("customer_session");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'fpdf/fpdf.php';
require 'vendor/autoload.php';
require 'dbcon.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if (isset($_SESSION['modal_message'])) {
    $modalMessage = $_SESSION['modal_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showMessageModal('{$modalMessage['message']}', '{$modalMessage['type']}');
        });
    </script>";
    unset($_SESSION['modal_message']); // Clear the session message after showing
}


// Check if session has pending order data
if (!isset($_SESSION['pending_order'])) {
    die("No order details found.");
}

// Retrieve order details from session
$orderData = $_SESSION['pending_order'];
unset($_SESSION['pending_order']); // Remove after processing

$orderId = $orderData['orderId'];
$userId = $orderData['userId'];
$totalAmount = $orderData['totalAmount'];
$paymentMethod = $orderData['paymentMethod'];
$orderDate = $orderData['orderDate'];
$items = $orderData['items'];
$paymentStatus = "Paid";
$paymentId = rand(1000000, 9999999);
$paymentDate = date("Y-m-d H:i:s");

// Retrieve user email
$stmt = $conn->prepare("SELECT email FROM users WHERE uid = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows == 0) {
    throw new Exception("User not found.");
}
$row = $result->fetch_assoc();
$userEmail = $row['email'];

try {
    $conn->begin_transaction();

    // Insert into orders table
    $stmt = $conn->prepare("INSERT INTO orders (orderId, uid,  orderDate, totalamount, status, paymentMethod, paymentStatus) VALUES (?, ?, ?, ?, 'Completed', ?, 'Paid')");
    $stmt->bind_param("sisds", $orderId, $userId, $orderDate, $totalAmount, $paymentMethod);
    $stmt->execute();
    $stmt->close();

    // Insert into orderDetails table
    $stmt = $conn->prepare("INSERT INTO orderdetails (orderId, productId, quantity, image, orderPrice) VALUES (?, ?, ?, ?, ?)");
    foreach ($items as $item) {
        $productId = $item['productId'];
        $quantity = $item['quantity'];
        $orderPrice = $item['orderPrice'];
        $productImage = $item['productImage'] ?? ''; // Default empty if not provided

        $stmt->bind_param("sidsd", $orderId, $productId, $quantity, $productImage, $orderPrice);
        $stmt->execute();
    }
    $stmt->close();

    // Insert into payments table
    $stmt = $conn->prepare("INSERT INTO payments (paymentId, orderId, paymentDate, amount) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $paymentId, $orderId, $paymentDate, $totalAmount);
    $stmt->execute();
    $stmt->close();

    $stmt = $conn->prepare("UPDATE product SET stock = stock - ? WHERE productId = ? AND stock >= ?");
    $stmt->bind_param("iii", $quantity, $productId, $quantity);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    // Generate PDF receipt
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(190, 10, "Order Receipt", 1, 1, 'C');
    
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(95, 10, "Order ID: $orderId", 1, 0);
    $pdf->Cell(95, 10, "Date: $orderDate", 1, 1);
    $pdf->Cell(95, 10, "Payment Method: $paymentMethod", 1, 0);
    $pdf->Cell(95, 10, "Payment Status: $paymentStatus", 1, 1);

    $pdf->Ln(5);
    $pdf->Cell(190, 10, "Ordered Items", 1, 1, 'C');
    $pdf->Cell(50, 10, "Product ID", 1, 0);
    $pdf->Cell(50, 10, "Quantity", 1, 0);
    $pdf->Cell(50, 10, "Price", 1, 1);

    foreach ($items as $item) {
        $pdf->Cell(50, 10, $item['productId'], 1, 0);
        $pdf->Cell(50, 10, $item['quantity'], 1, 0);
        $pdf->Cell(50, 10, "LKR" . number_format($item['orderPrice'], 2), 1, 1);
    }

    $pdf->Cell(150, 10, "Total Amount", 1, 0, 'R');
    $pdf->Cell(50, 10, "LKR" . number_format($totalAmount, 2), 1, 1);

    $pdfFile = "receipts/receipt_$orderId.pdf";
    $pdf->Output('F', $pdfFile); // Save PDF to file

    // Send email with PDF attachment
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'claimorgiftshop@gmail.com'; // SMTP username
        $mail->Password = 'jeyu mgdv gotj hvjo'; // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('claimorgiftshop@gmail.com', 'CM Gift Shop');
        $mail->addAddress($userEmail);
        $mail->Subject = "Order Receipt - $orderId";
        $mail->Body = "Thank you for your order. Please find your receipt attached. Our delivery unit will contact you soon.";
        $mail->addAttachment($pdfFile);

        $mail->send();
    } catch (Exception $e) {
        //error_log("Email sending failed: " . $mail->ErrorInfo);
        $_SESSION['modal_message'] = [
            "message" => "There was an error sending your email. Please try again.".$mail->ErrorInfo,
            "type" => "error"
        ];
        header("Location: index.php");
    }

    //Success message
    $_SESSION['modal_message'] = [
        "message" => "Your order has been successfully placed!",
        "type" => "success"
    ];
    header("Location: index.php");
    exit();

} catch (Exception $e) {
    $conn->rollback();
    error_log("Order Processing Error: " . $e->getMessage());
    die("Error processing order.");
}
?>
