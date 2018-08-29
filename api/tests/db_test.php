<?php
include_once '../config/database.php';

$database = new Database();
// $db_conn = $database->conn;

#echo var_dump($database);

// select all query
// $query = "SELECT * FROM config";
//
// // prepare query statement
// $stmt = $db_conn->prepare($query);
//
// // execute query
// $stmt->execute();
//
// while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
//         // extract row
//         // this will make $row['name'] to
//         // just $name only
//         echo var_dump($row);
//
//
// }

echo $database->runStatement("INSERT INTO logging (log_type, log_message) VALUES ('Test', 'This is a test log - v2')" );
// $result = $database->runQuery("SELECT * FROM logging WHERE log_type='Test'" );

 ?>
