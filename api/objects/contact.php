<?php
include_once 'logan.php';

class Contact{

    public function __construct(){

        // setup default mail object
        $this->logan = new Logan();
    }

    function addContact($params) {

        $columns = '';
        $values = '';

        // build up whatever columns/ vals the caller supplied
        foreach ($params as $col => $val) {
            if (!$columns) { $columns = $col; }
            else { $columns .= ",$col"; }

            if (!$values) { $values = "'$val'"; }
            else { $values .= ",'$val'"; }
        }

        // add record but return boolean success status
        return $this->logan->db->runStatement("INSERT INTO contacts ($columns) VALUES ($values)");
    }
}
