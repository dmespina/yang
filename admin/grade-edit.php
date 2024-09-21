<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) &&
    isset($_GET['grade_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/grade.php";

       $grade_id = $_GET['grade_id'];
       $grade = getGradeById($grade_id, $conn);

       if ($grade == 0) {
         header("Location: grade.php");
         exit;
       }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f4f6f9;
            margin: 0;
        }

        .navbar {
            background-color: #6c5ce7;
            border-bottom: 3px solid #f1f1f1;
            margin-bottom: 0;
            padding: 10px;
        }

        .navbar-brand {
            font-weight: 600;
            color: #fff;
        }

        .card-header h5, .form-section h5 {
            background-color: #6c5ce7;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
        }

        .form-section {
            margin-bottom: 20px;
        }

        .form-w h3 {
            background-color: #6c5ce7;
            color: #fff;
            padding: 10px;
            border-radius: 5px;
            margin: 0;
            border: none;
            box-shadow: none;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
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

    <!-- Main Content -->
    <div class="container mt-4">
        <script>
            $(document).ready(function(){
                // SweetAlert form for editing grade
                Swal.fire({
                    title: 'Edit Grade',
                    html: `
                        <form id="gradeForm">
                            <div class="mb-3">
                                <label class="form-label">Grade Code</label>
                                <input type="text" class="form-control" id="gradeCode" value="<?= htmlspecialchars($grade['grade_code'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Grade</label>
                                <input type="text" class="form-control" id="gradeName" value="<?= htmlspecialchars($grade['grade'], ENT_QUOTES, 'UTF-8') ?>" required>
                            </div>
                            <input type="hidden" id="gradeId" value="<?= htmlspecialchars($grade['grade_id'], ENT_QUOTES, 'UTF-8') ?>">
                        </form>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Update',
                    cancelButtonText: 'Cancel',
                    preConfirm: function() {
                        return {
                            grade_code: $('#gradeCode').val(),
                            grade: $('#gradeName').val(),
                            grade_id: $('#gradeId').val()
                        };
                    }
                }).then(function(result) {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: 'req/grade-edit.php',
                            method: 'POST',
                            data: result.value,
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success!',
                                    text: 'Grade has been updated successfully!',
                                }).then(function() {
                                    window.location.href = 'grade.php';
                                });
                            },
                            error: function() {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: 'An error occurred while updating the grade.',
                                });
                            }
                        });
                    } else if (result.isDismissed) {
                        // Redirect to grade.php when cancel is clicked
                        window.location.href = 'grade.php';
                    }
                });

                // SweetAlert notifications based on query parameters
                const urlParams = new URLSearchParams(window.location.search);
                const error = urlParams.get('error');
                const success = urlParams.get('success');

                if (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: error,
                    });
                }

                if (success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: success,
                    });
                }
            });
        </script>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>  
</body>
</html>
<?php 

  } else {
    header("Location: grade.php");
    exit;
  } 
} else {
    header("Location: grade.php");
    exit;
} 
?>
