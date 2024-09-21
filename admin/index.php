<?php
session_start();
include '../DB_connection.php';

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    try {
        // Fetch total number of students
        $total_students_query = "SELECT COUNT(*) as total_students FROM students";
        $stmt = $conn->query($total_students_query);
        $total_students = $stmt->fetch(PDO::FETCH_ASSOC)['total_students'];

        // Fetch total students per grade level
        $students_per_grade_query = "SELECT grade, section, COUNT(*) as total_students FROM students GROUP BY grade, section";
        $stmt2 = $conn->query($students_per_grade_query);
        $students_per_grade = $stmt2->fetchAll(PDO::FETCH_ASSOC);

    } catch (PDOException $e) {
        echo "Query failed: " . $e->getMessage();
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Home</title>
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
            min-height: 100vh;
            transition: transform 0.3s ease, width 0.3s ease;
            width: 170px; /* Adjust the width of the sidebar */
        }

        #sidebar.collapsed {
            visibility: hidden;
            width: 60px; /* Width when collapsed */
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
        /* Adjust space between sidebar icons and text */
        #sidebar .nav-link i {
            margin-right: 10px; /* Adjust this value to control the space between the icon and text */
        }
        
        /* Optional: Adjust text padding for better alignment */
        #sidebar .nav-link {
            padding-left: 10px; /* Ensure there's space on the left side of the text */
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
            margin-left: 50px;
            margin-top: 10px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 10px; /* Adjusted margin when collapsed */
            margin-right: 10px;
        }

        .card {
            margin-top: 20px;
            border: none;
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .card-header {
            background: linear-gradient(135deg, #003366, #00BFFF); /* Gradient background */
            text-align: center;
            color: white; 
            font-size: 1.25rem;
            border-bottom: none;
            border-radius: 10px 10px 0 0;
        }

        .card-body {
            background-color: white;
            height: 100px;
            color: #333; /* Dark gray text */
            padding: 10px;
            text-align: center;
        }

        .card-title {
            font-size: 1.35rem;
            font-weight: bold;
            text-align: center;
        }

        .card-text {
            font-size: 1.5rem; /* Adjusted font size */
            font-weight: bold;
        }
        .btn-custom {
            background-color: #00BFFF; /* Light Blue */
            color: white;
            border: none;
        }

        .btn-custom:hover {
            background-color: #003366; /* Darker Blue */
            color: white;
        }

        .modal-content {
            background-color: white;
            color: #333; /* Dark gray text */
        }

        .modal-header {
            border-bottom: none;
            padding: 7px;
        }

        .modal-footer .btn-secondary {
            background-color: #003366; /* Dark Blue */
            color: white;
        }

        .modal-footer .btn-primary {
            background-color: #00BFFF; /* Light Blue */
            color: white;
        }

        .modal-footer .btn-primary:hover {
            background-color: #87CEFA; /* Sky Blue */
        }

        .analytics-section {
            color: black;
            margin-left: 0; /* Remove default margin */
            padding-left: 0; /* Remove left padding */
        }

        /* Custom button style for the three-line icon */
        .menu-icon {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 24px;
            height: 18px;
        }

        .menu-icon .line {
            height: 3px;
            width: 100%;
            background-color: #333;
        }

        .menu-icon:hover {
            background-color: transparent; /* Remove the background color on hover */
        }

        .line {
            width: 20px; /* Adjust width to fit the icon size */
            height: 3px; /* Thickness of each line */
            background-color: #333; /* Dark gray lines */
            border-radius: 2px; /* Slightly rounded edges for a smoother look */
            margin: 3px 0; /* Add spacing between lines */
            transition: background-color 0.3s ease; /* Smooth transition for color change */
        }

        .menu-icon:hover .line {
            background-color: #777; /* Change lines to gray on hover */
        }

        .profile-img {
            width: 40px; /* Adjust width as needed */
            height: 40px; /* Adjust height as needed */
            border-radius: 100%; /* To make the image circular */
            margin-right: 1px; /* Adjust spacing between image and text */
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
                    <img src="../img/admin.jpg" class="profile-img">
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
                <li class="nav-item mb-2">
                    <a href="index.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="teacher.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'teacher.php' ? 'active' : ''; ?>">
                        <i class="bi bi-person-badge"></i> Teachers
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="student.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'student.php' ? 'active' : ''; ?>">
                        <i class="bi bi-mortarboard"></i> Students
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="class.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'class.php' ? 'active' : ''; ?>">
                        <i class="bi bi-box"></i> Class
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="section.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'section.php' ? 'active' : ''; ?>">
                        <i class="bi bi-layout-sidebar"></i> Section
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="grade.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'grade.php' ? 'active' : ''; ?>">
                        <i class="bi bi-bar-chart-steps"></i> Grade
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="subject.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'subject.php' ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> Subjects
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="message.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'message.php' ? 'active' : ''; ?>">
                        <i class="bi bi-envelope"></i> Message
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="events.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>">
                        <i class="bi bi-calendar-event"></i> Events
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="settings.php" class="nav-link text-white <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
                <li class="nav-item mt-auto">
                    <a href="../logout.php" class="nav-link text-warning">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                </li>
            </ul>
        </nav>

        <!-- Main content -->
        <div class="content">
            <div class="dashboard-analytics">
                <h5 class="card-title">DASHBOARD ANALYTICS</h5>
                <div class="analytics-section row">
                    <!-- Total Students -->
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card shadow">
                            <div class="card-header">
                                Total Students
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">Total</h5>
                                <p class="card-text"><?php echo $total_students; ?></p>
                            </div>
                        </div>
                    </div>
        
                    <!-- Students Per Grade Level -->
                    <?php foreach ($students_per_grade as $grade_data) { ?>
                        <div class="col-md-6 col-lg-3 mb-3">
                            <div class="card shadow">
                                <div class="card-header">
                                    <?php echo $grade_data['grade']; ?> - <?php echo $grade_data['section']; ?>
                                </div>
                                <div class="card-body">
                                    <p class="card-text"><?php echo $grade_data['total_students']; ?></p>
                                    <h5 class="card-title">Students</h5>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            // Toggle the collapsed class on the sidebar
            document.getElementById('sidebar').classList.toggle('collapsed');
            document.querySelector('.content').classList.toggle('collapsed');
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
} else {
    header("Location: login.php");
    exit;
}
?>
