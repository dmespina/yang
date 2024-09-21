<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
        include '../DB_connection.php';
        include 'data/grade.php';
        $grades = getAllGrades($conn);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Subject</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
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
            width: 100px; /* Width when collapsed */
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
        .custom-modal-content {
            border-radius: 15px;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .sidebar-toggle-btn {
            display: none;
        }
        @media (max-width: 768px) {
            #sidebar {
                width: 0;
                display: none;
            }
            #sidebar.active {
                width: 250px;
                display: block;
            }
            .sidebar-toggle-btn {
                display: block;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light sidebar-toggle-btn me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="#">SIMS: Rangayen High School</a>
            <div class="dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../profile.png" alt="profile" width="32" height="32" class="rounded-circle me-2">
                    <span><?php echo $_SESSION['username']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php">Profile</a></li>
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
                <li class="nav-item mb-2"><a href="profile.php" class="nav-link text-white"><i class="bi bi-person-circle"></i> Profile</a></li>
                <li class="nav-item"><a href="../logout.php" class="nav-link text-white"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </nav>

        <!-- Main content -->
        <div class="content p-4 w-100">
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0">Add New Subject</h5>
                </div>
                <div class="card-body">
                    <?php if ($grades == 0) { ?>
                        <div class="alert alert-info" role="alert">
                            First create a grade.
                        </div>
                    <?php } else { ?>
                        <form method="post" class="shadow p-3 mt-5 form-w" action="req/subject-add.php">
                            <h3>Add New Subject</h3><hr>
                            <?php if (isset($_GET['error'])) { ?>
                                <div class="alert alert-danger" role="alert">
                                    <?= htmlspecialchars($_GET['error']) ?>
                                </div>
                            <?php } ?>
                            <?php if (isset($_GET['success'])) { ?>
                                <div class="alert alert-success" role="alert">
                                    <?= htmlspecialchars($_GET['success']) ?>
                                </div>
                            <?php } ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Subject Name</label>
                                <input type="text" class="form-control" name="subject_name">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Subject Code</label>
                                <input type="text" class="form-control" name="subject_code">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Grade</label>
                                <select name="grade" class="form-control">
                                    <?php foreach ($grades as $grade) { ?>
                                        <option value="<?= htmlspecialchars($grade['grade_id']) ?>">
                                            <?= htmlspecialchars($grade['grade_code'].'-'.$grade['grade']) ?>
                                        </option> 
                                    <?php } ?>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Create</button>
                        </form>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#sidebarToggle").click(function() {
                $("#sidebar").toggleClass("active");
            });
            $("#navLinks li:nth-child(8) a").addClass('active');
        });
    </script>
</body>
</html>
<?php 
    } else {
        header("Location: ../login.php");
        exit;
    } 
} else {
    header("Location: ../login.php");
    exit;
} 
?>
