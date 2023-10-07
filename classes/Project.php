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

    
    public function editPackage($packageId, $title, $service, $biller, $size, $price, $validity, $type)
    {
        $query = "UPDATE packages 
          SET title = :title, service = :service, biller = :biller, size = :size, price = :price, 
              validity = :validity, type = :type WHERE packageId = :packageId";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':service', $service);
        $stmt->bindParam(':biller', $biller);
        $stmt->bindParam(':size', $size);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':validity', $validity);
        $stmt->bindParam(':type', $type);
        $stmt->bindParam(':packageId', $packageId, PDO::PARAM_INT); // Bind as integer
        // Attempt to edit the package
        $editPackage = $stmt->execute();
        return $editPackage;
    }

    public function addProject($title, $userId, $category, $field, $tags, $descripton, $license, $link, $fundingType, $fundingSource, $fundingAmount, $fundingDescription, $requirements, $visibility, $status)
    {
        // if (isset($_SESSION['nasa_user_id'])) {
            $query = "INSERT INTO packages (title, userId, category, field, tags, description, license, link, fundingType, fundingSource, fundingAmount, fundingDescription, requirements, visibility, status) 
                VALUES (:title, :userId, :category, :field, :tags, :description, :license, :link, :fundingType, :fundingSource, :fundingAmount, :fundingDescription, :requirements, :visibility, :status)";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':userId', $userId);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':field', $field);
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
            $addProject = $stmt->execute();

            return $addProject;
        // }else{
        //     return false;
        // }
    }


}


$action = !isset($_GET['f']) ? 'none' : strtolower($_GET['f']);
$Project = new Project();
switch ($action) {
    case 'fetch_projects':
        $getProjects = $project->getProjects();
        $response = $getProjects;
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    case 'add_project':
        $userId = $_POST['userId'];
        $title = $_POST['title'];
        $category = $_POST['category'];
        $field = $_POST['field'];
        $tags = $_POST['tags'];
        $description = $_POST['description'];
        $license = $_POST['license'];
        $link = $_POST['link'];
        $fundingType = $_POST['fundingType'];
        $fundingAmount = $_POST['fundingAmount'];
        $fundingSource = $_POST['fundingSource'];
        $fundingDescription = $_POST['fundingDescription'];
        $requirements = $_POST['requirements'];
        $visibility = $_POST['visibility'];
        $status = 1;
        $projectAdd =  $Project->addProject($title, $userId, $category, $field, $tags, $description, $license, $link, $fundingType, $fundingSource, $fundingAmount, $fundingDescription, $requirements, $visibility, $status);
        if($projectAdd){
            $response = array('success' => true, 'message' => 'Project added successfully');
        } else {
            $response = array('success' => false, 'message' => 'Can not add the project.Try again later');
        }
        header('Content-Type: application/json');
        echo json_encode($response);
        break;
    default:
        break;
}
