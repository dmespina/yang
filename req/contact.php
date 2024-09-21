<?php
session_start();
include "../DB_connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $full_name = $_POST['full_name'];
    $message = $_POST['message'];

    try {
        // Check if the email exists in students, teachers, or admin tables
        $stmt = $conn->prepare("
            SELECT * FROM students WHERE email_address = :email
            UNION
            SELECT * FROM teachers WHERE email_address = :email
        ");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            // Email exists, insert the message into the message table
            $insert_stmt = $conn->prepare("
                INSERT INTO message (sender_full_name, sender_email, message)
                VALUES (:full_name, :email, :message)
            ");
            $insert_stmt->bindParam(':full_name', $full_name, PDO::PARAM_STR);
            $insert_stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $insert_stmt->bindParam(':message', $message, PDO::PARAM_STR);
            $insert_stmt->execute();

            // Redirect with success message
            header("Location: ../contact.php?success=Your message has been sent successfully.");
        } else {
            // Email not found, return error
            header("Location: ../contact.php?error=Your email is not recognized as a registered user.");
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
    exit;
} else {
    header("Location: ../contact.php");
    exit;
}
