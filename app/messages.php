<?php
$page = 'messages'; //to add this as active class
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $userId = $_SESSION['nasa_user_id'];
    $User = new User(); 
    if(!isset($_GET['user'])){
        header('Location:' . WEBSITE_URL . "app");
    }else{
        $otherUserId = $_GET['user'];
        $otherUser = $User->getUser($otherUserId);
    }
    $me = $User->getUserDetails();
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

    <title><?= WEBSITE_NAME ?> | Messages</title>

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
                        <h1 class="h3 mb-0 text-gray-800">Messages</h1>
                        <!-- <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i
                                class="fas fa-download fa-sm text-white-50"></i> Generate Report</a> -->
                    </div>
                    <div class="row">
                        <script>
                            (function(t,a,l,k,j,s){
                            s=a.createElement('script');s.async=1;s.src="https://cdn.talkjs.com/talk.js";a.head.appendChild(s)
                            ;k=t.Promise;t.Talk={v:3,ready:{then:function(f){if(k)return new k(function(r,e){l.push([f,r,e])});l
                            .push([f])},catch:function(){return k&&new k()},c:l}};})(window,document,[]);
                        </script>
                        <div class="col-md-10 mx-auto">
                        <!-- container element in which TalkJS will display a chat UI -->
                            <div id="talkjs-container" style="width: 90%; margin: 30px; height: 500px">
                                <i>Loading chat...</i>
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
                        <span>Copyright &copy; Your Website 2021</span>
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
    <script href="../assets/vendor/jquery/jquery.min.js"></script>
    <script href="../assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script href="../assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script href="../assets/js/sb-admin-2.min.js"></script>
    <!-- Page level custom scripts -->
    <script src="../assets/js/index.js"></script>
    <script>
            Talk.ready.then(function () {
                var me = new Talk.User({
                    id: <?= json_encode($me['userId'])?>,
                    name: <?= json_encode($me['firstName']." ".$me['lastName'])?>,
                    email: <?= json_encode($me['email'])?>,
                    photoUrl: 'https://talkjs.com/images/avatar-1.jpg',
                    welcomeMessage: 'Hey there! How are you? :-)',
                });
                window.talkSession = new Talk.Session({
                    appId: 'tp8BcbPX',
                    me: me,
                });
                var other = new Talk.User({
                    id: <?= json_encode($otherUser['userId'])?>,
                    name: <?= json_encode($otherUser['firstName']." ".$otherUser['lastName'])?>,
                    email: <?= json_encode($otherUser['email'])?>,
                    photoUrl: 'https://talkjs.com/images/avatar-5.jpg',
                    welcomeMessage: 'Hey, how can I help?',
                });
                var conversation = talkSession.getOrCreateConversation(
                    Talk.oneOnOneId(me, other)
                );
                conversation.setParticipant(me);
                conversation.setParticipant(other);
        
            var inbox = talkSession.createInbox();
            inbox.select(conversation);
            inbox.mount(document.getElementById('talkjs-container'));
        });
    </script>

</body>

</html>