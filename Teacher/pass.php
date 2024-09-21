<?php 
session_start();
if (isset($_SESSION['teacher_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] == 'Teacher') {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teacher - Change Password</title>
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
        .sidebar {
            width: 240px;
            height: 100vh;
            background-color: #343a40;
            position: fixed;
        }
        .sidebar .nav-link {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #b2bec3;
            transition: color 0.2s ease, background-color 0.2s ease;
            border-radius: 10px;
            margin: 1px;
            padding: 15px;
        }
        .sidebar .nav-link i {
            margin-right: 10px;
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
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
        .form-container {
            max-width: 500px;
            width: 100%;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            margin: auto;
        }
        .form-container h5 {
            text-align: center;
            margin-bottom: 20px;
        }
        .content-wrapper {
            display: flex;
        }
        .form-container {
            margin-left: 20px;
        }

        @keyframes popInOut {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }

        .btn-primary, .btn-secondary {
            background: linear-gradient(45deg, #007bff, #00bcd4); /* Gradient background */
            border: none;
            color: #fff;
            border-radius: 12px; /* Rounded corners */
            padding: 10px 20px;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.3s ease, background 0.3s ease;
        }

        .btn-primary:hover, .btn-primary:focus,
        .btn-secondary:hover, .btn-secondary:focus {
            background: linear-gradient(45deg, #007bff, #00bcd4); /* Same gradient on hover */
            animation: popInOut 0.6s ease;
        }

        .btn-primary:active, .btn-secondary:active {
            transform: scale(0.98);
        }

        .btn-primary span, .btn-secondary span {
            position: relative;
            z-index: 1;
        }
    </style>
</head>
<body>
    <header class="navbar navbar-expand-lg navbar-light shadow-sm">
        <div class="container-fluid">
            <button class="btn btn-light me-2" id="sidebarToggle" type="button">
                <i class="bi bi-list"></i>
            </button>
            <a class="navbar-brand" href="#">SIMS: Rangayen High School</a>
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
    <nav id="sidebar" class="bg-dark p-3 sidebar">
        <ul class="nav flex-column">
            <li class="nav-item mb-2"><a href="classes.php" class="nav-link text-white"><i class="bi bi-house-door"></i> Dashboard</a></li>
            <li class="nav-item mb-2"><a href="#" class="nav-link text-white active" id="changePasswordLink"><i class="bi bi-lock"></i> Change Password</a></li>
            <li class="nav-item mt-auto">
                <a href="../logout.php" class="nav-link text-warning"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </li>
        </ul>
    </nav>
    <div class="main-content">
        <div class="content-wrapper">
            <div class="form-container">
                <h5>Change Password</h5>
                <form method="post" id="changePasswordForm">
                    <div class="mb-3">
                        <label class="form-label">Old password</label>
                        <input type="password" class="form-control" name="old_pass" required> 
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New password</label>
                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="new_pass" id="passInput" required>
                            <button class="btn btn-secondary" id="gBtn">Random</button>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm new password</label>
                        <input type="text" class="form-control" name="c_new_pass" id="passInput2" required> 
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <span>Change</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('sidebarToggle').addEventListener('click', function () {
            document.getElementById('sidebar').classList.toggle('d-none');
        });

        function makePass(length) {
            var result = '';
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
            var charactersLength = characters.length;
            for (var i = 0; i < length; i++) {
                result += characters.charAt(Math.floor(Math.random() * charactersLength));
            }
            var passInput = document.getElementById('passInput');
            var passInput2 = document.getElementById('passInput2');
            passInput.value = result;
            passInput2.value = result;
        }

        var gBtn = document.getElementById('gBtn');
        gBtn.addEventListener('click', function(e) {
            e.preventDefault();
            makePass(8); // Adjust length as needed
        });

        document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission

            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to change your password?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, change it!',
                cancelButtonText: 'No, cancel!'
            }).then((result) => {
                if (result.isConfirmed) {
                    var formData = new FormData(this);
                    
                    fetch('req/teacher-change.php', {
                        method: 'POST',
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'success') {
                            Swal.fire({
                                title: 'Success!',
                                text: data.message,
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        } else {
                            Swal.fire({
                                title: 'Error!',
                                text: data.message,
                                icon: 'error',
                                confirmButtonText: 'OK'
                            });
                        }
                    })
                    .catch(error => {
                        Swal.fire({
                            title: 'Error!',
                            text: 'Something went wrong!',
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    });
                }
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
} else {
    header("Location: ../login.php");
    exit;
}
?>
