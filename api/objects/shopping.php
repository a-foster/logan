<?php
include_once 'logan.php';

class Shopping{

    public function __construct(){

        // setup default logan object
        $this->logan = new Logan();
    }

    function addItem($name, $category, $frequency) {

        // add record but return success status
        return $this->logan->db->runStatement("INSERT INTO shopping_list (item_name, item_category, item_frequency, item_added)
                                              VALUES ($name, $category, $frequency, 'Y')");
    }

    // Can only update item by name as it's unique
    function updateItem($name, $params) {

        $changes = '';

        foreach ($params as $col => $val) {
            if (!$changes) { $changes = "$col='$val'"; }
            else { $changes .= ",$col='$val'"; }

        }

        return $this->logan->db->runStatement("UPDATE shopping_list SET $changes WHERE item_name='$name'");
    }

    function deleteItem($name) {
        return $this->logan->db->runStatement("DELETE FROM shopping_lsit WHERE item_name='$name'");
    }

    function getItems($params) {

        $wheres = '';

        foreach ($params as $col => $val) {
            if (!$wheres) { $wheres = "$col='$val'"; }
            else { $wheres .= "AND $col='$val'"; }

        }

        // add record but return boolean success status
        return $this->logan->db->runQuery("SELECT * FROM shopping_list WHERE $wheres");
    }
}
