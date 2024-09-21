<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) &&
    isset($_GET['section_id'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/section.php";

        $id = $_GET['section_id'];
        if (removeSection($id, $conn)) {
            // Redirect with success message
            header("Location: section.php?success=Successfully deleted!");
            exit;
        } else {
            // Redirect with error message
            header("Location: section.php?error=Unknown error occurred");
            exit;
        }

    } else {
        header("Location: section.php");
        exit;
    } 
} else {
    header("Location: section.php");
    exit;
} 
?>
