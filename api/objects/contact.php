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

    // probably only need to update contact by email at this point
    function updateContact($email, $params) {

        $changes = '';

        foreach ($params as $col => $val) {
            if (!$changes) { $changes = "$col='$val'"; }
            else { $changes .= ",$col='$val'"; }

        }

        return $this->logan->db->runStatement("UPDATE contacts SET $changes WHERE email='$email'");
    }

    function deleteContact($email) {
        return $this->logan->db->runStatement("DELETE FROM contacts WHERE email='$email'");
    }

    function getContacts($params) {

        $wheres = '';

        foreach ($params as $col => $val) {
            if (!$wheres) { $wheres = "$col='$val'"; }
            else { $wheres .= "AND $col='$val'"; }

        }

        // add record but return boolean success status
        return $this->logan->db->runQuery("SELECT * FROM contacts WHERE $wheres");
    }
}
