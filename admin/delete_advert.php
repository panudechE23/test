<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: ../login.php");
    exit;
}
require "../db_connect.php";

if (isset($_GET['id'])) {
    $id_advert = $_GET['id'];

    // Retrieve the advert information before deletion
    $sql = "SELECT * FROM advert WHERE id_advert = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id_advert]);
    $advert = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($advert) {
        // Directory path for the advert images
        $upload_dir = "../images/advert/" . $id_advert . "/";



        // Delete the directory if it exists
        if (is_dir($upload_dir)) {
            // Use recursive rmdir function to delete the directory and its contents
            deleteDirectory($upload_dir);
        }

        // Delete record from the database
        $sql_delete = "DELETE FROM advert WHERE id_advert = ?";
        $stmt_delete = $pdo->prepare($sql_delete);
        $stmt_delete->execute([$id_advert]);
    }

    // Redirect after deletion
    header("Location: list_advert.php");
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
