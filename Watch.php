<?php
include 'database/DB_config.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$fetch_user = null;

if ($user_id) {
    $select_user = mysqli_query($conn, "SELECT * FROM `user_info` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

    if (mysqli_num_rows($select_user) > 0) {
        $fetch_user = mysqli_fetch_assoc($select_user);

        $fullName = $fetch_user['name'];
        $nameParts = explode(" ", $fullName);
        $firstName = $nameParts[0];
        $_SESSION['user_name'] = $firstName;
    }
}

if (isset($_POST['add_to_cart'])) {
    $user_id = $_SESSION['user_id'];
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $quantity = mysqli_real_escape_string($conn, $_POST['quantity']);
    $order_date = date("Y-m-d H:i:s"); // Current date and time

    // Check if the product already exists in the user's orders
    $check_query = "SELECT * FROM `orders` WHERE user_id = '$user_id' AND product_id = '$product_id'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) == 0) {
        // Product doesn't exist, proceed with adding to the cart
        $insert_query = "INSERT INTO `orders` (user_id, product_id, product_name, price, quantity, order_date) 
                         VALUES ('$user_id', '$product_id', '$product_name', '$price', '$quantity', '$order_date')";

        $result = mysqli_query($conn, $insert_query);

        // Redirect with success parameter
        echo '<script>alert("Product added to cart successfully!");</script>';
        header('Location: ' . $_SERVER['PHP_SELF'] . '?success=1');
        exit();
    } else {
        // Product already exists in the cart
        echo '<script>alert("Product already exists in the cart.");</script>';
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
    <title>EchoBazar | Watch</title>
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

    <img src="images/banner/4.jpg" class="w-100" alt="">

    <!-- Men's Watches -->
    <div class="container">
        <h1 class="text-center m-3 text-black">Men's Watches</h1>
        <div class="products">
            <div class="row justify-content-center">
                <?php
                $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE category = 'men_watch'") or die('query failed');
                if (mysqli_num_rows($select_product) > 0) {
                    while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                        $price = $fetch_product['price'];
                        $old_price = $fetch_product['old_price'];
                        $productLink = $fetch_product['link'];
                ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card" style="width: 320px; height:450px;">
                                <div class="bg-image d-flex align-items-center justify-content-center mt-3 hover-zoom ripple ripple-surface ripple-surface-light position-relative">
                                    <img src="images/products/<?php echo $fetch_product['image']; ?>" class="card-img-top w-75" style=" height:250px; " alt="Product Image">
                                    <div class="hover-overlay">
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </div>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-2"><?php echo $fetch_product['name']; ?></h5>
                                    <div class="d-flex align-items-center my-2">
                                        <span class="price fs-4 fw-bold">₹ <?php echo $price; ?></span>
                                        <span class="ms-2 text-muted fs-6"><strike>M.R.P <?php echo $old_price; ?></strike></span>
                                    </div>
                                    <h5 class="fs-6">FREE Delivery by EchoBazaar</h5>
                                    <div class="d-flex justify-content-between">
                                        <a style="height: 36px;" href="/echobazar/pages/<?php echo $productLink; ?>" target="_blank" class="btn btn-primary">View Details</a>
                                        <form method="post" action="">
                                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" name="add_to_cart" class="btn btn-warning">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "No products found in the database.";
                }
                ?>
            </div>
        </div>
    </div>

    <div class="container d-flex">
        <img src="images/banner/10.png" class="w-50" alt="">
        <img src="images/banner/11.png" class="w-50" alt="">
    </div>
    <!-- Women's Watches -->
    <div class="container">
        <h1 class="text-center m-3 text-black">Women's Watches</h1>
        <div class="products">
            <div class="row justify-content-center">
                <?php
                $select_product = mysqli_query($conn, "SELECT * FROM `products` WHERE category = 'women_watch'") or die('query failed');
                if (mysqli_num_rows($select_product) > 0) {
                    while ($fetch_product = mysqli_fetch_assoc($select_product)) {
                        $price = $fetch_product['price'];
                        $old_price = $fetch_product['old_price'];
                        $productLink = $fetch_product['link'];
                ?>
                        <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                            <div class="card" style="width: 320px; height:450px;">
                                <div class="bg-image d-flex align-items-center justify-content-center mt-3 hover-zoom ripple ripple-surface ripple-surface-light position-relative">
                                    <img src="images/products/<?php echo $fetch_product['image']; ?>" class="card-img-top w-75" style=" height:250px; " alt="Product Image">
                                    <div class="hover-overlay">
                                        <div class="mask" style="background-color: rgba(251, 251, 251, 0.15);"></div>
                                    </div>
                                    </a>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title mb-2"><?php echo $fetch_product['name']; ?></h5>
                                    <div class="d-flex align-items-center my-2">
                                        <span class="price fs-4 fw-bold">₹ <?php echo $price; ?></span>
                                        <span class="ms-2 text-muted fs-6"><strike>M.R.P <?php echo $old_price; ?></strike></span>
                                    </div>
                                    <h5 class="fs-6">FREE Delivery by EchoBazaar</h5>
                                    <div class="d-flex justify-content-between">
                                        <a style="height: 36px;" href="/echobazar/pages/<?php echo $productLink; ?>" target="_blank" class="btn btn-primary">View Details</a>
                                        <form method="post" action="">
                                            <input type="hidden" name="product_id" value="<?php echo $fetch_product['id']; ?>">
                                            <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
                                            <input type="hidden" name="price" value="<?php echo $price; ?>">
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" name="add_to_cart" class="btn btn-warning">Add to Cart</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                <?php
                    }
                } else {
                    echo "No products found in the database.";
                }
                ?>
            </div>
        </div>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
</body>

</html>