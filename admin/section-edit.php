<?php 
session_start();
if (isset($_SESSION['admin_id']) && 
    isset($_SESSION['role']) &&
    isset($_GET['section_id'])) {

    if ($_SESSION['role'] == 'Admin') {
        include "../DB_connection.php";
        include "data/section.php";

        $section_id = $_GET['section_id'];
        $section = getSectioById($section_id, $conn);

        if ($section == 0) {
            header("Location: section.php");
            exit;
        }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Edit Section</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="icon" href="../logo.png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <?php include "inc/navbar.php"; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#navLinks li:nth-child(4) a").addClass('active');

            // SweetAlert form for editing section
            Swal.fire({
                title: 'Edit Section',
                html: `
                    <div class="mb-3">
                        <label class="form-label">Section</label>
                        <input type="text" id="sectionInput" class="form-control" value="<?= htmlspecialchars($section['section']) ?>">
                    </div>
                `,
                showCancelButton: true,
                confirmButtonText: 'Update',
                cancelButtonText: 'Cancel',
                preConfirm: function() {
                    return {
                        section: $('#sectionInput').val(),
                        section_id: <?= json_encode($section['section_id']) ?>
                    };
                }
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.ajax({
                        url: 'req/section-edit.php',
                        method: 'POST',
                        data: result.value,
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: 'Section has been updated successfully!',
                            }).then(function() {
                                window.location.href = 'section.php';
                            });
                        },
                        error: function() {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'An error occurred while updating the section.',
                            });
                        }
                    });
                } else if (result.isDismissed) {
                    // Redirect to section.php when cancel is clicked
                    window.location.href = 'section.php';
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
        header("Location: section.php");
        exit;
    }
} else {
    header("Location: section.php");
    exit;
} 
?>
