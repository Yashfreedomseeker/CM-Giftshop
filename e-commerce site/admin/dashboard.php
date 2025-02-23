<?php
session_name("admin_session");
session_start();

echo "<script>console.log('SESSION user_id: " . (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : "NOT SET") . "');</script>";
echo "<script>console.log('COOKIE session_expire: " . (isset($_COOKIE['session_expire']) ? $_COOKIE['session_expire'] : "NOT SET") . "');</script>";

// Check if session exists
if (!isset($_SESSION['user_id'])) {
    echo "<script>console.log('SESSION user_id NOT SET! Redirecting...');</script>";
    session_destroy();
    header("Location: login.php");
    exit();
}

if (!isset($_COOKIE['session_expire'])) {
    echo "<script>console.log('COOKIE session_expire NOT SET! Redirecting...');</script>";
    session_destroy();
    header("Location: login.php");
    exit();
}

// Debug: Show timestamps
$currentTime = time();
$cookieTime = $_COOKIE['session_expire'];
echo "<script>console.log('Current Time: " . $currentTime . "');</script>";
echo "<script>console.log('Cookie Expiration Time: " . $cookieTime . "');</script>";

// Check if the session time has expired
if ($currentTime > $cookieTime) {
    echo "<script>console.log('Session EXPIRED! Redirecting...');</script>";
    setcookie("session_expire", "", [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => false,
        'httponly' => true
    ]);
    
    session_destroy();
    header("Location: login.php");
    exit();
}

// Refresh session expiration (Extend by 30 minutes)
$newExpiryTime = time() + 1800; // 30 minutes
setcookie("session_expire", $newExpiryTime, [
    'expires' => $newExpiryTime,
    'path' => '/',
    'secure' => false,
    'httponly' => true
]);

