<?php
include_once '../config.php';
class User
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }

    public function getUserDetails()
    {
        /*
         session already started in  the config file
         Check if the user session data exists
        */
        if (isset($_SESSION['nasa_user_id'])) {
            $userId = $_SESSION['nasa_user_id'];
            $query = "SELECT userId, firstName, lastName, dob, gender, phone, email, state, nationality, address, skills, isVerified FROM users
            WHERE userId = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            return $userDetails;
        }

        return null;
    }

    public function getUser($userId)
    {
        if (isset($_SESSION['nasa_user_id'])) {
            $query = "SELECT userId, firstName, lastName, dob, gender, phone, email, state, nationality, address, skills FROM users
            WHERE userId = :userId";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $userDetails = $stmt->fetch(PDO::FETCH_ASSOC);

            return $userDetails;
        }

        return null;
    }

    public function updateUserInfo($firstName, $lastName, $userId, $dob, $gender, $email, $phone, $state, $address, $nationality, $skills)
    {


        // Prepare the update query
        $query = "UPDATE users SET firstName = :firstName, lastName = :lastName, dob = :dob, gender = :gender, email = :email, phone = :phone, state = :state, address = :address, nationality=:nationality, skills=:skills WHERE userId = :userId";
        $statement = $this->db->prepare($query);

        // Bind the parameters
        $statement->bindParam(':firstName', $firstName);
        $statement->bindParam(':lastName', $lastName);
        $statement->bindParam(':dob', $dob);
        $statement->bindParam(':gender', $gender);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':phone', $phone);
        $statement->bindParam(':state', $state);
        $statement->bindParam(':address', $address);
        $statement->bindParam(':nationality', $nationality);
        $statement->bindParam(':skills', $skills);
        $statement->bindParam(':userId', $userId);

        // Execute the update query
        $result = $statement->execute();

        if (!$result) {
            // Handle the case where the update fails
            return false;
        }

        return true;
    }

    public function checkEmailExists($email, $userId)
    {
        // Prepare the query to check if the email exists
        $query = "SELECT COUNT(*) FROM users WHERE email = :email AND userId = :userId";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':email', $email);
        $statement->bindParam(':userId', $userId);
        $statement->execute();

        $count = $statement->fetchColumn();

        return $count > 0;
    }
    public function updatePassword($currentPassword, $newPassword, $userId)
    {
        // Fetch the current hashed password from the database using $userId
        $query = "SELECT password FROM users WHERE userId = :userId";
        $statement = $this->db->prepare($query);
        $statement->bindParam(':userId', $userId);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            // Handle the case where the user doesn't exist
            return false;
        }

        $hashedPassword = $result['password'];

        // Verify the current password
        if (!password_verify($currentPassword, $hashedPassword)) {
            // Handle incorrect current password
            return "Incorrect password";
        }

        // Generate a new hashed password
        $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $updateQuery = "UPDATE users SET password = :newPassword WHERE userId = :userId";
        $updateStatement = $this->db->prepare($updateQuery);
        $updateStatement->bindParam(':newPassword', $newHashedPassword);
        $updateStatement->bindParam(':userId', $userId);
        $updateResult = $updateStatement->execute();

        if (!$updateResult) {
            // Handle the case where the password update fails
            return false;
        }

        return true;
    }

}

$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$user = new User();
switch ($action) {
    case "fetch_user_information":
        $getUserInformation = $user->getUserDetails();
        $response = $getUserInformation;
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case "fetch_user":
        $userId = $_GET['userId'];
        $response = $getUser($userId);
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'update_user_profile':
        $response = array();
        $firstName = filter_input(INPUT_POST, 'fname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $lastName = filter_input(INPUT_POST, 'lname', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $userId = $_POST['userId'];
        $dob = $_POST['dob'];
        $gender = $_POST['gender'];
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $state = $_POST['state'];
        $nationality = $_POST['nationality'];
        $skills = implode(',', $_POST['skills']);
        $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        
        $update = $user->updateUserInfo($firstName, $lastName, $userId, $dob, $gender, $email, $phone, $state, $address, $nationality, $skills);
        if ($update) {
            $response['success'] = true;
            $response['message'] = "Updated successfully!";
            $_SESSION['msg'] = "Profile has been updated successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "An error occurred! Please try again later.";
            $_SESSION['msg'] = "An error occurred! Please try again later.";
        }        
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/profile-edit");
        break;
    case 'update_user_password':
        $response = array();
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        $confirmPassword = $_POST['confirmPassword'];
        $userId = $_POST['userId'];
        // Call the updatePassword function
        $update = $user->updatePassword($currentPassword, $newPassword, $userId);
        if ($update === "Incorrect password") {
            $response['success'] = false;
            $response['message'] = "Incorrect password";
            $_SESSION['msg'] = "Incorrect password.";
        } else if ($update) {
            $response['success'] = true;
            $response['message'] = "Password updated successfully!";
            $_SESSION['msg'] = "Password updated successfully!";
        } else {
            $response['success'] = false;
            $response['message'] = "Failed to update password. Please try again.";
            $_SESSION['msg'] = "Failed to update password. Please try again.";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/profile-edit");
        break;
    default:
        // echo "an error";
        break;
}
