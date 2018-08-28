<?php
class Database{

    public function __construct(){
        $this->getConnection();
    }

    // get db credentials from base_config file
    private function getCredentials() {
        $fh = fopen('../shared/email_config','r');
        while ($line = fgets($fh)) {
          $line = str_replace(array("\r", "\n"), '', $line);
          $key_pair = explode("=", $line);
          $this->{$key_pair[0]} = $key_pair[1];
        }
        fclose($fh);
    }

    // get the database connection
    public function getConnection(){

        $this->getCredentials();
        $this->conn = null;

        try{
            $this->conn = new PDO("mysql:host=" . $this->db_host . ";dbname=" . $this->db_name, $this->db_user, $this->db_password);
            $this->conn->exec("set names utf8");
        }catch(PDOException $exception){
            echo "Connection error: " . $exception->getMessage();
        }
    }
}
?>
