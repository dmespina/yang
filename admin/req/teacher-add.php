<?php 
session_start();

// Check if the admin is logged in
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    // Include the necessary files
    include "../../DB_connection.php";

    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Get the form data and sanitize it
        $fname = htmlspecialchars($_POST['fname']);
        $mname = htmlspecialchars($_POST['mname']);
        $lname = htmlspecialchars($_POST['lname']);
        $address = htmlspecialchars($_POST['address']);
        $date_of_birth = htmlspecialchars($_POST['date_of_birth']);
        $employee_number = htmlspecialchars($_POST['employee_number']);
        $phone_number = htmlspecialchars($_POST['phone_number']);
        $qualification = htmlspecialchars($_POST['qualification']);
        $email = htmlspecialchars($_POST['email']);
        $gender = htmlspecialchars($_POST['gender']); // Capture the gender
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        // Hash the password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Prepare the SQL query with gender
        $sql = "INSERT INTO teachers (fname, mname, lname, address, date_of_birth, employee_number, phone_number, qualification, email_address, username, password, gender) 
                VALUES (:fname, :mname, :lname, :address, :date_of_birth, :employee_number, :phone_number, :qualification, :email, :username, :password, :gender)";

        $stmt = $conn->prepare($sql);

        // Bind the parameters
        $stmt->bindParam(':fname', $fname);
        $stmt->bindParam(':mname', $mname);
        $stmt->bindParam(':lname', $lname);
        $stmt->bindParam(':address', $address);
        $stmt->bindParam(':date_of_birth', $date_of_birth);
        $stmt->bindParam(':employee_number', $employee_number);
        $stmt->bindParam(':phone_number', $phone_number);
        $stmt->bindParam(':qualification', $qualification);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':gender', $gender); // Bind gender parameter
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);

        // Execute the statement
        if ($stmt->execute()) {
            // Return success response as JSON
            echo json_encode(['success' => true, 'message' => 'Teacher added successfully']);
        } else {
            // Return error response as JSON
            echo json_encode(['success' => false, 'message' => 'Failed to add teacher']);
        }
    } else {
        // Return error if request method is not POST
        echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    }

} else {
    // If not logged in as admin, redirect to the login page
    header("Location: ../login.php");
    exit;
}
