<?php
session_name("admin_session");
session_start();

// Redirect to dashboard if already logged in
if (isset($_SESSION['user_id'])) {
    echo "<script>console.log('Already logged in. Redirecting to dashboard.');</script>";
    header("Location: dashboard.php");
    exit();
}

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

$message = ""; // To store error/success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        if (!isset($_POST['user']) || !isset($_POST['password'])) {
            throw new Exception("Username or password is missing.");
        }

        $username = trim($_POST['user']);
        $password = trim($_POST['password']);

        if (empty($username) || empty($password)) {
            throw new Exception("Username or password cannot be empty.");
        }

        // Prepare query to fetch user details
        $stmt = $conn->prepare("SELECT adminId, adminName, adminMobile, adminPassword FROM admin WHERE adminId = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            $hashedPassword = $user['adminPassword'];

            if (password_verify($password, $hashedPassword)) {
                // Start session and store user data
                $_SESSION['user_id'] = $user['adminId'];
                $_SESSION['user_name'] = $user['adminName'];
                $_SESSION['user_phone'] = $user['adminMobile'];

                $newExpiryTime = time() + 1800; // 30 minutes from now
                setcookie("session_expire", $newExpiryTime, [
                    'expires' => $newExpiryTime,
                    'path' => '/',
                    'secure' => false,
                    'httponly' => true
                ]);
                echo "<script>console.log('New session_expire cookie set: " . $newExpiryTime . "');</script>";
                echo "<script>alert('Login successful!'); window.location.href='dashboard.php';</script>";
                
                exit();
            } else {
                throw new Exception("Incorrect password!");
            }
        } else {
            throw new Exception("Username not found!");
        }
    } catch (Exception $e) {
        $message = $e->getMessage(); // Store error message to display later
    } finally {
        if ($conn) {
            $conn->close();
        }
    }
}
?>

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
    <script src="modal.js"></script> <!-- Include the modal script -->
    <title>CM | Login</title>
</head>
<body>
    <div class="row container-login">
        <div class="col-md-6 loginlogo">
            <img src="../images/logo.png" alt="logo" class="d-flex loginlogo" width="350px" height="350px">
        </div>
        <div class="col-md-6">
            <form method="POST" id="loginForm">
                <h1>Admin Login</h1>
                <hr>

                <?php if (!empty($message)): ?>
                    <script>
                        showMessageModal("<?php echo $message; ?>", "error");
                    </script>
                <?php endif; ?>

                <div class="mb-3">
                    <input type="text" class="form-control" name="user" id="user" placeholder="User Id">
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Password">
                </div>
                <br>
                <button type="submit" class="btn btn-success">Submit</button><br>
                <button class= "btn btn-outline-light me-3"><a href="signup.php" class="text-decoration-none text-light">Create an Account</a></button>
            </form>
        </div>
    </div>
</body>
</html>
