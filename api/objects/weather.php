<?php
include_once 'logan.php';

class Weather{

    public function __construct(){

        // setup default logan object
        $this->logan = new Logan();
        $this->weather_api_url = $this->logan->conf->weather_api_url . $this->logan->conf->weather_api_key . '&q=';
    }

    # return full weather forecast for a location for next 5 days in json format
    function getWeather($location) {

        # if location is empty then search based on default city
        $location = $location ? $location : $this->logan->conf->default_city;
        $weather_json = '';

        # weather should be updated every 6 hours
        $acceptable_weather_age = date('Y-m-d H:i:s', strtotime('-6 hour'));

        $weather_record = $this->logan->db->runQuery(
          "SELECT weather_json, last_updated>='$acceptable_weather_age' as fresh_data FROM weather WHERE location='$location'");

        $weather_record = isset($weather_record[0]) ? $weather_record[0] : '';

        # if there's no entry for this location or data is out of date
        if (! $weather_record || ! $weather_record['fresh_data']) {

            # get weather for this location from API and then store it before returning
            $weather_json = file_get_contents($this->weather_api_url . $location);

            # if no results for this location then add it to database§
            if (! $weather_record){
                $this->storeWeather($location, $weather_json);
            }
            # else just update the json
            else {
                $this->updateWeather($location, $weather_json);
            }
        }
        else {
            $weather_json = $weather_record['weather_json'];
        }
        return $weather_json;
    }

    # store weather for new locations
    function storeWeather($location, $weather_json) {
        return $this->logan->db->runStatement(
          "INSERT INTO weather (location, weather_json, last_updated)
          VALUES ('$location', '$weather_json', current_timestamp())");
    }

    # update weather for existing locations
    function updateWeather($location, $weather_json) {
        return $this->logan->db->runStatement(
          "UPDATE weather SET weather_json='$weather_json', last_updated=current_timestamp() WHERE location='$location'");
    }

}
