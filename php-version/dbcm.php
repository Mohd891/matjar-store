<?php
// dbcm.php - Database Connection File
// Settings can be overridden with environment variables (DB_HOST, DB_PORT, DB_USER, DB_PASS, DB_NAME)

$servername = getenv('DB_HOST') ?: "localhost";
$username = getenv('DB_USER') ?: "root";
$password = getenv('DB_PASS') ?: "";
$db = getenv('DB_NAME') ?: "matjardb";
$port = (int)(getenv('DB_PORT') ?: 3306);

// Create connection (report errors without showing details to visitors)
mysqli_report(MYSQLI_REPORT_OFF);
$conn = @new mysqli($servername, $username, $password, $db, $port);

// Check connection
if ($conn->connect_error) {
    error_log("Matjar DB connection failed: " . $conn->connect_error);
    die("Sorry, the store is temporarily unavailable.");
}
$conn->set_charset("utf8mb4");

// Escape text before printing it in HTML (prevents XSS)
function h($text) {
    return htmlspecialchars((string)$text, ENT_QUOTES, 'UTF-8');
}
?>
