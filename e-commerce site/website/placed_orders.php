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
header('Content-Type: application/json');

try {
    if (!isset($_POST['productId']) || !isset($_POST['quantity']) || !isset($_POST['orderPrice']) || !isset($_POST['productImage']) || !isset($_POST['paymentMethod'])) {
        throw new Exception("Invalid request parameters.");
    }

    $productId = intval($_POST['productId']);
    $quantity = intval($_POST['quantity']);
    $orderPrice = floatval($_POST['orderPrice']);
    $productImage = $_POST['productImage']; // Retrieve the product image URL
    $userId = $_SESSION['user_id']; // Assuming user is logged in
    $orderStatus = "Pending";
    $orderDate = date('Y-m-d H:i:s');
    $paymentMethod = $_POST['paymentMethod'];
    $paymentStatus = "Pending"; 

    // Generate a unique order ID
    $orderId = uniqid("ORD");

    // Get the unit price from the product table (prevents tampering)
    $stmt = $conn->prepare("SELECT price FROM product WHERE productId = ?");
    $stmt->bind_param("i", $productId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows == 0) {
        throw new Exception("Product not found.");
    }
    $row = $result->fetch_assoc();
    $unitPrice = $row['price'];

    // Insert order
    $stmt = $conn->prepare("INSERT INTO orders (orderId, uid, orderDate, totalamount, status, paymentMethod, paymentStatus) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisdsss", $orderId, $userId, $orderDate, $orderPrice, $orderStatus, $paymentMethod, $paymentStatus);
    $stmt->execute();

    // Insert order details
    $stmt = $conn->prepare("INSERT INTO orderDetails (orderId, productId, quantity, image, orderPrice) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sidsd", $orderId, $productId, $quantity, $productImage, $orderPrice);
    $stmt->execute();

    $stmt = $conn->prepare("UPDATE product SET stock = stock - ? WHERE productId = ? AND stock >= ?");
    $stmt->bind_param("iii", $quantity, $productId, $quantity);
    $stmt->execute();
    $stmt->close();

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
        $mail->Body = "Thank you for your order. Please find your receipt attached.Our delivery unit will contact you soon.";
        $mail->addAttachment($pdfFile);

        $mail->send();
    } catch (Exception $e) {
        //error_log("Email sending failed: " . $mail->ErrorInfo);
        echo json_encode(["status" => "error", "message" => "There was an error sending your email. Please try again. ". $mail->ErrorInfo]);
        header("Location: index.php");
    }

    //Success message
    echo json_encode(["status" => "success", "message" => "Your order has been successfully placed!"]);
    header("Location: index.php");
    exit();
    ob_clean(); // Prevent unwanted output
    flush();

    echo json_encode(["success" => true, "message" => "Order placed successfully!"]);
} catch (Exception $e) {
    error_log("Order Error: " . $e->getMessage()); // Log errors instead of showing HTML
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>
