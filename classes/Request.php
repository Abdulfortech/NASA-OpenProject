<?php
include_once '../config.php';
class Request
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }

    public function fetchRequests($projectId)
    {
        $query = "SELECT r.userId, r.projectId, r.requestId, r.title, r.description, r.status, r.createdAt, u.userId, u.firstName, u.lastName, u.dob, u.gender, u.phone, u.email, u.state, u.nationality, u.address, u.skills, u.isVerified FROM requests r
            JOIN users u ON r.userId = u.userId
            WHERE r.projectId = :projectId AND r.status=1";
        // $query = "SELECT * FROM requests WHERE projectId = :projectId ORDER BY requestId DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':projectId', $projectId);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $operators;
    }

    public function updateRequests($requestId, $status)
    {
        $query = "UPDATE requests SET status=:status WHERE requestId = :requestId ";
        // $query = "SELECT * FROM requests WHERE projectId = :projectId ORDER BY requestId DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':requestId', $requestId);
        
        return $stmt->execute();
    }

    public function checkUserRequests($projectId, $userId)
    {
        $query = "SELECT COUNT(*) FROM requests WHERE projectId = :projectId AND userId = :userId AND status is not null";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':projectId', $projectId);
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        $count = $stmt->fetchColumn();
        return $count > 0;
    }

    public function fetchRequest($requestId)
    {
        $status = 1;
        $query = "SELECT * FROM requests WHERE requestId = :requestId ORDER BY requestId DESC";
        $stmt = $this->db->prepare($query);
        // $stmt->bindParam(':status', $status);
        $stmt->bindParam(':requestId', $requestId);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $operators;
    }
    

    public function addRequest($projectId, $userId, $subject, $description, $status)
    {
        // if (isset($_SESSION['nasa_user_id'])) {
            $query = "INSERT INTO requests (projectId, userId, title, description, status) 
                VALUES (:projectId, :userId, :title, :description, :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':projectId', $projectId);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':title', $subject);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':status', $status);
            $addRequest = $stmt->execute();

            return $addRequest;

    }

    public function deleteProject($projectId)
    {
        $query = "DELETE FROM projects WHERE projectId = :projectId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT); // Bind as integer
        // Attempt to edit the package
        $editPackage = $stmt->execute();
        return $editPackage;
    }

}


$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$Request = new Request(); $Contributors = new Contributors();
switch ($action) {
    // fetching all projects
    case 'fetch_projects':
        $getProjects = $Project->getProjects();
        $response = $getProjects;
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    // adding project
    case 'accept_request':
        $projectId = $_POST['projectId'];
        $userId = $_POST['userId'];
        $requestId = $_POST['requestId'];
        $status = 2;
        if($Request->updateRequests($requestId,$status)){
            $Contributors->addContributor($projectId,$userId,'Member', 1);
        }
        $_SESSION['msg'] = "Request updated successfully.";
        header('Location:' . WEBSITE_URL . "app/project-view?project=".$projectId);
        break;
    case 'reject_request':
        $userId = $_POST['userId'];
        $projectId = $_POST['projectId'];
        $requestId = $_POST['requestId'];
        $status = 0;
        $Request->updateRequests($requestId,$userId, $status);
        $_SESSION['msg'] = "Request updated successfully.";
        header('Location:' . WEBSITE_URL . "app/project-view?project=".$projectId);
        break;
    case 'add_request':
        $userId = $_POST['userId'];
        $projectId = $_POST['projectId'];
        $subject = $_POST['subject'];
        $description = $_POST['description'];
        $status = 1;
        $projectAdd =  $Request->addRequest($projectId, $userId, $subject, $description, $status);
        if($projectAdd){
            $response = array('success' => true, 'message' => 'Request added successfully');
            $_SESSION['msg'] = "request added successfully";
        } else {
            $response = array('success' => false, 'message' => 'Can not add the request.Try again later');
            $_SESSION['msg'] = "Can not add the request.Try again later.";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/project-view?project=".$projectId);
        break;
    default:
        break;
}
