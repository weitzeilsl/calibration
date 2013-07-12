<?php
/**
 * File:   fileIO
 * Author: Steven L. Weitzeil
 * Date:   28 June 2013
 */
class fileIO {
    private $exportDir = '..\export';
      
    function download($filename , $name) {
        $fullpath = $this->exportDir . "\\" . $filename . ".csv";
        if (file_exists($fullpath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename='.basename($name));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($fullpath));
            ob_clean();
            flush();
            readfile($fullpath);
        }
    }
    
    function fileHeading ($filename, $line) {
        $fullpath = $this->exportDir . "\\" . $filename . ".csv";
        file_put_contents($fullpath, $line,  LOCK_EX);
    }
    
    function export($filename, $line) {
        $fullpath = $this->exportDir . "\\" . $filename . ".csv";
        file_put_contents($fullpath, $line, FILE_APPEND | LOCK_EX);
    }
    
    function deleteFile($filename) {
        $fullpath = $this->exportDir . "\\" . $filename . ".csv";
        system('del $fullpath');
    }
}

?>
