<!DOCTYPE html>
<?php
//File:   Budget.php
//Author: Steven L. Weitzeil
//Date:   7 June 2013
//Desc:   Cache the budget information (by grade) for each employee in the
//        Employee table.
//Code Review:

gc_enable();

class Budget {    
    protected static $_instance;
    private $hrRate;
    private $budget = array(
        "87" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "88" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "89" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "90" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "91" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "92" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "93" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "94" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "95" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "96" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "97" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "98" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        ),
        "99" => array(
            "EmpCnt"         => 0,
            "AvailBudget"    => 0,
            "CompAdjust"     => 0,
            "BudgetAdjust"   => 0,
            "NumLimited"     => 0,
            "Redistribution" => 0,
            "BudgetAdjUsed"  => 0,
            "SalaryMax"      => 0
        )
    );
    
    protected function __construct(){ }	 
    protected function __clone() { }

    // Create an instance if necessary and return an instance.
    public static function getInstance(){
          if( self::$_instance === NULL ) {
            self::$_instance = new self();

            //Establish DB Connection
            if(!class_exists('DBAccess')) {
                include 'DBAccess.php';
            }
            $db = new DBAccess(); 
            $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());

            // Check connection
            if ($mysqli->connect_errno){
                $db->logDBError($_SESSION['name'], "Budget::getInstance: Failed to connect to Server");
                echo "Failed to connect to server: " . mysqli_connect_error();
                exit();
            } else {
                $mysqli->select_db($db->getDB());
                $HRSQL = "SELECT hrrate FROM Hrrate WHERE idhrrate=1";
                $resultHR = $mysqli->query($HRSQL);
                if($resultHR) {
                    $data = $resultHR->fetch_assoc();
                    if($data) {
                        self::$_instance->setHrRate($data['hrrate']);
                    }
                    $resultHR->close();
                } else {
                    $db->logDBError($_SESSION['name'], "Budget: Error on $HRSQL");
                }

                $EMPSQL = "SELECT * FROM Employee ORDER BY Grade ASC";
                $resultEmp = $mysqli->query($EMPSQL);

                if($resultEmp) {
                    //Zero out the buffer before tabulating the totals
                    for($grade=88; $grade<=99; $grade++) {
                        self::$_instance->initializeBudget($grade);
                    }

                    while ($db_field = mysqli_fetch_assoc($resultEmp)){
                        self::$_instance->budget[$db_field['Grade']]['Grade'] = $db_field['Grade'];
                        self::$_instance->budget[$db_field['Grade']]['EmpCnt'] += 1;
                        self::$_instance->budget[$db_field['Grade']]['AvailBudget'] += ($db_field['CurSal'] * self::$_instance->hrRate);
                        self::$_instance->budget[$db_field['Grade']]['CompAdjust'] += $db_field['Temp1'];
                        self::$_instance->budget[$db_field['Grade']]['BudgetAdjUsed'] += $db_field['Temp2'];
                        self::$_instance->budget[$db_field['Grade']]['NumLimited'] += $db_field['Limited'];
                    }
                    $resultEmp->close();
                }else {
                    $db->logDBError($_SESSION['name'], "Budget: Error on $EMPSQL");
                }

                $RNGSQL = "SELECT * FROM Salary ORDER BY Grade ASC";
                $resultRng = $mysqli->query($RNGSQL);
                if($resultRng) {
                    while ($db_field = mysqli_fetch_assoc($resultRng)){
                        self::$_instance->budget[$db_field['Grade']]['SalaryMax'] = $db_field['Max'];
                    }
                    $resultRng->close();
                }else {
                    $db->logDBError($_SESSION['name'], "Budget: Error on $RNGSQL");
                }
                mysqli_close($mysqli);
            }
            unset($db);
        }
        return self::$_instance;
    }
    
    function setHrRate($hrRate) {
        $this->hrRate = $hrRate;
    }
    
    function getHrRate() {
        return $this->hrRate;
    }
    
    function getAvailBudget($grade) {
        return $this->budget[$grade]['AvailBudget'];
    }
    
    function getEmpCnt($grade) {
        return $this->budget[$grade]['EmpCnt'];
    }
    
    function getCompAdjust($grade) {
        return $this->budget[$grade]['CompAdjust'];
    }
    
    function getSalaryMax($grade) {
        return $this->budget[$grade]['SalaryMax'];
    }
    
    function getBudgetAdjUsed($grade) {
        return $this->budget[$grade]['BudgetAdjUsed'];
    }
    
    function setRedistribution($grade, $redistribution) {
        $this->budget[$grade]['Redistribution'] = $redistribution;
    }
    
    function getRedistribution($grade) {
        return $this->budget[$grade]['Redistribution'];
    }
    
    function getNumLimited($grade) {
        return $this->budget[$grade]['NumLimited'];
    }
    
    function updateRedistribution($grade) {
        $numLimited = $this->getNumLimited($grade);
        
        if($numLimited == 0) {
            $redistribution = 0;
        } else {
            $availBudget = $this->getAvailBudget($grade);
            $budgetAdjust = $this->getBudgetAdjUsed($grade);
            if($availBudget > $budgetAdjust) {
                $redistribution = ($availBudget - $budgetAdjust)/$numLimited;
            } else {
                $redistribution = 0;
            }
        }
        $this->setRedistribution($grade, $redistribution);
        
        //$RDSQL = "UPDATE Budget SET Redistribution=$redistribution WHERE Grade=$grade";
        //$this->getMySQLi()->query($RDSQL);
    }
    
    function initializeBudget($grade) {
        $this->budget[$grade]['EmpCnt'] = 0;
        $this->budget[$grade]['AvailBudget'] = 0;
        $this->budget[$grade]['CompAdjust'] = 0;
        $this->budget[$grade]['BudgetAdjUsed'] = 0;
        $this->budget[$grade]['NumLimited'] = 0;
    }
}

gc_disable();
?>
