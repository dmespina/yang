<?php 
session_start();
if (isset($_SESSION['student_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Student') {
        include "../DB_connection.php";
        include "data/student.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";

        $student_id = $_SESSION['student_id'];
        $student = getStudentById($student_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student - Home</title>
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
            background: #2d3436;
            color: #fff;
            min-height: 100vh;
            width: 250px;
            transition: transform 0.3s ease;
            transform: translateX(0);
            position: fixed;
            left: 0;
        }

        #sidebar.hidden {
            transform: translateX(-100%);
        }

        .sidebar a {
            color: #b2bec3;
            transition: color 0.2s ease, background-color 0.2s ease;
        }

        .sidebar a:hover {
            background-color: #bdc3c7;
            color: #2d3436;
        }

        .sidebar a.active {
            background-color: #007bff;
            color: #fff;
        }

        .nav-item {
            margin-bottom: 0.5rem;
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

        .card-title {
            text-align: center;
            font-size: 1.5rem; /* Adjust the size if necessary */
            font-weight: 600;
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

        .login-title {
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
        }

        /* New styles for profile and username */
        .profile-container {
            display: flex;
            align-items: center;
        }
        
        .profile-container img {
            border-radius: 50%;
            width: 40px;
            height: 40px;
            object-fit: cover;
        }
        
        .profile-container .username {
            margin-left: 10px; /* Adjust this value to reduce the space between the picture and the username */
            font-weight: 600;
            color: #fff; /* Change text color to white */
            cursor: pointer;
            font-family: 'Poppins', sans-serif; /* Apply Poppins font */
        }

        .dropdown-menu {
            min-width: 150px;
        }

        /* Adjustments for card */
        .card-body {
            padding: 1.25rem;
        }

        .card-body h5 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
        }

        /* Adjustments for header layout */
        .header-content {
            display: flex;
            align-items: center;
            margin-left: 5px;
        }

        .navbar-title {
            margin-left: 10px;
        }

        /* New table styling */
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        td {
            background-color: #fff;
        }

        tr:nth-child(even) td {
            background-color: #f2f2f2;
        }

        tr:hover td {
            background-color: #e9ecef;
        }
    </style>
</head>
<body>
<header class="navbar navbar-expand-lg navbar-light shadow-sm">
    <div class="container-fluid">
        <div class="d-flex align-items-center">
            <button class="btn btn-light me-3" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="#">
                SIMS: Rangayen High School
            </a>
        </div>
        <!-- Updated profile dropdown section -->
        <div class="profile-container d-flex align-items-center ms-auto">
            <img src="../img/student-<?=$student['gender']?>.png" alt="Profile Picture">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle username" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?=$student['username']?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profile.php"><i class="bi bi-person"></i> Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-warning" href="../logout.php">
                            <i class="bi bi-box-arrow-right"></i> Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<div class="d-flex">
    <!-- Sidebar -->
    <nav id="sidebar" class="bg-dark p-3 sidebar">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white active"><i class="bi bi-house-door"></i> Home</a></li>
            <li class="nav-item mb-2"><a href="grade.php" class="nav-link text-white"><i class="bi bi-bar-chart-steps"></i> Grades</a></li>
            <li class="nav-item mb-2"><a href="subjects.php" class="nav-link text-white"><i class="bi bi-calendar"></i> Subjects</a></li>
            <li class="nav-item mb-2"><a href="profile.php" class="nav-link text-white"><i class="bi bi-person"></i> Profile</a></li>
            <li class="nav-item mt-auto">
                <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </li>
        </ul>
    </nav>

    <!-- Main Content -->
    <div class="flex-fill p-4" id="content" style="margin-left: 250px;">
        <div class="container mt-5">
            <?php if ($student != 0) { ?>
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Student Dashboard</h5>
                </div>
                <div class="row g-0">
                    <div class="col-md-12">
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>First name</th>
                                    <td><?=$student['fname']?></td>
                                </tr>
                                <tr>
                                    <th>Last name</th>
                                    <td><?=$student['lname']?></td>
                                </tr>
                                <tr>
                                    <th>Username</th>
                                    <td><?=$student['username']?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?=$student['address']?></td>
                                </tr>
                                <tr>
                                    <th>Date of birth</th>
                                    <td><?=$student['date_of_birth']?></td>
                                </tr>
                                <tr>
                                    <th>Email address</th>
                                    <td><?=$student['email_address']?></td>
                                </tr>
                                <tr>
                                    <th>Gender</th>
                                    <td><?=$student['gender']?></td>
                                </tr>
                                <tr>
                                    <th>Grade</th>
                                    <td>
                                        <?php 
                                            $grade = $student['grade'];
                                            $g = getGradeById($grade, $conn);
                                            echo $g['grade_code'].'-'.$g['grade'];
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Section</th>
                                    <td>
                                        <?php 
                                            $section = $student['section'];
                                            $s = getSectionById($section, $conn);
                                            echo $s['section'];
                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Parent first name</th>
                                    <td><?=$student['parent_fname']?></td>
                                </tr>
                                <tr>
                                    <th>Parent last name</th>
                                    <td><?=$student['parent_lname']?></td>
                                </tr>
                                <tr>
                                    <th>Parent phone number</th>
                                    <td><?=$student['parent_phone_number']?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <?php } else { ?>
            <div class="alert alert-danger" role="alert">
                Student information not found. <a href="home.php" class="alert-link">Go back to home</a>.
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.getElementById('sidebarToggle').addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('hidden');
        document.getElementById('content').classList.toggle('expanded'); // Adjust content margin
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
