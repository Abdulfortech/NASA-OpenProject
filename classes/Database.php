<?php
class Database {
  private $host;
  private $dbName;
  private $username;
  private $password;
  private $connection;

  public function __construct($host, $dbName, $username, $password) {
    $this->host = $host;
    $this->dbName = $dbName;
    $this->username = $username;
    $this->password = $password;
  }

  public function connect() {
    try {
      $this->connection = new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->username, $this->password);
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->connection;
    } catch (PDOException $e) {
      die("Database connection failed: " . $e->getMessage());
    }
  }

  public function disconnect() {
    $this->connection = null;
  }
}
?>
