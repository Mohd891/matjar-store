<?php
// about.php - About Us Page
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - About Us</title>
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

        <h2>About Matjar</h2>

        <div class="about-section">
            <h3>Who We Are</h3>
            <p>Matjar is a Saudi-based e-commerce platform that allows customers to browse, search, and purchase products online. We are dedicated to providing the best shopping experience for our customers across Saudi Arabia.</p>
        </div>

        <div class="about-section">
            <h3>Our Mission</h3>
            <p>Our mission is to make online shopping easy, safe, and affordable for everyone in Saudi Arabia. We aim to connect customers with high-quality products at competitive prices, with fast and reliable delivery.</p>
        </div>

        <div class="about-section">
            <h3>What We Offer</h3>
            <ul>
                <li><strong>Electronics:</strong> Smartphones, laptops, tablets, gaming consoles, and accessories</li>
                <li><strong>Fashion:</strong> Men's, women's, and children's clothing, shoes, and bags</li>
                <li><strong>Home &amp; Kitchen:</strong> Furniture, appliances, and home decor</li>
                <li><strong>Grocery:</strong> Snacks, beverages, and personal care products</li>
            </ul>
        </div>

        <div class="about-section">
            <h3>Payment Methods</h3>
            <ol>
                <li>Mada Card</li>
                <li>Visa / Mastercard</li>
                <li>Apple Pay</li>
                <li>Cash on Delivery</li>
            </ol>
        </div>

        <div class="about-section">
            <h3>Delivery Areas</h3>
            <ul>
                <li>Al Ahsa (Same-day delivery available)</li>
                <li>Riyadh</li>
                <li>Jeddah</li>
                <li>Dammam</li>
            </ul>
        </div>

        <h3>Why Choose Matjar?</h3>
        <table>
            <thead>
                <tr>
                    <th>Feature</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Secure Payment</td>
                    <td>All transactions are encrypted and safe</td>
                </tr>
                <tr>
                    <td>Fast Delivery</td>
                    <td>Same-day delivery in Al Ahsa, 2-3 days nationwide</td>
                </tr>
                <tr>
                    <td>Easy Returns</td>
                    <td>7-day return policy on all products</td>
                </tr>
                <tr>
                    <td>24/7 Support</td>
                    <td>Customer service available around the clock</td>
                </tr>
            </tbody>
        </table>

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
