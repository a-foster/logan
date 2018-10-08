<?php
include_once 'logan.php';

class EventTrigger{

    public function __construct(){

        // setup default logan object
        $this->logan = new Logan();
    }

    function addTrigger($trigger_type, $trigger_time) {

        // add record but return success status
        return $this->logan->db->runStatement("INSERT INTO event_triggers (trigger_type, trigger_time)
                                              VALUES ('$trigger_type', '$trigger_time')");
    }

    function updateTrigger($trigger_type, $triggered, $trigger_id) {

        // can update by type and id, but don't have to specify id
        $wheres = "trigger_type = '$trigger_type'";
        if ( $trigger_id ) { $wheres .= " and id = $trigger_id"; }

        return $this->logan->db->runStatement("UPDATE event_triggers SET triggered=$triggered WHERE $wheres");
    }

    // Return all trigger times for a given event
    function getTriggers($trigger_type) {

        return $this->logan->db->runQuery("SELECT * FROM event_triggers where trigger_type = '$trigger_type'");
    }

    // Determine if event should be triggered based on current time and triggered flag
    function shouldTrigger($trigger_type) {

        $curr_time = date("H:i:s");

        // get the most recent event and check if it's already been triggered
        $most_recent_trigger = $this->logan->db->runQuery("SELECT * FROM event_triggers where trigger_type = '$trigger_type'
                                                           and trigger_time <= '$curr_time' order by trigger_time desc limit 1");

        if ( $most_recent_trigger && ! $most_recent_trigger[0]['triggered'] ) {

            // reset all triggers
            $this->updateTrigger($trigger_type, 0, '');

            // update most recent trigger to 'triggered'
            $this->updateTrigger($trigger_type, 1, $most_recent_trigger[0]['id']);

            return "true";
        }
    }
}
