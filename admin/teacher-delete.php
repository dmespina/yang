<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) &&
    isset($_GET['teacher_id'])) {

  if ($_SESSION['role'] == 'Admin') {
     include "../DB_connection.php";
     include "data/teacher.php";

     $id = $_GET['teacher_id'];
     $response = array();

     if (removeTeacher($id, $conn)) {
         $response['status'] = 'success';
         $response['message'] = "Successfully deleted!";
     } else {
         $response['status'] = 'error';
         $response['message'] = "Unknown error occurred";
     }

     echo json_encode($response);

  } else {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
  } 
} else {
  echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
  exit;
} 
?>