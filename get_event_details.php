<?php
include 'DB_connection.php';

if (isset($_GET['id'])) {
    $eventId = intval($_GET['id']);

    $query = "SELECT * FROM events WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $eventId, PDO::PARAM_INT);
    $stmt->execute();

    $event = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($event) {
        echo json_encode([
            'title' => htmlspecialchars($event['title']),
            'date' => date("F j, Y", strtotime($event['event_date'])),
            'description' => htmlspecialchars($event['description']),
            'image' => htmlspecialchars($event['images']) // Ensure this is a single image path
        ]);
    } else {
        echo json_encode(['error' => 'Event not found']);
    }
}
?>
