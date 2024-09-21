<?php 
include "DB_connection.php";
include "data/setting.php";
$setting = getSetting($conn);

if ($setting != 0) {
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Rangayen High School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body, h1, h2, h3, h4, h5, h6, p, a, div, span, input, button, select {
            font-family: 'Roboto', Arial, sans-serif;
        }

        body {
            background: url(../img/bg.jpg) no-repeat center center fixed;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .black-fill {
            padding: 10px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
        }

        .container {
            background-color: transparent;
            padding: 10px 0;
            box-shadow: none;
        }

        .navbar {
            background-color: #ffffff;
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1);
        }

        .nav-link {
            color: #333 !important;
            font-weight: normal;
            transition: color 0.3s, text-decoration 0.3s;
        }

        .nav-link:hover {
            color: #ff0000 !important;
            text-decoration: none;
        }

        .nav-link.active {
            font-weight: bold;
            color: #ff0000 !important;
            text-decoration: none;
        }

        .nav-link.btn.btn-primary {
            background-color: transparent;
            color: #333;
            border: 1px solid #333;
        }

        .nav-link.btn.btn-primary:hover {
            background-color: #f8f9fa;
            color: #000;
        }

        .carousel-item img {
            max-height: 250px;
            object-fit: cover;
        }

        .carousel-caption h5, .carousel-caption p {
            font-size: 1.5rem;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px;
            margin: 0;
        }

        .carousel-indicators button {
            background-color: #000;
        }

        #eventCarousel {
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden;
            height: 250px;
            position: relative;
        }

        .carousel-control-prev, .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
            width: 5%;
        }

        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 10px;
            border-radius: 50%;
        }

        .footer {
            width: 100%;
            background-color: #f8f9fa;
            color: black;
            text-align: center;
            padding: 10px 0;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            font-size: 14px;
            color: #6c757d;
            box-sizing: border-box;
        }

        .footer strong {
            color: #343a40;
        }
        .contact-container {
            width: 600px; /* Adjust the width to match the login form */
            margin: 0 auto; /* Center the container */
            padding: 20px; /* Add padding for spacing */
            border-radius: 10px; /* Rounded corners to match the login form */
        }
        .contact-heading {
            color: #FFFFFF;
            font-family: 'lobster';
            font-size: 3rem;
           font-style: italic;
        }
    </style>
</head>
<body class="body-home">
    <div class="black-fill">
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-light" id="homeNav">
                <div class="container-fluid">
                    <a class="navbar-brand" href="index.php">
                        <img src="logo.png" width="40" alt="Logo">
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link" href="index.php">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="about.php">About</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="contact.php">Contact</a>
                            </li>
                        </ul>
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                            <li class="nav-item">
                                <a class="nav-link btn btn-primary text-white" href="login.php">Login</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <section id="contact" class="d-flex justify-content-center align-items-center flex-column">
                <div class="contact-container">
                    <form method="post" action="req/contact.php">
                        <?php if (isset($_GET['error'])) { ?>
                            <div class="alert alert-danger" role="alert">
                                <?= htmlspecialchars($_GET['error']) ?>
                            </div>
                        <?php } ?>
                        <?php if (isset($_GET['success'])) { ?>
                            <div class="alert alert-success" role="alert">
                                <?= htmlspecialchars($_GET['success']) ?>
                            </div>
                        <?php } ?>
                        <div class="mb-3">
                            <label for="exampleInputEmail1" class="form-label">Email address</label>
                            <input type="email" class="form-control" id="exampleInputEmail1" name="email" aria-describedby="emailHelp" required>
                            <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="full_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Message</label>
                            <textarea class="form-control" name="message" rows="4" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send</button>
                    </form>
                </div>
            </section>
            <div class="footer">
                <strong>Rangayen High School Information Management System</strong>| Version 1.0 by<a href="https://www.facebook.com/profile.php?id=61564529794100" target="_blank">  S.I.C.K CIS of <i>i</i>-LinkCST</a>
            </div>
        </div>
    </div>
</body>
</html>
<?php } else {
	header("Location: login.php");
	exit;
} ?>