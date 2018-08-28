<?php
include_once '../objects/email.php';
include_once '../objects/logan.php';

$lg = new Logan();
$email = new Email($lg, '', 'Initial test email', 'TEST');
echo $email->sendMail();
 ?>
