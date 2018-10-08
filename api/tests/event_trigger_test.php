<?php
include_once '../objects/event_trigger.php';

$trigger = new EventTrigger();

// $trigger->addTrigger('weather_forecast', '08:00:00');
//
// echo var_dump($trigger->getTriggers('weather_forecast'));
//
$trigger->updateTrigger('weather_forecast', '1', '');
// echo var_dump($trigger->getTriggers('weather_forecast'));

echo $trigger->shouldTrigger('weather_forecast');

// echo var_dump($trigger->getTriggers('weather_forecast'));

 ?>
