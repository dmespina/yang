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
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Garamond:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Apply Roboto font universally */
        body, h1, h2, h3, h4, h5, h6, p, a, div, span, input, button, select {
            font-family: 'Roboto', Arial, sans-serif;
        }

        /* Background Image for the whole page */
        body {
            background: url(../img/bg.jpg) no-repeat center center fixed;
            color: #000; /* Default text color */
            margin: 0;
            padding: 0;
            font-family: 'Roboto', Arial, sans-serif; /* Default font */
        }

        .black-fill {
            padding: 10px;
            background-attachment: fixed;
            background-position: center;
            background-repeat: no-repeat;
        }

        /* Remove white background from container */
        .container {
            background-color: transparent; /* Make container background transparent */
            padding: 10px 0; /* Remove padding if not needed */
            box-shadow: none; /* Remove shadow if not needed */
        }

        /* Updated Navbar Background and Link Colors */
        .navbar {
            background-color: #ffffff; /* Pure white background for the navbar */
            box-shadow: 0 4px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow for prominence */
        }

        .nav-link {
            color: #333 !important; /* Darker color for the links */
            font-weight: normal; /* Ensure the text is not bold */
            transition: color 0.3s, text-decoration 0.3s; /* Smooth transition for color change */
        }

        .nav-link:hover {
            color: #ff0000 !important; /* Red color on hover */
            text-decoration: none; /* No underline on hover */
        }

        /* Highlight the active page link */
        .nav-link.active {
            font-weight: bold; /* Bold for active page */
            color: #ff0000 !important; /* Red color for active link */
            text-decoration: none; /* No underline for active link */
        }

        /* Styling for Login Button */
        .nav-link.btn.btn-primary {
            background-color: transparent; /* Remove the blue background color */
            color: #333; /* Change text color if needed */
            border: 1px solid #333; /* Add border if needed */
        }

        .nav-link.btn.btn-primary:hover {
            background-color: #f8f9fa; /* Light background color on hover, adjust as needed */
            color: #000; /* Change text color on hover if needed */
        }

        /* Additional styling for the slideshow */
        .carousel-item img {
            max-height: 0px; /* Reduced height to ensure the page fits */
            : cover;
        }
        .carousel-caption h5, .carousel-caption p {
            font-size: 1.5rem;
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px;
            margin: 0; /* Reduced padding and margin */
        }
        .carousel-indicators button {
            background-color: #000;
        }

        /* Border styling for the carousel */
        #eventCarousel {
            border: 1px solid #dee2e6; /* Slightly reduced border size */
            border-radius: 5px;
            overflow: hidden;
            height: 300px; /* Reduced height */
            position: relative;
        }

        /* Adjust the position of the previous and next buttons */
        .carousel-control-prev, .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
            width: 5%;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5);
            padding: 8px;
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

    </style>
</head>
<body class="body-home">
    <div class="black-fill">
        <div class="container">
        <!-- Navbar unchanged -->
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
                            <a class="nav-link active" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="contact.php">Contact</a>
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

        <!-- About Section -->
        <section id="about" class="d-flex justify-content-center align-items-center flex-column">
            <div class="card mb-3 card-1">
                <div class="row g-0">
                    <div class="col-md-4">
                        <img src="logo.png" class="img-fluid rounded-start" alt="School Logo">
                    </div>
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title">About Us</h5>
                            <p class="card-text"><?=$setting['about']?></p>
                            <p class="card-text"><small class="text-muted">RHS</small></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

  <!-- Footer for Version and System Name -->
<div class="footer">
    <strong>Rangayen High School Information Management System</strong> 
    | Version 1.0 by 
    <a href="https://www.facebook.com/profile.php?id=61564529794100" target="_blank">
        S.I.C.K CIS of <i>i</i>-LinkCST
    </a>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var eventModal = document.getElementById('eventModal');
        eventModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var eventId = button.getAttribute('data-event-id');

            // Use AJAX to fetch event details based on eventId
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'get_event_details.php?id=' + eventId, true);
            xhr.onload = function () {
                if (xhr.status >= 200 && xhr.status < 400) {
                    var event = JSON.parse(xhr.responseText);
                    document.getElementById('eventModalLabel').textContent = event.title;
                    document.getElementById('eventDate').textContent = event.date;
                    document.getElementById('eventDescription').textContent = event.description;
                    document.getElementById('eventImage').src = 'uploads/' + event.image;
                }
            };
            xhr.send();
        });
    });
</script>
</body>
</html>

<?php } else {
	header("Location: login.php");
	exit;
} ?>
