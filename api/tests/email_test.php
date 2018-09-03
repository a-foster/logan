<?php
include_once '../objects/email.php';

$email = new Email();
$email->addRecipients('teat@test.co.uk');
echo $email->sendMail("Test", "Test email");
 ?>
