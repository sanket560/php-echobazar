<?php
include '../database/DB_config.php';

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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

    if ($user_id) {
        // Get product information from the form
        $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
        $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
        $price = mysqli_real_escape_string($conn, $_POST['price']);
        $quantity = 1; // Default quantity
        
        // Insert the product into the orders table
        $insert_query = "INSERT INTO `orders` (user_id, product_id, product_name, price, quantity) 
                         VALUES ('$user_id', '$product_id', '$product_name', '$price', '$quantity')";
        
        $result = mysqli_query($conn, $insert_query);

        if ($result) {
            echo '<script>alert("Product added to cart successfully!");</script>';
        } else {
            echo '<script>alert("Failed to add product to cart.");</script>';
        }
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
    <title>Scott International Mens Cotton Blend High Neck Jacket</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/product_details.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.min.css" rel="stylesheet" />
</head>

<body>
    <!-- navbar -->
    <nav class="px-5 navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="../index.php">
            <img src="../images/logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav m-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../Phone.php">Phone</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../Watch.php">Watch</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../Fashion.php">Fashion</a>
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
                        <a class="btn btn-primary" href="../cart.php">
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

    <nav aria-label="..." style="width:83%; margin:10px auto; ">
        <ul class="pagination">
            <li class="page-item">
                <a class="page-link" href="../index.php">Home</a>
            </li>
            <li class="page-item">
                <a class="page-link">></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="../Fashion.php">Fashion</a>
            </li>
            <li class="page-item">
                <a class="page-link">></a>
            </li>
            <li class="page-item">
                <a class="page-link" href="../Fashion.php">Men</a>
            </li>
            <li class="page-item">
                <a class="page-link">></a>
            </li>
            <li class="page-item active" aria-current="page">
                <a class="page-link" href="#">Scott International Mens Cotton Blend High Neck Jacket <span class="visually-hidden">(current)</span></a>
            </li>
        </ul>
    </nav>

    <!-- product section -->
    <section class="product-container container mt-3">
        <!-- left side -->
        <div class="img-card">
            <img src="../images/product_details_image/mensjacket/1.jpg" alt="" id="featured-image">
            <!-- small img -->
            <div class="small-Card">
                <img src="../images/product_details_image/mensjacket/1.jpg" alt="" class="small-Img">
                <img src="../images/product_details_image/mensjacket/2.jpg" alt="" class="small-Img">
                <img src="../images/product_details_image/mensjacket/3.jpg" alt="" class="small-Img">
                <img src="../images/product_details_image/mensjacket/4.jpg" alt="" class="small-Img">
            </div>
        </div>
        <!-- Right side -->
        <div class="product-info" style="width: 800px;">
            <h1 class="bold">Scott International Mens Cotton Blend High Neck Jacket</h1>
            <p class="disc">5K+ bought in past month | 6,393 ratings</p>
            <hr>
            <p class="h4"><span class="text-danger">-78%</span> ₹ 899</p>
            <p class="text-muted">M.R.P.: <strike> 3,999</strike></p>
            <p style="margin-top: -10px;">Inclusive of all taxes</p>
            <p style="margin-top: -10px;"><b>EMI</b> starts at ₹1,212. No Cost EMI available </p>
            <hr>
            <p><i class="fas fa-tag text-success m-2"></i>Offers</p>
            <div class="d-flex gap-3 mb-3">
                <div class="card" style="text-align:justify; width: 280px; height:150px;">
                    <div class="p-3">
                        <p style="font-weight: bold;">Bank Offer</p>
                        <p style="margin-top: -16px;">Upto ₹2,000.00 discount on select Credit Cards, Debit CardsUpto ₹2,000.00 discount on select Credit Cards</p>
                    </div>
                </div>
                <div class="card" style="text-align:justify; width: 280px; height:150px;">
                    <div class="p-3">
                        <p style="font-weight: bold;">No Cost EMI</p>
                        <p style="margin-top: -16px;">Upto ₹1,125.67 EMI interest savings on select Credit Cards</p>
                    </div>
                </div>
                <div class="card" style="text-align:justify; width: 280px; height:150px;">
                    <div class="p-3">
                        <p style="font-weight: bold;">Partner Offers</p>
                        <p style="margin-top: -16px;">Get GST invoice and save up to 28% on business purchases.</p>
                    </div>
                </div>
            </div>
            <hr>
            <div class="d-flex mb-4 gap-4">
                <div class="card d-flex align-items-center p-3" style="width: 200px; height:100px;">
                    <i class="fas fa-arrows-rotate fs-3"></i>
                    <p>Replacement in 7 Days </p>
                </div>
                <div class="card d-flex align-items-center p-3" style="width: 200px; height:100px;">
                    <i class="fas fa-truck fs-3"></i>
                    <p>Free Delivery </p>
                </div>
                <div class="card d-flex align-items-center p-3" style="width: 200px; height:100px;">
                    <i class="fas fa-shield fs-3"></i>
                    <p>1 Year Warranty </p>
                </div>
                <div class="card d-flex align-items-center p-3" style="width: 200px; height:100px;">
                    <i class="fas fa-hand-holding-dollar fs-3"></i>
                    <p>Cash On Delivery </p>
                </div>
            </div>
            <div class="d-flex gap-3">
                <form method="post" action="" class="w-100">
                    <input type="hidden" name="product_id" value="17">
                    <input type="hidden" name="product_name" value="Scott International Mens Cotton Blend High Neck Jacket">
                    <input type="hidden" name="price" value="899">
                    <div class="d-flex gap-3" >
                        <button type="submit" class="btn btn-primary w-100 mt-3" name="add_to_cart">
                            <i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;Add to Cart
                        </button>
                        <button class="btn btn-warning w-100 mt-3"><i class="fa fa-shopping-cart"></i>&nbsp;&nbsp;Buy Now</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
    <hr>
    <div class="container">
        <p style="text-align: center;font-weight:600; font-size:20px;">Scott International Men's Cotton Blend High Neck Jacket</p>
        <div class="d-flex">
            <div class="w-100 d-flex flex-column">
                <table>
                    <tbody>
                        <tr>
                            <td class="bg-light p-2">Material Composition </td>
                            <td class="p-2">Cotton</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Sleeve Type </td>
                            <td class="p-2">Long Sleeve</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Long Sleeve </td>
                            <td class="p-2">Long Sleeve</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Fit Type</td>
                            <td class="p-2">Regular</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-100 d-flex flex-column">
                <table>
                    <tbody>
                        <tr>
                            <td class="bg-light p-2">Neck Style</td>
                            <td class="p-2">High Neck</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Country of Origin</td>
                            <td class="p-2">India</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Package Dimensions </td>
                            <td class="p-2">30.8 x 24.6 x 7.2 cm; 340 Grams</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Country of Origin</td>
                            <td class="p-2">India</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="w-100 d-flex flex-column">
                <table>
                    <tbody>
                        <tr>
                            <td class="bg-light p-2">Department </td>
                            <td class="p-2">Men</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Manufacturer </td>
                            <td class="p-2">Switz Inc</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Item Weight </td>
                            <td class="p-2"> 340 g</td>
                        </tr>
                        <tr>
                            <td class="bg-light p-2">Length </td>
                            <td class="p-2">Standard Length</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Footer -->
    <footer>
        <hr>
        <div class="container py-4">
            <div class="row">

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <img src="../images/logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
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
    <script src="../js/product_details.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>
</body>

</html>