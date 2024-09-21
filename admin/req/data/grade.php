<?php
// All Grades - Numerical and Alphabetical Order
function getAllGrades($conn) {
    // SQL query to select all grades, sorted by numeric and alphabetical order
    $sql = "SELECT * FROM grades
            ORDER BY 
            -- Extract numeric part from grade_code and cast it to UNSIGNED for numerical sorting
            CAST(SUBSTRING_INDEX(grade_code, '-', -1) AS UNSIGNED) ASC,
            -- Extract text part from grade_code for alphabetical sorting
            SUBSTRING_INDEX(grade_code, '-', 1) ASC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt->rowCount() >= 1) {
        // Fetch all results if any exist
        $grades = $stmt->fetchAll();
        return $grades;
    } else {
        // Return 0 if no results found
        return 0;
    }
}

// Get Grade by ID
function getGradeById($grade_id, $conn) {
    $sql = "SELECT * FROM grades WHERE grade_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$grade_id]);

    if ($stmt->rowCount() == 1) {
        // Fetch the grade with the given ID if it exists
        $grade = $stmt->fetch();
        return $grade;
    } else {
        // Return 0 if the grade with the given ID does not exist
        return 0;
    }
}

// DELETE
function removeGrade($id, $conn) {
    $sql = "DELETE FROM grades WHERE grade_id=?";
    $stmt = $conn->prepare($sql);
    $re = $stmt->execute([$id]);
    if ($re) {
        // Return 1 if the deletion was successful
        return 1;
    } else {
        // Return 0 if the deletion failed
        return 0;
    }
}
?>
