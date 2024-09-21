<?php 
session_start();
include '../DB_connection.php';

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    include "data/message.php";
    $messages = getAllMessages($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Messages</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    #sidebar {
        background: #2d3436;
        color: #fff;
        min-height: 100vh;
        transition: transform 0.3s ease, width 0.3s ease;
        width: 170px; /* Adjust the width of the sidebar */
    }

    #sidebar.collapsed {
        visibility: hidden;
        width: 60px; /* Width when collapsed */
    }

    .sidebar a {
        color: #b2bec3;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        opacity: 1; /* Default visibility */
        transition: opacity 0.3s ease; /* Smooth transition for opacity */
        padding: 10px; /* Padding for better click area */
        border-radius: 5px; /* Rounded corners */
        text-decoration: none; /* Remove underline */
    }
    
    /* Adjust space between sidebar icons and text */
    #sidebar .nav-link i {
        margin-right: 10px; /* Adjust this value to control the space between the icon and text */
    }

    /* Optional: Adjust text padding for better alignment */
    #sidebar .nav-link {
        padding-left: 10px; /* Ensure there's space on the left side of the text */
    }

    /* Sidebar link hover effect */
    .sidebar a:hover {
        background-color: gray; /* Light grey background on hover */
        color: #003366; /* Darker text color on hover */
        text-decoration: none; /* Remove underline on hover */
    }

    /* Active link styling */
    .nav-link.active {
        background-color: #007bff; /* Blue background for active link */
        color: white; /* White text color for active link */
        border-radius: 5px; /* Rounded corners for active link */
        font-weight: bold; /* Bold text for active link */
    }

    /* Hide text when sidebar is collapsed */
    #sidebar.collapsed .sidebar a span {
        opacity: 0; /* Hide the text when collapsed */
        visibility: hidden; /* Ensure it's completely hidden */
    }

    /* Center icons and adjust visibility when collapsed */
    #sidebar.collapsed .sidebar a {
        justify-content: center; /* Center icons when collapsed */
        opacity: 0.5; /* Reduce visibility when collapsed */
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

    /* Content area */
    .content {
        margin-left: 170px; /* Default margin when sidebar is expanded */
        margin-top: 10px;
        padding: 20px;
        transition: margin-left 0.3s ease;
    }

    .content.collapsed {
        margin-left: 60px; /* Adjusted margin when sidebar is collapsed */
        margin-right: 10px;
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

    .btn-primary {
        background-color: #6c5ce7;
        border-color: #6c5ce7;
        transition: background-color 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #4b4c72;
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
                <li class="nav-item mb-2"><a href="message.php" class="nav-link text-white active"><i class="bi bi-envelope"></i> Message</a></li>
                <li class="nav-item mb-2"><a href="events.php" class="nav-link text-white"><i class="bi bi-calendar-event"></i> Events</a></li>
                <li class="nav-item mb-2"><a href="settings.php" class="nav-link text-white"><i class="bi bi-gear"></i> Settings</a></li>
                <li class="nav-item mt-auto">
                     <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="flex-fill p-4" id="content">
            <div class="container">
                <h2 class="mb-4">Inbox</h2>

                <?php if ($messages) { ?>
                    <div class="accordion accordion-flush" id="accordionFlushExample">
                        <?php foreach ($messages as $message) { ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="flush-heading_<?=$message['message_id']?>">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse_<?=$message['message_id']?>" aria-expanded="false" aria-controls="flush-collapse_<?=$message['message_id']?>">
                                        <?=$message['sender_full_name']?>
                                    </button>
                                </h2>
                                <div id="flush-collapse_<?=$message['message_id']?>" class="accordion-collapse collapse" aria-labelledby="flush-heading_<?=$message['message_id']?>" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <?=$message['message']?>
                                        <div class="d-flex mb-3">
                                            <div class="p-2">Email: <b><?=$message['sender_email']?></b></div>
                                            <div class="ms-auto p-2">Date: <?=$message['date_time']?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } else { ?>
                    <div class="alert alert-info" role="alert">
                        No messages found!
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('d-none');
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
