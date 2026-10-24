<?php
// product_detail.php - Single Product Detail Page
// Uses $_GET['id'] to get the product from the database
include 'dbcm.php';
session_start();

// Get product ID from URL
$product_id = (int)($_GET['id'] ?? 0);

// Get product details from database (prepared statement)
$stmt = $conn->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

// Check if product exists
if (!$product) {
    http_response_code(404);
    echo "Product not found.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - <?php echo h($product['name']); ?></title>
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

        <h2><?php echo h($product['name']); ?></h2>

        <!-- Product Detail Section -->
        <div class="product-detail">

            <div class="product-image">
                <img src="images/<?php echo h($product['image']); ?>" alt="<?php echo h($product['name']); ?>" width="300" height="300">
            </div>

            <div class="product-info">
                <p class="price" style="font-size: 24px;"><?php echo number_format($product['price'], 2); ?> SAR</p>

                <p><?php echo h($product['description']); ?></p>

                <!-- Specifications Table -->
                <h3>Specifications</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Feature</th>
                            <th>Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Split specs by | separator
                        $specs = explode(" | ", $product['specs']);
                        for ($i = 0; $i < count($specs); $i++) {
                            $parts = explode(": ", $specs[$i]);
                            if (count($parts) == 2) {
                                echo "<tr>";
                                echo "<td>" . h($parts[0]) . "</td>";
                                echo "<td>" . h($parts[1]) . "</td>";
                                echo "</tr>";
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <!-- Quantity and Add to Cart -->
                <div class="form-group">
                    <label for="quantity">Quantity:</label>
                    <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="10">
                </div>

                <button class="btn" onclick="addToCart(<?php echo (int)$product['product_id']; ?>, <?php echo h(json_encode($product['name'])); ?>, <?php echo (float)$product['price']; ?>)">Add to Cart</button>
                <a href="products.php" class="btn" style="background-color: #7f8c8d;">Back to Products</a>
            </div>

        </div>

        <div class="clearfix"></div>

        <!-- Customer Reviews from Database -->
        <h3>Customer Reviews</h3>

        <?php
        // Get reviews for this product from the database
        $stmt_rev = $conn->prepare("SELECT * FROM reviews WHERE product_id = ? ORDER BY review_date DESC");
        $stmt_rev->bind_param("i", $product_id);
        $stmt_rev->execute();
        $reviews_result = $stmt_rev->get_result();

        if ($reviews_result->num_rows > 0) {
            while ($review = $reviews_result->fetch_assoc()) {
        ?>
            <div class="review-item">
                <strong><?php echo h($review['customer_name']); ?></strong>
                <span class="stars">
                    <?php
                    // Show stars based on rating
                    for ($s = 0; $s < $review['rating']; $s++) {
                        echo "&#9733;";
                    }
                    ?>
                </span>
                <p><?php echo h($review['comment']); ?></p>
            </div>
        <?php
            } // end while
        } else {
            echo "<p>No reviews yet.</p>";
        }
        ?>

        <!-- Add Review Form -->
        <h3>Write a Review</h3>
        <?php
        if (isset($_SESSION['user'])) {
        ?>
        <form method="POST" action="add_review.php">
            <input type="hidden" name="product_id" value="<?php echo (int)$product_id; ?>">

            <div class="form-group">
                <label for="rating">Rating:</label>
                <select name="rating" id="rating">
                    <option value="5">5 Stars</option>
                    <option value="4">4 Stars</option>
                    <option value="3">3 Stars</option>
                    <option value="2">2 Stars</option>
                    <option value="1">1 Star</option>
                </select>
            </div>

            <div class="form-group">
                <label for="comment">Your Review:</label>
                <textarea name="comment" id="comment" rows="3" cols="50" placeholder="Write your review here..."></textarea>
            </div>

            <input type="submit" value="Submit Review">
        </form>
        <?php
        } else {
            echo "<p><a href='login.php'>Login</a> to write a review.</p>";
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
