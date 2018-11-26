<?php
include_once '../objects/weather.php';

$weather = new Weather();

echo $weather->isRain('nottingham,uk','2018-11-30', 'pm');
 ?>
