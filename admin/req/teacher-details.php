<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && isset($_GET['teacher_id'])) {
    if ($_SESSION['role'] == 'Admin') {
        // Adjusted paths based on the current directory structure
        include "../DB_connection.php"; // Adjusted path
        include "../data/teacher.php"; // Adjusted path

        $teacher_id = $_GET['teacher_id'];
        $teacher = getTeacherById($teacher_id, $conn);

        if ($teacher != 0) {
            echo '<p><strong>First Name:</strong> ' . htmlspecialchars($teacher['fname']) . '</p>';
            echo '<p><strong>Last Name:</strong> ' . htmlspecialchars($teacher['lname']) . '</p>';
            echo '<p><strong>Username:</strong> ' . htmlspecialchars($teacher['username']) . '</p>';
            echo '<p><strong>Address:</strong> ' . htmlspecialchars($teacher['address']) . '</p>';
            echo '<p><strong>Employee Number:</strong> ' . htmlspecialchars($teacher['employee_number']) . '</p>';
            echo '<p><strong>Date of Birth:</strong> ' . htmlspecialchars($teacher['date_of_birth']) . '</p>';
            echo '<p><strong>Phone Number:</strong> ' . htmlspecialchars($teacher['phone_number']) . '</p>';
            echo '<p><strong>Qualification:</strong> ' . htmlspecialchars($teacher['qualification']) . '</p>';
            echo '<p><strong>Email Address:</strong> ' . htmlspecialchars($teacher['email_address']) . '</p>';
            echo '<p><strong>Gender:</strong> ' . htmlspecialchars($teacher['gender']) . '</p>';
        } else {
            echo '<p>Teacher not found.</p>';
        }
    } else {
        echo '<p>Unauthorized access.</p>';
    }
} else {
    echo '<p>Invalid request.</p>';
}
?>
