<?php
// login.php - User Login Page
// Following Chapter 5 pattern for user login
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Login</title>
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

        <h2>Login to Your Account</h2>

        <div class="auth-section">

            <form method="POST" action="login.php" onsubmit="return validateLoginForm()">
                <div class="form-group">
                    <label for="login-email">Email:</label>
                    <input type="email" id="login-email" name="email" placeholder="Enter your email">
                </div>

                <div class="form-group">
                    <label for="login-pwd">Password:</label>
                    <input type="password" id="login-pwd" name="pwd" placeholder="Enter your password">
                </div>

                <input type="submit" name="submit" value="Login">
            </form>

            <br>
            <p>Don't have an account? <a href="register.php">Register here</a></p>

        </div>

        <?php
        // Process login when form is submitted
        if (isset($_POST['submit'])) {
            $email = trim($_POST['email'] ?? '');
            $pwd = $_POST['pwd'] ?? '';

            // Look the user up with a prepared statement (prevents SQL injection)
            $stmt = $conn->prepare("SELECT customer_id, fname, lname, email, pswd FROM customers WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $row = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            // Passwords are stored hashed, so compare with password_verify
            if ($row && password_verify($pwd, $row['pswd'])) {
                session_regenerate_id(true);

                // Set session variables
                $_SESSION['user'] = $row['email'];
                $_SESSION['user_name'] = $row['fname'] . " " . $row['lname'];
                $_SESSION['customer_id'] = $row['customer_id'];

                // Redirect to homepage
                header("Location:index.php");
                exit;
            } else {
                echo "<div class='error-box'>Login failed. Please check your email and password.</div>";
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
