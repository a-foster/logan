<?php
include_once '../objects/logan.php';

$logan = new Logan();

// echo $logan->db->runStatement("INSERT INTO logging (log_type, log_message) VALUES ('rubbidh', 'Test', 'This is a test log - v2')" );
$result = $logan->db->runQuery("SELECT * FROM logging WHERE log_type='Test'" );
echo var_dump($result);
 ?>
