<?php
session_start();
include '../../DB_connection.php'; // Include your database connection

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Collect form data
    $subjectId = $_POST['subject'];
    $gradeId = $_POST['grade'];
    $sectionId = $_POST['section'];
    $teacherId = $_POST['teacher']; // Updated to match form
    $scheduleTime = $_POST['schedule']; // Updated to reflect 'schedule_time' in DB
    $days = isset($_POST['weekdays']) ? implode(', ', $_POST['weekdays']) : ''; // Updated to reflect 'day' in DB

    // Check if all fields are filled
    if (!empty($subjectId) && !empty($gradeId) && !empty($sectionId) && !empty($teacherId) && !empty($scheduleTime) && !empty($days)) {

        // Prepare the SQL INSERT query
        $query = "INSERT INTO class (subject_id, grade, section, teachers, schedule_time, day) 
                  VALUES (:subject, :grade, :section, :teachers, :schedule_time, :day)";
        $stmt = $conn->prepare($query);

        // Bind the parameters
        $stmt->bindParam(':subject', $subjectId);
        $stmt->bindParam(':grade', $gradeId);
        $stmt->bindParam(':section', $sectionId);
        $stmt->bindParam(':teachers', $teacherId);
        $stmt->bindParam(':schedule_time', $scheduleTime);
        $stmt->bindParam(':day', $days);

        // Execute the query and check if successful
        if ($stmt->execute()) {
            // Redirect with success message
            header("Location: ../class.php?success=Class added successfully");
        } else {
            // Redirect with error message
            header("Location: ../class.php?error=Error adding class");
        }
    } else {
        // Redirect with error if fields are missing
        header("Location: ../class.php?error=All fields are required");
    }
} else {
    header("Location: ../class.php");
}
?>
