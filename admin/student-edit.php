<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])     &&
    isset($_GET['student_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/subject.php";
       include "data/grade.php";
       include "data/student.php";
       include "data/section.php";
       $subjects = getAllSubjects($conn);
       $grades = getAllGrades($conn);
       $sections = getAllsections($conn);
       
       $student_id = $_GET['student_id'];
       $student = getStudentById($student_id, $conn);

       if ($student == 0) {
         header("Location: student.php");
         exit;
       }
 ?>
<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Student</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link href="../css/style.css" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../logo.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
        }

        .navbar {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            background: linear-gradient(45deg, #007bff, #6c5ce7);
            border-bottom: 3px solid #f1f1f1;
            padding-left: 10px; 
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
            position: fixed; /* Fixed positioning for the sidebar */
            height: 100%; /* Ensure the sidebar spans the full height */
            left: 0; /* Align the sidebar to the left */
            top: 0; /* Align the sidebar to the top */
            z-index: 1000; /* Ensure the sidebar is above other content */
            margin-top: 62px;
        }
        
        /* Sidebar collapsed state */
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

        /* Main content area */
        .content {
            margin-left: 170px; /* Ensure the content starts after the sidebar */
            margin-top: 10px;
            padding: 20px;
            transition: margin-left 0.3s ease;
        }
        
        /* Adjust margin when sidebar is collapsed */
        .content.collapsed {
            margin-left: 60px; /* Adjusted margin when collapsed */
        }
        
        /* Ensure full-width container adjustments */
        .container-fluid {
            padding-left: 0;
            padding-right: 0;
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
                /* Align the button and title horizontally */
        .d-flex {
            display: flex;
        }
        
        .justify-content-between {
            justify-content: space-between;
        }
        
        .align-items-center {
            align-items: center;
        }
        
        .mb-4 {
            margin-bottom: 1.5rem;
        }
        
        .btn-dark {
            background-color: #343a40;
            color: #fff;
        }
        
        .btn-dark:hover {
            background-color: #23272b;
        }
        .go-back-btn {
            margin-left: 120px; /* Adjust this value as needed */
            margin-top: 30px;
        }
        
        .edit-teacher-heading {
            margin-left: 120px; /* Adjust this value as needed */
            margin-top: 10px;
        }
        .form-container {
            margin-left: 120px; /* Adjust this value as needed */
            margin-top: 10px;
        }
        /* Style for Change Password heading */
        .change-password-heading {
            background-color: #6c5ce7; /* Matching the background color */
            color: white; /* Text color */
            padding: 10px; /* Padding for better appearance */
            border-radius: 5px; /* Rounded corners */
            margin-top: 5px; /* Space above the heading */
    
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
                    <img src="../img/admin.jpg" alt="profile" width="32" height="32" class="rounded-circle me-2">
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="settings.php">Settings</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="container-fluid">
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

  <div class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="student.php" class="btn btn-dark go-back-btn">Go Back</a>
    </div>
 <div class="mb-4 edit-teacher-heading">
            <h5>Edit Student Info</h5>
        </div>

    <div class="container mt-5 form-container">
        <form method="post" action="req/student-edit.php">
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

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['fname']) ?>" name="fname" required>
                </div>
                 <div class="col-md-6 mb-3">
                    <label class="form-label">Middle name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['mname']) ?>" name="mname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Last name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['lname']) ?>" name="lname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Address</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['address']) ?>" name="address" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email address</label>
                    <input type="email" class="form-control" value="<?= htmlspecialchars($student['email_address']) ?>" name="email_address" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of birth</label>
                    <input type="date" class="form-control" value="<?= htmlspecialchars($student['date_of_birth']) ?>" name="date_of_birth" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Gender</label>
                    <select class="form-select" name="gender" required>
                        <option value="" disabled>Select gender</option>
                        <option value="Male" <?= $student['gender'] == 'Male' ? 'selected' : '' ?>>Male</option>
                        <option value="Female" <?= $student['gender'] == 'Female' ? 'selected' : '' ?>>Female</option>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['username']) ?>" name="username" required>
                </div>

                <input type="text" value="<?= htmlspecialchars($student['student_id']) ?>" name="student_id" hidden>
<div class="col-md-6 mb-3">
    <label class="form-label">Grade</label>
    <select class="form-select" name="grade" required>
        <option value="" disabled>Select grade</option>
        <?php foreach ($grades as $grade){ ?>
            <option value="<?= htmlspecialchars($grade['grade_id']) ?>" <?= $student['grade'] == $grade['grade_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($grade['grade_code'].'-'.$grade['grade']) ?>
            </option>
        <?php } ?>
    </select>
</div>

<div class="col-md-6 mb-3">
    <label class="form-label">Section</label>
    <select class="form-select" name="section" required>
        <option value="" disabled>Select section</option>
        <?php foreach ($sections as $section){ ?>
            <option value="<?= htmlspecialchars($section['section_id']) ?>" <?= $student['section'] == $section['section_id'] ? 'selected' : '' ?>>
                <?= htmlspecialchars($section['section']) ?>
            </option>
        <?php } ?>
    </select>
</div>


                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent first name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['parent_fname']) ?>" name="parent_fname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent last name</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['parent_lname']) ?>" name="parent_lname" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Parent phone number</label>
                    <input type="text" class="form-control" value="<?= htmlspecialchars($student['parent_phone_number']) ?>" name="parent_phone_number" required>
                </div>
              <div class="mt-3 text-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </form>
    </div>

    <div class="container mt-5 form-container">
        <form method="post" class="p-4 rounded" action="req/student-change.php" id="change_password">
            <h3 class="text-center text-white p-3" style="background-color: #6c5ce7;">Change Password</h3>
            <hr>
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Admin password</label>
                    <input type="password" class="form-control" name="admin_pass" required>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">New password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="new_pass" id="passInput" required>
                        <button type="button" class="btn btn-outline-secondary" id="gBtn">
                            <i class="fas fa-random"></i>
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                            <i class="fas fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-12 mb-3">
                    <label class="form-label">Confirm new password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" name="c_new_pass" id="passInput2" required>
                        <button type="button" class="btn btn-outline-secondary" id="toggleConfirmPassword">
                            <i class="fas fa-eye" id="eyeIconConfirm"></i>
                        </button>
                    </div>
                </div>

                <input type="text" value="<?= htmlspecialchars($student['student_id']) ?>" name="student_id" hidden>

                <div class="col-md-12 text-center mt-4">
                    <button type="button" class="btn btn-primary btn-lg" id="confirmChangePassword">Change Password</button>
                </div>
            </div>
        </form>
    </div>
</div>

     
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>	
    <script>
        $(document).ready(function(){
             $("#navLinks li:nth-child(3) a").addClass('active');
        });

        function makePass(length) {
            var result           = '';
            var characters       = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for ( var i = 0; i < length; i++ ) {
              result += characters.charAt(Math.floor(Math.random() * 
         charactersLength));

           }
           var passInput = document.getElementById('passInput');
           var passInput2 = document.getElementById('passInput2');
           passInput.value = result;
           passInput2.value = result;
        }

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e){
          e.preventDefault();
          makePass(4);
        });
    </script>

</body>
</html>
<?php 

  }else {
    header("Location: student.php");
    exit;
  } 
}else {
	header("Location: student.php");
	exit;
} 

?>