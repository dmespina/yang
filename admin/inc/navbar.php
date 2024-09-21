<?php
$role = isset($_SESSION['role']) ? htmlspecialchars($_SESSION['role']) : 'Guest';
$fname = isset($_SESSION['fname']) ? htmlspecialchars($_SESSION['fname']) : 'Guest';
?>

<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: #003366;"> <!-- Dark Blue -->
  <div class="container-fluid">
    <a class="navbar-brand" href="index.php">
      <img src="../logo.png" width="40" alt="Brand Logo">
    </a>
    <div class="d-flex ms-auto">
      <div class="dropdown">
        <a class="btn btn-secondary dropdown-toggle d-flex align-items-center" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
          <img src="../img/registrar-office-Male.jpg" alt="Profile" width="30" height="30" class="rounded-circle me-2">
          <?php echo $role; ?>: <?php echo $fname; ?>
        </a>

        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuLink">
          <li><a class="dropdown-item" href="settings.php">Settings</a></li>
          <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
        </ul>
      </div>
    </div>
  </div>
</nav>
