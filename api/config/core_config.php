<?php
class CoreConfig{

    public function getConfig(){

        $this->config = new stdClass();

        $fh = fopen('../shared/base_config','r');
        while ($line = fgets($fh)) {
          $line = str_replace(array("\r", "\n"), '', $line);
          $key_pair = explode("=", $line);
          $this->config->{$key_pair[0]} = $key_pair[1];
        }
        fclose($fh);
        return $this->config;
    }
}
?>
