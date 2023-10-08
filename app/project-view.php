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
    $User = new User(); $Contributors = new Contributors(); $Request = new Request(); $Project = new Project();
    $projects = $Project->fetchProject($projectId);;
    $checkUserRequest = $Request->checkUserRequests($projectId, $userId);
    $requests = $Request->fetchRequests($projectId);
    $contributors = $Contributors->fetchContributors($projectId);
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

    <title><?= WEBSITE_NAME ?> | Project View</title>

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
                        <!-- Area Chart -->
                        <div class="col-xl-8 col-lg-8">
                            <div class="card shadow mb-4">
                            <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between"
                                >
                                    <h6 class="m-0 font-weight-bold text-primary">
                                    Project Details
                                    </h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <h1 class="card-title"><?= $projects[0]['title']?></h1>
                                    <h6 class="text-muted mt-0"><?= $projects[0]['category']?> <i class="fa fa-dot-circle"></i> <?= $projects[0]['visibility']?> <i class="fa fa-dot-circle"></i> <?= substr($projects[0]['createdAt'],0,10)?></h6>
                                    
                                    <p class="card-text">
                                            <?php
                                                foreach (explode(',', $projects[0]['tags']) as $skill) {
                                                    echo '
                                                    <span class="badge badge-primary badge-pill p-1">'.$skill .'</span>';
                                                }
                                            ?>
                                        </p>
                                    <h5 class="text-muted font-weight-bold">Project Description</h5>
                                    <p><?= $projects[0]['description']?></p>
                                    <h5 class="text-muted font-weight-bold">Project Requirements</h5>
                                    <p><?= $projects[0]['requirements']?></p>
                                    <h5 class="text-muted font-weight-bold">Funding information</h5>
                                    <p>Funding Amount: <b>$ <?= $projects[0]['fundingAmount']?></b></p>
                                    <p><?= $projects[0]['fundingDescription']?></p>
                                    <h5 class="text-muted font-weight-bold">Other Information</h5>
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Project Lead : </b> 
                                                <?php 
                                                     $projectLead = $User->getUser($projects[0]['userId']); 
                                                     echo $projectLead['firstName'] ." ". $projectLead['lastName'];
                                                ?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Category : </b> <?= $projects[0]['category']?>
                                                </li>
                                            </ol>
                                        </div>
                                        <div class="col-md-6">
                                            <ol class="breadcrumb">
                                                <li class="breadcrumb-item">
                                                <b>Visibility : </b> <?= $projects[0]['visibility']?>
                                                </li>
                                            </ol>
                                        </div>
                                    </div>
                                    <?php 
                                        if($projects[0]['userId'] == $userId) { ?>
                                            <center>
                                                <div class="col-md-12 d-flex justify-content-center">
                                                    <a href="project-edit?project=<?= $projects[0]['projectId']?>" class="mx-2 btn btn-primary">Edit Project</a>
                                                    <form action="../classes/Project.php?f=delete_project" method="POST">
                                                        <input type="hidden" name="projectId" value="<?= $projectId?>">
                                                        <button type="submit" class="btn btn-primary">Delete Project</button>
                                                    </form>
                                                </div>
                                            </center>
                                    <?php   } else{
                                        if($checkUserRequest > 0) {}else{?>
                                        <center>
                                            <div class="col-md-12 d-flex justify-content-center">
                                                <a href="request-send?project=<?= $projects[0]['projectId']?>" class="mx-2 btn btn-primary">Request to Contribute</a>
                                            </div>
                                        </center>
                                    <?php } } ?>
                                </div>
                            </div>
                        </div>

                        <!-- Pie Chart -->
                        <div class="col-xl-4 col-lg-5">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Contributors</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <h5>Project Manager</h5>
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between">
                                            <div>
                                            <i class="fa fa-user-circle"></i> <?= $projectLead['firstName'] ." ". $projectLead['lastName'];?>
                                            </div>
                                            <div>
                                                <a href="messages?user=<?= $projects[0]['userId']?>" class="btn py-1"><i class="fa fa-envelope"></i></a>
                                            </div>
                                            
                                        </li>
                                    </ul>
                                    <h5>Members</h5>
                                    <ul class="list-group">
                                        <?php 
                                            foreach ($contributors as $people) { ?>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <div>
                                                <i class="fa fa-user-circle"></i> <?= $people['firstName'] ." ". $people['lastName']?>
                                                </div>
                                                <div>
                                                    <a href="chatbox?user=<?= $people['userId']?>" class="btn py-1"><i class="fa fa-envelope"></i></a>
                                                </div>
                                                
                                            </li>
                                        <?php }?>
                                    </ul>
                                </div>
                            </div>
                            <?php
                                if($userId == $projects[0]['userId']){ ?>
                                <div class="card shadow mb-4">
                                    <!-- Card Header - Dropdown -->
                                    <div
                                        class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                        <h6 class="m-0 font-weight-bold text-primary">Requests</h6>
                                    </div>
                                    <!-- Card Body -->
                                    <div class="card-body">
                                        <ul class="list-group">
                                        <?php 
                                            foreach ($requests as $request) { ?>
                                            <li class="list-group-item d-flex justify-content-between">
                                                <div>
                                                <i class="fa fa-user-circle"></i> <?= $request['firstName'] ." ". $request['lastName']?>
                                                </div>
                                                <div>
                                                    <a href="request-view?request=<?= $request['requestId']?>" class="btn py-1"><i class="fa fa-eye"></i></a>
                                                    <a href="chatbox?user=<?= $request['userId']?>" class="btn py-1"><i class="fa fa-envelope"></i></a>
                                                </div>
                                                
                                            </li>
                                            <?php }?>
                                        </ul>
                                    </div>
                                </div>
                            <?php  } ?>
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