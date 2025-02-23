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

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    $isLoggedIn = true;  // User is logged in
} else {
    $isLoggedIn = false;  // User is not logged in
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
    <title>CM gifts | Home</title>

</head>
<header>
    <body>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <!-- container fluid spans to all the columns -->
                <div class= logoContainer>
                    <a class= "logo-link" href="index.php"><img src="../images/logo.png" alt="logo" class= "logo"></a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="collapsibleNavbar">
                    <button id="darkModeToggle" class="btn btn-outline-dark">Dark</button>
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" aria-current="page" href="index.php">Home</a></li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown" data-bs-toggle="dropdown">Products
                                <ul class="dropdown-menu">
                                    <a class="dropdown-item" href="#Homedecor">Home & Lifestyle</a>
                                    <a class="dropdown-item" href="#Stationery">Stationary & Crafts</a>
                                    <a class="dropdown-item" href="#ClothingAccessories">Fashion & Accessories</a>
                                    <a class="dropdown-item" href="#Keepsakes">Keepsakes & Gifts</a>
                                    <a class="dropdown-item" href="#Festive">Festive & Seasonal</a>
                                </ul>
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#about">About us</a></li>
                        <li class="nav-item"><a class="nav-link" href="#contact">Contact us</a></li>
                    </ul>   
                    <div class="icon-group ms-auto d-flex">
                        <!-- <div class="d-flex justify-content-between align-items-center mb-3 search-wrapper">
                               
                            <div class="search-icon-container">
                                <i class="bi bi-search"></i>
                            </div>

                            <div class="search-bar-container">
                                <input type="text" class="form-control" placeholder="Search">
                            </div>
                        </div> -->
                        <a href="#" id="cartButton" class="btn btn-outline-light d-flex align-items-center justify-content-center"><i class="fa-solid fa-cart-shopping"></i></a>
                        <a href = "#" id ="orderButton" class="btn btn-outline-light me-2 d-flex align-items-center justify-content-center"><i class="fa-solid fa-dollar-sign"></i></a>
                        
                        <!-- Login -->
                        <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#loginModal"><i class="fas fa-user"></i> Login</button>

                        <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Login</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action ="log.php" method = "POST">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="username" name="username" placeholder="User Id">
                                            </div>
                                            <div class="mb-3">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" name="forgot-password" class="btn btn-secondary">Forgot Password?</button>
                                                <button type="submit" name="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Submit</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Sign Up -->
                        <?php if (!$isLoggedIn): ?>
                            <button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#signupModal">
                                <i class="fas fa-user-plus"></i> Sign Up
                            </button>
                        <?php else: ?>
                            <button type="button" class="btn btn-outline-light me-2" disabled>
                                <i class="fas fa-user-plus"></i> Already Signed Up
                            </button>
                        <?php endif; ?>

                        <div class="modal fade" id="signupModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Sign Up</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action = "signup.php" method = "POST">
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="name" name="name" placeholder="Name">
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No.">
                                            </div>
                                            <div class="mb-3">
                                                <input type="text" class="form-control" id="address" name="address" placeholder="Address">
                                            </div>
                                            <div class="mb-3">
                                                <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                                            </div>
                                            <div class="mb-3">
                                                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" name="submit" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#loginModal">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="profile"><button type="button" class="btn btn-outline-light me-2" data-bs-toggle="modal" data-bs-target="#profileModal"><i class="fa-solid fa-circle-user fs-3"></i></button></div>

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
                                            <span id="profile-id"><?php echo $_SESSION['user_id'] ?? 'N/A'; ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Name:</label>
                                            <span id="profile-name"><?php echo $_SESSION['user_name'] ?? 'N/A'; ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Address:</label>
                                            <span id="profile-address"><?php echo $_SESSION['user_address'] ?? 'N/A'; ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Phone:</label>
                                            <span id="profile-phone"><?php echo $_SESSION['user_phone'] ?? 'N/A'; ?></span>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email:</label>
                                            <span id="profile-email"><?php echo $_SESSION['user_email'] ?? 'N/A'; ?></span>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="logout.php" class="btn btn-danger">Log Out</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- carousal -->
        <div id="carouselExample"  class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item">
                    <img src="../images/homedecor.jpg" class="d-block w-100" alt="the image">
                    <!-- d-block: display block -->
                    <div class="carousel-caption">
                        <h1>Home & Lifestyle</h1>
                        <p>Printed Mugs, wall tiles, cushions and pillows, photo frames, LED lamps</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../images/stationary.jpg" class="d-block w-100" alt="the image">
                    <div class="carousel-caption">
                        <h1>Stationery & Crafts</h1>
                        <p>Notebooks, Planners and journals, Pens and pencils</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../images/accessories.jpg" class="d-block w-100" alt="the image">
                    <div class="carousel-caption">
                        <h1>Fashion & Accessories</h1>
                        <p>T-Shirts and Hoodies</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../images/keepsakes.jpg" class="d-block w-100" alt="the image">
                    <div class="carousel-caption">
                        <h1>Keepsakes & Gifts</h1>
                        <p>Handmade cards, Giftboxes, Photo albums</p>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="../images/arts&craft.jpg" class="d-block w-100" alt="the image">
                    <div class="carousel-caption">
                        <h1>Festive & seasonal</h1>
                        <p>Pencil arts, Digital printed gifts</p>
                    </div>
                </div>

                <div class="carousel-item active">
                    <img src="https://imgcdn.stablediffusionweb.com/2024/10/26/da1bd332-38fa-4e82-a614-deab61758962.jpg" class="d-block w-100 snow-wrap" alt="the image">
                    <div class="carousel-caption cd">
                        <div class="snow"></div>
                        <h1 class="cbonus">Ho Ho Ho! <br>Christmas deal!</h1>
                        <p>2 candles + Christmas card + 5 Ornaments</p>
                        <h2>25% off</h2>
                        <h3>Just for Rs 800/=</3>
                        <h2>Grab your deal right away!</h2>
                    </div>
                </div>

                <div class="carousel-item">
                    <img src="https://static.vecteezy.com/system/resources/previews/035/614/114/non_2x/ai-generated-color-balloons-an-gift-boxes-for-birthday-decoration-ai-generated-free-photo.jpg" class="d-block w-100" alt="the image">
                    <div class="carousel-caption hb">
                        <h1 class="bdeal" style="font-family:freestyle script; font-size:65px; font-weight:bold;">Happy Birthday deal!</h1>
                        <div class="bdeal">
                            <h4>Birthday card + Pencil art = Rs 1000/-</h4>
                            <h4>Birthday card + printed Mug = Rs 800/-</h4>
                            <h4>Photo album + Custom Ring  = Rs 1200/-</n4>
                        </div>
                        <h3>20% off</h3>
                        <h2>Surprise your loved ones!</h2>
    
                            <!-- <div class= "col-md-4">
                                <img src = "https://www.fabulousflowers.co.za/cdn/shop/files/BirthdaySurpriseGiftBox-FabulousFlowers_Gifts2.jpg?v=1729500477&width=800" alt = "the image" width="200rem" height ="200rem">
                            </div> -->
                    </div>
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

<!-- Deals section -->
        <!-- <div class = "row">
            <div class = "col-md-3 deal christmasD">
                <div class = "dealText">
                    <h2>Chrismas Bonus</h2>
                    <p>2 candles + Christmas card + 5 Ornaments</p>
                    <p>Just for Rs 800/=</p>
                    <h4>Grab your deal right away!</h4>
                </div>
            </div>
            <div class = "col-md-3 deal birthdayD">
                <table class= "birthdayDeal" border ="0" cellspacing = "0" cellpadding ="3">
                    <tr>
                        <td>
                            <h2>Happy Birthday</h2>
                            <p>Birthday card + Pencil art = Rs 1000/-</p>
                            <p>Birthday card + printed Mug = Rs 800/-</p>
                            <p>Photo album + Custom Ring  = Rs 1200/-</p>
                            <h4>Surprise your loved ones!</h4>
                        </td>
                        <td><img src = "https://www.fabulousflowers.co.za/cdn/shop/files/BirthdaySurpriseGiftBox-FabulousFlowers_Gifts2.jpg?v=1729500477&width=800" alt = "the image" width="200rem" height ="200rem"></td>
                    </tr>
                </table>
            </div>    
        </div> -->
<!-- home decor section -->
        <section id="Homedecor">
            <div class="containersec">
                <center><h2 class="categorytitle">Home & Lifestyle</h2></center>
                <div id="carouselcardHomedecor" class="carousel">
                    <div class="carousel-track cc carousel-track-hd" id = "HL">
                        <!-- Product cards will be inserted here dynamically -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselcardHomedecor" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselcardHomedecor" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

<!-- stationery section -->
        <section id = "Stationery">
            <div class = "containersec">
                <center><h2 class="categorytitle">Stationery & Crafts</h2></center>
                <div id="carouselcardStationery" class="carousel">
                    <div class="carousel-track cc carousel-track-s" id="SC">
                        <!-- products will apppear here through AJAX -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselcardStationery" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselcardStationery" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

<!-- Clothing & Accessories -->
        <section id = "ClothingAccessories">
            <div class = "containersec">
                <center><h2 class="categorytitle">Fashion & Accessories</h2></center>
                <div id="carouselcardClothAcc" class="carousel">
                    <div class="carousel-track cc carousel-track-ca" id = "FA">
                        <!-- products will apppear here through AJAX -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselcardClothAcc" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselcardClothAcc" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

<!-- keepsakes & gifts -->
        <section id = "Keepsakes">
            <div class = "containersec">
                <center><h2 class="categorytitle">Keepsakes & Gifts</h2></center>
                <div id="carouselcardKeepsake" class="carousel">
                    <div class="carousel-track cc carousel-track-k" id ="KG">
                        <!-- products will apppear here through AJAX -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselcardKeepsake" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselcardKeepsake" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
<!-- Festive & Seasonal -->
        <section id = "Festive">
            <div class = "containersec">
                <center><h2 class="categorytitle">Festive & Seasonal</h2></center>
                <div id="carouselcardFestive" class="carousel">
                    <div class="carousel-track cc carousel-track-f" id="FS">
                        <!-- <div class="carousel-item active cit cit-f"> -->
                        <!-- products will apppear here through AJAX -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselcardFestive" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselcardFestive" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>

        <!-- About Us page-->
        <section id = "about">
            <div id = "aboutus">
                <h1>About us</h1>
                <p>CM giftshop is your beloved partner when you need special custom made items for your loved one's special day. Surprise them and bring a smile to their face! We are there for you whenever you need us.</p>
            </div>
        </section>

        <!-- Contact Us page -->
        <section id="contact">
            <div class="contactus col-md-5">
                <h1>Contact Us</h1>
                <hr>
                <form action="contact.php" method="POST">
                    <input type="text" name="name" class="form-control fc" placeholder="Name" required>
                    <input type="email" name="email" class="form-control fc" placeholder="Email" required>
                    <input type="text" name="mobile" class="form-control fc" placeholder="Mobile Number" required>
                    <textarea name="message" class="form-control fc" placeholder="Enter your message here" required></textarea>
                    <button type="submit" name="submit" class="btn btn-success fc form-control">Submit</button>
                </form>
            </div>
        </section>
        
    </body>
<script>
        document.addEventListener("DOMContentLoaded", function () {
            let orderMessage = sessionStorage.getItem("orderMessage");
            let orderMessageType = sessionStorage.getItem("orderMessageType");

            if (orderMessage) {
                showMessageModal(orderMessage, orderMessageType || "info"); // Default to "info" if type is missing
                sessionStorage.removeItem("orderMessage");
                sessionStorage.removeItem("orderMessageType");
            }
        });
    //cart direct to login if no session is there
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("cartButton").addEventListener("click", function (event) {
            event.preventDefault();
            <?php if (!isset($_SESSION['user_id'])): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            <?php else: ?>
                window.location.href = "cart.php";
            <?php endif; ?>
        });
    });
    //orders direct to login if there is no session found
    document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("orderButton").addEventListener("click", function (event) {
            event.preventDefault();
            <?php if (!isset($_SESSION['user_id'])): ?>
                var loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
                loginModal.show();
            <?php else: ?>
                window.location.href = "purchase.php";
            <?php endif; ?>
        });
    });

