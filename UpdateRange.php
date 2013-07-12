<?php
    gc_enable();
    
    if(isset($_REQUEST['value']) && 
       isset($_REQUEST['change'])) {
        
        $grade = substr($_REQUEST['change'], 3, 2);
        $column = substr($_REQUEST['change'], 0, 3);
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
            $RNSQL = "UPDATE Salary SET $column=$value WHERE Grade=$grade";
            $result = $mysqli->query($RNSQL);
            if($result) {
                $result->close();
            } else {
                $db->logDBError($_SESSION['name'], "UpdateRange - unable to $RNSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    gc_disable();
?>