echo "<script>console.log('Session is valid. Expiry time extended to: " . $newExpiryTime . "');</script>";
require 'dbcon.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="adstyle.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <!-- <link rel="stylesheet" href="css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <title>CM | Admin</title>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class = bg-white id="sidebar-wrapper">
            <!-- sidebar -->
            <div class ="border-bottom">
                <img src="../images/logo.png" alt="logo" class= "logo" width="180px" height="240px">
            </div>
            <div class="list-group list-group-flush my-3">
                <a href = "#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" data-target="users">
                    <i class="fas fa-user-plus"></i> Users
                </a>
                <a href = "#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" data-target="customers">
                    <i class="fa-solid fa-bag-shopping"></i> Customers
                </a>
                <a href = "#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" data-target="products">
                    <i class="fa-solid fa-gift"></i> Products
                </a>
                <a href = "#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" data-target="transactions">
                    <i class="fa-solid fa-hand-holding-dollar"></i> Trasactions
                </a>
                <a href = "#" class="list-group-item list-group-item-action bg-transparent second-text fw-bold" data-target="payment">
                    <i class="fa-solid fa-dollar-sign"></i> Payment
                </a>
            </div>
        </div>
        <!-- navbar -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light bg-transparent py-4 px-4">
                <div class="d-flex align-items-center">
                    <i class="fas fa-align-left primary-text fs-4 me-3" id="menu-toggle"></i>
                    <h2 class="fs-2 m-0">Dashboard</h2>
                </div>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-targer="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-label="Toggle navigation">
                    <span class= "navbar-toggler-icon"></span>
                </button>

                <div class ="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class ="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item dropdown mx-4">
                            <a href="#" class="nav-link dropdown second-text fw-bold" id="navbarDropdown" data-bs-toggle="dropdown">
                                <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa-solid fa-circle-user fs-3"></i></button>

                                <!-- Profile Modal -->
                                <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h1 class="modal-title fs-5" id="exampleModalLabel">My Profile</h1>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label class="form-label">Id:</label>
                                                    <span id="profile-name"><?php echo $_SESSION['user_id'] ?? 'N/A'; ?></span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Name:</label>
                                                    <span id="profile-name"><?php echo $_SESSION['user_name'] ?? 'N/A'; ?></span>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Phone:</label>
                                                    <span id="profile-phone"><?php echo $_SESSION['user_phone'] ?? 'N/A'; ?></span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <a href="logout.php" class="btn btn-danger">Log Out</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>                        
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid px-4">
                <!-- admin user section -->
                <div id="users" class="page active">
                    <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                        <h3 class="mb-0">Admin Details</h3>
                        
                        <!-- Search Icon -->
                        <div class="search-icon-container">
                            <i class="bi bi-search"></i>
                        </div>

                        <!-- Search Bar (Initially Hidden) -->
                        <div class="search-bar-container">
                            <input type="text" id="searchInput" name="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class = "table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="adminTable">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- customer section -->
                <div id="customers" class="page"> 
                    <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                        <h3 class="mb-0">Customer Details</h3>
                        
                        <!-- Search Icon -->
                        <div class="search-icon-container">
                            <i class="bi bi-search"></i>
                        </div>

                        <!-- Search Bar (Initially Hidden) -->
                        <div class="search-bar-container">
                            <input type="text" id="searchcus" name="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Address</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="userTable">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
                <div id="products" class="page">
                    <h3 class="mb-0">Add a Product</h3>
                    <div class="container container-product mt-4">
                        <form action ="insert_product.php" method = "POST" id="productForm" enctype="multipart/form-data">
                            <div class="mb-3">
                                <input type="text" class="form-control" id="productID" name="id" placeholder= "Product ID"required>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="productName" name="name" placeholder= "Product Name"required>
                            </div>
                            <div class="mb-3">
                                <textarea class="form-control" id="description" name="description" placeholder= "Product Description" required></textarea>
                            </div>
                            <div class="mb-3">
                                <select class="form-select" id="productCategory" name="category" required>
                                    <option selected disabled>Select the Category</option>
                                    <option value="HL">Home & Lifestyle</option>
                                    <option value="SC">Stationery & Crafts</option>
                                    <option value="FA">Fashion & Accessories</option>
                                    <option value="KG">Keepsakes & Gifts</option>
                                    <option value="FS">Festive & Seasonal</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <input type="text" class="form-control" id="price" name="price" placeholder= "Unit Price" required>
                            </div>
                            <div class="mb-3">
                                <input type="number" class="form-control" id="stock" name="stock" placeholder= "Stock Amount" required>
                            </div>
                            <div class="mb-3">
                                <label for="productImage" class="form-label">Product Image</label>
                                <input type="file" class="form-control" name="image" id="productImage" accept="image/*" required>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Add Product</button>
                        </form>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                        <h3 class="mb-0">Product Details</h3>
                            
                        <!-- Search Icon -->
                        <div class="search-icon-container">
                            <i class="bi bi-search"></i>
                        </div>

                        <!-- Search Bar (Initially Hidden) -->
                        <div class="search-bar-container">
                            <input type="text" id="searchProduct" name="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Product ID</th>
                                    <th>Category</th>
                                    <th>Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Stock</th>
                                    <th>Image</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="productTable">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div> 
                </div>

                <div id="transactions" class="page">
                    <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                        <h3 class="mb-0">Order Details</h3>
                            
                        <!-- Search Icon -->
                        <div class="search-icon-container">
                            <i class="bi bi-search"></i>
                        </div>

                        <!-- Search Bar (Initially Hidden) -->
                        <div class="search-bar-container">
                            <input type="text" id="searchOrder" name="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>User Id</th>
                                    <th>Order Id</th>
                                    <th>Order Date</th>
                                    <th>Order Status</th>
                                    <th>Order Price</th>
                                    <th>product Name</th>
                                    <th>Unit Price</th>
                                    <th>Quantity</th>
                                    <th>Total Amount</th>
                                </tr>
                            </thead>
                            <tbody id="orderviewTable">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="payment" class="page">
                    <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                        <h3 class="mb-0">Payment Details</h3>
                            
                        <!-- Search Icon -->
                        <div class="search-icon-container">
                            <i class="bi bi-search"></i>
                        </div>

                        <!-- Search Bar (Initially Hidden) -->
                        <div class="search-bar-container">
                            <input type="text" id="searchPayment" name="search" class="form-control" placeholder="Search">
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Payment ID</th>
                                    <th>Order Id</th>
                                    <th>Payment date</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody id="paymentviewTable">
                                <!-- Users will be loaded here via AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
    </div>
    <!-- <script src="js/bootstrap.min.js"></script>-->
    <script src ="pages.js"></script>
    <script src ="fetch_users.js"></script>
    <script src ="fetch_admin.js"></script>
    <script src ="fetch_product.js"></script>
    <script src ="fetch_order_admin.js"></script>
    <script src ="fetch_payment.js"></script>
</body>
</html>


