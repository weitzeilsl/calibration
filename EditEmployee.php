<!DOCTYPE html>
<?php
    //File:   EditEmployee
    //Author: Steven L. Weitzeil
    //Date:   14 June 2013
    //Desc:   This PHP file deletes the employee selected in DeleteEmplyoeeWeb.php
    //Review: 
    session_start();
    gc_enable();
    
    //If Cancel was clicked, return to main Administration page
    if($_REQUEST['button'] == "Cancel"){
        //Yes - Go back to
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $uri = 'https://';
        } else {
            $uri = 'http://';
        }
        $uri .= $_SERVER['HTTP_HOST'];

        header('Location: '.$uri.'/AdminWeb.php');
        exit;
    }
    
    //Validate that the Get passed all required parameters
    if((isset($_REQUEST['empID'])) &&
       (isset($_REQUEST['firstName'])) &&
       (isset($_REQUEST['lastName'])) &&
       (isset($_REQUEST['role'])) &&
       (isset($_REQUEST['grade']))) {
        
        //Get the manager ID
        $popDownStr = $_REQUEST['mgrName'];
        $leftParLoc = strrchr($popDownStr, "(");
        $rightHalf = ltrim($leftParLoc, "(");
        $mgrEmpID = rtrim($rightHalf, ")");
        
        $empID = $_REQUEST['empID'];
        $firstName = $_REQUEST['firstName'];
        $lastName = $_REQUEST['lastName'];
        $role = substr($_REQUEST['role'], -3, 2);
        $grade = $_REQUEST['grade'];
        $curSal = $_REQUEST['salary'];

        //Add this person to the Manager table if the email and password were included
        if(!class_exists('ManagerData')) {
            include 'ManagerData.php';
        }
        $mgrData = new ManagerData();
        if(isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
            $mgrData->addManager($empID, $firstName, $lastName, $_REQUEST['password'], $_REQUEST['email'], $mgrEmpID);
        } else {
            $mgrData->deleteManager($empID, $lastName);
        }
        
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
            //Update the employee                    
            $EDTSQL = "UPDATE employee SET empID=$empID, 
                                           firstName='$firstName',
                                           lastName='$lastName',    
                                           Grade='$grade',    
                                           Role='$role',
                                           MgrID='$mgrEmpID',
                                           CurSal='$curSal' 
                                       WHERE empID=$_SESSION[oldEmpID]";
            unset($_SESSION['oldEmpID']);
            $result = $mysqli->query($EDTSQL);

            //Log results
            if($result) {
                $db->logDBError($_SESSION['name'], "EditEmployee: employe updated - $lastName, $firstName ($empID)");
                $result->close();
            } else {
                $db->logDBError($_SESSION['name'], "EditEmployee: unable to update - $lastName, $firstName ($empID)");
            }

            //Recalculate ATI/AMI results based upon edited values.
            if(!class_exists('EmployeeData')) {
                include 'EmployeeData.php';
            }
            $employee = new EmployeeData();
            $comps = $employee->getComps($empID);
            $res = $employee->getResults($empID);
            $ranks = $employee->getRanks($empID);

            if(!class_exists('Calculator')) {
                include 'Calculator.php';
            }
            $calc = new Calculator($empID);
            $calc->UpdateCompScore('Comp1', $comps['Comp1'], $comps['Comp1'], $comps['Comp2'], $comps['Comp3'], 
                                                             $comps['Comp4'], $comps['Comp5'], $comps['Comp6'], 
                                                             $comps['Comp7'], $comps['Comp8'], $comps['Comp9']);

            $calc->UpdateResultsScore("DivGoal", $res['DivGoal'], $res['DivGoal'], $res['TeamGoal'], $res['PerGoal']);

            $calc->UpdateRankTotal('Romp1', $ranks['Romp1'], $ranks['Romp1'], $ranks['Romp2'], $ranks['Romp3'], 
                                                             $ranks['Romp4'], $ranks['Romp5'], $ranks['Romp6'], 
                                                             $ranks['Romp7'], $ranks['Romp8'], $ranks['Romp9']);
            mysqli_close($mysqli);
            
            unset($calc);
            unset($mgrData);
            unset($employee);
            unset($db);

            if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                $uri = 'https://';
            } else {
                $uri = 'http://';
            }
            $uri .= $_SERVER['HTTP_HOST'];

            header('Location: '.$uri.'/AdminWeb.php');
        }
    }
    gc_disable();
?>
