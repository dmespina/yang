<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";

          // Function to fetch all students sorted by last name
          function getAllStudents($conn) {
            $query = "SELECT * FROM students ORDER BY lname ASC";
            $stmt = $conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Function to fetch students based on search query
        function searchStudents($searchKey, $conn) {
            $query = "SELECT * FROM students WHERE lname LIKE :searchKey OR fname LIKE :searchKey OR mname LIKE :searchKey ORDER BY lname ASC";
            $stmt = $conn->prepare($query);
            $searchKey = "%$searchKey%";
            $stmt->bindParam(':searchKey', $searchKey, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // Function to fetch grade and section by student ID
        function getGradeById($studentId, $conn) {
            $query = "SELECT lname, grade, section FROM students WHERE student_id = :student_id LIMIT 1";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':student_id', $studentId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }

        // Check if the search form is submitted
        if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {
            $searchKey = $_GET['searchKey'];
            $students = searchStudents($searchKey, $conn);
        } else {
            // Fetch all students if no search key is provided
            $students = getAllStudents($conn);
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Students</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
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
        width: 100px; /* Width when collapsed */
    }
    
    /* Style for links */
    #sidebar a {
        color: #333;
        transition: color 0.2s ease;
        display: flex;
        align-items: center;
        opacity: 1;
        padding: 10px;
        border-radius: 5px;
        text-decoration: none;
    }
    
    #sidebar .nav-link i {
        margin-right: 10px; /* Adjust this value to control the space between the icon and text */
    }
    
    #sidebar .nav-link {
        padding-left: 10px; /* Ensure there's space on the left side of the text */
    }
    
    /* Active link styling */
    #sidebar a.active {
        background-color: #007bff; /* Blue background for active link */
        color: white; /* Keep text color white for active link */
        font-weight: bold; /* Bold text for active link */
    }
    
    /* Hover effect for sidebar links */
    #sidebar a:hover {
        background-color: gray; /* Light grey background on hover */
        color: white; /* Ensure text color stays white on hover */
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
    
    /* Main Content Adjustments */
    #content {
        transition: margin-left 0.3s ease;
    }
    
    /* Adjust content margin when sidebar is collapsed */
    #sidebar.collapsed ~ #content {
        margin-left: -10px; /* Adjust the margin as needed */
    }
    
    .btn-dark {
        background-color: #6c5ce7;
        border-color: #6c5ce7;
        transition: background-color 0.3s ease;
    }
    
    .btn-dark:hover {
        background-color: #4b4c72;
    }
    
    .table-responsive {
        margin-top: 20px;
    }
    
    .table thead th {
        background-color: #6c5ce7;
        color: #fff;
    }
    
    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
    
    .alert-info {
        border-color: #d6d6d6;
        background-color: #f4f6f9;
        color: #333;
    }
    
    .card {
        border: none;
        border-radius: 10px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card-header {
        font-weight: 500;
        background: #6c5ce7;
        border-bottom: 2px solid #f1f1f1;
        border-radius: 0;
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
                <li class="nav-item mb-2"><a href="student.php" class="nav-link text-white active"><i class="bi bi-mortarboard"></i> Students</a></li>
                <li class="nav-item mb-2"><a href="class.php" class="nav-link text-white"><i class="bi bi-box"></i> Class</a></li>
                <li class="nav-item mb-2"><a href="section.php" class="nav-link text-white"><i class="bi bi-layout-sidebar"></i> Section</a></li>
                <li class="nav-item mb-2"><a href="grade.php" class="nav-link text-white"><i class="bi bi-bar-chart-steps"></i> Grade</a></li>
                <li class="nav-item mb-2"><a href="subject.php" class="nav-link text-white"><i class="bi bi-book"></i> Subjects</a></li>
                <li class="nav-item mb-2"><a href="message.php" class="nav-link text-white"><i class="bi bi-envelope"></i> Message</a></li>
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
            <h2 class="mb-4">Student Management</h2>

            <a href="student-add.php" class="btn btn-primary">Add New Student</a>
            <form action="student.php" class="mt-3" method="get">
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="searchKey" placeholder="Search by..Lastname/Firstname" value="<?php echo isset($searchKey) ? $searchKey : ''; ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>


                <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $_GET['error']; ?>
                    </div>
                <?php } ?>

                <?php if (isset($_GET['success'])) { ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $_GET['success']; ?>
                    </div>
                <?php } ?>

                <?php if (count($students) == 0) { ?>
                <div class="alert alert-info" role="alert">
                    No students found!
                </div>
            <?php } else { ?>
                <div class="card-body">
                    <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Last Name</th>
                                        <th scope="col">First Name</th>
                                        <th scope="col">Middle Name</th>
                                        <th scope="col">Username</th>
                                        <th scope="col">Grade</th>
                                        <th scope="col">Section</th>
                                        <th scope="col">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        <?php foreach ($students as $index => $student) { 
                                        $gradeSection = getGradeById($student['student_id'], $conn); ?>
                                        <tr>
                                            <th scope="row"><?php echo $index + 1; ?></th>
                                            <td><?php echo $student['lname']; ?></td>
                                            <td><?php echo $student['fname']; ?></td>
                                            <td><?php echo $student['mname']; ?></td>
                                            <td><?php echo $student['username']; ?></td>
                                            <td><?php echo $gradeSection['grade']; ?></td>
                                            <td><?php echo $gradeSection['section']; ?></td>
                                            <td>
                                                <a href="student-view.php?student_id=<?php echo $student['student_id']; ?>" class="btn btn-info btn-sm">View</a>
                                                <a href="student-edit.php?student_id=<?php echo $student['student_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="student-delete.php?student_id=<?php echo $student['student_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle Sidebar
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
    </script>
</body>
</html>

<?php 
    } else {
        header("Location: ../logout.php?error=unauthorized");
    }

} else {
    header("Location: ../logout.php");
} 
?>