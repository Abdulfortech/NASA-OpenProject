<?php
$page = 'index'; //to add this as active class
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $userId = $_SESSION['nasa_user_id'];
    if(!isset($_GET['profile'])){
        header('Location:' . WEBSITE_URL . "auth");
        exit;
    }else{
        $otherUserId = $_GET['profile'];
    }
    $User = new User(); $Skills = new Skills();
    $userInfo = $User->getUser($otherUserId);
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

    <title><?= WEBSITE_NAME ?> | Profile View</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Edit Profile</h1>
                        <a href="project-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-plus fa-sm text-white-50"></i> Add Project</a>
                    </div>

                    <!-- Content Row -->

                    <div class="row">
                        <div class="col-md-8 mx-auto">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <!-- head -->
                                    <div class="row">
                                        <div class="col-2">
                                            <img src="../assets/img/1.jpg" width="100" height="100" class="rounded-circle">
                                        </div>
                                        <div class="col-10">
                                            <div class="row d-flex justify-content-between">
                                                <div class="col-12">
                                                    <h1><?= $userInfo['firstName']." ".$userInfo['lastName']?></h1>
                                                    <h5>Software Engineer</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="mt-4">Personal Information</h5>
                                    <div class="row mt-2">
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Date of Birth : </b> <?= $userInfo['dob']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Gender : </b> <?= $userInfo['gender']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>State : </b> <?= $userInfo['state']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Nationality : </b> <?= $userInfo['nationality']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-12">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Address : </b> <?= $userInfo['address']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Phone : </b> <?= $userInfo['phone']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Email : </b> <?= $userInfo['email']?>
                                                </li>
                                            </ol>
                                        </div>

                                    </div>
                                    <h5>Skills</h5>
                                    <p class="card-text">
                                            <?php
                                                foreach (explode(',', $userInfo['skills']) as $skill) {
                                                    echo '
                                                    <span class="badge badge-primary badge-pill p-1">'.$skill .'</span>';
                                                }
                                            ?>
                                    </p>
                                    <?php 
                                        if($userInfo['userId'] == $userId) { ?>
                                            <center>
                                                <div class="col-md-12 d-flex justify-content-center">
                                                    <a href="profile-edit?user=<?= $userInfo['userId']?>" class="mx-2 btn btn-primary">Edit Profile</a>
                                                </div>
                                            </center>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
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
    <script src="../assets/js/profile-edit.js"></script>
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