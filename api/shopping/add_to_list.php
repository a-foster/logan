<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/shopping.php';

$shopping = new Shopping();

// first need to get the item's frequency so we can increment it and mark as added
$this_item = $shopping->getItems($_GET)[0];

// update frequency, mark as item_added, return success status
echo json_encode($shopping->updateItem($this_item['item_name'], array('item_frequency' => ($this_item['item_frequency'] + 1), 'item_added' => '1')));
?>
