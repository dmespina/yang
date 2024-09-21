<?php
session_start();
include '../DB_connection.php';

// Ensure the user is logged in as Teacher or Admin
if ((isset($_SESSION['teacher_id']) && $_SESSION['role'] == 'Teacher') || (isset($_SESSION['admin_id']) && $_SESSION['role'] == 'Admin')) {

    // Determine if the user is a Teacher or Admin
    $isTeacher = isset($_SESSION['teacher_id']) && $_SESSION['role'] == 'Teacher';
    $isAdmin = isset($_SESSION['admin_id']) && $_SESSION['role'] == 'Admin';

    if ($isTeacher) {
        try {
            $teacher_id = $_SESSION['teacher_id'];

            // Fetch the classes assigned to the teacher from the `class` table
           $query = "
                SELECT t.username, c.grade AS grade_id, c.section AS section_id, c.schedule_time, c.day, s.subject, s.subject_code, g.grade_code, g.grade, sec.section 
                FROM class AS c
                JOIN subjects AS s ON c.subject_id = s.subject_id
                JOIN grades AS g ON c.grade = g.grade_id
                JOIN section AS sec ON c.section = sec.section_id
                JOIN teachers AS t ON c.teachers = t.teacher_id
                WHERE c.teachers = :teacher_id
                ORDER BY g.grade_code, sec.section";

            $stmt = $conn->prepare($query);
            $stmt->bindParam(':teacher_id', $teacher_id, PDO::PARAM_INT);

            // Execute the query
            if (!$stmt->execute()) {
                $errorInfo = $stmt->errorInfo();
                echo "SQL Error: " . $errorInfo[2];
                exit;
            }

            // Fetch the results
            $classes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (empty($classes)) {
                echo "<p>No subjects found for this teacher.</p>";
            }
        } catch (PDOException $e) {
            echo "Query failed: " . $e->getMessage();
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $isTeacher ? 'Teacher - Classes' : 'Admin - Home'; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
        .sidebar {
            width: 250px; /* Adjust this width as needed */
            height: 100vh;
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
            font-size: 0.9rem; /* Adjust button font size */
            padding: 10px 20px; /* Adjust button padding */
            background: purple;
            border: none;
            color: #fff;
            transition: all 0.3s ease; /* Add transition for smooth effects */
            transform: scale(1); /* Initial state of the button */
        }
        
        .btn-primary:hover {
            background: linear-gradient(135.9deg, rgb(109, 25, 252) 16.4%, rgb(125, 31, 165) 56.1%);
            transform: scale(1.1); /* Enlarge the button slightly */
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3); /* Add shadow for a popping effect */
        }
        
        .btn-primary:active {
            transform: scale(0.9); /* Slightly shrink the button when clicked */
        }
       /* Assuming this class is applied to the container holding the <p> tag */
        .container {
            text-align: left; /* Aligns the text to the left side of the container */
            padding: 35px; /* Optional: Adds padding inside the container */
        }
        
        .card-text {
            margin: 0; /* Optional: Adjusts margin if needed */
        }

        .card-body {
            text-align: center; /* Center the text inside the card */
        }
        .card-title {
            font-size: 1.25rem; /* Adjust card title size */
            font-weight: 600; /* Bold font weight */
            background: linear-gradient(to right, rgb(5, 117, 230), rgb(2, 27, 121));
            color: #fff; /* Text color inside the box */
            padding: 10px; /* Add padding inside the box */
            border-radius: 5px; /* Round the corners of the box */
            display: block; /* Make the background cover the full width */
            width: calc(100% + 40px); /* Stretch the width beyond its container */
            box-sizing: border-box; /* Ensure padding is included in width calculation */
            margin-left: -20px; /* Move the title to the left */
            margin-right: -20px; /* Extend the title to the right */
            margin-top: -15px;
        }
        .class-container {
            text-align: left; /* Aligns the text to the left side of the container */
            padding: 10px; /* Optional: Adds padding inside the container */
        }
        
        .card-text {
            margin: 0; /* Optional: Adjusts margin if needed */
        }

        .btn-primary {
            font-size: 0.9rem; /* Adjust button font size */
            padding: 10px 20px; /* Adjust button padding */
        }
        .heading-container {
            display: flex;
            justify-content: center; /* Center the heading horizontally */
            margin-bottom: 20px; /* Add some spacing below the heading */
        }
        .heading-container h2 {
            font-size: 2rem; /* Adjust heading size */
            font-weight: 500; /* Adjust heading weight */
        }
        
        .profile-img {
            width: 40px; /* Adjust width as needed */
            height: 40px; /* Adjust height as needed */
            border-radius: 100%; /* To make the image circular */
            margin-right: 1px; /* Adjust spacing between image and text */
        }
        .icon-gradient {
        background: radial-gradient(circle at 74.2% 50.9%, rgb(14, 72, 222) 5.2%, rgb(3, 22, 65) 75.3%);
        -webkit-background-clip: text;
        color: transparent;
        display: inline-block;
        margin-right: 0.5rem;
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
<div class="dropdown ms-auto">
    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="../img/teacher-Female.png" class="profile-img">
        <?php
        // Display the username if it exists in the session
        if (isset($_SESSION['username'])) {
            echo '<span class="ms-2">' . $_SESSION['username'] . '</span>';
        }
        ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
        <li><a class="dropdown-item" href="index.php"><i class="bi bi-person"></i> View</a></li>
        <li><hr class="dropdown-divider"></li>
        <li>
            <a class="dropdown-item text-warning" href="../logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</div>
</header>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav id="sidebar" class="bg-dark p-3 sidebar">
            <ul class="nav flex-column">
                <li class="nav-item mb-2"><a href="classes.php" class="nav-link text-white active"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="pass.php" class="nav-link text-white"><i class="bi bi-lock"></i> Change Password</a></li>
                <li class="nav-item mt-auto">
                    <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </li>
            </ul>
        </nav>
        <!-- Main Content -->
    <!-- Teacher's Subject List -->
    <?php if ($isTeacher && !empty($classes)) { ?>
        <div class="container">
            <div class="heading-container">
                <h2>Teacher's Subjects</h2>
            </div>
            <div class="row">
                <?php foreach ($classes as $class) { ?>
                    <div class="col-md-4 mb-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($class['subject']) ?></h5>
                         <div class="class-container">
                                 <p class="card-text">
                                    <i class="bi bi-person icon-gradient"></i> <strong>Teacher:</strong> <?= htmlspecialchars($class['username']) ?><br>
                                    <i class="bi bi-star icon-gradient"></i> <strong>Grade:</strong> <?= htmlspecialchars($class['grade_code'] . ' ' . $class['grade']) ?><br>
                                    <i class="bi bi-house-door icon-gradient"></i> <strong>Section:</strong> <?= htmlspecialchars($class['section']) ?><br>
                                    <i class="bi bi-calendar icon-gradient"></i> <strong>Schedule:</strong> <?= htmlspecialchars($class['schedule_time']) ?><br>
                                    <i class="bi bi-calendar-week icon-gradient"></i> <strong>Day:</strong> <?= htmlspecialchars($class['day']) ?>
                                </p>

                            </div>
                                <form method="POST" action="view_students.php">
                                    <input type="hidden" name="subject" value="<?= htmlspecialchars($class['subject']) ?>">
                                    <input type="hidden" name="grade_year" value="<?= htmlspecialchars($class['grade_code'] . ' ' . $class['grade']) ?>">
                                    <input type="hidden" name="section" value="<?= htmlspecialchars($class['section']) ?>">
                                    <button type="submit" class="btn btn-primary mt-2">View Class</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="alert alert-info" role="alert">
            No subjects available.
        </div>
    <?php } ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Sidebar toggle functionality
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('d-none');
        });

        // Active link logic for the sidebar
        const currentLocation = location.href;
        const menuItem = document.querySelectorAll('.nav-link');
        const menuLength = menuItem.length;
        for (let i = 0; i < menuLength; i++) {
            if (menuItem[i].href === currentLocation) {
                menuItem[i].className += " active";
            }
        }
    </script>
<?php
} else {
    header("Location: ../index.php");
    exit;
}
?>