<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - RHS</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="logo.png">
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
            background-size: cover; /* Keep the background size intact */
        }

        .black-fill {
            padding: 10px;
            background-color: rgba(0, 0, 0, 0.70); /* Semi-transparent black fill */
            height: 100vh; /* Make sure the overlay covers the entire viewport height */
            width: 100%;
            position: relative;
        }

        /* Style for the container of the form */
        .login-container {
            width: 90%;
            max-width: 500px; /* Adjust the max-width as needed */
            padding: 20px; /* Increased padding for a more spacious feel */
            background-color: rgba(255, 255, 255, 0.90); /* Semi-transparent white background */
            color: #000000; /* Black text color for contrast */
            border-radius: 20px; /* More rounded corners */
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25); /* Enhanced shadow effect */
            margin-top: 50px; /* Margin from the top */
            position: relative; /* Ensure it sits above the black background */
        }

        /* Custom style for the dropdown to match the text box */
        .custom-dropdown {
            position: relative;
        }
        .custom-dropdown select {
            background-color: #ffffff; /* White background for select */
            border: 1px solid #ced4da; /* Light border */
            color: #495057; /* Dark text color for select */
            border-radius: .25rem;
            padding: .375rem .75rem;
            height: calc(1.5em + .75rem + 2px);
            appearance: none; /* Remove default dropdown arrow */
        }
        .custom-dropdown::after {
            content: "\f078"; /* FontAwesome down arrow */
            font-family: 'Font Awesome 6 Free'; /* Use FontAwesome's font family */
            font-weight: 900; /* Ensure the icon is displayed */
            position: absolute;
            top: 50%;
            right: 10px;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 1rem;
            color: #495057; /* Dark color for the arrow */
        }

        /* Style for the Home link */
        .home-link {
            color: #000; /* Black color for the link */
            text-decoration: none;
            margin-left: 10px; /* Space between the button and link */
            align-self: center; /* Center align with the button */
        }

        /* Highlighted style for the Home link on hover */
        .home-link:hover {
            color: #ff5722 !important; /* Highlight color when hovered */
            font-weight: bold; /* Make the link bold */
            text-decoration: underline; /* Underline effect */
        }

        /* Style for the Login link */
        .login-link {
            color: #007bff; /* Primary color for the link */
            text-decoration: none;
            margin-left: 10px; /* Space between the button and link */
            align-self: center; /* Center align with the button */
        }
        .login-link:hover {
            color: #ff5722; /* Change color on hover */
        }

        /* Align the button and link to the left */
        .button-group {
            display: flex;
            align-items: center; /* Align items vertically in the center */
            gap: 10px; /* Space between the button and link */
        }

        /* Custom styles for form labels */
        .form-label {
            color: #000000; /* Black color for labels */
        }

        /* Super red for help text */
        .alert-danger {
            color: #ff0000; /* Super red color */
        }
          /* Updated Styling for Login Button */
        .nav-link.btn.btn-primary {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(135deg, #003366, #0099CC); /* Gradient background */
            color: #ffffff; /* White text color */
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
            color: #ffffff; /* Ensure text remains white */
        }
        
        .nav-link.btn.btn-primary:hover::before {
            width: 300%;
            height: 300%;
        }
        
        .nav-link.btn.btn-primary span {
            position: relative;
            z-index: 1;
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
       /* Base Button Styles */
        .btn-primary {
            display: inline-block;
            padding: 10px 15px;
            background: linear-gradient(135deg, #003366, #0099CC); /* Gradient background */
            color: #ffffff; /* White text color */
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
        
        .btn-primary::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2);
            transition: width 0.4s ease, height 0.4s ease;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        
        .btn-primary:hover {
            transform: scale(1.1); /* Scale on hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Shadow on hover */
            color: #ffffff; /* Ensure text remains white */
        }
        
        .btn-primary:hover::before {
            width: 300%;
            height: 300%;
        }
        
        .btn-primary span {
            position: relative;
            z-index: 1;
            
        }
        
        /* Updated style for the Home link with transparent background */
        .home-link {
            display: inline-block;
            padding: 10px 15px;
            background: transparent; /* Transparent background */
            color: #fd7e14;; /* White text color */
            font-size: 16px;
            text-transform: uppercase;
            font-weight: bold;
            border-radius: 8px; /* Same border radius */
            border: 2px solid #ffffff; /* Border to show the button outline */
            transition: transform 0.3s ease, box-shadow 0.3s ease, color 0.3s ease; /* Same transitions */
            text-decoration: none; /* Remove underline */
            position: relative; /* For pseudo-element positioning */
            overflow: hidden; /* Contain pseudo-element */
        }
        
        .home-link::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 200%;
            height: 200%;
            background: rgba(255, 255, 255, 0.2); /* White overlay for hover effect */
            transition: width 0.4s ease, height 0.4s ease;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
        }
        
        .home-link:hover {
            transform: scale(1.1); /* Scale on hover */
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.3); /* Shadow on hover */
            color: #ffffff; /* Ensure text remains white */
        }
        
        .home-link:hover::before {
            width: 300%;
            height: 300%;
        }
        
        .home-link span {
            position: relative;
            z-index: 1;
        }
        
        /* Style for the LOGIN heading */
        h3.text-center {
            color: #4a4a4a; /* Semi-black color for LOGIN text */
        }
        
        /* Style for the LOGIN heading on hover */
        h3.text-center:hover {
            color: #4a4a4a; /* Ensure semi-black color remains on hover */
        }
    </style>
</head>
<body class="login">
    <div class="black-fill">
        <div class="container">
            <div class="d-flex justify-content-center align-items-center flex-column">
                <form class="login-container" method="post" action="req/login.php">
                    <div class="text-center">
                        <img src="logo.png" width="100">
                    </div>
                    <h3 class="text-center">LOGIN</h3>
                    <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?= $_GET['error'] ?>
                    </div>
                    <?php } ?>
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" name="uname">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control" name="pass">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Login As</label>
                        <div class="custom-dropdown">
                            <select class="form-control" name="role">
                                <option value="" disabled selected>Select User Role</option>
                                <option value="1">Admin</option>
                                <option value="2">Teacher</option>
                                <option value="3">Student</option>
                            </select>
                        </div>
                    </div>
                    <!-- Button and link container -->
                    <div class="button-group mb-3">
                        <button type="submit" class="btn btn-primary"><span>Login</span></button>
                        <a href="index.php" id="homeLink" class="text-decoration-none home-link"><span>Home</span></a>
                    </div>
                </form>
                <br /><br />
                <div class="footer">
                    <strong>Rangayen High School Information Management System</strong>| Version 1.0 by <a href="https://www.facebook.com/profile.php?id=61564529794100" target="_blank"> S.I.C.K CIS of <i>i</i>-LinkCST</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
