<?php
//Author: Steven L. Weitzeil
//Date:   24 June 2013
//Description: Class to handle log entry
gc_enable();

class LogManager {
    private $file = '..\log\FSMT_Error_Log.txt';
    
    function append($userName, $message) {
       $date = new DateTime('now', new DateTimeZone('America/Denver'));
       $date_str = date_format($date,"Y-m-d H:i:s");
       $line = $date_str . " - " . $userName . " - " . $message . PHP_EOL;
       file_put_contents($this->file, $line, FILE_APPEND | LOCK_EX);
       
       unset($date);
    }
}

gc_disable();
?>
