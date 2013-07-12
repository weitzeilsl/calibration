<?php
    //DeleteEmplyoeeWeb.php
    //Author: Steven L. Weitzeil
    //Date:   14 June 2013
    //Desc:   This PHP file deletes the employee selected in 
    //Review:

    session_start();
    gc_enable();
    
    //If not signed in, go to MustSignIn
    if (!isset($_SESSION["name"])){
        session_unset();

        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }
        $uri .= $_SERVER['HTTP_HOST'];

        header('Location: '.$uri.'/MustSignInWeb.php');
    }
    
    //Get the employeename from the Get
    if(isset($_REQUEST['employeeName'])){
        
        //Extract the employee ID from the text in the pop-down selection
        $begin = strrpos($_REQUEST['employeeName'], '(') + 1;
        $end = strrpos($_REQUEST['employeeName'], ')');
        $empID = substr($_REQUEST['employeeName'], $begin, $end-$begin);
        
        //Make the database connection
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
            //Delete the employee
            $DESQL = "DELETE FROM employee WHERE empID=$empID";
            $result = $mysqli->query($DESQL);
            //Log the results
            if(!$result) {
                $db->logDBError($_SESSION['name'], "DeleteEmployee: unable to delete - $empID");
            } else {
                $db->logDBError($_SESSION['name'], "DeleteEmployee: Deleted - $_REQUEST[employeeName]");
                $result->close();
            }
            mysqli_close($mysqli);

            //Return to main Administration page
            if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                $uri = 'https://';
            } else {
                $uri = 'http://';
            }
            $uri .= $_SERVER['HTTP_HOST'];

            header('Location: '.$uri.'/AdminWeb.php');
        }
        unset($db);
    }
    
    gc_disable();
?>
