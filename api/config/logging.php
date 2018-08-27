<?php
include_once 'database.php';
class Logging{

    public function __construct(){
        $this->db = new Database();
    }

    public function writeLog($type, $message){

      // query to insert record
      $query = "INSERT INTO logging SET log_type=:log_type, log_message=:log_message";

      // prepare query
      $stmt = $this->db->conn->prepare($query);

      // sanitize
      $this->log_type=htmlspecialchars(strip_tags($type));
      $this->log_message=htmlspecialchars(strip_tags($message));

      // bind values
      $stmt->bindParam(":log_type", $this->log_type);
      $stmt->bindParam(":log_message", $this->log_message);

      // execute query
      if($stmt->execute()){
          return true;
      }

      return false;
    }
}
?>
