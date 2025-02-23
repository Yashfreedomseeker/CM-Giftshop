<?php
session_name("customer_session");
session_start(); // Start session at the beginning
if (isset($_SESSION['modal_message'])) {
    $modalMessage = $_SESSION['modal_message'];
    echo "<script>
        document.addEventListener('DOMContentLoaded', function() {
            showMessageModal('{$modalMessage['message']}', '{$modalMessage['type']}');
        });
    </script>";
    unset($_SESSION['modal_message']); // Clear the session message after showing
}

if (isset($_SESSION['user_id'])) {
    $_SESSION['modal_message'] = ['type' => 'info', 'message' => 'You are already signed up and logged in.'];
    header("Location: index.php");  // Redirect back to home or another page
    exit;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
require 'vendor/autoload.php';
require 'dbcon.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Function to sanitize user inputs
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {
    try {
        // Generate unique IDs
        $unic = rand(10000, 99999);
        $cartid = rand(1000, 9999);

        // Sanitize inputs
        $uname = clean_input($_POST['name']);
        $umobile = clean_input($_POST['mobile']);
        $uaddress = clean_input($_POST['address']);
        $umail = clean_input($_POST['email']);
        $upassword = $_POST['password'];

        // Input Validation
        if (empty($uname) || empty($umobile) || empty($uaddress) || empty($umail) || empty($upassword)) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'All fields are required.'];
            header("Location: index.php");
            exit;
        }

        if (!preg_match("/^[a-zA-Z-' ]*$/", $uname)) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Invalid name format.'];
            header("Location: index.php");
            exit;
        }

        if (!filter_var($umail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Please enter a valid email address.'];
            header("Location: index.php");
            exit;
        }

        if (!preg_match("/^[0-9]{10}$/", $umobile)) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Invalid phone number. Must be 10 digits.'];
            header("Location: index.php");
            exit;
        }

        if (strlen($upassword) < 6) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Password must be at least 6 characters long.'];
            header("Location: index.php");
            exit;
        }

        // Secure password hashing
        $upassword_hashed = password_hash($upassword, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (uid, name, address, phone, email, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssss", $unic, $uname, $uaddress, $umobile, $umail, $upassword_hashed);
        $stmt->execute();

        $stmt3 = $conn->prepare("INSERT INTO cart (cartId, uid) VALUES (?, ?)");
        $stmt3->bind_param("ii", $cartid, $unic);
        $stmt3->execute();

        $stmt->close();
        $stmt3->close();

        // Send email
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'claimorgiftshop@gmail.com';
            $mail->Password = 'jeyu mgdv gotj hvjo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('claimorgiftshop@gmail.com', 'CM Gift Shop');
            $mail->addAddress($umail, $uname);

            $mail->isHTML(true);
            $mail->Subject = 'Welcome to CM Giftshop';
            $mail->Body = "<h2>Your account has been created!</h2>
                           <p>Your User ID: <strong>{$unic}</strong></p>";

            $mail->send();

            $_SESSION['modal_message'] = ['type' => 'success', 'message' => 'Account successfully created! Check your email.'];
        } catch (Exception $e) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Error sending email: ' . $e->getMessage()];
        }

        header("Location: index.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Something went wrong. Please try again.'];
        header("Location: index.php");
        exit;
    } finally {
        if (isset($conn) && $conn instanceof mysqli) {
            $conn->close();
        }
    }
}
?>
