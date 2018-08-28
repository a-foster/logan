<?php
include_once '../objects/email.php';
include_once '../objects/logan.php';

$email = new Email();
echo $email->sendMail('Initial test email', 'TEST');
 ?>
