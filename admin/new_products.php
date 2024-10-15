<?php
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
// Database connection (assuming you have a file for this)
include '../db_connection.php';

// Get the current date
$current_date = new DateTime();
$current_date->modify('-3 months');
$three_months_ago = $current_date->format('Y-m-d');

// Prepare and execute the query
$sql = "SELECT * FROM products WHERE date_product >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $three_months_ago);
$stmt->execute();
$result = $stmt->get_result();

// Fetch products
$products = [];
while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}
?>
