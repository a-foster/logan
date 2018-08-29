<?php
include_once '../objects/email.php';

$email = new Email();
echo $email->sendMail('Initial test email', 'TEST');
 ?>
