<?php

require_once '../config.php';

class Registration
{
    private $db;

    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }

    public function registerUser($firstName, $lastName, $email, $phone, $hashedPassword)
    {
        $code = $this->generateRandomCode();
        $query = "INSERT INTO users(firstName, lastName, email, phone, password, verificationCode, isVerified, status) 
                  VALUES (:firstName, :lastName, :email, :phone, :password, :verificationCode, :isVerified, :status)";
        $stmt = $this->db->prepare($query);

        $isVerified = 0; // the initial verification status is 0 (not verified)
        $userType = 'user';
        $status = 1; //active

        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':verificationCode', $code);
        $stmt->bindParam(':isVerified', $isVerified);
        $stmt->bindParam(':status', $status);

        // Attempt to register the user
        if ($stmt->execute()) {
            // send email
            // Instantiate the EmailSender
            // $emailSender = new EmailSender();
            // // Prepare the email data
            // $recipient = $email;
            // $subject = 'Account Verification';
            // $templateName = 'registration_email.html.twig';
            // $link = WEBSITE_URL . "auth/verify?v=" . $code;
            // $website_name = WEBSITE_NAME;
            // $templateVariables = [
            //     'name' => $firstName . " " . $lastName,
            //     'link' => $link,
            //     'website_name' => $website_name
            // ];
            // Send the email
            // $sendEmail = $emailSender->sendEmail($recipient, $subject,  $templateName, $templateVariables);
            return $this->db->lastInsertId();
        }else{
            return false;
        }
    }

    public function emailExists($email)
    {
        // Prepare the SQL query to check if the email exists
        $query = "SELECT COUNT(*) FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function verifyEmail($code)
    {
        $query = "SELECT * FROM users WHERE verificationCode = :code AND isVerified = :isVerified";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->bindValue(':isVerified', 0);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verification code matches
            $email = $user['email'];
            $firstName = $user['firstName'];
            $lastName = $user['lastName'];
            $userId = $user['userId'];

            $updateVerificationStatus = $this->updateVerificationStatus($userId);
            if ($updateVerificationStatus) {
                return true;
            }else{
                return false;
            }
        } else {
            return false;
        }
    }

    private function updateVerificationStatus($userId)
    {
        $query = "UPDATE users SET isVerified = :isVerified WHERE userId = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':isVerified', 1);
        $stmt->bindValue(':userId', $userId);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function resendVerificationLink()
    {
        if (isset($_SESSION['nasa_user_id'])) {
            $userId = $_SESSION['nasa_user_id'];
            $query = "SELECT firstName, lastName, email, userId FROM users WHERE userId = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);
            $email = $userDetails['email'];
            $firstName = $userDetails['firstName'];
            $lastName = $userDetails['lastName'];
            $isVerified = 0;
            $code = $this->generateRandomCode();
            $query2 = "UPDATE users SET verificationCode = :code, isVerified = :isVerified WHERE userId = :userId";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bindParam(':code', $code);
            $stmt2->bindParam(':isVerified', $isVerified);
            $stmt2->bindParam(':userId', $userId);
            $stmt2->execute();
            $emailSender = new EmailSender();
            $recipient = $email;
            $subject = 'Account Verification';
            $templateName = 'registration_email.html.twig';
            $link = WEBSITE_URL . "auth/verify?v=" . $code;
            $website_name = WEBSITE_NAME;
            $templateVariables = [
                'name' => $firstName . " " . $lastName,
                'link' => $link,
                'website_name' => $website_name

            ];
            // Send the email
            $emailSender->sendEmail($recipient, $subject, $templateName, $templateVariables);
            return true;
        } else {
            return false;
        }
    }

    public function generateRandomCode()
    {
        $randomBytes = random_bytes(10); // Generates 10 random bytes
        $code = bin2hex($randomBytes); // Convert the bytes to hexadecimal representation
        return $code;
    }

}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Registration();
switch ($action) {
    case 'check_email':
        $email = $_GET['value'];
        $exists = $auth->emailExists($email);
        // Prepare the response
        $response = array('exists' => $exists);
        // Send the response as JSON
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'register_user':
        // var_dump($VirtualAccount);
        $response = array();
        // Sanitize and validate the input
        $firstName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $password = $_POST['password'];
        $confirmPassword = $_POST['confirm-password'];
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Attempt to register the user
        $registrationResult = $auth->registerUser($firstName, $lastName, $email, $phone, $hashedPassword);
        if ($registrationResult) {
            // User registration successful
            $response['success'] = true;
            $response['message'] = "Registration successfull!";
        } else {
            // User registration failed
            $response['success'] = false;
            $response['message'] = "An error occured! Try again later";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'verify_email':
        $response = array();
        $verification_code = $_GET['value'];
        $verificationStatus =  $auth->verifyEmail($verification_code);
        if ($verificationStatus) {
            // User registration successful
            $response['success'] = true;
            $response['message'] = "Verified succesfully!";
        } else {
            $response['success'] = false;
            $response['message'] = "An error occured! Try again later";
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'resend_verification':
        $response = array();
        $resend = $auth->resendVerificationLink();

        if ($resend) {
            $response['success'] = true;
            $response['message'] = "Verification link sent!";
        } else {
            $response['success'] = false;
            $response['message'] = "Cannot send verification link!";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    default:
        $response = array('message' => "invalid action");
        echo json_encode($response);
        break;
}
