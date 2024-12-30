<?php
// Include database connection
include 'config.php';

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; // Get logged-in user's ID
$product_id = $_POST['product_id']; // Get the product ID to remove

// Delete the product from favorites
mysqli_query($conn, "DELETE FROM `favorites` WHERE user_id = '$user_id' AND product_id = '$product_id'") or die('query failed');

// Redirect back to favorites page after removal
header('Location: favorites.php');
exit();
?>
