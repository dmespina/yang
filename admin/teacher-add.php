<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] === 'Admin') {

    // Only include necessary files
    include "../DB_connection.php";

    // Sanitize GET parameters
    $fname = htmlspecialchars($_GET['fname'] ?? '');
    $mname = htmlspecialchars($_GET['mname'] ?? '');
    $lname = htmlspecialchars($_GET['lname'] ?? '');
    $uname = htmlspecialchars($_GET['uname'] ?? '');
    $address = htmlspecialchars($_GET['address'] ?? '');
    $en = htmlspecialchars($_GET['en'] ?? '');
    $pn = htmlspecialchars($_GET['pn'] ?? '');
    $qf = htmlspecialchars($_GET['qf'] ?? '');
    $email = htmlspecialchars($_GET['email'] ?? '');
    $password = htmlspecialchars($_GET['password'] ?? '');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Teacher</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
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
            transition: width 0.3s;
        }
        #sidebar.active {
            width: 80px;
        }
        .sidebar a {
            color: #b2bec3;
            transition: color 0.2s ease;
        }
        .sidebar a:hover {
            color: #fff;
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
        S
               .card {
            border: none;
            border-radius: 0; /* Remove border radius for a flat look */
            overflow: hidden;
            box-shadow: none; /* Remove box shadow */
        }
        
        .card-header {
            font-weight: 500;
            background: none; /* Remove background color for consistency */
            border-bottom: 2px solid #f1f1f1; /* Keep the border to distinguish the header */
            border-radius: 0;
        }
        
        .form-w {
            max-width: 1150px;
            margin: 0 auto;
            padding: 0; /* Remove padding to fit more seamlessly */
        }
        
        .shadow {
            box-shadow: none; /* Remove box shadow */
        }
        
        .form-control {
            box-shadow: none;
            border-color: #ced4da; /* Ensure border color is consistent */
        }
        
        .form-control:focus {
            border-color: #6c5ce7;
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.25);
        }
        
        .input-group .form-control {
            border-right: 0;
        }
        
        .input-group .btn {
            border-left: 0;
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
        .input-group .form-control {
            border-right: 0;
        }
        .input-group .btn {
            border-left: 0;
        }
        .form-label {
            font-weight: 500;
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
                <li class="nav-item mb-2"><a href="teacher.php" class="nav-link text-white  active"><i class="bi bi-person-badge"></i> Teachers</a></li>
                <li class="nav-item mb-2"><a href="student.php" class="nav-link text-white"><i class="bi bi-mortarboard"></i> Students</a></li>
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
            <main class="flex-fill p-4">
                <div class="container">
                    <div class="card shadow">
                        <div class="card-header">
                            <h5 class="card-title">Add New Teacher</h5>
                        </div>
                        <div class="card-body">
                            <!-- <a href="teacher.php" class="btn btn-dark mb-3">Go Back</a> -->
                            <form method="post" class="form-w" action="req/teacher-add.php">
                   <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">First Name</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($fname)?>" name="fname" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Last Name</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($lname)?>" name="lname" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Middle Name: (Type N/A if No Middle Name)</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($mname)?>" name="mname">
            </div>
            <!-- Address field next to Middle Name -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Address</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($address)?>" name="address" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Birth Date</label>
                <input type="date" id="dateInput" class="form-control" name="date_of_birth" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee Number</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($en)?>" name="employee_number" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Phone Number</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($pn)?>" name="phone_number" required>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Qualification</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($qf)?>" name="qualification" required>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?=htmlspecialchars($email)?>" name="email" required>
            </div>
            <!-- Gender Dropdown Field -->
            <div class="col-md-6 mb-3">
                <label class="form-label">Gender</label>
                <select class="form-select" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" value="<?=htmlspecialchars($uname)?>" name="username" required>
            </div>
            <div class="col-md-6 mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="password" name="password" required>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-secondary" id="generatePassword"><i class="bi bi-shuffle"></i></button>
                        <button type="button" class="btn btn-outline-secondary" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                    </div>
                </div>
            </div>
        </div>
                        <button type="submit" class="btn btn-primary">Add Teacher</button>
                    </form>
                </div>
            </div>
        </main>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.5/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.min.js"></script>

    <!-- SweetAlert notifications -->
    <script>
        <?php if ($successMessage): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: '<?= htmlspecialchars($successMessage) ?>',
                confirmButtonText: 'OK'
            });
        <?php elseif ($errorMessage): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '<?= htmlspecialchars($errorMessage) ?>',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>

        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordField = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                passwordField.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        });

        // Generate a random password
        document.getElementById('generatePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const newPassword = Math.random().toString(36).slice(-8);
            passwordInput.value = newPassword;
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