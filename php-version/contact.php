<?php
// contact.php - Contact Us Page
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Contact Us</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <header>
        <h1>Matjar</h1>
        <p>Online Shopping in Saudi Arabia</p>
    </header>

    <div class="user-bar">
        <?php
        if (isset($_SESSION['user'])) {
            echo "Welcome, " . h($_SESSION['user_name']) . " | ";
            echo "<a href='logout.php'>Logout</a>";
        } else {
            echo "<a href='login.php'>Login</a> | <a href='register.php'>Register</a>";
        }
        ?>
    </div>

    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="cart.php">Shopping Cart</a>
    </nav>

    <div class="main-content">

        <h2>Contact Us</h2>
        <p>Have a question or need help? Fill out the form below and we will get back to you.</p>

        <div class="about-section">
            <h3>Our Contact Information</h3>
            <p><strong>Email:</strong> <a href="mailto:info@matjar.com">info@matjar.com</a></p>
            <p><strong>Phone:</strong> +966 50 123 4567</p>
            <p><strong>Office:</strong> Al Ahsa, Eastern Province, Saudi Arabia</p>
            <p><strong>Hours:</strong> Sunday - Thursday, 9:00 AM - 5:00 PM</p>
        </div>

        <div class="about-section">
            <h3>Follow Us</h3>
            <ul>
                <li><a href="https://twitter.com" target="_blank">Twitter / X</a></li>
                <li><a href="https://instagram.com" target="_blank">Instagram</a></li>
                <li><a href="https://snapchat.com" target="_blank">Snapchat</a></li>
            </ul>
        </div>

        <h3>Send Us a Message</h3>

        <form method="post" action="contact.php" onsubmit="return validateContactForm()">

            <div class="form-group">
                <label for="name">Full Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your full name">
                <p class="error-message" id="name-error"></p>
            </div>

            <div class="form-group">
                <label for="email">Email Address:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email">
                <p class="error-message" id="email-error"></p>
            </div>

            <div class="form-group">
                <label for="phone">Phone Number:</label>
                <input type="tel" id="phone" name="phone" placeholder="Enter your phone number">
                <p class="error-message" id="phone-error"></p>
            </div>

            <div class="form-group">
                <label for="subject">Subject:</label>
                <select id="subject" name="subject">
                    <option value="General Inquiry">General Inquiry</option>
                    <option value="Order Issue">Order Issue</option>
                    <option value="Return Request">Return Request</option>
                    <option value="Feedback">Feedback</option>
                    <option value="Other">Other</option>
                </select>
            </div>

            <div class="form-group">
                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="5" cols="50" placeholder="Type your message here..."></textarea>
                <p class="error-message" id="message-error"></p>
            </div>

            <input type="submit" name="submit" value="Send Message">
            <input type="reset" value="Clear Form">
        </form>

        <?php
        // Process the form when submitted
        if (isset($_POST['submit'])) {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            // Simple validation on server side
            if ($name != "" && $email != "" && $message != "") {
                echo "<div class='success-message'>Thank you, " . h($name) . "! Your message has been received. We will get back to you soon.</div>";
            } else {
                echo "<div class='error-box'>Please fill in all required fields.</div>";
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
