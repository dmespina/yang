<?php  

// data/section.php

// data/section.php

function getAllSections($conn) {
  $sql = "SELECT * FROM section ORDER BY section ASC"; // Ensure the correct table name
  $stmt = $conn->prepare($sql);
  $stmt->execute();

  return $stmt->fetchAll(PDO::FETCH_ASSOC);
}




// Get Section by ID
function getSectioById($section_id, $conn){
   $sql = "SELECT * FROM section
           WHERE section_id=?";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$section_id]);

   if ($stmt->rowCount() == 1) {
     $section = $stmt->fetch();
     return $section;
   }else {
    return 0;
   }
}

// DELETE
function removeSection($id, $conn){
   $sql  = "DELETE FROM section
           WHERE section_id=?";
   $stmt = $conn->prepare($sql);
   $re   = $stmt->execute([$id]);
   if ($re) {
     return 1;
   }else {
    return 0;
   }
}