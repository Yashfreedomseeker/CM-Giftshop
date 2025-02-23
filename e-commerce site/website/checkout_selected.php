<?php
session_name("customer_session");
session_start();

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'fpdf/fpdf.php';
require 'vendor/autoload.php';
require 'dbcon.php';
header("Content-Type: application/json");

if (isset($_SESSION['modal_message'])) {
    $modalMessage = $_SESSION['modal_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showMessageModal('{$modalMessage['message']}', '{$modalMessage['type']}');
        });
    </script>";
    unset($_SESSION['modal_message']); // Clear the session message after showing
}

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Read the JSON data
    $inputData = json_decode(file_get_contents("php://input"), true);

    // Check if the items data exists and is an array
    if (!isset($inputData['items']) || !is_array($inputData['items']) || count($inputData['items']) == 0) {
        throw new Exception("No items selected for checkout.");
    }

    // Validate user session
    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    $userId = $_SESSION['user_id'];
    $orderStatus = "Pending";
    $orderDate = date('Y-m-d H:i:s');
    $paymentStatus = "Pending";
    $paymentMethod = $inputData['paymentMethod'] ?? "Unknown";
    $orderId = uniqid("ORD");
    $totalAmount = $inputData['totalamount']; // Get the total amount passed from JS

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


    // Insert order into the database
    $stmt = $conn->prepare("INSERT INTO orders (orderId, uid, orderDate, totalamount, status, paymentMethod, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdsss", $orderId, $userId, $orderDate, $totalAmount, $orderStatus, $paymentMethod, $paymentStatus);
    $stmt->execute();

    // Insert order details for each product
    $stmt = $conn->prepare("INSERT INTO orderDetails (orderId, productId, quantity, image, orderPrice) VALUES (?, ?, ?, ?, ?)");
    foreach ($inputData['items'] as $item) {
        $productId = $item['productId'];
        $quantity = $item['quantity'];
        $orderPrice = $item['orderPrice'];
        $productImage = isset($item['productImage']) ? $item['productImage'] : '';  // Check if image exists

        // Ensure orderPrice is numeric and valid
        if (!is_numeric($orderPrice)) {
            throw new Exception("Invalid order price for product ID: $productId");
        }

        // Insert the order details into the database
        $stmt->bind_param("sidsd", $orderId, $productId, $quantity, $productImage, $orderPrice);
        $stmt->execute();
    }

    // Update total amount for the order after all products have been inserted
    $stmt = $conn->prepare("UPDATE orders SET totalamount = ? WHERE orderId = ?");
    $stmt->bind_param("ds", $totalAmount, $orderId);
    $stmt->execute();

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

    foreach ($inputData['items'] as $item) {
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
        echo json_encode(["status" => "error", "message" => "There was an error sending your email. Please try again.".$mail->ErrorInfo]);
        header("Location: index.php");
    }
   
    //Success message
    echo json_encode(["status" => "success", "message" => "Your order has been successfully placed!"]);
    header("Location: index.php");
    exit();

} catch (Exception $e) {
    // Set error message in session if something goes wrong
    echo json_encode(["status" => "error", "message" => "Error: " . $e->getMessage()]);
    header("Location: index.php");
}

?>
