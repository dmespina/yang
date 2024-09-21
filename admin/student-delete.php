<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && isset($_GET['student_id'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/student.php";

        $id = $_GET['student_id'];
        $successMessage = "Successfully deleted!";
        $errorMessage = "Unknown error occurred";

        if (removeStudent($id, $conn)) {
            // Redirect to the student list with success message
            header("Location: student.php?success=" . urlencode($successMessage));
            exit;
        } else {
            // Redirect to the student list with error message
            header("Location: student.php?error=" . urlencode($errorMessage));
            exit;
        }
    } else {
        header("Location: student.php");
        exit;
    } 
} else {
    header("Location: teacher.php");
    exit;
}
?>
