<?php
include 'database/DB_config.php';

if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $cpassword = mysqli_real_escape_string($conn, $_POST['cpassword']);
    $phoneNumber = mysqli_real_escape_string($conn, $_POST['phone_number']);
    $birthdate = mysqli_real_escape_string($conn, $_POST['birth_date']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $country = mysqli_real_escape_string($conn, $_POST['country']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $region = mysqli_real_escape_string($conn, $_POST['state']);
    $postalCode = mysqli_real_escape_string($conn, $_POST['postal_code']);

    if ($password != $cpassword) {
        echo 'Passwords do not match!';
    } else {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $select = mysqli_query($conn, "SELECT * FROM `user_info` WHERE email = '$email'") or die('query failed');

        if (mysqli_num_rows($select) > 0) {
            echo 'User already exists!';
        } else {
            $insertQuery = "INSERT INTO `user_info` (name, email, password, phone_number, birth_date, gender, address, country, city, region, postal_code) VALUES ('$name', '$email', '$hashedPassword', '$phoneNumber', '$birthdate', '$gender', '$address', '$country', '$city', '$region', '$postalCode')";
            $insertResult = mysqli_query($conn, $insertQuery) or die('Insert query failed: ' . mysqli_error($conn));

            if ($insertResult) {
                header('location: login.php');
            } else {
                echo 'Registration failed!';
            }
        }
    }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EchoBazar | Registration</title>
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

    <section class="card container Registration w-50 mt-4 mb-4 pb-4">
        <header>Be Our Member!</header>
        <form action="" method="post" class="form">
            <div class="input-box">
                <label>Full Name</label>
                <input type="text" name="name" placeholder="Enter full name" required />
            </div>

            <div class="input-box">
                <label>Email Address</label>
                <input type="email" name="email" placeholder="Enter email address" required />
            </div>

            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter password" required />
            </div>

            <div class="input-box">
                <label>Confirm Password</label>
                <input type="password" name="cpassword" placeholder="Confirm password" required />
            </div>

            <div class="column">
                <div class="input-box">
                    <label>Phone Number</label>
                    <input type="tel" name="phone_number" placeholder="Enter phone number" required />
                </div>
                <div class="input-box">
                    <label>Birth Date</label>
                    <input type="date" name="birth_date" placeholder="Enter birth date" required />
                </div>
            </div>

            <div class="gender-box">
                <h3>Gender</h3>
                <div class="gender-option">
                    <div class="gender">
                        <input type="radio" id="check-male" name="gender" value="male" checked />
                        <label for="check-male">Male</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-female" name="gender" value="female" />
                        <label for="check-female">Female</label>
                    </div>
                    <div class="gender">
                        <input type="radio" id="check-other" name="gender" value="prefer_not_to_say" />
                        <label for="check-other">Prefer not to say</label>
                    </div>
                </div>
            </div>
            <div class="input-box address">
                <label>Address</label>
                <input type="text" name="address" placeholder="Enter address" required />
                <div class="column">
                    <input type="text" name="country" placeholder="Enter your Country" required />
                    <input type="text" name="state" placeholder="Enter your state" required />
                </div>
                <div class="column">
                    <input type="text" name="city" placeholder="Enter your city" required />
                    <input type="text" name="postal_code" placeholder="Enter postal code" required />
                </div>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <p style="text-align:center; margin-top:10px;">already have an account? <a href="login.php">login now</a></p>
        </form>
    </section>

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