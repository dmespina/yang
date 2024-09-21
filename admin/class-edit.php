<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) && 
    isset($_GET['class_id'])) {

    if ($_SESSION['role'] == 'Admin') {
      
       include "../DB_connection.php";
       include "data/class.php";
       include "data/grade.php";
       include "data/section.php";

       $class = getClassById($_GET['class_id'], $conn);
       $grades = getAllGrades($conn);
       $sections = getAllSections($conn);
       
       if ($class == 0) {
         header("Location: class.php");
         exit;
       }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Class</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Include SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include "inc/navbar.php"; ?>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>    
   <script>
    $(document).ready(function(){
        $("#navLinks li:nth-child(6) a").addClass('active');

        // SweetAlert form for editing class
        Swal.fire({
            title: 'Edit Class',
            html: `
                <div class="mb-3">
                    <label class="form-label">Grade</label>
                    <select id="gradeSelect" class="form-control">
                        <?php foreach ($grades as $grade) { 
                            $selected = ($grade['grade_id'] == $class['grade']) ? "selected" : "";
                        ?>
                            <option value="<?= htmlspecialchars($grade['grade_id']) ?>" <?= htmlspecialchars($selected) ?>>
                                <?= htmlspecialchars($grade['grade_code'].'-'.$grade['grade']) ?>
                            </option> 
                        <?php } ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Section</label>
                    <select id="sectionSelect" class="form-control">
                        <?php foreach ($sections as $section) { 
                            $selected = ($section['section_id'] == $class['section']) ? "selected" : "";
                        ?>
                            <option value="<?= htmlspecialchars($section['section_id']) ?>" <?= htmlspecialchars($selected) ?>>
                                <?= htmlspecialchars($section['section']) ?>
                            </option> 
                        <?php } ?> 
                    </select>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            cancelButtonText: 'Cancel',
            preConfirm: function() {
                return {
                    grade: $('#gradeSelect').val(),
                    section: $('#sectionSelect').val(),
                    class_id: <?= json_encode($class['class_id']) ?>
                };
            }
        }).then(function(result) {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'req/class-edit.php',
                    method: 'POST',
                    data: result.value,
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Class has been updated successfully!',
                        }).then(function() {
                            window.location.href = 'class.php';
                        });
                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while updating the class.',
                        });
                    }
                });
            } else if (result.isDismissed) {
                // Redirect to class.php when cancel is clicked
                window.location.href = 'class.php';
            }
        });

        // SweetAlert notifications based on query parameters
        <?php if (isset($_GET['error'])) { ?>
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: '<?= htmlspecialchars($_GET['error']) ?>',
            });
        <?php } ?>
        
        <?php if (isset($_GET['success'])) { ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= htmlspecialchars($_GET['success']) ?>',
            });
        <?php } ?>
    });
</script>

</body>
</html>
<?php 

  } else {
    header("Location: class.php");
    exit;
  } 
} else {
    header("Location: class.php");
    exit;
} 
?>
