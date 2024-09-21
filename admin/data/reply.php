<?php

// Function to insert a reply into the database
function sendReply($admin_id, $message_id, $reply_text, $conn) {
    // Prepare an SQL statement to insert the reply into the database
    $stmt = $conn->prepare("INSERT INTO replies (admin_id, message_id, reply_text, date_time) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iis", $admin_id, $message_id, $reply_text);

    // Execute the statement
    if (!$stmt->execute()) {
        // Handle error (e.g., log the error, show an error message)
        die("Error: " . $stmt->error);
    }

    // Close the statement
    $stmt->close();
}

// Function to fetch replies for a specific message
function getRepliesByMessageId($message_id, $conn) {
    $stmt = $conn->prepare("SELECT r.*, a.username AS admin_username FROM replies r LEFT JOIN admins a ON r.admin_id = a.admin_id WHERE r.message_id = ?");
    $stmt->bind_param("i", $message_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $replies = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $replies;
}

?>
