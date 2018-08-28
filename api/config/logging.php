<?php
include_once 'database.php';
class Logging{

    public function __construct(){
        $this->db = new Database();
        $this->table_name = "logging";
    }

    public function writeLog($type, $message){
      return $this->db->insert($this->table_name, array( "log_type" => $type, "log_message" => $message));
    }
}
?>
