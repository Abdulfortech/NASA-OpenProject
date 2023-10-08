<?php
$page = 'projects'; //to add this as active class
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $userId = $_SESSION['nasa_user_id'];
    if(!isset($_GET['project'])){
        header('Location:' . WEBSITE_URL . "auth");
        exit;
    }else{
        $projectId = $_GET['project'];
    }
    $User = new User(); $Project = new Project(); $Skills = new Skills();
    $projects = $Project->fetchProject($projectId);
    $userInfo = $User->getUserDetails();
    $skills = $Skills->getSkills();
  }

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= WEBSITE_NAME ?> | Send a Request</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Select2 -->
    <link rel="stylesheet" href="../assets/vendor/select2/css/select2.min.css">
    <link rel="stylesheet" href="../assets/vendor/select2-bootstrap4-theme/select2-bootstrap4.min.css">   
    <!-- Custom styles for this template-->
    <link href="../assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <?php include "sidebar.php";?>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <?php include 'topbar.php';?>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <h1 class="h3 mb-0 text-gray-800">Requests</h1>
                        <a href="project-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-plus fa-sm text-white-50"></i> Send Request</a>
                    </div>

                    <div class="container-fluid">

                        <div class="row">
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-8 mx-auto">
                            <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                            <div
                                class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                            >
                                <h6 class="m-0 font-weight-bold text-primary">
                                Request To Join Project
                                </h6>
                            </div>
                            <!-- Card Body -->
                            <div class="card-body">
                                <form action="../classes/Request.php?f=add_request" method="POST">
                                    <input type="hidden" name="projectId" value="<?= $projectId?>">
                                    <input type="hidden" name="userId" value="<?= $userId?>">
                                    <div class="form-group">
                                        <label for="title">Subject</label>
                                        <input type="text" class="form-control" id="subject" value="Request to Join <?= $projects[0]['title']?>" name="subject" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="descriptions">Description(explain what you can contribute to this project)</label>
                                        <textarea class="form-control" id="descriptions" name="description" rows="6" required>
                                        </textarea>
                                    </div>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

            <!-- Content Row -->
          </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; ConnectX <?= date('Y')?></span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Bootstrap core JavaScript-->
    <script src="../assets/vendor/jquery/jquery.min.js"></script>
    <script src="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="../assets/js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../assets/js/index.js"></script>
    <!-- Select2 -->
    <script src="../assets/vendor/select2/js/select2.full.min.js"></script>
    <script>
        $(function () {
            //Initialize Select2 Elements
            $('.select2').select2();

            //Initialize Select2 Elements
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
        });
    </script>
    
    <!--  Notifications Plugin    -->
    <script src="../assets/vendor/bootstrap-notify-3/dist/bootstrap-notify.min.js"></script>
    <?php if (isset($_SESSION['msg'])) { $msg = $_SESSION['msg'];?>
    <script type="text/javascript">
      $(document).ready(function() {
          $.notify({
          title: "<b>Alert :</b>",
          message: "<?= $msg?>",
          icon: 'fa fa-bell',
          type: "info"
          });
      });
    </script>
    <?php  unset($_SESSION['msg']); }?>

</body>

</html>