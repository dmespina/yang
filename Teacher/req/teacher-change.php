<?php 
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
        if (isset($_POST['old_pass']) && isset($_POST['new_pass']) && isset($_POST['c_new_pass'])) {
            include '../../DB_connection.php';
            include "../data/student.php";

            $old_pass = $_POST['old_pass'];
            $new_pass = $_POST['new_pass'];
            $c_new_pass = $_POST['c_new_pass'];

            $teacher_id = $_SESSION['teacher_id'];

            if (empty($old_pass)) {
                echo json_encode(['status' => 'error', 'message' => 'Old password is required']);
                exit;
            } else if (empty($new_pass)) {
                echo json_encode(['status' => 'error', 'message' => 'New password is required']);
                exit;
            } else if (empty($c_new_pass)) {
                echo json_encode(['status' => 'error', 'message' => 'Confirmation password is required']);
                exit;
            } else if ($new_pass !== $c_new_pass) {
                echo json_encode(['status' => 'error', 'message' => 'New password and confirm password do not match']);
                exit;
            } else if (!studentPasswordVerify($old_pass, $conn, $teacher_id)) {
                echo json_encode(['status' => 'error', 'message' => 'Incorrect old password']);
                exit;
            } else {
                // Hashing the new password
                $new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

                $sql = "UPDATE teachers SET password = ? WHERE teacher_id = ?";
                $stmt = $conn->prepare($sql);

                if ($stmt->execute([$new_pass, $teacher_id])) {
                    echo json_encode(['status' => 'success', 'message' => 'The password has been changed successfully!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Failed to update password']);
                }
                exit;
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'An error occurred']);
            exit;
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}
?>
