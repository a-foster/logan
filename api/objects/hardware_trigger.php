<?php
include_once 'logan.php';

class HardwareTrigger{

    public function __construct(){

        // setup default logan object
        $this->logan = new Logan();
    }

    function addTrigger($trigger_type, $trigger_time) {

        // add record but return success status
        return $this->logan->db->runStatement("INSERT INTO hardware_triggers (trigger_type, trigger_time)
                                              VALUES ('$trigger_type', '$trigger_time')");
    }

    // For now all we need to be able to update is the 'triggered' column
    function resetTriggers($trigger_type) {

        return $this->logan->db->runStatement("UPDATE hardware_triggers SET triggered=NULL WHERE trigger_type=$trigger_type");
    }

    // TODO - as above
    function deleteItem($name) {
        // return $this->logan->db->runStatement("DELETE FROM shopping_lsit WHERE item_name='$name'");
    }

    // Return all trigger times for a given event
    function getTriggers($trigger_type) {

        return $this->logan->db->runQuery("SELECT * FROM hardware_triggers where trigger_type = $trigger_type");
    }

    // Determine if event should be triggered based on current time and triggered flag
    function shouldTrigger($trigger_type) {

        $curr_time = date("H:i:s");
        return $this->logan->db->runQuery("SELECT * FROM hardware_triggers where trigger_type = $trigger_type");
    }
}
