<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

if (isset($_GET['id'])) {
    $id_product = $_GET['id'];

    // Retrieve the product information before deletion
    $sql = "SELECT * FROM product WHERE id_product = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_product]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Directory path for the product images
        $upload_dir = "../images/product/" . $id_product . "/";



        // Delete the directory if it exists
        if (is_dir($upload_dir)) {
            // Use recursive rmdir function to delete the directory and its contents
            deleteDirectory($upload_dir);
        }

        // Delete record from the database
        $sql_delete = "DELETE FROM product WHERE id_product = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id_product]);
    }

    // Redirect after deletion
    header("Location: list_product.php");
    exit;
}

// Function to delete a directory and its contents
function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }

    $files = array_diff(scandir($dir), array('.', '..'));
    foreach ($files as $file) {
        (is_dir("$dir/$file")) ? deleteDirectory("$dir/$file") : unlink("$dir/$file");
    }
    rmdir($dir);
}
?>
