<?php 
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Teacher') {
       include "../DB_connection.php";
       include "data/teacher.php";
       include "data/subject.php";
       include "data/grade.php";
       include "data/section.php";
       include "data/class.php";

       $teacher_id = $_SESSION['teacher_id'];
       $teacher = getTeacherById($teacher_id, $conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Home</title>
    <!-- Google Fonts: Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script> <!-- Bootstrap JS for dropdown functionality -->
    <style>
        /* Apply Poppins font to the sidebar and navbar */
        body, .header, .sidebar {
            font-family: 'Poppins', sans-serif;
        }

        .sidebar {
            height: 100%;
            width: 200px;
            position: fixed;
            top: 65px;
            left: 0;
            background-color: #343a40;
            padding-top: 10px;
            border-right: 2px solid #495057;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar .nav-link {
            color: white;
            padding: 10px 15px;
            font-size: 1.1rem;
            transition: background-color 0.2s ease-in-out, color 0.2s, border-radius 0.2s, margin 0.2s;
            border-radius: 10px;
            margin: 5px 15px;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #f8f9fa;
            border-radius: 10px;
            margin: 5px 15px;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            margin-left: 250px;
            margin-top: 60px;
            padding: 20px;
            background-color: #f8f9fa;
        }

        .navbar {
            background-color: #007bff;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .navbar .navbar-brand, 
        .navbar .profile-section,
        .navbar .dropdown-toggle {
            color: white !important;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-size: 1.5rem;
        }

        .profile-img {
            width: 35px;
            height: 35px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .teacher-info {
            display: none;
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
            <img src="../img/teacher-Female-<?=$teacher['gender']?>.png" alt="Profile Picture">
            <div class="dropdown">
                <button class="btn btn-link dropdown-toggle username" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <?=$teacher['username']?>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
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
    <div class="collapse show" id="sidebarToggle">
        <div class="sidebar">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="index.php">
                        <i class="fa fa-home"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="classes.php">
                        <i class="fa fa-user"></i> Classes 
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="subjects.php">
                        <i class="fa fa-lock"></i> Change Password
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php">
                        <i class="fa fa-sign-out"></i> Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
    
    <div class="main-content">
        <?php 
            if ($teacher != 0) {
        ?>
        <div class="container mt-5 teacher-info">
            <div class="card" style="width: 22rem;">
                <img src="../img/teacher-male<?=$teacher['gender']?>.png" class="card-img-top" alt="Teacher Image">
                <div class="card-body">
                    <h5 class="card-title text-center">@<?=$teacher['username']?></h5>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">First name: <?=$teacher['fname']?></li>
                    <li class="list-group-item">Last name: <?=$teacher['lname']?></li>
                    <li class="list-group-item">Username: <?=$teacher['username']?></li>
                    <li class="list-group-item">Employee number: <?=$teacher['employee_number']?></li>
                    <li class="list-group-item">Address: <?=$teacher['address']?></li>
                    <li class="list-group-item">Date of birth: <?=$teacher['date_of_birth']?></li>
                    <li class="list-group-item">Phone number: <?=$teacher['phone_number']?></li>
                    <li class="list-group-item">Qualification: <?=$teacher['qualification']?></li>
                    <li class="list-group-item">Email address: <?=$teacher['email_address']?></li>
                    <li class="list-group-item">Gender: <?=$teacher['gender']?></li>
                    <li class="list-group-item">Date of joined: <?=$teacher['date_of_joined']?></li>
                    <li class="list-group-item">Subject: 
                        <?php 
                            $s = '';
                            $subjects = str_split(trim($teacher['subjects']));
                            foreach ($subjects as $subject) {
                                $s_temp = getSubjectById($subject, $conn);
                                if ($s_temp != 0) 
                                    $s .= $s_temp['subject_code'] . ', ';
                            }
                            echo rtrim($s, ', ');
                        ?>
                    </li>
                    <li class="list-group-item">Class: 
                        <?php 
                            $c = '';
                            $classes = str_split(trim($teacher['class']));
                            foreach ($classes as $class_id) {
                                $class = getClassById($class_id, $conn);
                                $c_temp = getGradeById($class['grade'], $conn);
                                $section = getSectioById($class['section'], $conn);
                                if ($c_temp != 0) 
                                    $c .= $c_temp['grade_code'] . '-' . $section['section'] . ', ';
                            }
                            echo rtrim($c, ', ');
                        ?>
                    </li>
                </ul>
            </div>
        </div>
        <?php } ?>
    </div>

<script>
    document.getElementById('viewInfoBtn').addEventListener('click', function() {
        const info = document.querySelector('.teacher-info');
        if (info.style.display === 'none' || info.style.display === '') {
            info.style.display = 'block';
        } else {
            info.style.display = 'none';
        }
    });
</script>

</body>
</html>

<?php } else {
   header("Location: login.php");
   exit;
} } else {
   header("Location: login.php");
   exit;
} ?>
