<?php
include_once '../objects/logan.php';

$logan = new Logan();

echo $logan->db->runStatement("INSERT INTO logging (log_type, log_message) VALUES ('Test', 'This is a test log - v2')" );
// $result = $database->runQuery("SELECT * FROM logging WHERE log_type='Test'" );

 ?>
