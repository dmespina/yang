<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['subject_name']) &&
            isset($_POST['subject_code']) && 
            isset($_POST['grade'])) {
        
            include '../../DB_connection.php';

            $subject_name = $_POST['subject_name'];
            $subject_code = $_POST['subject_code'];
            $grade = $_POST['grade'];

            if (empty($subject_name)) {
                $em  = "Subject name is required";
                header("Location: ../subject-add.php?error=$em");
                exit;
            } else if (empty($subject_code)) {
                $em  = "Subject code is required";
                header("Location: ../subject-add.php?error=$em");
                exit;
            } else if (empty($grade)) {
                $em  = "Grade is required";
                header("Location: ../subject-add.php?error=$em");
                exit;
            } else {
                // Check if the subject already exists
                $sql_check = "SELECT * FROM subjects 
                              WHERE grade=? AND subject_code=?";
                $stmt_check = $conn->prepare($sql_check);
                $stmt_check->execute([$grade, $subject_code]);
                if ($stmt_check->rowCount() > 0) {
                    $em  = "The subject already exists";
                    header("Location: ../subject-add.php?error=$em");
                    exit;
                } else {
                    $sql  = "INSERT INTO subjects(grade, subject, subject_code)
                             VALUES(?,?,?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$grade, $subject_name, $subject_code]);
                    $sm = "New subject created successfully";
                    header("Location: ../subject-add.php?success=$sm");
                    exit;
                } 
            }
        
        } else {
            $em = "An error occurred";
            header("Location: ../subject-add.php?error=$em");
            exit;
        }

    } else {
        header("Location: ../../logout.php");
        exit;
    } 
} else {
    header("Location: ../../logout.php");
    exit;
} 
?>
