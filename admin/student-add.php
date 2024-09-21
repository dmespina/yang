<?php 
session_start();
include '../DB_connection.php';
include 'data/grade.php';
include 'data/section.php';

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {

    try {
        $grades = getAllGrades($conn);
        $sections = getAllSections($conn);
    } catch (PDOException $e) {
        echo "Query failed: ". $e->getMessage();
        exit;
    }

    $fname = $lname = $mname = $address = $email = $phone = $gender = $grade_id = $section_id = '';
    $username = $password = $parent_fname = $parent_lname = $parent_phone = '';
    $error = $success = '';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $mname = $_POST['mname'];
        $address = $_POST['address'];
        $email = $_POST['email_address'];
        $gender = $_POST['gender'];
        $grade_id = $_POST['grade_id'];
        $section_id = $_POST['section_id'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $parent_fname = $_POST['parent_fname'];
        $parent_lname = $_POST['parent_lname'];
        $parent_phone = $_POST['parent_phone_number'];

        // Validate inputs
        if (empty($fname) || empty($lname) || empty($mname) || empty($address) || empty($email) || empty($gender) || empty($grade_id) || empty($section_id) || empty($username) || empty($password) || empty($parent_fname) || empty($parent_lname) || empty($parent_phone)) {
            $error = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Invalid email format.";
        } else {
            try {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                // Insert into database
                $stmt = $conn->prepare("INSERT INTO students (fname, lname, mname, address, email_address, gender, grade, section, username, password, parent_fname, parent_lname, parent_phone_number) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->execute([$fname, $lname, $mname, $address, $email, $gender, $grade_id, $section_id, $username, $hashed_password, $parent_fname, $parent_lname, $parent_phone]);

                $success = "Student added successfully.";
                
                // Clear form values after successful submission
                $fname = $lname = $mname = $address = $email = $phone = $gender = $grade_id = $section_id = '';
                $username = $password = $parent_fname = $parent_lname = $parent_phone = '';

            } catch (PDOException $e) {
                $error = "Error adding student: " . $e->getMessage();
            }
        }
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
            background: #2d3436; /* Dark gray background */
            color: #fff; /* White text color */
            min-height: 100vh; /* Full viewport height */
            width: 200px; /* Default expanded width */
            transition: width 0.3s, opacity 0.3s, transform 0.3s ease; /* Smooth transition for width, opacity, and transform */
        }
        
        #sidebar.collapsed {
            width: 80px; /* Collapsed width */
            opacity: 0.8; /* Slight opacity when collapsed */
            visibility: hidden; /* Hide text when collapsed */
            transform: translateX(-120px); /* Adjust for visibility */
        }
        
        /* Style for links */
        #sidebar a {
            color: #b2bec3; /* Light gray text color */
            display: flex; /* Align items flexibly */
            align-items: center; /* Center icon and text vertically */
            padding: 10px 15px; /* Padding for better spacing */
            border-radius: 5px;
            text-decoration: none;
            transition: color 0.2s ease, background-color 0.2s ease; /* Smooth transitions */
            opacity: 1; /* Full visibility by default */
        }
        
        #sidebar .nav-link i {
            margin-right: 10px; /* Space between icon and text */
        }
        
        #sidebar a.active {
            background-color: #007bff; /* Blue background for active link */
            color: white; /* Keep text color white for active link */
            font-weight: bold; /* Bold text for active link */
        }
        
        
        #sidebar a:hover {
            background-color: #7f8c8d; /* Gray background color on hover */
            color: #fff; /* White text color on hover */
        }
        
        /* Adjustments for responsive design */
        @media (max-width: 768px) {
            #sidebar {
                width: 0; /* Hide sidebar by default on mobile */
                opacity: 0; /* Fully transparent */
            }
        
            #sidebar.collapsed {
                width: 250px; /* Expanded width for active state */
                opacity: 1; /* Fully visible when active */
            }
        
            .sidebar-toggle-btn {
                display: block; /* Show the toggle button on mobile */
            }
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
                    <img src="../img/admin.png" alt="profile" width="32" height="32" class="rounded-circle me-2">
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

        <!-- Main content -->
        <div class="content p-4 w-100">
            <div class="card">
                <div class="card-header text-white">
                    <h5 class="mb-0">Add New Student</h5>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    <form method="post" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fname" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="fname" name="fname" value="<?php echo htmlspecialchars($fname); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lname" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="lname" name="lname" value="<?php echo htmlspecialchars($lname); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="mname" class="form-label">Middle Name: (<i>Type N/A if No Middle Name</i>)</label>
                    <input type="text" class="form-control" id="mname" name="mname" value="<?=htmlspecialchars($mname)?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address" value="<?php echo htmlspecialchars($address); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email_address" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email_address" name="email_address" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="gender" class="form-label">Gender</label>
                    <select class="form-select" id="gender" name="gender" required>
                        <option value="">Choose...</option>
                        <option value="Male" <?php echo ($gender == 'Male') ? 'selected' : ''; ?>>Male</option>
                        <option value="Female" <?php echo ($gender == 'Female') ? 'selected' : ''; ?>>Female</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="dob" class="form-label">Date of Birth</label>
                    <input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="grade_id" class="form-label">Grade</label>
                    <select class="form-select" id="grade_id" name="grade_id" required>
                        <option value="">Choose...</option>
                        <?php foreach ($grades as $grade): ?>
                            <option value="<?php echo $grade['grade_code']; ?>-<?php echo $grade['grade']; ?>" <?php echo ($grade_id == $grade['grade_code']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($grade['grade_code']); ?>-<?php echo htmlspecialchars($grade['grade']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="section_id" class="form-label">Section</label>
                    <select class="form-select" id="section_id" name="section_id" required>
                        <option value="">Choose...</option>
                        <?php foreach ($sections as $section): ?>
                            <option value="<?php echo $section['section']; ?>" <?php echo ($section_id == $section['section']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($section['section']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="parent_phone_number" class="form-label">Parent Phone Number</label>
                    <input type="text" class="form-control" id="parent_phone_number" name="parent_phone_number" value="<?php echo htmlspecialchars($parent_phone); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="parent_fname" class="form-label">Parent First Name</label>
                    <input type="text" class="form-control" id="parent_fname" name="parent_fname" value="<?php echo htmlspecialchars($parent_fname); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="parent_lname" class="form-label">Parent Last Name</label>
                    <input type="text" class="form-control" id="parent_lname" name="parent_lname" value="<?php echo htmlspecialchars($parent_lname); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control" id="password" name="password" value="<?php echo htmlspecialchars($password); ?>" required>
                        <div class="btn-group">
                            <button type="button" class="btn btn-outline-secondary" id="generatePassword"><i class="bi bi-shuffle"></i></button>
                            <button type="button" class="btn btn-outline-secondary" id="togglePassword"><i class="bi bi-eye-slash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 text-end">
                <button type="submit" class="btn btn-primary">Add Student</button>
            </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    sidebar.classList.toggle('collapsed');
}
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('active');
        });

        // Toggle password visibility (existing)
        document.getElementById('togglePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });

        // Generate random password (existing)
        document.getElementById('generatePassword').addEventListener('click', function () {
            const passwordInput = document.getElementById('password');
            const newPassword = Math.random().toString(36).slice(-8);
            passwordInput.value = newPassword;
        });

        // Highlight active sidebar link (new)
        document.querySelectorAll('#sidebar a').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            }
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
