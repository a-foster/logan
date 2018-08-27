<?php
include_once '../config/logging.php';

$log = new Logging();
if ($log->writeLog("Test", "This is a test log message")) { echo "Log written successfully"; };

 ?>
