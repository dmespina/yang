<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['subject_id'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/subject.php";

        $id = $_GET['subject_id'];
        if (removeSubject($id, $conn)) {
            $sm = "Successfully deleted!";
            header("Location: subject.php?success=$sm");
            exit;
        } else {
            $em = "Unknown error occurred";
            header("Location: subject.php?error=$em");
            exit;
        }

    } else {
        header("Location: subject.php");
        exit;
    } 
} else {
    header("Location: subject.php");
    exit;
}
