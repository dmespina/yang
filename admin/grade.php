<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    include "../DB_connection.php";
    include "data/grade.php";
    $grades = getAllGrades($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Grade</title>
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
            width: 40px; /* Width when collapsed */
        }

        /* Style for links */
        .sidebar a {
            color: white; /* Change font color to white */
            transition: color 0.2s ease;
            display: flex;
            align-items: center;
            opacity: 1;
            transition: opacity 0.3s ease;
            padding: 10px;
            border-radius: 5px;
            text-decoration: none;
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
            color: white; /* Keep text color white for active link */
            font-weight: bold; /* Bold text for active link */
        }

        /* Hover effect for sidebar links */
        .sidebar a:hover {
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

        .logout-item a {
            background-color: #FFCC00; /* Background color for logout button */
            color: #003366; /* Text color for logout button */
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

        .table tbody tr:hover {
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
                <li class="nav-item mb-2"><a href="grade.php" class="nav-link text-white active"><i class="bi bi-bar-chart-steps"></i> Grade</a></li>
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
        <h2 class="mb-4">Grade Management</h2>
        <!-- Button to trigger modal -->
        <button type="button" class="btn btn-dark mb-4" data-bs-toggle="modal" data-bs-target="#addGradeModal">
            Add New Grade
        </button>

        <!-- Error and success alerts using SweetAlert2 -->
        <?php if (isset($_GET['error'])) { ?>
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?= htmlspecialchars($_GET['error']) ?>',
                });
            </script>
        <?php } ?>

        <?php if (isset($_GET['success'])) { ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?= htmlspecialchars($_GET['success']) ?>',
                });
            </script>
        <?php } ?>

        <div class="table-responsive">
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Grade</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                        usort($grades, function($a, $b) {
                            return $a['grade'] - $b['grade'];
                        });
                        
                        $i = 0; 
                        foreach ($grades as $grade) { 
                            $i++;  
                    ?>
                    <tr>
                        <th scope="row"><?=$i?></th>
                        <td>
                            <?php echo htmlspecialchars($grade['grade_code'].'-'.$grade['grade'], ENT_QUOTES, 'UTF-8'); ?>
                        </td>
                        <td>
                            <a href="grade-edit.php?grade_id=<?=$grade['grade_id']?>" class="btn btn-warning">Edit</a>
                            <a href="#" onclick="confirmDelete(<?=$grade['grade_id']?>);" class="btn btn-danger">Delete</a>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>

        <?php if (empty($grades)) { ?>
            <div class="alert alert-info" role="alert">
                No grades found.
            </div>
        <?php } ?>
    </div>

    <!-- Modal for Adding New Grade -->
    <div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGradeModalLabel">Add New Grade</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" action="req/grade-add.php">
                        <div class="mb-3">
                            <label class="form-label">Grade Code</label>
                            <input type="text" class="form-control" name="grade_code" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" name="grade" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
     document.addEventListener('DOMContentLoaded', (event) => {
        const sidebar = document.getElementById('sidebar');
        const toggleButton = document.getElementById('sidebarToggle');

        toggleButton.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
        });
    });
        function confirmDelete(gradeId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'grade-delete.php?grade_id=' + gradeId;
                }
            });
        }
    </script>
</body>
</html>
<?php 
} else {
    header("Location: ../login.php");
    exit;
} 
?>