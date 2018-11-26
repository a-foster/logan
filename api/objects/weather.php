<?php
include_once 'logan.php';

class Weather{

    public function __construct(){

        // setup default logan object
        $this->logan = new Logan();
        $this->weather_api_url = $this->logan->conf->weather_api_url . $this->logan->conf->weather_api_key . '&units=metric&q=';
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
            $weather_json = $this->getWeatherJson($location);

            if (! $weather_json) {
                return '{"cod":"404","message":"city not found"}';
            }

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

    # get weather from api and return relevant information
    function getWeatherJson($location) {

        $weather_api_response = @file_get_contents($this->weather_api_url . $location);

        if ( ! $weather_api_response) {
            $this->logan->log->writeLog('Weather Error', "City not found in weather api - $location");
            return null;
        }

        # get forecast as json
        $forecast_array = json_decode($weather_api_response)->list;
        $weather_json = [];

        foreach($forecast_array as $forecast) {
            $weather_json[$forecast->dt_txt]['temperature'] = $forecast->main->temp;
            $weather_json[$forecast->dt_txt]['wind_speed'] = $forecast->wind->speed;
            $weather_json[$forecast->dt_txt]['weather_type'] = $forecast->weather[0]->main;
            $weather_json[$forecast->dt_txt]['weather_description'] = $forecast->weather[0]->description;
        }
        return json_encode($weather_json);
    }

    # split full forecast into an average forecast for a single day
    function getForecastByDay($location, $date_to_find) {

        # declare vars needed to compute daily forecast data
        $am_temp_sum=0;
        $am_wind_sum=0;
        $am_desc = '';
        $pm_temp_sum=0;
        $pm_wind_sum=0;
        $pm_desc = '';
        $day_weather = [];
        $forecast = [];
        foreach(['am', 'pm'] as $am_or_pm) {
            $day_weather[$am_or_pm]['total_temp'] = 0;
            $day_weather[$am_or_pm]['total_wind_speed'] = 0;
            $day_weather[$am_or_pm]['weather_types'] = [];
            $day_weather[$am_or_pm]['weather_description'] = [];
            $day_weather[$am_or_pm]['entries'] = 0;
        }

        # get full forecast to process
        $forecast_array = json_decode($this->getWeather($location));

        foreach($forecast_array as $forecast_datetime => $forecast) {
            list($date, $time) = explode(" ", $forecast_datetime);
            $hour = intval(explode(":", $time)[0]);

            # skip if it's not the date we need to get forecast for or this is outside of 'daylight' hours
            if ($date != $date_to_find || $hour < 5 || $hour > 22) {
                continue;
            }

            # if morning, add to morning values
            $am_or_pm = $hour<12 ? 'am' : 'pm';

            $day_weather[$am_or_pm]['total_temp'] += floatval($forecast->temperature);
            $day_weather[$am_or_pm]['total_wind_speed'] += floatval($forecast->wind_speed);
            array_push($day_weather[$am_or_pm]['weather_types'], $forecast->weather_type);
            array_push($day_weather[$am_or_pm]['weather_description'], $forecast->weather_description);

            $day_weather[$am_or_pm]['entries']++;
        }

        # populate the return hash but only if we have forecast for it (won't always have am)
        foreach(['am', 'pm'] as $am_or_pm) {
            if ($day_weather[$am_or_pm]['total_temp']) {
                $weather_json[$am_or_pm]['temperature'] = round($day_weather[$am_or_pm]['total_temp'] / $day_weather[$am_or_pm]['entries']);
                $weather_json[$am_or_pm]['wind_speed'] = round($day_weather[$am_or_pm]['total_wind_speed'] / $day_weather[$am_or_pm]['entries']);
                $weather_json[$am_or_pm]['weather_type'] = $this->getAverageWeatherType($day_weather[$am_or_pm]['weather_types']);
                $weather_json[$am_or_pm]['weather_description'] = $this->getAverageWeatherDescription($day_weather[$am_or_pm]['weather_description']);
            }
        }
        return json_encode($weather_json);
    }

    # takes an array of forecats and returns the two most often occuring descriptions as a string
    function getAverageWeatherDescription($weather_array) {

        # sort array so that mode values appear first
        $counts = array_count_values($weather_array);
        arsort($counts);
        $mode_weather = array_slice(array_keys($counts), 0, 1, true);

        # return 2 most common descriptions, or 1 if only 1
        return isset($mode_weather[1]) ? $mode_weather[0] . ', ' . $mode_weather[1] : $mode_weather[0];
    }

    # returns the most often occuring weather type, unless one of these is Rain!
    function getAverageWeatherType($weather_array) {

        # check if rain is mentioned first
        foreach($weather_array as $weather) {
            if ($weather == 'Rain') {
                return 'Rain';
            }
        }

        # sort array so that mode values appear first - if there's no mode just pick one at random
        $counts = array_count_values($weather_array);
        arsort($counts);
        $mode_val = array_slice(array_keys($counts), 0, 0, true) ? array_slice(array_keys($counts), 0, 0, true) : array_keys($counts)[0];
        return $mode_val;
    }

    # returns boolean if weather type or description contains rain
    function isRain($location, $date, $am_or_pm) {
        $day_weather = json_decode($this->getForecastByDay($location, $date));

        # check if rain in morning or afternoon (or both)
        if ($am_or_pm == '*') {
            foreach(['am', 'pm'] as $am_or_pm) {
                if ($day_weather->$am_or_pm->weather_type == 'Rain' || stripos($day_weather->$am_or_pm->weather_description, 'rain')) {
                    return true;
                }
            }
        }
        else {
            if ($day_weather->$am_or_pm->weather_type == 'Rain' || stripos($day_weather->$am_or_pm->weather_description, 'rain')) {
                return true;
            }
        }
        return false;
    }
}
