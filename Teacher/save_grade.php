<?php
include '../DB_connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $grade_year = $_POST['grade_year'];
    $section = $_POST['section'];
    $subject = $_POST['subject'];
    
    // Loop through the POST data to handle multiple student grades
    foreach ($_POST as $key => $value) {
        if (strpos($key, 'q1_') === 0) {
            // Extract student id from the field name (e.g., q1_123 where 123 is the student ID)
            $student_id = str_replace('q1_', '', $key);
            $q1_grade = $value;

            // Update or insert the grade for the 1st quarter in the database
            $stmt = $conn->prepare("UPDATE Student_grade SET q1 = :q1_grade WHERE student_id = :student_id AND subject = :subject");
            $stmt->bindParam(':q1_grade', $q1_grade);
            $stmt->bindParam(':student_id', $student_id);
            $stmt->bindParam(':subject', $subject);
            $stmt->execute();
        }
        // Similarly handle q2_, q3_, q4_, and final_...
    }
    
    echo "Grades submitted successfully!";
}
?>
