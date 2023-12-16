<?php
include 'database/DB_config.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$fetch_user = null;

if ($user_id) {
    $select_user = mysqli_query($conn, "SELECT * FROM `user_info` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

    if (mysqli_num_rows($select_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($select_user);

        // Extract the first name
        $fullName = $fetch_user['name'];
        $nameParts = explode(" ", $fullName);
        $firstName = $nameParts[0];
        $_SESSION['user_name'] = $firstName;
    }
}

if (isset($_GET['logout'])) {
    unset($user_id);
    session_destroy();
    header('location:index.php');
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBazar | Home</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>

    <!-- navbar -->
    <nav class="px-5 navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">
            <img src="images/logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="Phone.php">Phone</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="Watch.php">Watch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="Fashion.php">Fashion</a>
                </li>
            </ul>
            <ul class="navbar-nav d-flex flex-row me-1">
                <?php if (!isset($_SESSION['user_id'])) { ?>
                    <li class="nav-item me-3 me-lg-0">
                        <a class="btn btn-primary" href="login.php">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </a>
                    </li>
                <?php } else { ?>
                    <li class="nav-item me-3" style="margin-top:5px;">
                        <a class="btn btn-primary" href="./cart.php">
                            <i class="fas fa-shopping-cart"></i>&nbsp; Cart
                        </a>
                    </li>
                    <li class="nav-item dropdown me-lg-0 btn d-flex mt-1" style="height:40px;">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="far fa-circle-user" style="font-size:24px;"></i>
                            <span style="font-weight: 400;margin-left:5px; margin-right: 10px; font-size:20px;text-transform: capitalize; "><?php echo $_SESSION['user_name']; ?></span>
                        </a>
                        <!-- Dropdown menu with logout option -->
                        <ul class="dropdown-menu text-uppercase" aria-labelledby=" userDropdown">
                            <li style="text-transform: capitalize;"><a class="dropdown-item" href="index.php?logout=<?php echo $user_id; ?>" onclick="return confirm('are your sure you want to logout?');" class="delete-btn">logout</a></li>
                        </ul>
                    </li>
                <?php } ?>
            </ul>

        </div>
    </nav>

    <!-- Carousel -->
    <div id="carouselBasicExample" class="carousel slide carousel-fade" data-mdb-ride="carousel" data-mdb-carousel-init>
        <div class="carousel-indicators">
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide-to="2" aria-label="Slide 3"></button>
        </div>

        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="images/banner/1.png" class="d-block w-100" style="height: 650px;" alt="Sunset Over the City" />
            </div>

            <div class="carousel-item">
                <img src="images/banner/2.png" class="d-block w-100" style="height: 650px;" alt="Canyon at Nigh" />
            </div>

            <div class="carousel-item">
                <img src="images/banner/3.png" class="d-block w-100" style="height: 650px;" alt="Cliff Above a Stormy Sea" />
            </div>
        </div>

        <button class="carousel-control-prev" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-mdb-target="#carouselBasicExample" data-mdb-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
    <!-- Carousel -->

    <!-- "Latest Phones & Smart Watch's Deals" -->
    <div class="container mt-4 latest-deal">
        <h2 style="font-size:30px; color:black; text-align:center;">"Latest Phones & Smart Watch's Deals"</h2>
        <div class="row mt-3">
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/phone/realme_narzo_n53.jpg" style=" height:200px; " class="card-img-top w-50 mt-3 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%;margin:auto;">
                        <p class="fs-5">realme narzo N53</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 8,999</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 10,999</strike></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/watches/Noise.jpg" style=" height:200px; " class="card-img-top w-50 mt-3 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Noise Quad Call</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 1,399</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 5,999</strike></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/phone/iphone15.jpg" class="card-img-top w-50 mt-3 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Apple iPhone 15</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 79,900</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/watches/fastrack_ultaVu_HD.jpg" class="card-img-top mt-3 m-auto" style="width: 200px;" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Fastrack UltraVU HD</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 1,699</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 3,995</strike></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- upcoming products -->
    <div class="container mt-3">
        <h2 style="font-size:30px; color:black; text-align:center;">upcoming products</h2>
        <div class="row mt-3 g-4">
            <div class="col">
                <div class="card" style="max-width: 400px; height:350px;">
                    <div style="display:flex; flex-wrap:wrap;">
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/1.jpg" alt="">
                            <p class="pt-3 m-0">Cushion covers & more</p>
                        </div>
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/2.jpg" alt="">
                            <p class="pt-3 m-0">Home decoration</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/3.jpg" alt="">
                            <p class="pt-3 m-0">Home storage</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/4.jpg" alt="">
                            <p class="pt-3 m-0">Lighting solutions</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="max-width: 400px; height:350px;">
                    <div style="display:flex; flex-wrap:wrap;">
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/5.jpg" alt="">
                            <p class="pt-3 m-0">Air conditioner</p>
                        </div>
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/6.jpg" alt="">
                            <p class="pt-3 m-0">Refrigerators</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/7.jpg" alt="">
                            <p class="pt-3 m-0">Microwaves</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/8.jpg" alt="">
                            <p class="pt-3 m-0">Washing machines</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card" style="max-width: 400px; height:350px;">
                    <div style="display:flex; flex-wrap:wrap;">
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/9.jpg" alt="">
                            <p class="pt-3 m-0">Racks & holders</p>
                        </div>
                        <div style="width:200px;padding:8px 8px 0 8px;">
                            <img src="images/upcoming/10.jpg" alt="">
                            <p class="pt-3 m-0">Storage containers</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/11.jpg" alt="">
                            <p class="pt-3 m-0">Cookwares</p>
                        </div>
                        <div style="width:200px;padding:8px;">
                            <img src="images/upcoming/12.jpg" alt="">
                            <p class="pt-3 m-0">Water bottles</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Elevate Your Style -->
    <div class="container latest-deal mt-4">
        <h2 style="font-size:30px; color:black; text-align:center;">"Elevate Your Style"</h2>
        <div class="row mt-4">
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/fashion/womens/tshirt.jpg" style=" height:200px; " class="card-img-top w-50 mt-3 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%;margin:auto;">
                        <p class="fs-5">Women T-Shirt</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 389</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 1,299</strike></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/fashion/mens/jacket.jpg" style=" height:200px; " class="card-img-top w-50 mt-3 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Mens Jackets </p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 799</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 3,999</strike></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/fashion/womens/saree.jpg" class="card-img-top mt-3 m-auto" style="width: 135px;" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Women's Saree</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 499</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 3,499</strike></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card" style="width: 300px; height:330px;">
                    <img src="images/products/fashion/mens/kurta.jpg" class="card-img-top w-75 m-auto" alt="echobazar product" />
                    <div class="card-body" style="width: 80%; margin:auto;">
                        <p class="fs-5">Kurta set</p>
                        <div style="margin-top:-10px;">
                            <span class="fs-5"> ₹ 529</span>
                            <span class="card-text"> M.R.P.:<strike>₹ 1,999</strike></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribe Section -->
    <div class="newsletter-subscribe w-50 mx-auto">
        <div class="intro">
            <h2 class="text-center">Subscribe for the Latest Updates!</h2>
            <p class="text-center">Stay in the loop with EchoBazar. Subscribe to our newsletter for exciting news on the
                latest phones, watches, and clothing.</p>
        </div>
        <form class="form-inline d-flex gap-4" method="post">
            <input class="form-control w-75" type="email" name="email" placeholder="Your Email">
            <button class="btn btn-primary w-20" type="submit">Subscribe</button>
        </form>
    </div>


    <!-- Footer -->
    <footer>
        <hr>
        <div class="container py-4">
            <div class="row">

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <img src="images/logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
                    <p style="text-align: justify;">Discover a world of endless possibilities at EchoBazar. We bring you
                        a curated collection of the latest trends, must-have essentials, and exclusive deals. </p>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Shop Categories</h5>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!" class="text-dark">Electronics</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Clothing</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Home & Living</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Toys & Games</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Books</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Useful Links</h5>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!" class="text-dark">FAQ</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Shipping Information</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Return Policy</a>
                        </li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <h5 class="text-uppercase">Connect With Us</h5>
                    <ul class="list-unstyled mb-0">
                        <li>
                            <a href="#!" class="text-dark">Contact Us</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Follow us on Twitter</a>
                        </li>
                        <li>
                            <a href="#!" class="text-dark">Like us on Facebook</a>
                        </li>
                    </ul>
                </div>

            </div>
        </div>

        <div class="text-center">
            &copy; 2023 EchoBazar. All rights reserved. | Terms of Service | Privacy Policy |
        </div>

    </footer>
    <!-- Footer -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
</body>

</html>