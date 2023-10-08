<?php
include_once "../config.php";
unset($_SESSION['nasa_user_id']);
session_destroy();
header('Location:' . WEBSITE_URL . "auth/");
exit;
