<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {

        if (isset($_POST['student_id']) &&
            isset($_POST['fname']) &&
            isset($_POST['lname']) &&
            isset($_POST['mname']) &&
            isset($_POST['address']) &&
            isset($_POST['email_address']) &&
            isset($_POST['parent_phone_number']) &&
            isset($_POST['gender']) &&
            isset($_POST['username']) &&
            isset($_POST['date_of_birth'])) {

            include "../../DB_connection.php";

            $student_id = $_POST['student_id'];
            $fname = htmlspecialchars($_POST['fname']);
            $lname = htmlspecialchars($_POST['lname']);
            $mname = htmlspecialchars($_POST['mname']);
            $address = htmlspecialchars($_POST['address']);
            $email_address = htmlspecialchars($_POST['email_address']);
            $parent_phone_number = htmlspecialchars($_POST['parent_phone_number']);
            $gender = $_POST['gender'];
            $username = htmlspecialchars($_POST['username']);
            $dob = $_POST['dob']; // New field for date of birth
            $password = $_POST['password'];

            $data = 'student_id=' . $student_id;

            // Input validation
            if (empty($fname)) {
                $em = "First name is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($lname)) {
                $em = "Last name is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($mname)) {
                $em = "Middle name is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($address)) {
                $em = "Address is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($email_address)) {
                $em = "Email address is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($parent_phone_number)) {
                $em = "Parent phone number is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($gender)) {
                $em = "Gender is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($username)) {
                $em = "Username is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else if (empty($dob)) {
                $em = "Date of birth is required";
                header("Location: ../student-edit.php?error=$em&$data");
                exit;
            } else {
                // Check if password is provided and update accordingly
                if (!empty($password)) {
                    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                    $sql = "UPDATE students SET fname=?, lname=?, mname=?, address=?, email_address=?, parent_phone_number=?, gender=?, username=?, date_of_birth=?, password=? WHERE student_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$fname, $lname, $mname, $address, $email_address, $parent_phone_number, $gender, $username, $date_of_birth, $password_hashed, $student_id]);
                } else {
                    $sql = "UPDATE students SET fname=?, lname=?, mname=?, address=?, email_address=?, parent_phone_number=?, gender=?, username=?,date_of_birth=? WHERE student_id=?";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$fname, $lname, $mname, $address, $email_address, $parent_phone_number, $gender, $username, $date_of_birth, $student_id]);
                }

                $sm = "Student information updated successfully";
                header("Location: ../student.php?success=$sm");
                exit;
            }

        } else {
            $em = "An error occurred";
            header("Location: ../student.php?error=$em");
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
