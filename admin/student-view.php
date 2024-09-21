<?php 
session_start();
if (isset($_SESSION['admin_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/student.php";
        include "data/subject.php";
        include "data/grade.php";
        include "data/section.php";

        if (isset($_GET['student_id'])) {
            $student_id = $_GET['student_id'];
            $student = getStudentById($student_id, $conn);

            if ($student != 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student - Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: 'Century Gothic', 'Century', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%); /* Light gradient background */
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
        .profile-card img {
            border-radius: 10px;
        }
        .info-card {
            flex: 2;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%); /* Gradient background for the card */
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
        .table {
            background-color: #ffffff;
        }
        .table th {
            background-color: #f1f3f5;
            color: #007bff; /* Blue color for labels */
        }
        .table td {
            color: #000000; /* Black color for values */
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
        <a href="student.php" class="btn btn-dark btn-back">Go Back</a>
        <div class="profile-card">
            <img src="../img/student-<?=$student['gender']?>.png" class="card-img-top" alt="Student Image">
            <h5 class="card-title text-center mt-3">@<?=$student['username']?></h5>
        </div>
        <div class="info-card">
            <h5>Student Information</h5>
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>First name</th>
                        <td><strong><?=$student['fname']?></strong></td>
                    </tr>
                    <tr>
                        <th>Last name</th>
                        <td><strong><?=$student['lname']?></strong></td>
                    </tr>
                    <tr>
                        <th>Username</th>
                        <td><strong><?=$student['username']?></strong></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><strong><?=$student['address']?></strong></td>
                    </tr>
                    <tr>
                        <th>Date of birth</th>
                        <td><strong><?=$student['date_of_birth']?></strong></td>
                    </tr>
                    <tr>
                        <th>Email address</th>
                        <td><strong><?=$student['email_address']?></strong></td>
                    </tr>
                    <tr>
                        <th>Parent first name</th>
                        <td><strong><?=$student['parent_fname']?></strong></td>
                    </tr>
                    <tr>
                        <th>Parent last name</th>
                        <td><strong><?=$student['parent_lname']?></strong></td>
                    </tr>
                    <tr>
                        <th>Parent phone number</th>
                        <td><strong><?=$student['parent_phone_number']?></strong></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(3) a").addClass('active');
        });
    </script>
</body>
</html>
<?php 
            } else {
                header("Location: student.php");
                exit;
            }
        } else {
            header("Location: student.php");
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