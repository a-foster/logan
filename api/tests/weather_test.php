<?php
include_once '../objects/weather.php';

$weather = new Weather();

echo $weather->getWeather('nottingham,uk');
 ?>
