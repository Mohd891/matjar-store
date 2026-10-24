<?php
// checkout.php - Process Checkout and Save Order to Database
include 'dbcm.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matjar - Checkout</title>
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

        <?php
        $order_placed = false;

        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo "<h2>Please login first</h2>";
            echo "<p><a href='login.php'>Click here to login</a></p>";
        } else if (isset($_POST['checkout'])) {

            // Get cart data from the form (only product ids and quantities are trusted)
            $cart = json_decode($_POST['cart_data'] ?? '', true);

            if (!is_array($cart) || count($cart) === 0) {
                echo "<h2>Your cart is empty</h2>";
                echo "<p><a href='products.php'>Go shopping</a></p>";
            } else {

                // Look up every product in the database: prices come from the server, not the browser
                $items = array();
                $total = 0;
                $valid = true;
                $stmt_p = $conn->prepare("SELECT product_id, name, price FROM products WHERE product_id = ?");
                foreach ($cart as $line) {
                    $pid = (int)($line['id'] ?? 0);
                    $qty = (int)($line['quantity'] ?? 0);
                    if ($qty < 1 || $qty > 10) {
                        $valid = false;
                        break;
                    }
                    $stmt_p->bind_param("i", $pid);
                    $stmt_p->execute();
                    $product = $stmt_p->get_result()->fetch_assoc();
                    if (!$product) {
                        $valid = false;
                        break;
                    }
                    $items[] = array('id' => $pid, 'name' => $product['name'], 'price' => (float)$product['price'], 'quantity' => $qty);
                    $total = $total + ((float)$product['price'] * $qty);
                }
                $stmt_p->close();

                if (!$valid || count($items) === 0) {
                    echo "<h2>Error</h2>";
                    echo "<div class='error-box'>Your cart contains an invalid item. Please review your cart.</div>";
                    echo "<p><a href='cart.php'>Back to cart</a></p>";
                } else {
                    $vat = $total * 0.15;
                    $grand_total = $total + $vat;
                    $customer_id = (int)$_SESSION['customer_id'];

                    // Save the order and its items together (all or nothing)
                    $conn->begin_transaction();
                    $ok = false;
                    $stmt_o = $conn->prepare("INSERT INTO orders (customer_id, total, vat, grand_total) VALUES (?, ?, ?, ?)");
                    $stmt_o->bind_param("iddd", $customer_id, $total, $vat, $grand_total);
                    if ($stmt_o->execute()) {
                        $order_id = $conn->insert_id;
                        $ok = true;
                        $stmt_i = $conn->prepare("INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)");
                        foreach ($items as $item) {
                            $stmt_i->bind_param("iisdi", $order_id, $item['id'], $item['name'], $item['price'], $item['quantity']);
                            if (!$stmt_i->execute()) {
                                $ok = false;
                                break;
                            }
                        }
                        $stmt_i->close();
                    }
                    $stmt_o->close();

                    if ($ok) {
                        $conn->commit();
                        $order_placed = true;

                        // Show success message
                        echo "<h2>Order Placed Successfully!</h2>";
                        echo "<div class='success-message'>";
                        echo "<p>Thank you for your order!</p>";
                        echo "<p><strong>Order ID:</strong> " . (int)$order_id . "</p>";
                        echo "<p><strong>Subtotal:</strong> " . number_format($total, 2) . " SAR</p>";
                        echo "<p><strong>VAT (15%):</strong> " . number_format($vat, 2) . " SAR</p>";
                        echo "<p><strong>Grand Total:</strong> " . number_format($grand_total, 2) . " SAR</p>";
                        echo "</div>";

                        // Show order items
                        echo "<h3>Order Items</h3>";
                        echo "<table>";
                        echo "<thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead>";
                        echo "<tbody>";
                        foreach ($items as $item) {
                            echo "<tr>";
                            echo "<td>" . h($item['name']) . "</td>";
                            echo "<td>" . number_format($item['price'], 2) . " SAR</td>";
                            echo "<td>" . $item['quantity'] . "</td>";
                            echo "<td>" . number_format($item['price'] * $item['quantity'], 2) . " SAR</td>";
                            echo "</tr>";
                        }
                        echo "</tbody></table>";

                        echo "<br><a href='index.php' class='btn'>Back to Home</a>";
                    } else {
                        $conn->rollback();
                        error_log("Matjar checkout error: " . $conn->error);
                        echo "<h2>Error</h2>";
                        echo "<div class='error-box'>Sorry, we could not place your order. Please try again.</div>";
                    }
                }
            }
        } else {
            echo "<h2>No checkout data received</h2>";
            echo "<p><a href='cart.php'>Go back to cart</a></p>";
        }
        ?>

    </div>

    <footer>
        <p>&copy; 2026 Matjar - Online Shopping in Saudi Arabia. All rights reserved.</p>
        <p>Contact us: info@matjar.com | Phone: +966 50 123 4567</p>
    </footer>

    <?php if ($order_placed) { ?>
    <!-- Clear localStorage cart after successful checkout only -->
    <script>
        localStorage.removeItem("matjarCart");
    </script>
    <?php } ?>

</body>
</html>
<?php
$conn->close();
?>
