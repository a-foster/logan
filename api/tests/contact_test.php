<?php
include_once '../objects/contact.php';

$contact = new Contact();

echo $contact->addContact('someone@test.com', 'John', 'Doe', '0123456789', 'Johannesburg');
 ?>