//dark mode
        document.getElementById('darkModeToggle').addEventListener('click', function () {
            // Toggle the 'dark-mode' class on the body
            document.body.classList.toggle('dark-mode');

            // Update the toggle button text
            if (document.body.classList.contains('dark-mode')) {
                this.textContent = 'Light';
            } else {
                this.textContent = 'Dark';
            }
        });

// carousel controls in item carousels
        const carousels = [
            { id: '#carouselcardHomedecor', track: '.carousel-track-hd', item: '.cit-hd' },
            { id: '#carouselcardStationery', track: '.carousel-track-s', item: '.cit-s' },
            { id: '#carouselcardClothAcc', track: '.carousel-track-ca', item: '.cit-ca' },
            { id: '#carouselcardKeepsake', track: '.carousel-track-k', item: '.cit-k' },
            { id: '#carouselcardFestive', track: '.carousel-track-f', item: '.cit-f' }
        ];

        if (window.matchMedia("(min-width: 601px)").matches) {
            carousels.forEach(carouselData => {
                const carouselElement = document.querySelector(carouselData.id);
                if (!carouselElement) return;

                // Initialize Bootstrap carousel
                const carouselInstance = new bootstrap.Carousel(carouselElement, {
                    interval: false
                });

                // Calculate width and scroll behavior
                const carouselTrack = document.querySelector(carouselData.track);
                const cardWidth = document.querySelector(carouselData.item)?.offsetWidth || 0;
                let scrollPosition = 0;

                const carouselWidth = carouselTrack.scrollWidth;

                // Handle next button
                carouselElement.querySelector('.carousel-control-next')?.addEventListener('click', function () {
                    if (scrollPosition < (carouselWidth - (cardWidth * 4))) {
                        scrollPosition += cardWidth;
                        carouselTrack.scrollTo({ left: scrollPosition, behavior: 'smooth' });
                    }
                });

                // Handle previous button
                carouselElement.querySelector('.carousel-control-prev')?.addEventListener('click', function () {
                    if (scrollPosition > 0) {
                        scrollPosition -= cardWidth;
                        carouselTrack.scrollTo({ left: scrollPosition, behavior: 'smooth' });
                    }
                });
            });
        }else { // For screen sizes < 601px
            carousels.forEach(carouselData => {
                const carouselElement = document.querySelector(carouselData.id);
                if (!carouselElement) return;

                // Add the "slide" class for smooth sliding behavior
                carouselElement.classList.add('slide');

                // Initialize Bootstrap carousel
                const carouselInstance = new bootstrap.Carousel(carouselElement, {
                    interval: true // Optional: set interval for automatic sliding
                });
            });
        }
