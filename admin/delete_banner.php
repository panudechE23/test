<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

if (isset($_GET['id'])) {
    $id_banner = $_GET['id'];

    // Retrieve the banner information before deletion
    $sql = "SELECT * FROM banner WHERE id_banner = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_banner]);
    $banner = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($banner) {
        // Directory path for the banner images
        $upload_dir = "../images/banner/" . $id_banner . "/";



        // Delete the directory if it exists
        if (is_dir($upload_dir)) {
            // Use recursive rmdir function to delete the directory and its contents
            deleteDirectory($upload_dir);
        }

        // Delete record from the database
        $sql_delete = "DELETE FROM banner WHERE id_banner = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id_banner]);
    }

    // Redirect after deletion
    header("Location: list_banner.php");
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
