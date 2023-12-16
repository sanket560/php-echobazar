<?php
include 'database/DB_config.php';

session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
$fetch_user = null;

if (!$user_id) {
    header('location:index.php');
    exit();
}

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

$select_cart = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

if (isset($_GET['remove'])) {
    $remove_id = $_GET['remove'];
    mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$remove_id'") or die('query failed');
    header('location:cart.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['remove_all'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id) {
        // Remove all items from the orders table for the user
        $delete_query = "DELETE FROM `orders` WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $delete_query);

        if ($result) {
            echo '<script>alert("All items removed successfully!");</script>';
        } else {
            echo '<script>alert("Failed to remove items.");</script>';
        }

        // Redirect to the cart page to avoid form resubmission
        header('Location: cart.php');
        exit();
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
    <title>EchoBazar | Cart</title>
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

    <section class="bg-light my-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-9">
                    <div class="card border shadow-0">
                        <div class="m-4">
                            <div class="d-flex justify-content-between">
                                <h4 class="card-title mb-4">Your shopping cart</h4>
                                <form method="post" action="">
                                    <button type="submit" name="remove_all" class="btn btn-danger">Remove All</button>
                                </form>
                            </div>

                            <?php $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die(mysqli_error($conn));
                            if (mysqli_num_rows($select_orders) > 0) {
                                while ($order = mysqli_fetch_assoc($select_orders)) {
                                    $product_id = $order['product_id'];
                                    $select_images = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$product_id'") or die(mysqli_error($conn));
                            ?>
                                    <div class="row mb-4 d-flex justify-content-between align-items-center ">
                                        <div class="col-md-2 col-lg-2 col-xl-2">
                                            <?php
                                            if ($image = mysqli_fetch_assoc($select_images)) {
                                                echo '<img src="images/products/' . $image['image'] . '" class="img-fluid rounded-3 w-75" alt="Product Image">';
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-3 ">
                                            <h6 class="text-black mb-0"><?php echo $order['product_name']; ?></h6>
                                        </div>
                                        <div class="col-md-3 col-lg-3 col-xl-1 ">
                                            <h6 class="text-black mb-0">Qty : <?php echo $order['quantity']; ?></h6>
                                        </div>
                                        <div class="col-md-3 col-lg-2 col-xl-2 offset-lg-1 ">
                                            <h6 class="text-black mb-0">₹ <?php echo $order['price']; ?></h6>
                                        </div>
                                        <div class="col-md-1 col-lg-1 col-xl-1 text-end">
                                            <a href="cart.php?remove=<?php echo $order['id']; ?>" class="delete-btn text-danger" onclick="return confirm('Remove item from cart?');"><i class="fas fa-trash-alt"></i></a>
                                        </div>
                                    </div>
                            <?php
                                }
                            } else {
                                echo "No orders found.";
                            }

                            ?>

                        </div>

                        <div class="border-top pt-4 mx-4 mb-4">
                            <p><i class="fas fa-truck text-muted fa-lg"></i>&nbsp; Free Delivery within 1-2 weeks</p>
                        </div>
                    </div>
                </div>

                <?php
                $select_orders = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id'") or die(mysqli_error($conn));

                $total = 0;
                $discount = 60.00;
                $tax = 14.00;

                while ($order = mysqli_fetch_assoc($select_orders)) {
                    $total += ($order['price'] * $order['quantity']);
                }

                $totalWithDiscount = $total - $discount;
                $totalWithTax = $totalWithDiscount + $tax;
                ?>

                <div class="col-lg-3">
                    <div class="card bg-primary text-white rounded-3">
                        <div class="card-body">
                            <div class="mb-4">
                                <h5 class="mb-0">Card details</h5>
                            </div>

                            <p class="small mb-2">Card type</p>
                            <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-mastercard fa-2x me-2"></i></a>
                            <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-visa fa-2x me-2"></i></a>
                            <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-amex fa-2x me-2"></i></a>
                            <a href="#!" type="submit" class="text-white"><i class="fab fa-cc-paypal fa-2x"></i></a>

                            <form class="mt-4">
                                <div class="form-outline form-white mb-4">
                                    <input type="text" id="typeName" class="form-control form-control-lg" siez="17" placeholder="Cardholder's Name" />
                                    <label class="form-label" for="typeName">Cardholder's Name</label>
                                </div>

                                <div class="form-outline form-white mb-4">
                                    <input type="text" id="typeText" class="form-control form-control-lg" siez="17" placeholder="1234 5678 9012 3457" minlength="19" maxlength="19" />
                                    <label class="form-label" for="typeText">Card Number</label>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-md-6">
                                        <div class="form-outline form-white">
                                            <input type="" id="typeExp" class="form-control form-control-lg" placeholder="MM/YYYY" size="7" id="exp" minlength="7" maxlength="7" />
                                            <label class="form-label" for="typeExp">Expiration</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-outline form-white">
                                            <input type="" id="typeText" class="form-control form-control-lg" placeholder="&#9679;&#9679;&#9679;" size="1" minlength="3" maxlength="3" />
                                            <label class="form-label" for="typeText">Cvv</label>
                                        </div>
                                    </div>
                                </div>

                            </form>

                            <hr class="my-4">

                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Subtotal</p>
                                <p class="mb-2">₹ <?php echo number_format($total, 2); ?></p>
                            </div>

                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Discount</p>
                                <p class="mb-2">₹ <?php echo number_format($discount, 2); ?></p>
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                                <p class="mb-2">Total(Incl. taxes)</p>
                                <p class="mb-2">₹ <?php echo number_format($tax, 2); ?></p>
                            </div>

                            <button type="button" class="btn btn-info btn-block btn-lg">
                                <div class="d-flex justify-content-between">
                                    <span>₹ <?php echo number_format($totalWithTax, 2); ?></span>
                                    <span>Checkout <i class="fas fa-long-arrow-alt-right ms-2"></i></span>
                                </div>
                            </button>

                        </div>
                    </div>

                    <!-- <div class="card mb-3 border shadow-0">
                        <div class="card-body">
                            <form>
                                <div class="form-group">
                                    <label class="form-label">Have coupon?</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control border" name="" placeholder="Coupon code" />
                                        <button class="btn btn-light border">Apply</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card shadow-0 border">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2">₹ <?php echo number_format($total, 2); ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Discount:</p>
                                <p class="mb-2 text-success">₹ <?php echo number_format($discount, 2); ?></p>
                            </div>
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">TAX:</p>
                                <p class="mb-2">₹ <?php echo number_format($tax, 2); ?></p>
                            </div>
                            <hr />
                            <div class="d-flex justify-content-between">
                                <p class="mb-2">Total price:</p>
                                <p class="mb-2 fw-bold">₹ <?php echo number_format($totalWithTax, 2); ?></p>
                            </div>

                            <div class="mt-3">
                                <a href="#" class="btn btn-success w-100 shadow-0 mb-2"> Make Purchase </a>
                                <a href="#" class="btn btn-light w-100 border mt-2"> Back to shop </a>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
        </div>
    </section>

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