<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="adstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>CM | Sign Up</title>
</head>
<body>
    <div class="row container-login">
        <div class="col-md-6 loginlogo">
            <img src="../images/logo.png" alt="logo" class= "d-flex loginlogo" width="350px" height="350px">
        </div>
        <div class="col-md-6">
            <form action="#" method="POST" id="loginForm">
                <h1>Admin Sign Up</h1>
                <hr>
                <div class="mb-3">
                    <input type="text" class="form-control" id="name" name="name" aria-describedby="emailHelp" placeholder="Name">
                </div>
                <div class="mb-3">
                    <input type="text" class="form-control" id="mobile" name="mobile" aria-describedby="emailHelp" placeholder="Mobile No">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                </div>

                <button type="submit" class="btn btn-success">Submit</button><br>
                <button class= "btn btn-outline-light me-3"><a href="login.php" class="text-decoration-none text-light">Already have an Account?</a></button>
            </form>
        </div>
    </div>

    <!-- Bootstrap Modal -->
    <div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="messageModalLabel">Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalMessage">
                    <!-- Message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showMessageModal(message, type) {
            var modalMessage = document.getElementById("modalMessage");
            var modalTitle = document.getElementById("messageModalLabel");

            // Change modal title and message content based on type
            if (type === "error") {
                modalTitle.innerHTML = "Error";
                modalMessage.innerHTML = `<div class="alert alert-danger">${message}</div>`;
            } else if (type === "success") {
                modalTitle.innerHTML = "Success";
                modalMessage.innerHTML = `<div class="alert alert-success">${message}</div>`;
            } else {
                modalTitle.innerHTML = "Message";
                modalMessage.innerHTML = `<div class="alert alert-info">${message}</div>`;
            }

            // Show the modal
            var modal = new bootstrap.Modal(document.getElementById("messageModal"));
            modal.show();
        }
    </script>

</body>
</html>

<?php
session_name("admin_session");
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Database connection
    require 'dbcon.php';

    // Initialize error messages
    $errors = [];

    $id = 'admin' . str_pad(rand(0, 999), 3, '0', STR_PAD_LEFT);

    // Sanitize and validate Name
    $name = trim($_POST["name"]);
    if (empty($name) || !preg_match("/^[a-zA-Z ]+$/", $name)) {
        $errors[] = "Invalid Name. Only letters and spaces allowed.";
    }

    // Validate Mobile Number (exactly 10 digits)
    $mobile = trim($_POST["mobile"]);
    if (empty($mobile) || !preg_match("/^[0-9]{10}$/", $mobile)) {
        $errors[] = "Invalid Mobile Number. Must be exactly 10 digits.";
    }

    // Validate Password (minimum 6 characters)
    $password = trim($_POST["password"]);
    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    // If there are validation errors, display them
    if (!empty($errors)) {
        foreach ($errors as $error) {
            // echo "<p style='color: red;'>$error</p>";
            echo "<script>showMessageModal('Error: $error ', 'error');</script>";
        }
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Prepare SQL statement
        try {
            $stmt = $conn->prepare("INSERT INTO admin (adminId, adminName, adminMobile, adminPassword) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssis", $id, $name, $mobile, $hashed_password);

            if ($stmt->execute()) {
                echo "<script>showMessageModal('Registration successful!', 'success');</script>";
            } else {
                echo "<script>showMessageModal('Error: Could not register user.', 'error');</script>";
            }
            $stmt->close();
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            echo "<script>showMessageModal('Error: $errorMessage', 'error');</script>";
        }
    }

    // Close database connection
    $conn->close();
}
?>
