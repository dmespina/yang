<?php
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    include '../DB_connection.php';  // Include the database connection

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $title = $_POST['eventTitle'];
        $date = $_POST['eventDate'];
        $description = $_POST['eventDescription'];
        
        $images = [];
        if (isset($_FILES['eventImages'])) {
            foreach ($_FILES['eventImages']['tmp_name'] as $key => $tmp_name) {
                if ($_FILES['eventImages']['error'][$key] == UPLOAD_ERR_OK) {
                    $file_name = basename($_FILES['eventImages']['name'][$key]);
                    $file_path = '../uploads/' . $file_name;
                    move_uploaded_file($tmp_name, $file_path);
                    $images[] = $file_path;
                }
            }
        }
        
        $image_paths = implode(',', $images); // Convert image paths array to a string
        $query = "INSERT INTO events (title, event_date, description, images) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $title, $date, $description, $image_paths);
        $stmt->execute();
        $stmt->close();
        $conn->close();

        echo "<script>alert('Event added successfully!'); window.location.href = 'index.php';</script>";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Event</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Event</h1>
        <form action="events.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="eventTitle" class="form-label">Event Title</label>
                <input type="text" class="form-control" id="eventTitle" name="eventTitle" required>
            </div>
            <div class="mb-3">
                <label for="eventDate" class="form-label">Event Date</label>
                <input type="date" class="form-control" id="eventDate" name="eventDate" required>
            </div>
            <div class="mb-3">
                <label for="eventDescription" class="form-label">Event Description</label>
                <textarea class="form-control" id="eventDescription" name="eventDescription" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label for="eventImages" class="form-label">Event Images (multiple)</label>
                <input type="file" class="form-control" id="eventImages" name="eventImages[]" multiple>
            </div>
            <button type="submit" class="btn btn-primary">Add Event</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
    header("Location: ../login.php");
    exit;
}
?>
