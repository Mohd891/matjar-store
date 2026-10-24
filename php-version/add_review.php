<?php
// add_review.php - Add a Review to the Database
include 'dbcm.php';
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    header("Location:login.php");
    exit;
}

// Only accept form submissions
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location:products.php");
    exit;
}

// Get and validate form data
$product_id = (int)($_POST['product_id'] ?? 0);
$customer_name = $_SESSION['user_name'];
$rating = (int)($_POST['rating'] ?? 0);
$comment = trim($_POST['comment'] ?? '');

if ($product_id < 1 || $rating < 1 || $rating > 5 || $comment === '') {
    header("Location:product_detail.php?id=" . $product_id);
    exit;
}

// Insert review into database with a prepared statement
$stmt = $conn->prepare("INSERT INTO reviews (product_id, customer_name, rating, comment) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isis", $product_id, $customer_name, $rating, $comment);

if ($stmt->execute()) {
    // Redirect back to the product page
    header("Location:product_detail.php?id=" . $product_id);
} else {
    error_log("Matjar review error: " . $stmt->error);
    echo "Sorry, your review could not be saved.";
}

$stmt->close();
$conn->close();
?>
