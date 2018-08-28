<?php
include_once 'database.php';
class CoreConfig{

    public function __construct(){
        return $this->getConfig();
    }

    public function getConfig(){

        $db = new Database();
        $query = "SELECT * FROM config";
        $stmt = $db->conn->prepare($query);
        $stmt->execute();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
            $this->{$row['config_key']} = $row['config_value'];
        }

        return $this;
    }
}
?>
