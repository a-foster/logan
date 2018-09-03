<?php
include_once 'database.php';
class Logging{

    public function __construct(){
        $this->db = new Database();
        $this->table_name = "logging";
    }

    public function writeLog($type, $message){
      return $this->db->runStatement("INSERT INTO logging (log_type, log_message) VALUES ('$type', '$message')");
    }
}
?>
