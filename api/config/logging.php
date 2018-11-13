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

    // Return logs of a certain type (or all), limit to certain number (or all)
    function getLogs($type, $limit) {

        $sql = "SELECT * FROM logging";

        if ($type != '*') {
            $sql .= " WHERE type='$type'";
        }

        if ($limit != '*') {
            $limit = intval($limit);
            $sql .= " LIMIT $limit";
        }

        return $this->db->runQuery($sql);
    }
}
?>
