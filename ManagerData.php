<?php
/**
 * ManagerData
 * Steven Weitzeil
 * FamilySearch
 * 6 April 2013
 * Code Reviewed: 24 June 2013
 */
gc_enable();

class ManagerData {
    private $manager = array(array());

    // Create an instance if necessary and return an instance.
    function __construct() {    
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();

        //todo: Create Get Managers method
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if ($mysqli->connect_errno){
            echo "Failed to connect to server: " . mysqli_connect_error();
            $db->logDBError($_SESSION['name'], "ManagerData: Failed to connect to server- " . mysqli_connect_error());
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            $SQL = "SELECT * FROM Manager ORDER BY lastName ASC";
            $result = $mysqli->query($SQL);
            if($result) {
                $managerCnt = 0;

                while ($db_field = mysqli_fetch_assoc($result)){
                    $this->manager[$managerCnt]['empid'] = $db_field['empID'];
                    $this->manager[$managerCnt]['firstname'] = $db_field['firstName'];
                    $this->manager[$managerCnt]['lastname'] = $db_field['lastName'];
                    $this->manager[$managerCnt]['password'] = ($db_field['password']);
                    $this->manager[$managerCnt]['email'] = $db_field['eMail'];
                    $this->manager[$managerCnt]['mgrid'] = $db_field['mgrID'];
                    $this->manager[$managerCnt]['admin'] = $db_field['admin'];
                    $this->manager[$managerCnt]['evalSubmit'] = $db_field['evalSubmit'];
                    $this->manager[$managerCnt]['rankSubmit'] = $db_field['rankSubmit'];
                    $managerCnt++;    
                }
                $result->close();
            }else {
                if(isset($_SESSION['name'])) {
                    $db->logDBError($_SESSION['name'], "ManagerData: unable to $SQL");
                } else {
                    $db->logDBError("Unknown User", "ManagerData: unable to $SQL");
                }
            } 
            mysqli_close($mysqli);
        }
        unset($db);
    }

    function getManagerNames()
    {
        $managerNames = array();
        $singleManager = array();
        $managerCnt = 0;
        
        foreach ($this->manager as $singleManager) {
            $name = $singleManager['firstname'] . " " . $singleManager['lastname'];
            $managerNames[$managerCnt++] = $name;
        }
        return $managerNames;
    }
    
    function getManagerNamesAndEmpId()
    {
        $managerNames = array();
        $singleManager = array();
        $managerCnt = 0;
        
        foreach ($this->manager as $singleManager) {
            $name = $singleManager['firstname'] . " " . $singleManager['lastname'] . " (" . $singleManager['empid'] . ")";
            $managerNames[$managerCnt++] = $name;
        }
        return $managerNames;
    }
    
