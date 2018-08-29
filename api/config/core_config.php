<?php
include_once 'database.php';
class CoreConfig{

    public function __construct(){
        return $this->getConfig();
    }

    public function getConfig(){

        $db = new Database();
        $results = $db->runQuery("SELECT * FROM config");

        foreach ($results as $row) {
            $this->{$row['config_key']} = $row['config_value'];
        }

        return $this;
    }
}
?>
