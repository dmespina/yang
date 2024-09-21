<?php
include "../DB_connection.php";

// Check if grade_id is set
if (isset($_GET['grade_id'])) {
    $gradeId = htmlspecialchars($_GET['grade_id']);
    
    // Function to fetch subjects based on grade
    function getSubjectsByGrade($conn, $gradeId) {
        $stmt = $conn->prepare("SELECT * FROM subject WHERE grade_code = :grade_id");
        $stmt->execute(['grade_id' => $gradeId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Fetch subjects
    $subjects = getSubjectsByGrade($conn, $gradeId);

    // Return JSON response
    echo json_encode($subjects);
} else {
    echo json_encode([]);
}
?>
