<?php
// products.php - Products Listing Page
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Products</title>
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

        <h2>Our Products</h2>

        <?php
        // Check if a category filter was passed in the URL
        if (isset($_GET['category'])) {
            $category = $_GET['category'];
            $stmt = $conn->prepare("SELECT * FROM products WHERE category = ? ORDER BY product_id");
            $stmt->bind_param("s", $category);
            echo "<p>Showing products in: <strong>" . h($category) . "</strong> | <a href='products.php'>Show All</a></p>";
        } else {
            $stmt = $conn->prepare("SELECT * FROM products ORDER BY category, product_id");
            echo "<p>Browse our wide selection of products.</p>";
        }

        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Store current category for grouping
            $current_category = "";

            while ($row = $result->fetch_assoc()) {
                // Show category heading when category changes
                if ($row['category'] !== $current_category) {
                    if ($current_category !== "") {
                        echo '<div class="clearfix"></div>';
                    }
                    $current_category = $row['category'];
                    echo "<h3>" . h($current_category) . "</h3>";
                }
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
