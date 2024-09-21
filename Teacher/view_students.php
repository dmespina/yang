<?php
session_start();
include '../DB_connection.php';



// Ensure the user is logged in as Teacher or Admin
if ((isset($_SESSION['teacher_id']) && $_SESSION['role'] == 'Teacher') || (isset($_SESSION['admin_id']) && $_SESSION['role'] == 'Admin')) {
    $isTeacher = isset($_SESSION['teacher_id']) && $_SESSION['role'] == 'Teacher';
    $isAdmin = isset($_SESSION['admin_id']) && $_SESSION['role'] == 'Admin';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['grade_year']) && isset($_POST['section']) && isset($_POST['subject'])) {
            $grade_year = trim($_POST['grade_year']);
            $section = trim($_POST['section']);
            $subject = trim($_POST['subject']);
            
            $combined_section = $grade_year . " " . $section;

            if (!empty($grade_year) && !empty($section)) {
                try {
                    // Prepare SQL query to fetch students based on grade and section
                    $student_query = "
                        SELECT student_id, fname, mname, lname 
                        FROM `students`
                        WHERE grade = 'grade_year' 
                        AND section = 'section'";

                    $stmt = $conn->prepare($student_query);
                    $stmt->bindParam(':grade_year', $grade_year, PDO::PARAM_STR);
                    $stmt->bindParam(':section', $section, PDO::PARAM_STR);
                    $stmt->execute();
                    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Function to handle grade insertion or update
                    function saveGrades($conn, $student_name, $subject, $combined_section, $quarter, $grade) {
                        $check_query = "
                            SELECT * FROM Student_grade 
                            WHERE student_name = :student_name 
                            AND subject_name = :subject 
                            AND section = :section";
                        
                        $check_stmt = $conn->prepare($check_query);
                        $check_stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
                        $check_stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
                        $check_stmt->bindParam(':section', $combined_section, PDO::PARAM_STR);
                        $check_stmt->execute();

                        if ($check_stmt->rowCount() > 0) {
                            // Update grades
                            $update_query = "
                                UPDATE Student_grade 
                                SET $quarter = :grade
                                WHERE student_name = :student_name 
                                AND subject_name = :subject 
                                AND section = :section";
                            
                            $update_stmt = $conn->prepare($update_query);
                            $update_stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
                            $update_stmt->bindParam(':section', $combined_section, PDO::PARAM_STR);
                            $update_stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
                            $update_stmt->bindParam(':grade', $grade, PDO::PARAM_STR);

                            $update_stmt->execute();
                        } else {
                            // Insert new grades
                            $insert_query = "
                                INSERT INTO Student_grade (student_name, subject_name, section, $quarter)
                                VALUES (:student_name, :subject, :section, :grade)";
                            
                            $insert_stmt = $conn->prepare($insert_query);
                            $insert_stmt->bindParam(':student_name', $student_name, PDO::PARAM_STR);
                            $insert_stmt->bindParam(':section', $combined_section, PDO::PARAM_STR);
                            $insert_stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
                            $insert_stmt->bindParam(':grade', $grade, PDO::PARAM_STR);

                            $insert_stmt->execute();
                        }
                    }

                    $quarters = [
                        'submit_q1' => 'q1',
                        'submit_q2' => 'q2',
                        'submit_q3' => 'q3',
                        'submit_q4' => 'q4',
                        'submit_final' => 'final_grade'
                    ];

                    foreach ($quarters as $button_name => $quarter) {
                        if (isset($_POST[$button_name])) {
                            foreach ($students as $student) {
                                $student_name = $student['fname'] . " " . $student['mname'] . " " . $student['lname'];
                                $grade = $_POST["{$quarter}_grade"][$student_name] ?? null;

                                if ($grade !== null) {
                                    saveGrades($conn, $student_name, $subject, $combined_section, $quarter, $grade);
                                }
                            }

                            $message = ucfirst(str_replace('_', ' ', $button_name)) . " grades have been successfully submitted!";
                            echo "<script type='text/javascript'>
                                document.addEventListener('DOMContentLoaded', function() {
                                    showAlert('$message');
                                });
                            </script>";
                        }
                    }

                } catch (PDOException $e) {
                    echo "Query failed: " . htmlspecialchars($e->getMessage());
                }
            } else {
                echo "Grade year and section cannot be empty.";
            }
        } else {
            echo "Required POST data is missing.";
        }
    }

    // Fetch grades to populate the form with previously submitted data
    if (isset($grade_year) && isset($section) && isset($subject)) {
        $grades_query = "
            SELECT student_name, q1, q2, q3, q4, final_grade 
            FROM Student_grade 
            WHERE subject_name = :subject 
            AND section = :section";
        
        $grades_stmt = $conn->prepare($grades_query);
        $grades_stmt->bindParam(':subject', $subject, PDO::PARAM_STR);
        $grades_stmt->bindParam(':section', $combined_section, PDO::PARAM_STR);
        $grades_stmt->execute();
        $grades = $grades_stmt->fetchAll(PDO::FETCH_ASSOC);
        $student_grades = [];
        foreach ($grades as $grade) {
            $student_grades[$grade['student_name']] = [
                'q1' => $grade['q1'],
                'q2' => $grade['q2'],
                'q3' => $grade['q3'],
                'q4' => $grade['q4'],
                'final_grade' => $grade['final_grade']
            ];
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students in <?= htmlspecialchars($grade_year) ?>, <?= htmlspecialchars($section) ?> <?= htmlspecialchars($subject) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            color: #333;
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
            width: 250px;
        }
        .sidebar a {
            color: #b2bec3;
            transition: color 0.2s ease, background-color 0.2s ease;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
        }
        
        .sidebar a:hover {
            color: #fff;
            background-color: #636e72; /* Hover background color */
        }
        
        .sidebar a.active {
            background-color: #6c5ce7; /* Active background color */
            color: #fff;
            font-weight: 600;
        }
               .sidebar .nav-link {
            white-space: nowrap; /* Prevents text from wrapping to a new line */
            overflow: hidden; /* Hides overflowed text */
            text-overflow: ellipsis; /* Adds ellipsis to overflowed text */
            color: #b2bec3;
            transition: color 0.2s ease, background-color 0.2s ease;
            border-radius: 10px;
            margin: 1px;
            padding: 15px;
        }
        .sidebar .nav-link i {
            margin-right: 10px; /* Space between icon and text */
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background-color: #495057;
            color: #f8f9fa;
        }
        .sidebar .nav-link.active {
            background-color: #007bff;
            color: #fff;
        }
        .card, .table {
            border-radius: 10px;
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        .card:hover, .table-hover tbody tr:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 25px rgba(0, 0, 0, 0.1);
        }
        .table th {
            background: #6c5ce7;
            color: #fff;
        }
        .btn-primary {
            background: linear-gradient(45deg, #007bff, #6c5ce7); /* Blue-Violet Gradient */
            border: none;
            color: #fff;
            font-weight: 500;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #0056b3, #5a3f77); /* Darker Gradient on Hover */
            animation: pop-in-out 0.6s ease-in-out;
        }
        @keyframes pop-in-out {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.1);
            }
            100% {
                transform: scale(1);
            }
            /* Subject section specific styles */
            .subject-section {
                background-color: #eaf6ff; /* Light blue background */
                border-radius: 10px;
                padding: 20px;
                margin-top: 20px;
                margin-left: auto; /* Center horizontally */
                margin-right: auto; /* Center horizontally */
                max-width: 80%; /* Adjust to your preferred width */
            }
            
            
            .subject-table thead {
                background-color: #007bff; /* Primary blue color */
                color: #fff;
            }
            
            .subject-table th, .subject-table td {
                text-align: center;
            }
            
            .subject-table input.form-control {
                border: 1px solid #007bff; /* Blue border for input fields */
                box-shadow: none;
                transition: border-color 0.3s ease;
            }
            
            .subject-table input.form-control:focus {
                border-color: #0056b3; /* Darker blue border on focus */
                box-shadow: none;
            }
            
            .btn-primary {
                background: linear-gradient(45deg, #007bff, #6c5ce7); /* Blue-Violet Gradient */
                border: none;
                color: #fff;
                font-weight: 500;
                transition: background-color 0.3s ease, transform 0.3s ease;
            }
            
            .btn-primary:hover {
                background: linear-gradient(45deg, #0056b3, #5a3f77); /* Darker Gradient on Hover */
                animation: pop-in-out 0.6s ease-in-out;
            }
            .custom-heading {
                text-align: center;
            }
            .student-name {
                font-weight: bold !important;
                font-size: 1.1rem !important;
            }
    </style>
</head>
<body>
      <div id="alertBox" class="alert alert-success alert-dismissible fade show" role="alert" style="display: none;">
              <span id="alertMessage"></span>
              <button type="button" class="btn-close" aria-label="Close" onclick="hideAlert()"></button>
        </div>
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
                    <img src="../profile.png" alt="profile" width="32" height="32" class="rounded-circle me-2">
                    <span><?php echo $_SESSION['username']; ?></span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </header>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark p-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="classes.php" class="nav-link text-white"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="view_student.php" class="nav-link text-white active"><i class="bi bi-person"></i> Students</a></li
                <li class="nav-item mb-2"><a href="pass.php" class="nav-link text-white"><i class="bi bi-lock"></i> Change Password</a></li>
                <li class="nav-item mt-auto">
                     <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </nav>
        
       
        
        <!-- Main Content -->
       <div class="container mt-4 subject-section" style="margin-left: 50px; margin-right: 50px;">
        <h2 class="text-center mb-4">Students in <?= htmlspecialchars($grade_year) ?> <?= htmlspecialchars($section) ?> (<?= htmlspecialchars($subject) ?>)</h2>
        <?php if (!empty($students)) { ?>
        <form method="POST" action="">
            <table class="table table-striped table-hover subject-table">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>1st Quarter</th>
                            <th>2nd Quarter</th>
                            <th>3rd Quarter</th>
                            <th>4th Quarter</th>
                            <th>Final Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student) {
                            $student_name = $student['fname'] . " " . $student['mname'] . " " . $student['lname'];
                            $q1_grade = $student_grades[$student_name]['q1'] ?? '';
                            $q2_grade = $student_grades[$student_name]['q2'] ?? '';
                            $q3_grade = $student_grades[$student_name]['q3'] ?? '';
                            $q4_grade = $student_grades[$student_name]['q4'] ?? '';
                            $final_grade = $student_grades[$student_name]['final_grade'] ?? '';
                        ?>
                            <tr>
                                <td class="student-name"><?= htmlspecialchars($student_name) ?></td>
                                <td><input type="number" class="form-control" name="q1_grade[<?= htmlspecialchars($student_name) ?>]" value="<?= htmlspecialchars($q1_grade) ?>" placeholder="Input grade"></td>
                                <td><input type="number" class="form-control" name="q2_grade[<?= htmlspecialchars($student_name) ?>]" value="<?= htmlspecialchars($q2_grade) ?>" placeholder="Input grade"></td>
                                <td><input type="number" class="form-control" name="q3_grade[<?= htmlspecialchars($student_name) ?>]" value="<?= htmlspecialchars($q3_grade) ?>" placeholder="Input grade"></td>
                                <td><input type="number" class="form-control" name="q4_grade[<?= htmlspecialchars($student_name) ?>]" value="<?= htmlspecialchars($q4_grade) ?>" placeholder="Input grade"></td>
                                <td><input type="number" class="form-control" name="final_grade[<?= htmlspecialchars($student_name) ?>]" value="<?= htmlspecialchars($final_grade) ?>" placeholder="Input grade"></td>
                            </tr>
                        <?php } ?>
                        <tr>
                            <td></td>
                            <td>
                                <button class="btn btn-primary w-100" type="submit" name="submit_q1">Submit 1st Quarter Grades</button>
                            </td>
                            <td>
                                <button class="btn btn-primary w-100" type="submit" name="submit_q2">Submit 2nd Quarter Grades</button>
                            </td>
                            <td>
                                <button class="btn btn-primary w-100" type="submit" name="submit_q3">Submit 3rd Quarter Grades</button>
                            </td>
                            <td>
                                <button class="btn btn-primary w-100" type="submit" name="submit_q4">Submit 4th Quarter Grades</button>
                            </td>
                            <td>
                                <button class="btn btn-primary w-100" type="submit" name="submit_final">Submit Final Grades</button>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <input type="hidden" name="grade_year" value="<?= htmlspecialchars($grade_year) ?>">
                                <input type="hidden" name="section" value="<?= htmlspecialchars($section) ?>">
                                <input type="hidden" name="subject" value="<?= htmlspecialchars($subject) ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </form>



        <?php } else { ?>
            <div class="alert alert-info" role="alert">
                No students found for this grade and section.
            </div>
        <?php } ?>
            </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        
         function showAlert(message) {
            var alertBox = document.getElementById('alertBox');
            var alertMessage = document.getElementById('alertMessage');

            alertMessage.innerText = message; // Set the alert message
            alertBox.style.display = 'block'; // Show the alert

            // Automatically hide the alert after 5 seconds
            setTimeout(hideAlert, 5000);
        }

        // Function to hide the alert
        function hideAlert() {
            var alertBox = document.getElementById('alertBox');
            alertBox.style.display = 'none';
        }
    
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('d-none');
        });

        // Active link logic for the sidebar
        const currentLocation = location.href;
        const menuItem = document.querySelectorAll('.nav-link');
        menuItem.forEach(item => {
            if (item.href === currentLocation) {
                item.classList.add("active");
            }
        });
    </script>
</body>
</html>
<?php
} else {
    header("Location: ../index.php");
    exit;
}
?>
