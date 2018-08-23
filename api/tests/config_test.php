<?php
include_once '../config/core_config.php';

$config = new CoreConfig();
$conf = $config->getConfig();

foreach ($conf as $conf_key => $conf_data) {
   echo "Key - " . $conf_key . "\n";
   echo "Value - " . $conf_data . "\n";
}

 ?>
