<?php
include_once '../objects/email.php';
include_once '../objects/logan.php';

$logan = new Logan();
echo $logan->email->sendMail('Initial test email', 'TEST');
 ?>
