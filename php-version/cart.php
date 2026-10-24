<?php
// cart.php - Shopping Cart Page
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Shopping Cart</title>
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

        <h2>Shopping Cart</h2>

        <!-- Empty cart message -->
        <p id="empty-cart-message" style="display: none; text-align: center; font-size: 18px; color: #777;">
            Your cart is empty. <a href="products.php">Start shopping!</a>
        </p>

        <!-- Cart Table -->
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="cart-table-body">
                <!-- Items added by JavaScript -->
            </tbody>
        </table>

        <!-- Cart Totals -->
        <div class="cart-total">
            <p>Subtotal: <strong id="cart-total">0.00 SAR</strong></p>
            <p>VAT (15%): <strong id="cart-vat">0.00 SAR</strong></p>
            <p style="font-size: 20px;">Grand Total: <strong id="cart-grand-total">0.00 SAR</strong></p>
        </div>

        <br>
        <a href="products.php" class="btn">Continue Shopping</a>
        <button class="btn btn-red" onclick="clearCart()">Clear Cart</button>

        <!-- Checkout Form - sends cart data to PHP -->
        <?php
        if (isset($_SESSION['user'])) {
        ?>
        <form method="POST" action="checkout.php" id="checkout-form">
            <input type="hidden" name="cart_data" id="cart-data" value="">
            <br><br>
            <input type="submit" name="checkout" value="Proceed to Checkout" id="checkout-btn">
        </form>
        <?php
        } else {
            echo "<br><br><p><a href='login.php'>Login</a> to proceed to checkout.</p>";
        }
        ?>

    </div>

    <footer>
        <p>&copy; 2026 Matjar - Online Shopping in Saudi Arabia. All rights reserved.</p>
        <p>Contact us: info@matjar.com | Phone: +966 50 123 4567</p>
    </footer>

    <script src="main.js"></script>
    <script>
        // Display cart when page loads
        displayCart();
    </script>

</body>
</html>
<?php
$conn->close();
?>
