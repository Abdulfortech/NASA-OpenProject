<?php
$page = 'projects'; //to add this as active class
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $userId = $_SESSION['nasa_user_id'];
    if(!isset($_GET['request'])){
        header('Location:' . WEBSITE_URL . "auth");
        exit;
    }else{
        $requestId = $_GET['request'];
    }
    $User = new User(); $Request = new Request(); $Project = new Project();
    $request = $Request->fetchRequest($requestId);
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

    <title><?= WEBSITE_NAME ?> | Request View</title>

    <!-- Custom fonts for this template-->
    <link href="../assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

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
                        <h1 class="h3 mb-0 text-gray-800">Projects</h1>
                        <a href="project-add" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-plus fa-sm text-white-50"></i> Add Project</a>
                    </div>

                    <div class="container-fluid">

                        <div class="row">
                            <div class="col-xl-8 col-lg-8 mx-auto">
                                <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                    >
                                        <h6 class="m-0 font-weight-bold text-primary">
                                        Request Details
                                        </h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <h1 class="card-title"><?= $request[0]['title']?></h1>
                                        <p><?= $request[0]['description']?></p>
                                        
                                        <?php 
                                            if($request[0]['status'] == 1) { ?>
                                                <center>
                                                    <div class="col-md-12 d-flex justify-content-center">
                                                        <form action="../classes/Request.php?f=accept_request" method="POST">
                                                            <input type="hidden" name="requestId" value="<?= $requestId?>">
                                                            <input type="hidden" name="projectId" value="<?= $request[0]['projectId']?>">
                                                            <input type="hidden" name="userId" value="<?= $request[0]['userId']?>">
                                                            <button type="submit" class="btn btn-primary mx-2">Accept</button>
                                                        </form>
                                                        <form action="../classes/Request.php?f=reject_request" method="POST">
                                                            <input type="hidden" name="requestId" value="<?= $requestId?>">
                                                                <input type="hidden" name="projectId" value="<?= $request[0]['projectId']?>">
                                                                <input type="hidden" name="userId" value="<?= $request[0]['userId']?>">
                                                            <button type="submit" class="btn btn-primary mx-2">Reject</button>
                                                        </form>
                                                    </div>
                                                </center>
                                        <?php   } else{ ?>
                                        <?php } ?>
                                    </div>
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

</body>

</html>