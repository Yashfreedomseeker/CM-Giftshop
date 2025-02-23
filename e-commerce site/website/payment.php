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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'fpdf/fpdf.php';
require 'vendor/autoload.php';
require 'dbcon.php';


try {
    // Ensure required POST parameters are present
    if (!isset($_POST['productId'], $_POST['quantity'], $_POST['orderPrice'], $_POST['productImage'], $_POST['paymentMethod'], $_POST['price'])) {
        throw new Exception("Missing required parameters.");
    }

    $productId = intval($_POST['productId']);
    $quantity = intval($_POST['quantity']);
    $orderPrice = floatval($_POST['orderPrice']);
    $productImage = $_POST['productImage'];
    $paymentMethod = $_POST['paymentMethod'];
    $unitPrice = $_POST['price'];

    if (!isset($_SESSION['user_id'])) {
        throw new Exception("User not logged in.");
    }

    $userId = $_SESSION['user_id'];
    $orderStatus = "Pending";
    $orderDate = date('Y-m-d H:i:s');
    $paymentStatus = "Paid";
    $paymentId = rand(1000000, 9999999);
    $paymentDate = date('Y-m-d H:i:s');
    $orderId = uniqid("ORD");

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

    // // Fetch the correct unit price from the database (prevent tampering)
    // $stmt = $conn->prepare("SELECT price FROM product WHERE productId = ?");
    // if (!$stmt) {
    //     throw new Exception("Database error: " . $conn->error);
    // }
    // $stmt->bind_param("i", $productId);
    // $stmt->execute();
    // $result = $stmt->get_result();

    // if ($result->num_rows == 0) {
    //     throw new Exception("Product not found.");
    // }

    // $row = $result->fetch_assoc();
    // $unitPrice = $row['price'];

    // Insert order into database
    $stmt = $conn->prepare("INSERT INTO orders (orderId, uid, orderDate, totalamount, status, paymentMethod, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    $stmt->bind_param("sisdsss", $orderId, $userId, $orderDate, $orderPrice, $orderStatus, $paymentMethod, $paymentStatus);
    $stmt->execute();

    // Insert order details
    $stmt = $conn->prepare("INSERT INTO orderDetails (orderId, productId, quantity, image, orderPrice) VALUES (?, ?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    $stmt->bind_param("sidsd", $orderId, $productId, $quantity, $productImage, $orderPrice);
    $stmt->execute();

    // Insert payment record
    $stmt = $conn->prepare("INSERT INTO payments (paymentId, orderId, paymentDate, amount) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        throw new Exception("Database error: " . $conn->error);
    }
    $stmt->bind_param("issd", $paymentId, $orderId, $paymentDate, $orderPrice);
    $stmt->execute();

    echo "<form id='stripePaymentForm' action='stripe.php' method='POST'>
        <input type='hidden' name='productId' value='{$productId}'>
        <input type='hidden' name='quantity' value='{$quantity}'>
        <input type='hidden' name='orderPrice' value='{$orderPrice}'>
        <input type='hidden' name='paymentMethod' value='{$paymentMethod}'>
        <input type='hidden' name='price' value='{$unitPrice}'>
      </form>
      <script>
          document.getElementById('stripePaymentForm').submit();
      </script>";
    // Close the database connection

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

    $pdf->Cell(50, 10, $productId, 1, 0);
    $pdf->Cell(50, 10, $quantity, 1, 0);
    $pdf->Cell(50, 10, "LKR" . number_format($orderPrice, 2), 1, 1);

    $pdf->Cell(150, 10, "Total Amount", 1, 0, 'R');
    $pdf->Cell(50, 10, "LKR" . number_format($orderPrice, 2), 1, 1);

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
    exit();

} catch (Exception $e) {
    // Log the error
    error_log("Payment Success Error: " . $e->getMessage());
}
?>
