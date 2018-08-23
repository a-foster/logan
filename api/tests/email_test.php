<?php
include_once '../objects/email.php';

$email = new Email('', 'Initial test email', 'TEST');
$email->sendMail();
 ?>
