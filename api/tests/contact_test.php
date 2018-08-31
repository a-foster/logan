<?php
include_once '../objects/contact.php';

$contact = new Contact();

// echo $contact->addContact(array('email' => 'logan@test.com', 'first_name' => 'Ben', 'surname' => 'Dover', 'phone_number' => '0123456789', 'city' => 'Johannesburg', 'is_friend' => 'true'));

echo var_dump($contact->getContacts(''));
 ?>
