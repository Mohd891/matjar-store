<?php
// register.php - User Registration Page
// Following Chapter 5 pattern for user registration
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Register</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <header>
        <h1>Matjar</h1>
        <p>Online Shopping in Saudi Arabia</p>
    </header>

    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="cart.php">Shopping Cart</a>
    </nav>

    <div class="main-content">

        <h2>Create an Account</h2>

        <div class="auth-section">

            <form method="POST" action="register.php" onsubmit="return validateRegisterForm()">
                <div class="form-group">
                    <label for="fname">First Name:</label>
                    <input type="text" id="fname" name="fname" placeholder="Enter your first name">
                </div>

                <div class="form-group">
                    <label for="lname">Last Name:</label>
                    <input type="text" id="lname" name="lname" placeholder="Enter your last name">
                </div>

                <div class="form-group">
                    <label for="reg-email">Email:</label>
                    <input type="email" id="reg-email" name="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="phone">Phone:</label>
                    <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                </div>

                <div class="form-group">
                    <label for="reg-pwd">Password:</label>
                    <input type="password" id="reg-pwd" name="pwd" placeholder="Enter your password">
                </div>

                <input type="submit" name="submit" value="Register">
            </form>

            <br>
            <p>Already have an account? <a href="login.php">Login here</a></p>

        </div>

        <?php
        // Process registration when form is submitted
        if (isset($_POST['submit'])) {
            $fname = trim($_POST['fname'] ?? '');
            $lname = trim($_POST['lname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            $pwd = $_POST['pwd'] ?? '';

            // Server-side validation (JavaScript checks can be bypassed)
            if ($fname === '' || $lname === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)
                || strlen($pwd) < 6 || ($phone !== '' && !preg_match('/^\+?[0-9\s]{10,20}$/', $phone))) {
                echo "<div class='error-box'>Please fill in all fields correctly (password: at least 6 characters).</div>";
            } else {
                // Check the email is not already registered
                $check = $conn->prepare("SELECT customer_id FROM customers WHERE email = ?");
                $check->bind_param("s", $email);
                $check->execute();
                $exists = $check->get_result()->num_rows > 0;
                $check->close();

                if ($exists) {
                    echo "<div class='error-box'>This email is already registered.</div>";
                } else {
                    // Store only a hash of the password, never the password itself
                    $hash = password_hash($pwd, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO customers (fname, lname, email, pswd, phone) VALUES (?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssss", $fname, $lname, $email, $hash, $phone);

                    if ($stmt->execute()) {
                        echo "<div class='success-message'>You have successfully registered. You may use your credentials to <a href='login.php'>login</a> now.</div>";
                    } else {
                        error_log("Matjar register error: " . $stmt->error);
                        echo "<div class='error-box'>Sorry, we could not create your account. Please try again.</div>";
                    }
                    $stmt->close();
                }
            }
        }
        ?>

    </div>

    <footer>
        <p>&copy; 2026 Matjar - Online Shopping in Saudi Arabia. All rights reserved.</p>
        <p>Contact us: info@matjar.com | Phone: +966 50 123 4567</p>
    </footer>

    <script src="main.js"></script>

</body>
</html>
<?php
$conn->close();
?>
