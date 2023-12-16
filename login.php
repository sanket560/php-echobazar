<?php
include 'database/DB_config.php';

session_start();

if (isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email'") or die('query failed');

    if (mysqli_num_rows($select) > 0) {
        $user = mysqli_fetch_assoc($select);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id']; // Set user_id in the session
            $_SESSION['user_name'] = $user['name']; // Set user_name in the session, if needed

            header('location: index.php');
        } else {
            $_SESSION['login_error'] = 'Incorrect password!';
            header('location: login.php'); // Redirect back to the login page
            exit();
        }
    } else {
        $_SESSION['login_error'] = 'User not found!';
        header('location: login.php'); // Redirect back to the login page
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBazar | Login</title>
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
                <li class="nav-item me-3 me-lg-0">
                    <a href="your_link_here" class="btn btn-primary" data-mdb-ripple-init>
                        <i class="fas fa-sign-in-alt"></i> Login
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Error messages section -->
    <div class="container mt-3">
        <?php
        if (isset($_SESSION['login_error'])) {
            echo '<div class="alert alert-danger" role="alert">' . $_SESSION['login_error'] . '</div>';
            unset($_SESSION['login_error']);
        }
        ?>
    </div>

    <!-- main -->
    <div class="card login-page mt-3 m-auto">
        <header>Welcome Back!</header>
        <form action="" method="post">
            <div class="field email">
                <div class="input-area">
                    <input type="text" name="email" placeholder="Email Address">
                    <i class="icon fas fa-envelope"></i>
                </div>
            </div>
            <div class="field password">
                <div class="input-area">
                    <input type="password" name="password" placeholder="Password">
                    <i class="icon fas fa-lock"></i>
                </div>
            </div>
            <div class="pass-txt"><a href="#">Forgot password?</a></div>
            <input type="submit" name="submit" value="Login">
            <div class="sign-txt mt-3">Not yet member? <a href="register.php">Register now</a></div>
        </form>
    </div>

    <!-- Footer -->
    <footer>
        <hr>
        <div class="container py-4">
            <div class="row">

                <div class="col-lg-3 col-md-6 mb-4 mb-md-0">
                    <img src="images/logo.png" alt="Logo" class="img-fluid" style="max-width: 150px;">
                    <p style="text-align: justify;">Discover a world of endless possibilities at EchoBazar. We bring you a curated collection of the latest trends, must-have essentials, and exclusive deals. </p>
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

    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/7.1.0/mdb.umd.min.js"></script>

</body>

</html>