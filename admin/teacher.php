<?php 
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {


    if ($_SESSION['role'] == 'Admin') {
       include "../DB_connection.php";
       include "data/teacher.php";

    


       function searchTeacher($searchKey, $conn) {
        $query = "SELECT * FROM teachers WHERE lname LIKE :searchKey OR fname LIKE :searchKey OR mname LIKE :searchKey ORDER BY lname ASC";
        $stmt = $conn->prepare($query);
        $searchKey = "%$searchKey%";
        $stmt->bindParam(':searchKey', $searchKey, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    if (isset($_GET['searchKey']) && !empty($_GET['searchKey'])) {
        $searchKey = $_GET['searchKey'];
        $teachers = searchTeacher($searchKey, $conn);
    } else {
        // Fetch all students if no search key is provided
        $teachers = getAllTeachers($conn);
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Teachers</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
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
        .table thead th {
            background-color: #6c5ce7;
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
            width: 50px; /* Width when collapsed */
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

        /* Content area */
        .content {
            margin-left: 30px;
            margin-top: 10px;
            padding: 10px;
            transition: margin-left 0.3s ease;
        }

        .content.collapsed {
            margin-left: 5px; /* Adjusted margin when collapsed */
            margin-right: 5px;
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
    .header-custom-color {
        color: #6c5ce7;
    }
    </style>

</head>
<body>
    <!-- Navbar -->
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="#">SIMS: Rangayen High School</a>
            <div class="dropdown ms-auto">
                <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <img src="../img/admin.jpg" alt="profile" width="32" height="32" class="rounded-circle me-2">
                   <?php if (isset($_SESSION['fname'])) {
                        echo "<span>" . htmlspecialchars($_SESSION['fname'], ENT_QUOTES, 'UTF-8') . "</span>";
                    } else {
                        echo "<span>Guest</span>";
                    }
                    ?>
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
                <li class="nav-item mb-2"><a href="index.php" class="nav-link text-white"><i class="bi bi-house-door"></i> Dashboard</a></li>
                <li class="nav-item mb-2"><a href="teacher.php" class="nav-link text-white active"><i class="bi bi-person-badge"></i> Teachers</a></li>
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
        <div class="flex-fill p-4" id="content">
            <div class="container">
                <h2 class="mb-4">Teacher Management</h2>

                <a href="teacher-add.php" class="btn btn-primary">Add New Teacher</a>
                <form action="teacher.php" class="mt-3" method="get">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="searchKey" placeholder="Search..Lastname/Firstname" value="<?php echo isset($searchKey) ? $searchKey : ''; ?>">
                        <button class="btn btn-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <?php if ($teachers && count($teachers) > 0) { ?>
                <div class="table-responsive">
                    <table class="table table-hover mt-3">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col" class="col-lastname">Last Name</th>
                                <th scope="col">First Name</th>
                                <th scope="col">Username</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 0; foreach ($teachers as $teacher) { 
                                $i++;
                            ?>
                            <tr>
                                <th scope="row"><?= $i ?></th>
                                <td><?= htmlspecialchars($teacher['lname']) ?></td>
                                <td><?= htmlspecialchars($teacher['fname']) ?></td>
                                <td><?= htmlspecialchars($teacher['username']) ?></td>
                                  <td>
                                    <div>
                                        <a href="teacher-view.php?teacher_id=<?= htmlspecialchars($teacher['teacher_id']) ?>" class="btn btn-info btn-sm me-1">View</a>
                                        <a href="teacher-edit.php?teacher_id=<?= htmlspecialchars($teacher['teacher_id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="teacher-delete.php?teacher_id=<?= htmlspecialchars($teacher['teacher_id']) ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </div>
                                </td>
                            </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
        <!-- Edit Teacher Modal -->
        <div class="modal fade" id="editTeacherModal" tabindex="-1" aria-labelledby="editTeacherModalLabel" aria-hidden="true">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="editTeacherModalLabel">Edit Teacher</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                <form id="editTeacherForm">
                  <input type="hidden" id="teacherId" name="teacher_id">
                  <div class="mb-3">
                    <label for="editFirstName" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="editFirstName" name="fname">
                  </div>
                  <div class="mb-3">
                    <label for="editLastName" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="editLastName" name="lname">
                  </div>
                  <div class="mb-3">
                    <label for="editUsername" class="form-label">Username</label>
                    <input type="text" class="form-control" id="editUsername" name="username">
                  </div>
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Save changes</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
        
            document.addEventListener('DOMContentLoaded', () => {
                // Sidebar toggle functionality
                const sidebar = document.getElementById('sidebar');
                const sidebarToggle = document.getElementById('sidebarToggle');
                const content = document.getElementById('content');
        
                sidebarToggle.addEventListener('click', () => {
                    sidebar.classList.toggle('collapsed');
                    content.classList.toggle('collapsed');
                });

        // Update the modal with the teacher's information
        document.querySelectorAll('.edit-btn').forEach(button => {
            button.addEventListener('click', () => {
                const teacherId = button.getAttribute('data-teacher-id');
                fetch(`get-teacher.php?id=${teacherId}`)
                    .then(response => response.json())
                    .then(data => {
                        document.getElementById('teacherId').value = data.teacher_id;
                        document.getElementById('editFirstName').value = data.fname;
                        document.getElementById('editLastName').value = data.lname;
                        document.getElementById('editUsername').value = data.username;
                    })
                    .catch(error => console.error('Error fetching teacher data:', error));
            });
        });
         document.addEventListener('DOMContentLoaded', () => {
    // Delete confirmation using SweetAlert and AJAX
    document.querySelectorAll('.delete-btn').forEach(button => {
        button.addEventListener('click', function () {
            const teacherId = this.getAttribute('data-id'); // Get teacher ID from button's data-id attribute

            // Show SweetAlert confirmation dialog
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // If confirmed, make AJAX request to delete teacher
                    fetch(`teacher-delete.php?teacher_id=${teacherId}`, {
                        method: 'GET',
                    })
                        .then(response => response.json()) // Parse the JSON response
                        .then(data => {
                            if (data.status === 'success') {
                                // SweetAlert success message
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Teacher Deleted!',
                                    text: 'The teacher has been removed from the list successfully.',
                                    showConfirmButton: false,
                                    timer: 1500
                                }).then(() => {
                                    // After success, refresh the page or remove the row dynamically
                                    location.reload(); // Refresh the page to update the list
                                });
                            } else {
                                // Show error alert if the status is error
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: data.message,
                                });
                            }
                        })
                        .catch(error => {
                            // Handle any unexpected errors with SweetAlert
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An unexpected error occurred.',
                            });
                        });
                    }
                });
            });
        });
    });
</script>
</body>
</html>
<?php
    } else {
        header('Location: ../index.php');
    }
} else {
    header('Location: ../login.php');
}
?>