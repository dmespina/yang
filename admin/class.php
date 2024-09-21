
<?php
session_start();
require '../DB_connection.php'; // Include your database connection

// Fetch functions using PDO
function getSections($conn) {
    $query = "SELECT * FROM section";
    $stmt = $conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGrades($conn) {
    $query = "SELECT * FROM grades";
    $stmt = $conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function getSubjects($conn) {
    $query = "SELECT subject_id, subject FROM subjects"; // Adjusted query to only fetch required columns
    $stmt = $conn->prepare($query); // Use prepare for consistency
    $stmt->execute(); // Execute the prepared statement
    return $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all results as associative arrays
}

function getTeachers($conn) {
    $query = "SELECT * FROM teachers";
    $stmt = $conn->query($query);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getClasses($conn) {
    // Modify the query to include an ORDER BY clause for sorting by grade and section
    $query = "SELECT * FROM class ORDER BY grade ASC, section ASC"; // Sort first by grade, then by section
    $stmt = $conn->query($query);
    // Assuming you have a function to fetch all classes
$sql = "SELECT * FROM class"; // Make sure 'schedule' and 'day' are included in the fields fetched
$classes = $conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getGradeById($gradeId, $conn) {
    $query = "SELECT * FROM grades WHERE grade_id = :grade_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['grade_id' => $gradeId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSectionById($sectionId, $conn) {
    $query = "SELECT * FROM section WHERE section_id = :section_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['section_id' => $sectionId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getSubjectById($subjectId, $conn) {
    $query = "SELECT subject_id, subject FROM subjects WHERE subject_id = :subject_id"; // Query for a specific subject
    $stmt = $conn->prepare($query); // Prepare statement
    $stmt->bindParam(':subject_id', $subjectId, PDO::PARAM_INT); // Bind subject_id parameter
    $stmt->execute(); // Execute the statement
    return $stmt->fetch(PDO::FETCH_ASSOC); // Fetch a single result as an associative array
}

function getTeacherById($teacherId, $conn) {
    $query = "SELECT * FROM teachers WHERE teacher_id = :teacher_id";
    $stmt = $conn->prepare($query);
    $stmt->execute(['teacher_id' => $teacherId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['admin_id']) && isset($_SESSION['role']) && $_SESSION['role'] == 'Admin') {
    // Fetch necessary data
    $sections = getSections($conn);
    $grades = getGrades($conn);
    $classes = getClasses($conn);
    $subjects = getSubjects($conn); // Fetch all subjects before rendering the form
    $teachers = getTeachers($conn);
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
    <!-- Bootstrap Icons -->
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
        }
    </style>

</head>
<body>
    <!-- Header -->
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

    <div class="d-flex">
      <!-- Sidebar -->
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
                <a href="class.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'class.php' ? 'active' : ''; ?>">
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
<div class="flex-fill p-4" id="content">
    <div class="container">
        <h2 class="mb-4">Class Management</h2>

        <!-- Check if there are sections and grades -->
        <?php if (empty($sections) || empty($grades)) { ?>
            <div class="alert alert-info" role="alert">
                First create section and grade
            </div>
            <a href="class.php" class="btn btn-dark">Go Back</a>
        <?php } else { ?>
            <a href="class-add.php" class="btn btn-dark mb-3">Add New Class</a>
            
            <!-- Search Bar -->
            <form method="GET" action="class.php" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search classes..." aria-label="Search classes" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        <?php } ?>

        <div id="alert-placeholder"></div>

        <?php if (!empty($classes)) { ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Class</th>
                            <th scope="col">Subjects</th>
                            <th scope="col">Teachers</th>
                            <th scope="col">Schedule Time</th> <!-- New Column -->
                            <th scope="col">Day</th> <!-- New Column -->
                            <th scope="col">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 0; foreach ($classes as $class) { 
                            $i++;
                            // Fetch grade and section details
                            $grade = getGradeById($class['grade'], $conn);
                            $section = getSectionById($class['section'], $conn);
                            $subject = getSubjectById($class['subject_id'], $conn); // Fetch subject by subject ID
                            $teachers = getTeacherById($class['teachers'], $conn);
                        ?>
                        <tr>
                            <th scope="row"><?= htmlspecialchars($i) ?></th>
                            <td>
                                <?= htmlspecialchars($grade['grade_code'].'-'.$grade['grade'].' '.$section['section']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($subject['subject_code'] . '' . $subject['subject']) ?>
                            </td> 
                            <td>
                                <?= htmlspecialchars($teachers['username']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($class['schedule_time']) ?> <!-- Display Schedule Time -->
                            </td>
                            <td>
                                <?= htmlspecialchars($class['day']) ?> <!-- Display Day -->
                            </td>
                            <td>
                                <a href="class-edit.php?class_id=<?= htmlspecialchars($class['class_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="class-delete.php?class_id=<?= htmlspecialchars($class['class_id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this class?');">Delete</a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        <?php } else { ?>
            <div class="alert alert-info" role="alert">
                No classes found.
            </div>
        <?php } ?>
    </div>
</div>


    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar visibility
        document.getElementById('sidebarToggle').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });

        // Show Add Class Modal
        document.getElementById('addClassBtn').addEventListener('click', function() {
            var addClassModal = new bootstrap.Modal(document.getElementById('addClassModal'));
            addClassModal.show();
        });

        // Save Class Button click handler
        document.getElementById('saveClassBtn').addEventListener('click', function() {
            var gradeId = document.getElementById('gradeSelect').value;
            var sectionId = document.getElementById('sectionSelect').value;

            // AJAX request to add class
            fetch('req/class-add.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    grade: gradeId,
                    section: sectionId
                })
            }).then(response => response.json())
              .then(data => {
                  if (data.success) {
                      Swal.fire('Success!', 'Class added successfully!', 'success')
                          .then(() => location.reload());
                  } else {
                      Swal.fire('Error!', 'Failed to add class.', 'error');
                  }
              }).catch(() => {
                  Swal.fire('Error!', 'An unexpected error occurred.', 'error');
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
?>  