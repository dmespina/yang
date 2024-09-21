<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       $grade_code = '';
       $grade = '';

       if (isset($_GET['grade_code'])) $grade_code = $_GET['grade_code'];
       if (isset($_GET['grade'])) $grade = $_GET['grade'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Grade</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php 
        include "inc/navbar.php";
    ?>

    <!-- Modal -->
    <div class="modal fade" id="addGradeModal" tabindex="-1" aria-labelledby="addGradeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addGradeModalLabel">Add New Grade</h5>
                    <button type="button" id="closeModal" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="post" class="shadow p-3 form-w" action="req/grade-add.php">
                        <div class="mb-3">
                            <label class="form-label">Grade Code</label>
                            <input type="text" class="form-control" value="<?=$grade_code?>" name="grade_code">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Grade</label>
                            <input type="text" class="form-control" value="<?=$grade?>" name="grade">
                        </div>
                        <button type="submit" class="btn btn-primary">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            // Activate the correct navbar link
            $("#navLinks li:nth-child(4) a").addClass('active');

            // Show SweetAlert notifications if present
            <?php if (isset($_GET['error'])) { ?>
            Swal.fire({
                title: 'Error!',
                text: '<?= htmlspecialchars($_GET['error']) ?>',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            <?php } ?>

            <?php if (isset($_GET['success'])) { ?>
            Swal.fire({
                title: 'Success!',
                text: '<?= htmlspecialchars($_GET['success']) ?>',
                icon: 'success',
                confirmButtonText: 'OK'
            });
            <?php } ?>

            // Show the modal when the page loads
            var myModal = new bootstrap.Modal(document.getElementById('addGradeModal'));
            myModal.show();

            // Redirect to grade.php when the modal is closed
            document.getElementById('closeModal').addEventListener('click', function () {
                window.location.href = 'grade.php';
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