    function isCorrectPassword($userName, $userPassword)
    {
        //Get the employee Id
        $leftParLoc = strrchr($userName, "(");
        $rightHalf = ltrim($leftParLoc, "(");
        $empID = rtrim($rightHalf, ")");
        
        //Lookup the password for that ID
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               if($singleManager['password'] == $userPassword){
                   $_SESSION['empID'] = $singleManager['empid'];
                   $_SESSION['mgrID'] = $singleManager['mgrid'];
                   $_SESSION['admin'] = $singleManager['admin'];
                          
                   if($singleManager['lastname'] == "-"){
                       $_SESSION['name'] = $singleManager['firstname'];
                   } else {
                       $_SESSION['name'] = $singleManager['firstname'] . " " . $singleManager['lastname'];
                   }
                   
                   $_SESSION['password'] = $singleManager['password'];
                   $_SESSION['eMail'] = $singleManager['email'];
                   
                   if(!class_exists('LogManager')) {
                       include 'LogManager.php';
                   }
                   $log = new LogManager();
                   $log->append($_SESSION['name'], "Logged in");
                   unset($log);
                   
                   return TRUE;
               }
           }
        }
        return FALSE;
    }
    
    function getEmailAddress($empID) {
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               return $singleManager['email'];
           }
        }
        return "";
    }
    
    function base64url_encode($str) {
        return strtr(base64_encode($str), '+/', '-_');
    }

    function base64url_decode($base64url) {
        return base64_decode(strtr($base64url, '-_', '+/'));
    }
    
    function displayManagers($empID, $level, $target)
    {
        $singleMgr = array();
        $count = 0;
        
        echo"<ul>";
        
        //If Administrator, make the same as root
        if($empID === "000000") {$empID = "380034";}
        
        foreach ($this->manager as $singleMgr){
            if($singleMgr['mgrid'] == $empID){
                $id = $this->base64url_encode($singleMgr['empid']);
                $fname = $singleMgr['firstname'];
                $lname = $singleMgr['lastname'];
                switch ($target){
                    case "eval":         
                        echo"<li><a href='/EmployeeEvaluationWeb.php?n=$id&g=All&r=All&d=0'>$fname $lname</a>";
                        break;
                    case "rank":
                        echo"<li><a href='/EmployeeRankWeb.php?n=$id&g=All&r=All&d=0'>$fname $lname</a>";
                        break;
                    case "ati":
                        echo"<li><a href='/EmployeeATIWeb.php?n=$id&g=All&r=All&d=0'>$fname $lname</a>";
                        break;
                }
                $count++;
                $currentLevel = $level;
                //Look for sub-managers
                $level++;
                $lowerCount = $this->displayManagers($singleMgr['empid'], $level, $target);
                $count += $lowerCount;
                echo"</li>";
                $level = $currentLevel;
            }
        }
        echo"</ul>";
        return $count;
    }
    
    function getManagerName($empID) {
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               return $singleManager['firstname'] . " " . $singleManager['lastname'];
           }
        }
        return "";
    }
    
    function isManagerSubmitted($empID, $type) {
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               if($type =="Eval") {
                   if($singleManager['evalSubmit']){
                       return TRUE;
                   }else {
                       return FALSE;
                   }
               } else{
                   if($singleManager['rankSubmit']){
                       return TRUE;
                   }else {
                       return FALSE;
                   }
               }
           }
        }
        return "";
    }
    
    function getManagerEmpIDinParen($empID) {
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               return $name = $singleManager['firstname'] . " " . $singleManager['lastname'] . " (" . $singleManager['empid'] . ")";
           }
        }
        return "";
    }
    
    function getManagerLastInit($empID) {
        foreach ($this->manager as $singleManager) {
           if($singleManager['empid'] == $empID){
               return $singleManager['lastname'] . ", " . substr($singleManager['firstname'], 0, 1);
           }
        }
        return "";
    }
    
    function getManagerArray() {
        return $this->manager;
    }
    
    function addManager($empID, $firstName, $lastName, $password, $email, $mgrEmpID) {
        //Make the database connection
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();

        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if($mysqli->connect_errno){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            //Delete the employee
            $ADDSQL = "INSERT INTO manager (empID,  firstName,    lastName,    password,    eMail,    mgrID) 
                                    VALUES ($empID, '$firstName', '$lastName', '$password', '$email', $mgrEmpID)";
            $result = $mysqli->query($ADDSQL);
            if($result) {
                $result->close();
            } else {
                $db->logDBError($_SESSION['name'], "ManagerData: unable to $ADDSQL");
            }
            $mysqli->close();
        }
        unset($db);
    }
    
    function deleteManager($empID, $lastName) {
        //Make the database connection
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();

        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if($mysqli->connect_errno){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            //Delete the manager
            $DELSQL = "DELETE FROM manager WHERE empID=$empID AND lastName=$lastName";
            $result = $mysqli->query($DELSQL);
            if($result) {
                $result->close();
            } else {
                $db->logDBError($_SESSION['name'], "ManagerData: unable to $DELSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
}

gc_disable();
?>
