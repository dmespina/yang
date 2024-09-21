<?php
session_start();
include '../DB_connection.php'; // This file should set $conn

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['eventTitle']) && isset($_POST['eventDate']) && isset($_POST['eventDescription'])) {
            $title = $_POST['eventTitle'];
            $date = $_POST['eventDate'];
            $description = $_POST['eventDescription'];
            $images = [];

            // Handle file uploads
            if (isset($_FILES['eventImage']) && $_FILES['eventImage']['error'][0] === UPLOAD_ERR_OK) {
                $uploadedFiles = $_FILES['eventImage'];
                $uploadDir = '../uploads/';

                foreach ($uploadedFiles['name'] as $key => $name) {
                    $tmpName = $uploadedFiles['tmp_name'][$key];
                    $uploadFile = $uploadDir . basename($name);

                    if (move_uploaded_file($tmpName, $uploadFile)) {
                        $images[] = $name;
                    }
                }
            }

            // Convert image names to a comma-separated string
            $imagesList = implode(',', $images);

            // Prepare SQL statement
            $sql = "INSERT INTO events (title, event_date, description, images) VALUES (:title, :date, :description, :images)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':images', $imagesList);

            // Execute SQL statement
            if ($stmt->execute()) {
                echo "<div class='alert alert-success'>Event added successfully!</div>";
            } else {
                echo "<div class='alert alert-danger'>Error adding event.</div>";
            }
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Event</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .navbar {
            background: linear-gradient(45deg, #007bff, #6c5ce7);
            border-bottom: 3px solid #f1f1f1;
        }

        .navbar-brand {
            font-weight: 600;
            color: #fff;
        }

     
        /* Sidebar styles */
        #sidebar {
            background: #f4f6f9;
            color: #333;
            font-family: 'Poppins', sans-serif;
            min-height: 170vh;
            transition: transform 0.3s ease, width 0.3s ease;
            width: 170px; /* Adjust the width of the sidebar */
        }

        #sidebar.collapsed {
            visibility: hidden;
            width: 100px; /* Width when collapsed */
        }
        
        /* Adjust space between sidebar icons and text */
        #sidebar .nav-link i {
            margin-right: 10px; /* Adjust this value to control the space between the icon and text */
        }
        
        /* Optional: Adjust text padding for better alignment */
        #sidebar .nav-link {
            padding-left: 10px; /* Ensure there's space on the left side of the text */
        }

        /* Style for links */
        .sidebar a {
            color: #333;
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            opacity: 1; /* Default visibility */
            transition: opacity 0.3s ease; /* Smooth transition for opacity */
            padding: 10px; /* Padding for better click area */
            border-radius: 5px; /* Rounded corners */
            text-decoration: none; /* Remove underline */
        }

        /* Active link styling */
        .sidebar a.active {
            background-color: #007bff; /* Blue background for active link */
            color: white; /* White text color for active link */
            font-weight: bold; /* Bold text for active link */
        }

        /* Hover effect for sidebar links */
        .sidebar a:hover {
            background-color: gray; /* Light grey background on hover */
            color: #003366; /* Darker text color on hover */
        }

        /* Hide text when sidebar is collapsed */
        #sidebar.collapsed .sidebar a span {
            opacity: 0; /* Hide the text when collapsed */
            visibility: hidden; /* Ensure it's completely hidden */
        }

        #sidebar.collapsed .sidebar a:hover {
            opacity: 1; /* Restore full visibility on hover */
        }

        /* Ensure logout button stays visible */
        .logout-item {
            display: flex; /* Ensure button is in flex container */
            justify-content: center; /* Center the logout button */
            margin-top: auto; /* Push logout button to the bottom */
        }

        #sidebar.collapsed .logout-item {
            justify-content: center; /* Center logout icon when collapsed */
        }

        .logout-item a {
            background-color: #FFCC00; /* Background color for logout button */
            color: #003366; /* Text color for logout button */
            transition: background-color 0.3s, color 0.3s; /* Smooth transition */
            border-radius: 5px; /* Rounded corners */
            padding: 10px; /* Padding around the icon */
            display: flex; /* Center the content */
            align-items: center; /* Vertically center the icon */
        }

        /* Ensure logout button icon remains visible when sidebar is collapsed */
        #sidebar.collapsed .logout-item a {
            background-color: #FFCC00; /* Background color remains */
            color: #003366; /* Text color remains */
            opacity: 1; /* Ensure button remains visible */
            visibility: visible; /* Ensure button remains visible */
            justify-content: center; /* Center icon in collapsed state */
        }

        /* Hover effect for logout button */
        .logout-item a:hover {
            background-color: #003366; /* Darker background color on hover */
            color: #FFCC00; /* Lighter text color on hover */
        }

        .card {
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            font-weight: 500;
            background: #6c5ce7;
            border-bottom: 2px solid #f1f1f1;
            border-radius: 0;
        }
        
        #sidebar.collapsed {
            width: 10px; /* Width when collapsed */
        }
        
        #content {
            margin-left: 10px; /* Match the default sidebar width */
        }
        
        #sidebar.collapsed + #content {
            margin-left: 10px; /* Adjust this to match the collapsed sidebar width */
        }
        
        .container {
            max-width: 100%; /* Make sure the form container takes full width */
            padding-left: 0;
            padding-right: 0;
        }
        
        h2 {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }

                .btn-dark {
            background-color: #6c5ce7;
            border-color: #6c5ce7;
            transition: background-color 0.3s ease;
        }

        .btn-dark:hover {
            background-color: #4b4c72;
        }

        .btn-warning {
            background-color: #f39c12;
            border-color: #f39c12;
        }

        .btn-warning:hover {
            background-color: #e67e22;
        }

        .btn-danger {
            background-color: #e74c3c;
            border-color: #e74c3c;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        .table-responsive {
            margin-top: none;
        }

        .table thead th {
            background-color: #6c5ce7;
            color: #fff;
        }

        .table tbody tr:hover 1
            background-color: #f1f1f1;
        }

        .modal-content {
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <!-- Header with collapse button, title, and profile -->
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <!-- Sidebar Toggle Button -->
            <button class="btn btn-light me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>

            <!-- Title -->
            <a class="navbar-brand" href="#">SIMS: Rangayen High School</a>

            <!-- Profile Section -->
            <div class="dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../img/admin.jpg" alt="profile" width="32" height="32" class="rounded-circle me-2">
                    <span><?php echo $_SESSION['username']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark p-3 sidebar">
    <ul class="nav flex-column">
        <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i class="bi bi-house-door"></i> Dashboard</a></li>
        <li class="nav-item mb-2"><a href="teacher.php" class="nav-link text-white"><i class="bi bi-person-badge"></i> Teachers</a></li>
        <li class="nav-item mb-2"><a href="student.php" class="nav-link text-white"><i class="bi bi-mortarboard"></i> Students</a></li>
        <li class="nav-item mb-2"><a href="class.php" class="nav-link text-white"><i class="bi bi-box"></i> Class</a></li>
        <li class="nav-item mb-2"><a href="section.php" class="nav-link text-white"><i class="bi bi-layout-sidebar"></i> Section</a></li>
        <li class="nav-item mb-2"><a href="grade.php" class="nav-link text-white"><i class="bi bi-bar-chart-steps"></i> Grade</a></li>
        <li class="nav-item mb-2"><a href="subject.php" class="nav-link text-white"><i class="bi bi-book"></i> Subjects</a></li>
        <li class="nav-item mb-2"><a href="message.php" class="nav-link text-white"><i class="bi bi-envelope"></i> Message</a></li>
        <li class="nav-item mb-2"><a href="events.php" class="nav-link text-white active"><i class="bi bi-calendar-event"></i> Events</a></li>
        <li class="nav-item mb-2"><a href="settings.php" class="nav-link text-white"><i class="bi bi-gear"></i> Settings</a></li>
        <li class="nav-item mt-auto">
             <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
    </ul>
</nav>

        <!-- Main Content -->
         <div class="flex-fill p-4" id="content">
        <div class="container">
            <h2 class="mb-4">Add New Event</h2>
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
                        <label for="eventImage" class="form-label">Event Images</label>
                        <input type="file" class="form-control" id="eventImage" name="eventImage[]" multiple>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Event</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    // Sidebar Toggle
    document.getElementById('sidebarToggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        const content = document.getElementById('content');

        // Toggle the collapsed class on the sidebar
        sidebar.classList.toggle('collapsed');

        // Adjust the margin of the content area based on the sidebar state
        if (sidebar.classList.contains('collapsed')) {
            content.style.marginLeft = '100px'; // Adjust this value to match the collapsed sidebar width
        } else {
            content.style.marginLeft = '170px'; // Adjust this value to match the expanded sidebar width
        }
    });
</script>


</body>
</html>

<?php
} else {
    header("Location: ../login.php");
    exit;
}
?>