</script>
<script src = "display_cards.js"></script>
<script src = "./modal.js"></script>
</header>
<footer class="footer bg-dark text-white py-4">
    <div class="container-fluid">
        <div class="row">
            <!-- First Column -->
            <div class="col-md-3">
                <div class= logoContainer>
                    <a href="index.php"><img src="../images/logo.png" alt="logo" class= "logo"></a>
                </div>
            </div>
            <!-- second column -->
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="#about"><i class="bi bi-telephone-fill"></i></a> +94 713459900</li>
                    <li><a href="#about"><i class="bi bi-geo-alt-fill"></i></a> No. 34, Palm street avenue, battaramulla</li>
                    <li><a href="#about"><i class="bi bi-envelope-at"></i></a> wasanayasho@gmail.com</li>
                </ul>
            </div>
            <!-- Third Column -->
            <div class="col-md-3">
                <ul class="list-unstyled">
                    <li><a href="index.php" class="text-white pb-lg-4">Home</a></li>
                    <li><a href="index.php" class="text-white pb-lg-4">Products</a></li>
                    <li><a href="#about" class="text-white pb-lg-4">About</a></li>
                    <li><a href="#" class="text-white pb-lg-4">Privacy Policy</a></li>
                    <li><a href="#" class="text-white pb-lg-4">Terms of Services</a></li>
                    <li><a href="#contact" class="text-white pb-lg-4">Contact</a></li>
                </ul>
            </div>
            <!-- Fourth column -->
            <div class="col-md-2">
                <ul class="list-unstyled">
                    <li><h2><a href = "https://www.facebook.com/profile.php?id=100085798156000&mibextid=ZbWKwL"><i class="bi bi-facebook"></i></a></h2></li>
                    <li><h2><a href = "https://wa.me/message/C5ZPYIAVU5TYK1"><i class="bi bi-whatsapp"></i></a></h2></li>
                    <li><h2><a href = "https://youtube.com/@CmgrowBags"><i class="bi bi-instagram"></i></a></h2></li>
                </ul>
            </div>
        </div>
        <!-- Footer Bottom -->
        <div class="row">
            <div class="col-12">
                <p class="mb-0 text-center">&copy; 2024 RAY LTD. All Rights Reserved.</p>
            </div>
        </div>
    </div>
</footer>

</html>