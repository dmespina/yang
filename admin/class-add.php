<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include '../DB_connection.php';
        include 'data/grade.php';
        include 'data/section.php';
        include 'data/subject.php';
        include 'data/teacher.php'; // Include teacher data

        $grades = getAllGrades($conn);
        $sections = getAllSections($conn);
        $subjects = getAllSubjects($conn);
        $teachers = getAllTeachers($conn); // Fetch teachers

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Classes</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Select2 for searchable dropdowns -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

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
        }les... */
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="#">SIMS: Rangayen High School</a>
            <div class="dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../img/admin.jpg" alt="profile" width="32" height="32" class="rounded-circle me-2">
                    <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>

    <!-- Sidebar -->
    <div class="d-flex">
        <nav id="sidebar" class="bg-dark p-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item mb-2">
                    <a href="index.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="bi bi-house-door"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="teacher.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'teacher.php' ? 'active' : ''; ?>">
                        <i class="bi bi-person-badge"></i> Teachers
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="student.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'student.php' ? 'active' : ''; ?>">
                        <i class="bi bi-mortarboard"></i> Students
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="class.php" class="nav-link active<?php echo basename($_SERVER['PHP_SELF']) == 'class.php' ? 'active' : ''; ?>">
                        <i class="bi bi-box"></i> Class
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="section.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'section.php' ? 'active' : ''; ?>">
                        <i class="bi bi-layout-sidebar"></i> Section
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="grade.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'grade.php' ? 'active' : ''; ?>">
                        <i class="bi bi-bar-chart-steps"></i> Grade
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="subject.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'subject.php' ? 'active' : ''; ?>">
                        <i class="bi bi-book"></i> Subjects
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="message.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'message.php' ? 'active' : ''; ?>">
                        <i class="bi bi-envelope"></i> Message
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="events.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'events.php' ? 'active' : ''; ?>">
                        <i class="bi bi-calendar-event"></i> Events
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="settings.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : ''; ?>">
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
    
  <!-- Main Content -->
<div class="container mt-5">
    <?php if ($sections == 0 || $grades == 0 || $subjects == 0 || $teachers == 0) { ?>
        <div class="alert alert-info" role="alert">
            First create section, grade, subject, and teacher.
        </div>
    <?php } else { ?>
        <form method="post" action="req/class-add.php">
            <h3>Add New Class</h3><hr>
            <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                    <?=$_GET['error']?>
                </div>
            <?php } ?>
            <?php if (isset($_GET['success'])) { ?>
                <div class="alert alert-success" role="alert">
                    <?=$_GET['success']?>
                </div>
            <?php } ?>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Subject</label>
                    <select name="subject" id="subject" class="form-control select2">
                        <option value="">Select Subject</option>
                        <?php foreach ($subjects as $subject) { ?>
                            <option value="<?=$subject['subject_id']?>"><?=$subject['subject']?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Grade</label>
                    <select name="grade" id="grade" class="form-control select2">
                        <option value="">Select Grade</option>
                        <?php foreach ($grades as $grade) { ?>
                            <option value="<?=$grade['grade_id']?>"><?=$grade['grade_code'].'-'.$grade['grade']?></option> 
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Section</label>
                    <select name="section" id="section" class="form-control select2">
                        <option value="">Select Section</option>
                        <?php foreach ($sections as $section) { ?>
                            <option value="<?=$section['section_id']?>"><?=$section['section']?></option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Teacher Name</label>
                    <select name="teacher" id="teacher" class="form-control select2">
                        <option value="">Select Teacher</option>
                        <?php foreach ($teachers as $teacher) { ?>
                            <option value="<?=$teacher['teacher_id']?>"><?=$teacher['fname']?> <?=$teacher['mname']?> <?=$teacher['lname']?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label class="form-label">Schedule</label>
                    <input type="text" name="schedule" id="schedule" class="form-control" placeholder="Type schedule" value="">
                </div>
            </div>
            
            <div class="row mb-3">
                <div class="col-md-12">
                    <label class="form-label">Weekday Schedule</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekdays[]" value="M" id="monday">
                        <label class="form-check-label" for="monday">Monday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekdays[]" value="T" id="tuesday">
                        <label class="form-check-label" for="tuesday">Tuesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekdays[]" value="W" id="wednesday">
                        <label class="form-check-label" for="wednesday">Wednesday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekdays[]" value="Th" id="thursday">
                        <label class="form-check-label" for="thursday">Thursday</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="weekdays[]" value="F" id="friday">
                        <label class="form-check-label" for="friday">Friday</label>
                    </div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary">Create</button>
        </form>
    <?php } ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
    <script>
        $(document).ready(function(){
            // Autocomplete for subject name
            var subjects = <?php echo json_encode(array_column($subjects, 'subject_code')); ?>;
            $("#subject_name").autocomplete({
                source: subjects
            });


            // Autocomplete for schedule
            var schedules = ["7:00 AM-8:00 AM","8:00 AM - 9:00 AM", "9:00 AM - 10:00 AM","10:00 AM - 11:00 AM","11:00 AM - 12:00 PM", "1:00 PM - 2:00 PM", "3:00 PM - 4:00 PM", "3:00 PM - 4:00 PM"]; // Add your schedules here
            $("#schedule").autocomplete({
                source: schedules
            });

            // Select2 for searchable dropdowns
            $('.select2').select2({
                width: '100%' // Ensures dropdown fits input width
            });
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
