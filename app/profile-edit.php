<?php
$page = 'index'; //to add this as active class
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $userId = $_SESSION['nasa_user_id'];
    $User = new User(); $Skills = new Skills();
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

    <title><?= WEBSITE_NAME ?> | Profile Update</title>

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
                        <div class="col-md-8">
                            <div class="card shadow mb-4">
                                <!-- Card Header - Dropdown -->
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile</h6>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <form id="registrationForm" action="../classes/User.php?f=update_user_profile" method="POST">
                                        <div id="status-message" class="alert"></div>
                                        <h5 class="text-muted">Personal Information</h5>
                                        <input type="hidden" name="userId" value="<?= $userId?>">
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <label class="form-label">First Name</label>
                                                <input type="text" name="fname" class="form-control form-control-user" id="exampleFirstName"
                                                    value="<?= $userInfo['firstName']?>">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Last Name</label>
                                                <input type="text" name="lname" class="form-control form-control-user" id="exampleLastName"
                                                    value="<?= $userInfo['lastName']?>">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <label class="form-label">Date of Birth</label>
                                                <input type="date" name="dob" class="form-control form-control-user" id="dob"
                                                    value="<?= $userInfo['dob']?>">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Gender</label>
                                                <select name="gender" class="form-control form-control-user" id="gender">
                                                    <option><?= $userInfo['gender']?></option>
                                                    <option>Male</option>
                                                    <option>Female</option>
                                                </select>
                                            </div>
                                        </div>
                                        <h5 class="text-muted mt-4">Contact Information</h5>
                                        <div class="form-group row">
                                            <div class="col-sm-5">
                                                <label class="form-label">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-control-user" id="phone"
                                                    value="<?= $userInfo['phone']?>">
                                                <small id="phone-message"></small>
                                            </div>
                                            <div class="col-sm-7">
                                                <label class="form-label">Email</label>
                                                <input type="email" name="email" class="form-control form-control-user" id="email"
                                                    value="<?= $userInfo['email']?>">
                                                <small id="email-message"></small>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-sm-6 mb-3 mb-sm-0">
                                                <label class="form-label">State/Province</label>
                                                <input type="text" name="state" class="form-control form-control-user" id="state"
                                                    placeholder="State" value="<?= $userInfo['state']?>">
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="form-label">Nationality</label>
                                                <input type="text" name="nationality" class="form-control form-control-user" id="nationality"
                                                    value="<?= $userInfo['nationality']?>">
                                            </div>
                                            <div class="col-sm-12 mt-2">
                                                <label class="form-label">Address</label>
                                                <input type="text" name="address" class="form-control form-control-user" id="address"
                                                    value="<?= $userInfo['address']?>">
                                            </div>
                                        </div>
                                        <h5 class="text-muted mt-4">Skills</h5>
                                        <div class="form-group row">
                                            <div class="col-sm-12">
                                                <?php
                                                    foreach (explode(',', $userInfo['skills']) as $skill) {
                                                        echo '
                                                        <span class="badge badge-primary badge-pill p-2">'.$skill .'</span>';
                                                    }
                                                ?>
                                            </div>
                                            <div class="col-sm-12 mt-2">
                                                <select class="select2 form-control" multiple="multiple" data-placeholder="Select Skill(s)" style="width: 100%;" name="skills[]" required>
                                                    <?php
                                                        foreach ($skills as $skill) {
                                                            echo '
                                                            <option>'.$skill['title'] .'</option>';
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-user btn-block" name="editProfile" id="submitButton">
                                            Save
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Area Chart -->
                        <div class="col-lg-4">
                            <div class="card shadow mb-2">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Update Password</h6>
                                </div>
                                <div class="card-body mt-0 pt-0">
                                    <form id="updatePasswordForm" action="../classes/User.php?f=update_user_password" method="POST">
                                        <div id="status-message2" class="alert"></div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">Current Password</label>
                                            <input type="password" class="form-control" name="currentPassword" id="currentPassword" placeholder=" ">
                                            <small id="password-message"></small>
                                        </div>
                                        <div class="form-group">
                                            <label for="email" class="form-label">New Password</label>
                                            <input type="password" class="form-control" name="newPassword" id="newPassword" placeholder=" ">
                                            <small id="new-message"></small>
                                        </div>
                                        <input type="hidden" name="userId" value="<?= $userId?>">
                                        <div class="form-group">
                                            <label for="email" class="form-label">Confirm Password</label>
                                            <input type="password" class="form-control" name="confirmPassword" id="confirmPassword" placeholder=" ">
                                            <small id="confirm-message"></small>
                                        </div>
                                        <div class="d-flex justify-content-center ">
                                            <button class="btn btn-primary col-12 mt-3" type="submit" >Change Password</button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                            <!-- <div class="card shadow mb-2">
                                <div
                                    class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary">Profile Picture</h6>
                                </div>
                                <div class="card-body mt-0 pt-0">
                                    <form id="updatePictureForm">
                                        <center>
                                            <img src="" class="rounded-circle" width="100">
                                        </center>
                                        <div id="profile-message2" class="alert"></div>
                                        <div class="form-group">
                                            <label for="picture" class="form-label">Picture</label>
                                            <input type="file" class="form-control" name="upload" id="picture" placeholder=" ">
                                            <small id="picture-message"></small>
                                        </div>
                                        <input type="hidden" name="userId" value="" id="changePUserId">
                                        <div class="d-flex justify-content-center ">
                                            <button class="btn btn-primary col-12 mt-3" data-bs-toggle="modal" data-bs-target="#updatePassword">Change Password</button>
                                        </div>
                                    </form>
                                </div>

                            </div> -->
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