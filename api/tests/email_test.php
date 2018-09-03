<?php
include_once '../objects/email.php';

$email = new Email();
$email->addRecipients('someone@test.com');
echo $email->sendMail("Test", "Test email");
 ?>
