<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/hardware_trigger.php';

$hardware_trigger = new HardwareTrigger();

// Start by getting current time


// Get all trigger times for this trigger type

// Check that the last trigger time (one we've just passed) hasn't already triggered

// If it has been triggered, do nothing i.e. return 'false' to say "don't trigger event"

// Else we need to:
// 1 - Reset all other trigger times to 'not triggered'
// 2 - Set the last trigger time to 'triggered'
// 3 - Return 'true' to say "trigger event"

// update frequency, mark as item_added, return success status
echo json_encode($shopping->updateItem($this_item['item_name'], array('item_frequency' => ($this_item['item_frequency'] + 1), 'item_added' => '1')));
?>
