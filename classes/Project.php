<?php
include_once '../config.php';
class Project
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }
    public function getProjects()
    {
        $status = 1;
        $query = "SELECT * FROM projects WHERE status = :status ORDER BY projectId DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $operators;
    }

    public function fetchProject($projectId)
    {
        $status = 1;
        $query = "SELECT * FROM projects WHERE projectId = :projectId ORDER BY projectId DESC";
        $stmt = $this->db->prepare($query);
        // $stmt->bindParam(':status', $status);
        $stmt->bindParam(':projectId', $projectId);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $operators;
    }
    
    public function editProject($projectId, $title, $category, $tags, $descripton, $fundingType, $fundingAmount, $fundingDescription, $requirements, $visibility, $status)
    {
        $query = "UPDATE projects
          SET title = :title, category = :category, tags = :tags, description = :description,
              fundingType = :fundingType, fundingAmount = :fundingAmount, fundingDescription = :fundingDescription, 
              requirements = :requirements, visibility = :visibility, status = :status WHERE projectId = :projectId";
        $stmt = $this->db->prepare($query); 
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':tags', $tags);
        $stmt->bindParam(':description', $descripton);
        $stmt->bindParam(':fundingType', $fundingType);
        $stmt->bindParam(':fundingAmount', $fundingAmount);
        $stmt->bindParam(':fundingDescription', $fundingDescription);
        $stmt->bindParam(':requirements', $requirements);
        $stmt->bindParam(':visibility', $visibility);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT); // Bind as integer
        // Attempt to edit the package
        $editPackage = $stmt->execute();
        return $editPackage;
    }

    public function addProject($title, $userId, $category, $tags, $descripton, $fundingType, $fundingAmount, $fundingDescription, $requirements, $visibility, $status)
    {
        // if (isset($_SESSION['nasa_user_id'])) {
            $query = "INSERT INTO projects (title, userId, category, tags, description, license, link, fundingType, fundingSource, fundingAmount, fundingDescription, requirements, visibility, status) 
                VALUES (:title, :userId, :category, :tags, :description, :license, :link, :fundingType, :fundingSource, :fundingAmount, :fundingDescription, :requirements, :visibility, :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':category', $category);
            // $stmt->bindParam(':field', $field);
            $stmt->bindParam(':tags', $tags);
            $stmt->bindParam(':description', $descripton);
            $stmt->bindParam(':license', $license);
            $stmt->bindParam(':link', $link);
            $stmt->bindParam(':fundingType', $fundingType);
            $stmt->bindParam(':fundingAmount', $fundingAmount);
            $stmt->bindParam(':fundingSource', $fundingSource);
            $stmt->bindParam(':fundingDescription', $fundingDescription);
            $stmt->bindParam(':requirements', $requirements);
            $stmt->bindParam(':visibility', $visibility);
            $stmt->bindParam(':status', $status);
            $editProject = $stmt->execute();

            return $this->db->lastInsertId();
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
$Project = new Project();
switch ($action) {
    // fetching all projects
    case 'fetch_projects':
        $getProjects = $Project->getProjects();
        $response = $getProjects;
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    // adding project
    case 'add_project':
        $userId = $_POST['userId'];
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
        $projectAdd =  $Project->addProject($title, $userId, $category, $tags, $description, $fundingType, $fundingAmount, $fundingDescription, $requirements, $visibility, $status);
        if($projectAdd){
            $Contributors =new Contributors();
            $Contributors->addContributor($projectAdd,$userId,'Project Manager',$status);
            $response = array('success' => true, 'message' => 'Project added successfully');
            $_SESSION['msg'] = "Project added successfully";
        } else {
            $response = array('success' => false, 'message' => 'Can not add the project.Try again later');
            $_SESSION['msg'] = "Can not add the project.Try again later.";
        }
        // header('Content-Type: application/json');
        // echo json_encode($response);
        header('Location:' . WEBSITE_URL . "app/projects");
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
