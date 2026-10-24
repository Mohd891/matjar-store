<?php
// index.php - Homepage
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Online Shopping in Saudi Arabia</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>Matjar</h1>
        <p>Online Shopping in Saudi Arabia</p>
    </header>

    <!-- User Bar - show login status -->
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

    <!-- Navigation -->
    <nav>
        <a href="index.php">Home</a>
        <a href="products.php">Products</a>
        <a href="about.php">About Us</a>
        <a href="contact.php">Contact Us</a>
        <a href="cart.php">Shopping Cart</a>
    </nav>

    <!-- Main Content -->
    <div class="main-content">

        <!-- Hero Section -->
        <div class="hero">
            <h2>Welcome to Matjar!</h2>
            <p>Your trusted online shopping platform in Saudi Arabia.</p>
            <p>Browse electronics, fashion, home essentials, and more.</p>
            <br>
            <a href="products.php" class="btn">Shop Now</a>
        </div>

        <!-- Deal Banner -->
        <div class="deal-banner">
            Special Offer: Free Delivery on Orders Over 200 SAR!
        </div>

        <!-- Categories -->
        <h2>Shop by Category</h2>

        <div class="category-box">
            <h3>Electronics</h3>
            <p>Smartphones, laptops</p>
            <a href="products.php?category=Electronics">Browse</a>
        </div>

        <div class="category-box">
            <h3>Fashion</h3>
            <p>Clothing, shoes, bags</p>
            <a href="products.php?category=Fashion">Browse</a>
        </div>

        <div class="category-box">
            <h3>Home &amp; Kitchen</h3>
            <p>Furniture, appliances</p>
            <a href="products.php?category=Home+and+Kitchen">Browse</a>
        </div>

        <div class="clearfix"></div>

        <!-- Featured Products from Database -->
        <h2>Featured Products</h2>

        <?php
        // Get all products from the database
        $sql = "SELECT * FROM products";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
        ?>
            <div class="product-card">
                <img src="images/<?php echo h($row['image']); ?>" alt="<?php echo h($row['name']); ?>" width="150" height="150">
                <h3><?php echo h($row['name']); ?></h3>
                <p class="price"><?php echo number_format($row['price'], 2); ?> SAR</p>
                <br>
                <a href="product_detail.php?id=<?php echo (int)$row['product_id']; ?>" class="btn">View Details</a>
            </div>
        <?php
            } // end while
        } else {
            echo "<p>No products found.</p>";
        }
        ?>

        <div class="clearfix"></div>

        <!-- Trust Badges -->
        <div class="trust-badges">
            <span class="badge">&#128274; Secure Payment</span>
            <span class="badge">&#128666; Fast Delivery</span>
            <span class="badge">&#128260; Easy Returns</span>
            <span class="badge">&#128222; 24/7 Support</span>
        </div>

    </div>

    <!-- Footer -->
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
