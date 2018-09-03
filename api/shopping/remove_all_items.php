<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/shopping.php';

$shopping = new Shopping();

// remove added flag on all items, return success status
echo json_encode($shopping->updateItem('*', array('item_added' => '0')));
?>
