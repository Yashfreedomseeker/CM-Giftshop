<?php
session_name("customer_session");
session_start();

// Destroy session properly
$_SESSION = []; // Clear session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy session

// Set a session message for the modal
session_start();
$_SESSION['modal_message'] = ['type' => 'success', 'message' => 'Logout successful!'];

// Redirect to index.php
header("Location: index.php");
exit();
?>
