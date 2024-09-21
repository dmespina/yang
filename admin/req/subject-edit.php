<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['subject_name']) &&
            isset($_POST['subject_code']) &&
            isset($_POST['subject_id'])) {

            include '../../DB_connection.php';

            $subject_name = $_POST['subject_name'];
            $subject_code = $_POST['subject_code'];
            $subject_id = $_POST['subject_id'];

            $data = 'subject_id=' . $subject_id;

            // Validate input fields
            if (empty($subject_id)) {
                $em = "Subject ID is required";
                header("Location: ../subject-edit.php?error=$em&$data");
                exit;
            } else if (empty($subject_name)) {
                $em = "Subject name is required";
                header("Location: ../subject-edit.php?error=$em&$data");
                exit;
            } else if (empty($subject_code)) {
                $em = "Subject code is required";
                header("Location: ../subject-edit.php?error=$em&$data");
                exit;
            }

            // Check if the subject already exists
            $sql_check = "SELECT * FROM subjects WHERE subject_code=? AND subject_id!=?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->execute([$subject_code, $subject_id]);

            if ($stmt_check->rowCount() > 0) {
                // Subject already exists with the same code but different ID
                $em = "The subject already exists";
                header("Location: ../subject-edit.php?error=$em&$data");
                exit;
            } else {
                // Update the subject since it does not exist or is the same one being edited
                $sql = "UPDATE subjects SET subject=?, subject_code=? WHERE subject_id=?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$subject_name, $subject_code, $subject_id]);
                $sm = "Subject updated successfully";
                header("Location: ../subject-edit.php?success=$sm&$data");
                exit;
            }

        } else {
            // Handle case where required POST data is missing
            $em = "An error occurred";
            header("Location: ../subject.php?error=$em");
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
