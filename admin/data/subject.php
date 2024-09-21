<?php 

// All Subjects
function getAllSubjects($conn){
   $sql = "SELECT * FROM subjects ORDER BY subject ASC";  // Add ORDER BY clause
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $subjects = $stmt->fetchAll();
     return $subjects;
   } else {
     return 0;
   }
}

// Search Subjects
function searchSubjects($query, $conn) {
  $query = "%$query%";
  $sql = "SELECT * FROM subjects WHERE subject LIKE ? OR subject_code LIKE ? ORDER BY subject ASC"; // Add ORDER BY clause
  $stmt = $conn->prepare($sql);
  $stmt->execute([$query, $query]);
  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get Subject by ID
function getSubjectById($subject_id, $conn){
   $sql = "SELECT * FROM subjects WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$subject_id]);

   if ($stmt->rowCount() == 1) {
     $subject = $stmt->fetch();
     return $subject;
   } else {
     return 0;
   }
}

// DELETE Subject
function removeSubject($id, $conn){
   $sql  = "DELETE FROM subjects WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $result = $stmt->execute([$id]);
   return $result ? 1 : 0;
}

?>
