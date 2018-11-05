<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../objects/event_trigger.php';

$hardware_trigger = new EventTrigger();

$trigger_type = $_GET['trigger_type'];
$trigger_time = $_GET['trigger_time'];

echo json_encode($hardware_trigger->addTrigger($trigger_type, $trigger_time));
?>
