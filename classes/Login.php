<?php
include_once '../config.php';
class Login
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }

    
    public function user_login($username, $password)
    {
        $password = trim($password); // Trim any leading/trailing whitespace

        // Check if username and password are provided and valid
        if (!empty($username) && !empty($password) && $username !== false) {
            // Perform database query to check user credentials
            $query = "SELECT * FROM users WHERE email = :username OR email = :username OR phone = :username";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':username', $username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($user) {
                // User found, check password
                $storedPassword = $user['password'];
                if (password_verify($password, $storedPassword)) {
                    $status = $user['status'];
                    // Handle different user statuses
                    switch ($status) {
                        case '1':
                            $createSession = $this->createUserSession($user['userId'], $user['email'], $user['firstName'], $user['lastName']);
                            $this->createUserLogs($username, $user['userId'], $_SERVER['REMOTE_ADDR'], 'success');
                            if ($createSession) {
                                return 'success';
                            } else {
                                return 'session_failed';
                            }
                        case '0':
                            $this->createUserLogs($username, $user['userId'], $_SERVER['REMOTE_ADDR'], 'blocked');
                            return 'blocked';
                        default:
                            $this->createUserLogs($username, $user['userId'], $_SERVER['REMOTE_ADDR'], 'unknown');
                            return 'unknown';
                    }
                } else {
                    $this->createUserLogs($username, $user['userId'], $_SERVER['REMOTE_ADDR'], 'invalid_password');
                    return 'invalid_password';
                }
            } else {
                $this->createUserLogs($username, null, $_SERVER['REMOTE_ADDR'], 'user_not_found');
                return 'user_not_found';
            }
        } else {
            return 'invalid_input';
        }
    }

    private function createUserSession($userId, $email, $firstName, $lastName)
    {
        $_SESSION['nasa_user_id'] = $userId;
        return true;
    }

    public function destroyUserSession()
    {
        session_unset();
        session_destroy();
    }

    private function createUserLogs($username, $userId, $IPAddress, $status)
    {
        $query = "INSERT INTO userlogs (username, userId, IPAddress, status) VALUES (:username, :userId, :IPAddress, :status)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':userId', $userId);
        $stmt->bindParam(':IPAddress', $IPAddress);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
    }

    function userForgotPassword($email)
    {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {

            $register = new Registration();
            $verificationCode = $register->generateRandomCode();
            $query2 = "UPDATE users SET verificationCode = :verificationCode WHERE userId = :userId";
            $stmt2 = $this->db->prepare($query2);
            $stmt2->bindParam(':verificationCode', $verificationCode);
            $stmt2->bindParam(':userId', $user['userId']);
            $stmt2->execute();

            $emailSender = new EmailSender();
            // Prepare the email data
            $recipient = $email;
            $subject = 'Password Reset';
            $templateName = 'password-reset.html.twig';
            $link = WEBSITE_URL . "auth/reset-password?v=" . $verificationCode;
            $website_name = WEBSITE_NAME;
            $templateVariables = [
                'name' => $user['firstName'] . " " . $user['lastName'],
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
    public function validateResetPasswordRequest($code)
    {
        $query = "SELECT * FROM users WHERE verificationCode = :code";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user) {
            return array(true, $user['userId']);
        } else {
            return array(false, null);
        }
    }

    public function resetPassword($password, $userId)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $query2 = "UPDATE users SET password = :password WHERE userId = :userId";
        $stmt2 = $this->db->prepare($query2);
        $stmt2->bindParam(':password', $hashedPassword);
        $stmt2->bindParam(':userId', $userId);
        if ($stmt2->execute()) {
            $recipient = $this->getUserEmail($userId);
            $subject = 'Password Changed';
            $templateName = 'password-changed.html.twig';
            $website_name = WEBSITE_NAME;
            $templateVariables = [
                'website_name' => $website_name
            ];
            $emailSender = new EmailSender();
            // Send the email
            $emailSender->sendEmail($recipient, $subject, $templateName, $templateVariables);
            return true;
        } else {
            return false;
        }
    }

    public function getUserEmail($userId)
    {
        $query = "SELECT * FROM users WHERE userId = :userId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user['email'];
    }
}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$auth = new Login();
switch ($action) {
    case 'user_login':
        $response = array();
        $username = htmlspecialchars($_POST['email']);
        $password = $_POST['password'];
        $checkLogin = $auth->user_login($username, $password);

        // Handle the login response
        switch ($checkLogin) {
            case 'success':
                $response['success'] = true;
                $response['message'] = "Login successful";
                break;
            case 'inactive':
                $response['success'] = false;
                $response['message'] = "User is inactive";
                break;
            case 'blocked':
                $response['success'] = false;
                $response['message'] = "User is blocked";
                break;
            case 'invalid_password':
                $response['success'] = false;
                $response['message'] = "Invalid password";
                break;
            case 'session_failed':
                $response['success'] = false;
                $response['message'] = "Cannot grant access to your account now. Try again later";
                break;

            case 'user_not_found':
                $response['success'] = false;
                $response['message'] = "User not found";
                break;
            default:
                $response['success'] = false;
                $response['message'] = "Unknown error.";
                break;
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'logout':
        $auth->destroyUserSession();
        break;
    case 'forgot_password':
        $response = array();
        $email = $_POST['email'];
        $forgotPassword = $auth->userForgotPassword($email);
        if ($forgotPassword) {
            $response['success'] = true;
            $response['message'] = "An email has been sent to you for further instruction";
        } else {
            $response['success'] = false;
            $response['message'] = "User not found!";
        }

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'check_reset_password':
        $response = array();
        $code = $_GET['value'];
        $check = $auth->validateResetPasswordRequest($code);

        $response['success'] = $check[0];
        $response['message'] = $check[0] ? "Verified" : "Could not verify";
        $response['userId'] = $check[1];

        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'reset_password':
        $response = array();
        $password = $_POST['password'];
        $userId = $_POST['userId'];
        $reset = $auth->resetPassword($password, $userId);
        $response['success'] = $reset;
        $response['message'] = $reset ? "Password Changed Successfully" : "Could not change password";
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    default:
        // echo "invalid action";
        break;
}
