<!--
File:   UpdateHRRate
Author: Steven L. Weitzeil
Date:   1 June 2013
Desc:   Display and manage the HR Rate page.  The HR rate is used to calculate
        all AMI values for all employees.
Review: Jim Johnson - 24 June 2013
-->
<!DOCTYPE html>
<?php
    gc_enable();
    session_start();
    
    if(isset($_REQUEST['value'])) {
        $value = $_REQUEST['value'];
        
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();

        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if ($mysqli->connect_errno){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            $rate = sprintf("%0.4f", $value/100);
            $HRSQL = "UPDATE Hrrate SET hrrate=$rate WHERE idhrrate=1";
            $result = $mysqli->query($HRSQL);
            if($result) {
                $db->logDBError($_SESSION['name'], "UpdateHRRate - HR Rate changed to $rate");
                $result->close();
            } else {
                $db->logDBError($_SESSION['name'], "UpdateHRRate - unable to $HRSQL");
            }
            mysqli_close($mysqli);
        }
    }
    unset($db);
    gc_disable();
?>
