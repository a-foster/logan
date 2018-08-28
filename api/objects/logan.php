<?php
include_once '../config/core_config.php';
include_once '../config/database.php';
include_once '../config/logging.php';
include_once 'email.php';

class Logan{

    // constructor to initialise all config objects common to any logan request
    public function __construct(){

        // set core config
        $conf = new CoreConfig();
        $this->conf = $conf->getConfig();

        // set db connection
        $db = new Database();
        $this->db = $db->conn;

        // set logging object
        $this->log = new Logging();

        // email object
        $this->email = new Email();
    }
}
