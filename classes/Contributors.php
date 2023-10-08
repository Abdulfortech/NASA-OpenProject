<?php
include_once '../config.php';
class Contributors
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }

    public function fetchContributors($projectId)
    {
        $query = "SELECT r.contributorId, r.userId, r.projectId, r.status, r.createdAt, u.userId, u.firstName, u.lastName, u.dob, u.gender, u.phone, u.email, u.state, u.nationality, u.address, u.skills, u.isVerified FROM contributors r
            JOIN users u ON r.userId = u.userId
            WHERE r.projectId = :projectId AND r.status=1";
        // $query = "SELECT * FROM requests WHERE projectId = :projectId ORDER BY requestId DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':projectId', $projectId);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $operators;
    }


    public function addContributor($projectId, $userId, $role, $status)
    {
        // if (isset($_SESSION['nasa_user_id'])) {
            $query = "INSERT INTO contributors (projectId, userId, role, status) 
                VALUES (:projectId, :userId, :role, :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':projectId', $projectId);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':role', $role);
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
$Contributors = new Contributors();
switch ($action) {
    // fetching all projects
    case 'fetch_contributors':
        $getContributors = $Contributors->fetchContributors($projectId);
        $response = $getContributors;
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/project-view?project=".$projectId);
        break;
    case 'add_contributor':
        $userId = $_POST['userId'];
        $projectId = $_POST['projectId'];
        $status = 1;
        $role = 'Member';
        $projectAdd =  $Contributors->addContributor($projectId, $userId, $role, $status);
        if($projectAdd){
            $response = array('success' => true, 'message' => 'Contributor added successfully');
            $_SESSION['msg'] = "request added successfully";
        } else {
            $response = array('success' => false, 'message' => 'Can not add the request.Try again later');
            $_SESSION['msg'] = "Can not add the request.Try again later.";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/project-view?project=".$projectId);
        break;
    // updating project
    case 'edit_project':
        $projectId = $_POST['projectId'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        // $field = $_POST['field'];
        $tags = implode(',',$_POST['tags']);
        $description = $_POST['description'];
        $fundingType = $_POST['fundingType'];
        $fundingAmount = $_POST['fundingAmount'];
        $fundingDescription = $_POST['fundingDescription'];
        $requirements = $_POST['requirements'];
        $visibility = $_POST['visibility'];
        $status = 1;
        $projectAdd =  $Project->editProject($projectId, $title, $category, $tags, $description, $fundingType, $fundingAmount, $fundingDescription, $requirements, $visibility, $status);
        if($projectAdd){
            $response = array('success' => true, 'message' => 'Project has been updated successfully');
            $_SESSION['msg'] = "Project has been updated successfully.";
        } else {
            $response = array('success' => false, 'message' => 'Can not update the project.Try again later');
            $_SESSION['msg'] = "Can not update the project.Try again later.";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/project-edit?project=".$projectId);
        break;
    // deleting project
    case 'delete_project':
        $projectId = $_POST['projectId'];
        $project =  $Project->deleteProject($projectId);
        if($project){
            $response = array('success' => true, 'message' => 'Project has been deleted successfully');
            $_SESSION['msg'] = "Project has been deleted successfully";
        } else {
            $response = array('success' => false, 'message' => 'Can not delete the project.Try again later');
            $_SESSION['msg'] = "Can not delete the project.Try again later";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/projects");
        break;
    // deleting project
    case 'fetch_project':
        $projectId = $_POST['projectId'];
        $project =  $Project->fetchProject($projectId);
        if($project){
            $response = $project;
        } else {
            $response = array('success' => false, 'message' => 'Can not get the project.Try again later');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    default:
        break;
}
