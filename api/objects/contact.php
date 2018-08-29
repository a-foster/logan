<?php
include_once 'logan.php';

class Contact{

    public function __construct(){

        // setup default mail object
        $this->logan = new Logan();
    }

    function addContact($email, $first_name, $surname, $phone_number, $city) {

        // add record but return boolean success status
        return $this->logan->db->runStatement("INSERT INTO contacts (email, first_name, surname, phone_number, city) " .
                                              "VALUES ('$email', '$first_name', '$surname', '$phone_number', '$city')");
    }
}
