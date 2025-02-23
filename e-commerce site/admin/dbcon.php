<?php
//require 'vendor/autoload.php';

// Database configuration
$servername = "localhost";
$username = "root";
$password = "1234";
$dbname = "cmgiftshop";

// Enable mysqli exceptions for error handling
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Set charset to utf8mb4 (for better encoding support)
    $conn->set_charset("utf8mb4");

    // Check connection
    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Display error message
    die("Database Connection Error: " . $e->getMessage());
}

// Optional: Function to close the connection
function closeDatabaseConnection($conn) {
    if ($conn) {
        $conn->close();
    }
}
?>
