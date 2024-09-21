<?php 

// All Subjects
function getAllSubjects($conn){
   $sql = "SELECT * FROM subjects";
   $stmt = $conn->prepare($sql);
   $stmt->execute();

   if ($stmt->rowCount() >= 1) {
     $subjects = $stmt->fetchAll();
     return $subjects;
   } else {
     return 0;
   }


// Get Subject by ID
function getSubjectById($subjectId, $conn) {
   $sql = "SELECT * FROM subjects WHERE subject_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$subjectId]);

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
}

?>
