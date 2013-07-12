<!DOCTYPE html>
<?php
    //AddEmployee
    //Steven L. Weitzeil
    //14 June 2013
    //Description:  This PHP file deletes the employee selected in 
    //              DeleteEmplyoeeWeb.php
    session_start();
    gc_enable();
    
    //Make sure all require data has been passed in the Get
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
        $three = 3;

        //If the user entered an email and passward for the user, create a manager record.
        if(!class_exists('ManagerData')) {
            include 'ManagerData.php';
        }
        $mgrData = new ManagerData();
        $mgrName = $mgrData->getManagerLastInit($mgrEmpID);
        if(isset($_REQUEST['email']) && isset($_REQUEST['password'])) {
            $email = $_REQUEST['email'];
            $password = $_REQUEST['password'];
            if(strlen($email) && strlen($password)){
                $mgrData->addManager($empID, $firstName, $lastName, $_REQUEST['password'], $_REQUEST['email'], $mgrEmpID);
            }
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
            //Add the employee to the Employee table
            $ADDSQL = "INSERT INTO employee (empID,  firstName,    lastName,    Grade,    mgrID,     MgrName,    Comp1,  Comp2,  Comp3,  Comp4,  Comp5,  Comp6,  Comp7,  Comp8,  Comp9,  DivGoal, TeamGoal, PerGoal, Role,    Romp1,  Romp2,   Romp3,   Romp4,   Romp5,   Romp6,   Romp7,   Romp8,   Romp9,  GMAdjust, CurSal) 
                                     VALUES ($empID, '$firstName', '$lastName', '$grade', $mgrEmpID, '$mgrName', $three, $three, $three, $three, $three, $three, $three, $three, $three, $three,  $three,   $three,  '$role', $three, $three,  $three,  $three,  $three,  $three,  $three,  $three,  $three, 100,      $curSal)";
            $result = $mysqli->query($ADDSQL);
            if($result) {
                $db->logDBError($_SESSION['name'], "AddEmployee: Added employee: $lastName, $firstName ($empID)");
                $result->close();
            } else {
                echo("Error adding employee!");
                $db->logDBError($_SESSION['name'], "AddEmployee: Error on SQL insert!: $ADDSQL");
            }

            if(!class_exists('EmployeeData')) {
                include 'EmployeeData.php';
            }

            //Calculate the employee ATI and AMI data based upon the default data used
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
            unset($employee);
            unset($mgrData);
            unset($db);

            //Return to the Administration main page
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
