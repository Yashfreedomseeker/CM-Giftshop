<?php
session_name("admin_session");
session_start();
require 'dbcon.php'; // Database connection

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT); // error reporting

try {
    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check if form is submitted
    if (isset($_POST["submit"])) {
        // Validate product ID (must be alphanumeric)
        if (!preg_match('/^[a-zA-Z0-9]+$/', $_POST['id'])) {
            throw new Exception("Invalid Product ID.");
        }

        // Sanitize inputs
        $productID = trim($_POST['id']);
        $productName = trim($_POST['name']);
        $description = trim($_POST['description']);
        $price = floatval($_POST['price']);
        $stock = intval($_POST['stock']);
        $categoryID = trim($_POST['category']);

        // Validate price & stock
        if ($price <= 0 || $stock < 0) {
            throw new Exception("Invalid price or stock amount.");
        }

        // Image Upload Handling
        $uploadedFile = null;
        if (isset($_FILES["image"]) && $_FILES["image"]["error"] === UPLOAD_ERR_OK) {
            $fileName = $_FILES["image"]["name"];
            $fileTmpName = $_FILES["image"]["tmp_name"];
            $fileSize = $_FILES["image"]["size"];

            // Validate file extension
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'avif', 'jpeg', 'webp'];
            $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            if (!in_array($fileExt, $allowedExtensions)) {
                throw new Exception('Invalid file type.');
            }

            if ($fileSize > 5000000) { // Limit: 5MB
                throw new Exception('File size exceeds 5MB limit.');
            }

            // Upload file
            $targetDir = "../products/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $uploadedFile = $targetDir . basename($fileName);
            if (!move_uploaded_file($fileTmpName, $uploadedFile)) {
                throw new Exception('Error moving uploaded file.');
            }
        } else {
            throw new Exception("Image upload failed.");
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO product (productId, catId, productName, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        
        if (!$stmt) {
            throw new Exception("SQL prepare failed: " . $conn->error);
        }

        $stmt->bind_param("isssdis", $productID, $categoryID, $productName, $description, $price, $stock, $uploadedFile);
        
        if (!$stmt->execute()) {
            throw new Exception("SQL execute failed: " . $stmt->error);
        }

        echo "<script>alert('Product added successfully'); window.location.href='dashboard.php';</script>";

        $stmt->close();
    }

} catch (Exception $e) {
    echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    error_log("Database Error: " . $e->getMessage()); // Logs the error for debugging
} finally {
    if (isset($conn) && $conn instanceof mysqli) {
        $conn->close();
    }
}
?>
