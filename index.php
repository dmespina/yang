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
            font-family: 'Roboto', Arial; /* Default font */
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

        .welcome-text h4 {
            font-family: 'Poppins', serif;
            font-weight: 900;
            font-style: italic;
        }

        .welcome-text h4 {
            color: white;
            font-size: 51px;
        }
        /* Welcome Section Text Styling */
        .welcome-text p {
            background-color: #d5c3c3; /* Off-white background color */
            padding: 7px; /* Add some padding for better readability */
            border-radius: 5px; /* Optional: Rounded corners for a smoother look */
            color: #000; /* Ensure text color contrasts well with the background */
            font-size: 1.2rem; /* Adjust font size if needed */
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

       /* Updated Styling for Login Button */
        .nav-link.btn.btn-primary {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(to top, #00c6fb 0%, #005bea 100%);
            color: white; /* White text color */
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 8px; /* Rounded corners */
            border: none; /* No border */
            transition: transform 0.3s ease, box-shadow 0.3s ease, color 0.3s ease; /* Transitions */
            text-decoration: none; /* No underline */
            position: relative; /* For pseudo-element positioning */
            overflow: hidden; /* Contain pseudo-element */
        }
        
        .nav-link.btn.btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2); /* White overlay effect */
            transition: width 0.4s ease, height 0.4s ease;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        
        .nav-link.btn.btn-primary:hover {
            transform: scale(1.1); /* Scale on hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Shadow on hover */
            color: white; /* Ensure text remains white */
        }
        
        .nav-link.btn.btn-primary:hover::before {
            width: 300%;
            height: 300%;
        }
        
        .nav-link.btn.btn-primary span {
            position: relative;
            z-index: 1;
        }

        /* Additional styling for the slideshow */
        .carousel-item img {
            max-height: 400px;
            object-fit: cover;
        }

        .carousel-caption {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center; /* Center-align text */
        }

        .carousel-caption h5 {
            font-size: 1.8rem;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
            background-color: rgba(0, 0, 0, 0.5); /* Add background color for better text visibility */
            padding: 10px;
        }

        .carousel-caption p {
            text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.7);
            background-color: rgba(0, 0, 0, 0.5); /* Add background color for better text visibility */
            padding: 10px;
            font-size: 1.3rem;
        }

        .carousel-indicators button {
            background-color: #000;
        }

        /* Border styling for the carousel */
        #eventCarousel {
            border: 2px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
            height: 450px; /* Ensure consistent height */
            position: relative; /* Required for positioning the buttons */
        }

        /* Adjust the position of the previous and next buttons */
        .carousel-control-prev,
        .carousel-control-next {
            top: 50%; /* Center the buttons vertically */
            transform: translateY(-50%); /* Adjust for centering */
            width: 5%; /* Adjust the width if needed */
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            background-color: rgba(0, 0, 0, 0.5); /* Darker background for better visibility */
            padding: 10px;
            border-radius: 50%;
        }

        /* Increased font size for 'View details' button */
        .carousel-caption .btn {
            font-size: 1.3rem; /* Increase font size */
        }

        /* Footer styling */
        .footer {
            background-color: #f8f9fa;
            padding: 10px 0;
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #6c757d;
            border-top: 1px solid #e9ecef;
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
                            <a class="nav-link no-hover active" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link no-hover" href="about.php">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link no-hover" href="contact.php">Contact</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link btn btn-primary" href="login.php">Login</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
            <!-- Welcome Section -->
            <section class="welcome-text d-flex justify-content-center align-items-center flex-column">
                <img src="logo.png" alt="Rangayen High School Logo">
                <h4>Welcome to RHS</h4>
                <p style="color: black;">Rich, High, and Succeed.</p> <!-- Inline CSS example -->
            </section>
       
            <!-- Slideshow Section -->
            <div id="eventCarousel" class="carousel slide mt-5" data-bs-ride="carousel">
            <h4 style="color: white; font-family: Lobster;">&nbsp;  Incoming School Events</h4>
                <div class="carousel-indicators">
                    <?php
                    include 'DB_connection.php';
            
                    $query = "SELECT * FROM events ORDER BY event_date ASC";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();
            
                    $events = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    $activeClass = 'active';
            
                    foreach ($events as $index => $row) {
                        if (strtotime($row['event_date']) >= time()) {
                            echo '<button type="button" data-bs-target="#eventCarousel" data-bs-slide-to="' . $index . '" class="' . $activeClass . '" aria-current="true" aria-label="Slide ' . ($index + 1) . '"></button>';
                            $activeClass = '';
                        }
                    }
                    ?>
                </div>
                <div class="carousel-inner">
                    <?php
                    $activeClass = 'active';
            
                    if (count($events) > 0) {
                        foreach ($events as $row) {
                            if (strtotime($row['event_date']) >= time()) {
                                $eventTitle = htmlspecialchars($row['title']);
                                $eventDate = date("F j, Y", strtotime($row['event_date']));
                                $eventDescription = htmlspecialchars($row['description']);
                                $eventImages = explode(',', $row['images']);
                                $eventImage = $eventImages[0];
            
                                $imagePath = 'uploads/' . htmlspecialchars($eventImage);
            
                                echo '
                                <div class="carousel-item ' . $activeClass . '">
                                    <img src="' . $imagePath . '" class="d-block w-100" alt="' . $eventTitle . '">
                                    <div class="carousel-caption d-block d-md-block">
                                        <h5 class="d-none d-md-block">' . $eventTitle . '</h5>
                                        <p class="d-none d-md-block">' . $eventDate . '</p>
                                        <a href="#" data-event-id="' . $row['id'] . '" class="btn btn-primary btn-sm d-md-inline-block d-block mt-2" data-bs-toggle="modal" data-bs-target="#eventModal">View details</a>
                                    </div>
                                </div>';
                                $activeClass = '';
                            }
                        }
                    } else {
                        echo '<p>No events available at the moment.</p>';
                    }
            
                    $conn = null;
                    ?>
                </div>
            </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#eventCarousel" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#eventCarousel" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Event Title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Event Date: <span id="eventDate"></span></p>
                    <p id="eventDescription"></p>
                    <img id="eventImage" src="" alt="" class="img-fluid mt-3">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer for Version and System Name -->
    <div class="footer">
         <strong>Rangayen High School Information Management System</strong>| Version 1.0 by<a href="https://www.facebook.com/profile.php?id=61564529794100" target="_blank">  S.I.C.K CIS of <i>i</i>-LinkCST</a>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/script.js"></script>
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