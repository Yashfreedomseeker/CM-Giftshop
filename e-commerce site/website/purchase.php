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
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>CM gifts | Purchases</title>
</head>
<body>
<div class="cart">
        <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
            <h1>My Orders</h1>
            <div class="search-icon-container">
                <i class="bi bi-search"></i>
            </div>
   
            <div class="search-bar-container">
                <input type="text" id="searchitem" name="search" class="form-control" placeholder="Search">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">User Id - </label>
            <span id="profile-id"><?php echo $_SESSION['user_id'] ?? 'N/A'; ?></span>
        </div>

        <!-- Orders table -->
        <div class = "table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Order Id</th>
                        <th>Order Date</th>
                        <th>Order Status</th>
                        <th>Order Price</th>
                        <th>product Name</th>
                        <th>product Image</th>
                        <th>Unit Price</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <!-- <th>Action</th> -->
                        <!-- make this a verticle table -->
                    </tr>
                </thead>
                <tbody id="orderTable">
                    <!-- Users will be loaded here via AJAX -->
                </tbody>
            </table>
        </div>
        <script src="fetch_orders.js"></script>
</body>
</html>

