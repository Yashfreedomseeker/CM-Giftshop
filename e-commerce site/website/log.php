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

// Function to sanitize inputs
function clean_input($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['submit'])) {
        $username = clean_input($_POST['username']);
        $password = clean_input($_POST['password']);

        // Validate Username (should be numeric if it's UID)
        if (!ctype_digit($username)) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Invalid username format.'];
            header("Location: index.php");
            exit();
        }

        // Validate Password (Minimum 6 characters)
        if (strlen($password) < 6) {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Password must be at least 6 characters long.'];
            header("Location: index.php");
            exit();
        }

        // Prepare query to fetch user details
        $stmt = $conn->prepare("SELECT uid, name, address, phone, email, password FROM users WHERE uid = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['password'];

            // Verify Password
            if (password_verify($password, $hashedPassword)) {
                // Store user data in session
                $_SESSION['user_id'] = $user['uid'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_address'] = $user['address'];
                $_SESSION['user_phone'] = $user['phone'];
                $_SESSION['user_email'] = $user['email'];

                // Fetch cart_id associated with the user
                $stmt2 = $conn->prepare("SELECT cartId FROM cart WHERE uid = ?");
                $stmt2->bind_param("i", $user['uid']);
                $stmt2->execute();
                $cartResult = $stmt2->get_result();

                if ($cartResult->num_rows === 1) {
                    $cart = $cartResult->fetch_assoc();
                    $_SESSION['cart_id'] = $cart['cartId']; // Store the cart_id in the session
                } else {
                    // Handle case where no cart is found for the user
                    $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'No cart found for this user.'];
                    header("Location: index.php");
                    exit();
                }

                $_SESSION['modal_message'] = ['type' => 'success', 'message' => 'Login successful!'];
                header("Location: index.php");
                exit();
            } else {
                $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Incorrect password!'];
                header("Location: index.php");
                exit();
            }
        } else {
            $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Username not found!'];
            header("Location: index.php");
            exit();
        }
    } else {
        $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Form not submitted correctly.'];
        header("Location: index.php");
        exit();
    }
} catch (Exception $e) {
    $_SESSION['modal_message'] = ['type' => 'error', 'message' => 'Something went wrong: ' . $e->getMessage()];
    header("Location: index.php");
    exit();
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
