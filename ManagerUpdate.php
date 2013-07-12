<!DOCTYPE html>
<?php
/**
 * Description of ManagerUpdate
 *
 * @author weitzeilsl
 */
class ManagerUpdate {
    function updateSubmit($empID, $value){
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
            if($value == "Eval") {
                $SBSQL = "UPDATE manager SET EvalSubmit=1 WHERE empID=$empID";
            } else {
                $SBSQL = "UPDATE manager SET RankSubmit=1 WHERE empID=$empID";
            }
           
            $result = $mysqli->query($SBSQL);
            if(!$result){
                if(session_id() == '') {
                    session_start();
                }
                $db->logDBError($_SESSION['name'], "EmployeeUpdate: unable to $SBSQL");
            } 
            mysqli_close($mysqli);
        } 
        unset($db);
    }
}

?>
