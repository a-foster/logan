<?php
class Database{

    public function __construct(){
        $this->getConnection();
    }

    // get db credentials from base_config file
    private function getCredentials() {
        $db_config = file_exists('../shared/db_config') ? '../shared/db_config' : '/home/logan/db_config';
        $fh = fopen($db_config,'r');
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

        $this->conn = new mysqli($this->db_host, $this->db_user, $this->db_password, $this->db_name);

        // Check connection - TODO - log this somewhere
        if ($this->conn->connect_error) {
            die("Connection failed: " . $this->conn->connect_error);
        }
    }

    // run a select and return result set
    public function runQuery($query) {

        $result_set = array();
        $result = $this->conn->query( $query );

        if ($result && $result->num_rows > 0) {
            // push each row to result array
            while($row = $result->fetch_assoc()) {
                array_push($result_set, $row);
            }
        }
        return $result_set;
    }

    // for inserts, deletes, updates - only return success status
    public function runStatement($statement) {

        $stmt = $this->conn->query($statement);

        if ($stmt === TRUE) { return 'success'; }
        else {
            // log the error and return null so frontend knows it was a fail
            $sql_err = str_replace("'", "", $this->conn->error);
            $statement = str_replace("'", "", $statement);
            $this->runStatement("INSERT INTO logging (log_type, log_message) VALUES ('MySQL', 'Attempted to run: $statement - Error: $sql_err')");
        }
    }
}
?>
