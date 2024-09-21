<?php 
session_start();

if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/teacher.php";
        include "data/grade.php";
        include "data/section.php";

        if (isset($_GET['teacher_id'])) {
            $teacher_id = $_GET['teacher_id'];
            $teacher = getTeacherById($teacher_id, $conn);    

            if ($teacher != 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Teacher Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Century Gothic', 'Century', sans-serif;
        }
        .container {
            position: relative;
            display: flex;
            justify-content: flex-start;
            align-items: flex-start;
            margin-top: 50px;
        }
        .profile-card {
            flex: 1;
            max-width: 300px;
            margin-right: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .info-card {
            flex: 2;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
            border: 1px solid #dee2e6;
        }
        .info-card h5 {
            text-align: center;
            font-weight: bold;
            margin-bottom: 20px;
            color: #007bff;
            font-family: 'Century Gothic', 'Century', sans-serif;
        }
        .info-card .list-group-item {
            border: none;
            padding: 10px;
            font-size: 16px;
            color: #495057;
            background-color: #ffffff;
            font-family: 'Century Gothic', 'Century', sans-serif;
        }
        .info-card .list-group-item:nth-child(odd) {
            background-color: #f1f3f5;
        }
        .info-card .list-group-item strong {
            color: #007bff;
        }
        .btn-back {
            position: absolute;
            top: 20px;
            right: 20px;
            z-index: 10;
        }
    </style>
</head>
<body>
    <?php 
        include "inc/navbar.php";
    ?>
    <div class="container">
        <a href="teacher.php" class="btn btn-dark btn-back">Go Back</a>
        <div class="profile-card">
            <img src="../img/teacher-<?=$teacher['gender']?>.png" class="card-img-top" alt="Teacher Image">
            <h5 class="card-title text-center mt-3"><?=$teacher['username']?></h5>
        </div>
        <div class="info-card">
            <h5>Teacher Information</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>First name:</strong> <?=$teacher['fname']?></li>
                <li class="list-group-item"><strong>Last name:</strong> <?=$teacher['lname']?></li>
                <li class="list-group-item"><strong>Username:</strong> <?=$teacher['username']?></li>
                <li class="list-group-item"><strong>Employee number:</strong> <?=$teacher['employee_number']?></li>
                <li class="list-group-item"><strong>Address:</strong> <?=$teacher['address']?></li>
                <li class="list-group-item"><strong>Date of birth:</strong> <?=$teacher['date_of_birth']?></li>
                <li class="list-group-item"><strong>Phone number:</strong> <?=$teacher['phone_number']?></li>
                <li class="list-group-item"><strong>Qualification:</strong> <?=$teacher['qualification']?></li>
                <li class="list-group-item"><strong>Email address:</strong> <?=$teacher['email_address']?></li>
                <li class="list-group-item"><strong>Gender:</strong> <?=$teacher['gender']?></li>
                <li class="list-group-item"><strong>Date of joined:</strong> <?=$teacher['date_of_joined']?></li>
            </ul>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(2) a").addClass('active');
        });
    </script>

</body>
</html>
<?php 
            } else {
                header("Location: teacher.php");
                exit;
            }
        } else {
            header("Location: teacher.php");
            exit;
        }
    } else {
        header("Location: ../login.php");
        exit;
    }
} else {
    header("Location: ../login.php");
    exit;
}
?>
