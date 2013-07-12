<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of EmployeeUpdate
 *
 * @author weitzeilsl
 */
class EmployeeUpdate {
    function updatePromotion($empID, $value){
        //Access the database
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if ($mysqli->connect_errno){
            $db->logDBError($_SESSION['name'], "EmployeeUpdate: Unable to connect to " . $db->getServer());
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            //Get the current rate from the database 
            $PRSQL = "UPDATE employee SET Promote=$value WHERE empID=$empID";
            $result = $mysqli->query($PRSQL);
            if(!$result){
                if(session_id() == '') {
                    session_start();
                }
                $db->logDBError($_SESSION['name'], "EmployeeUpdate: unable to $PRSQL");
            } 
            mysqli_close($mysqli);
        } 
        unset($db);
    }
    
    function updateJustification($empID, $value){
        //Access the database
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if ($mysqli->connect_errno){
            $db->logDBError($_SESSION['name'], "EmployeeUpdate: Unable to connect to " . $db->getServer());
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            //Get the current rate from the database 
            $JUSQL = "UPDATE employee SET Justification='$value' WHERE empID=$empID";
            $result = $mysqli->query($JUSQL);
            if(!$result){
                if(session_id() == '') {
                    session_start();
                }
                $db->logDBError($_SESSION['name'], "EmployeeUpdate: unable to $JUSQL");
            } 
            mysqli_close($mysqli);
        } 
        unset($db);
    }
}

?>
