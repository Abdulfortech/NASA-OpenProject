<?php
include_once '../config.php';
class Skills
{
    private $db;
    public function __construct()
    {
        global $database;
        $this->db = $database->connect();
    }
    public function getSkills()
    {
        $status = 1;
        $query = "SELECT * FROM skills WHERE status = :status ORDER BY skillId DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->execute();
        $operators = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $operators;
    }


}
