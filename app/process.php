<?php
require_once "../config.php";
if (!isset($_SESSION['nasa_user_id'])) {
    header('Location:' . WEBSITE_URL . "auth");
    exit;
}else{
    $User = new User(); $Skills = new Skills();
    
// update Profile
// if(isset($_POST['editProfile'])){
//     $dob = $_POST['userId'];
//     $fname = htmlspecialchars($_POST['fname']);
//     $lname = htmlspecialchars($_POST['lname']);
//     $dob = $_POST['dob'];
//     $gender = $_POST['gender'];
//     $email = $_POST['email'];
//     $phone = $_POST['phone'];
//     $address = htmlspecialchars($_POST['address']);
//     $skills = implode(',', $_POST['skills']);
  
//     $process = $User->editProfile($agencyid, $name, $type, $role, $email, $phone, $address);
//     if ($process) {
//       $_SESSION['msg'] = 'Your profile has been updated';
//       header("Location:profile-edit");
//     }else{
//       $_SESSION['msg'] = 'There is a Problem, try again';
//       header("Location:profile-edit");
//     }
// }

}

?>