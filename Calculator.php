<!DOCTYPE html>
<?php
/**
 * File:   Calculator.php
 * Author: Steven Weitzeil
 * Date:   31 May 2013
 * Desc:   Updates numerical database values used to calculate ATI.
 * Code Review:
 */
gc_enable();

class Calculator {
    
    protected static $_instance;
    private $empid;
    private $grade;
    private $role;
    private $CompScore;
    private $ResultsScore;
    private $RankTotal;
    private $GMAdjust;
    private $AdjustScore;
    private $ATIResults;
    private $ATITalent;
    private $ATIFinal;
    private $CurSal;
    private $CompA;
    private $Multiplier;
    private $ActualInc;
    private $NewSal;
    private $IncrPercent;
    private $SalMidPoint;
    private $Temp1;
    private $Temp2;
    private $Limited;
    private $comp1Wt, $comp2Wt, $comp3Wt;
    private $comp4Wt, $comp5Wt, $comp6Wt;
    private $comp7Wt, $comp8Wt, $comp9Wt;
    private $divWt, $teamWt, $perWt;
    
    function __construct($empID) {
        if(session_id() == '') {
            // session isn't started
            session_start();
        }
        $this->setEmpID($empID);
        
        //Establish DB Connection
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
            $GRADESQL = "SELECT Grade, Role, CurSal, GMAdjust, AdjustScore, CompScore, RankTotal, ResultsScore, ATITalent, ATIFinal, 
                                CompA, Multiplier, Temp1, Temp2, Limited, ActualInc, NewSal, IncrPercent 
                                FROM Employee WHERE empID=$this->empid";
            $result = $mysqli->query($GRADESQL);
            if($result){
                $data = $result->fetch_assoc();
                if($data){
                    $this->setGrade($data['Grade']);
                    $this->setRole($data['Role']);
                    $this->setCompScore($data['CompScore']);
                    $this->setCurSal($data['CurSal']);
                    $this->setGMAdjust($data['GMAdjust']);
                    $this->setAdjustScore($data['AdjustScore']);
                    $this->setRankTotal($data['RankTotal']);
                    $this->setResultsScore($data['ResultsScore']);
                    $this->setATITalent($data['ATITalent']);
                    $this->setATIFinal($data['ATIFinal']);
                    $this->setCompA($data['CompA']);
                    $this->setMultiplier($data['Multiplier']);
                    $this->setTemp1($data['Temp1']);
                    $this->setTemp2($data['Temp2']);
                    $this->setLimited($data['Limited']);
                    $this->setActualInc($data['ActualInc']);
                    $this->setNewSalary($data['NewSal']);
                    $this->setIncrPercent($data['IncrPercent']);
                    
                    $this->updateCompWts();
                }
            }else {
                $db->logDBError($_SESSION['name'], "Calculator: Error on $GRADESQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function setEmpID($empID) {
        $this->empid = $empID;
    }
    
    function getEmpID() {
        return $this->empid;
    }
    
    function setGMAdjust($gmAdjust) {
        $this->GMAdjust = $gmAdjust;
    }
    
    function getGMAdjust() {
        return $this->GMAdjust;
    }
    
    function setAdjustScore($adjustScore) {
        $this->AdjustScore = $adjustScore;
    }
    
    function getAdjustScore() {
        return $this->AdjustScore;
    }
    
    function updateAdjustScore() {
        $compScore = $this->getCompScore();
        $rankScore = $this->getRankTotal();
        $gmAdjust = $this->getGMAdjust();
        
        $adjustScore = ($compScore * .5 + $rankScore * .5) * ($gmAdjust/100);
        $this->setAdjustScore(sprintf("%1.2f", $adjustScore));
                
        $this->updateATITalent();
    }
    
    function setGrade($grade) {
        $this->grade = $grade;
    }
   
    function getGrade() {
        return $this->grade;
    }
    
    function setRole($role) {
        $this->role = $role;
    }
   
    function getRole() {
        return $this->role;
    }
    
    function setCurSal($curSal) {
        $this->CurSal = $curSal;
    }
   
    function getCurSal() {
        return $this->CurSal;
    }
   
    function getSalMidPoint() {
        if(isset($this->SalMidPoint)) {
            return $this->SalMidPoint;
        } else {
            $this->updateSalMidPoint();
            return $this->SalMidPoint;
        }
    }
    
    function setSalMidPoint($salMidPoint) {
        $this->SalMidPoint = $salMidPoint;
    }
    
    function updateSalMidPoint() {
        //Update the salary mid-point for this employee from the database
        
        //Establish DB Connection
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
            $grade = $this->getGrade();
            $SQLMP = "SELECT Mid FROM Salary WHERE Grade=$grade";
            $result = $mysqli->query($SQLMP);
            if($result) {
                $data = $result->fetch_assoc();
                if($data){
                    $this->setSalMidPoint($data['Mid']); 
                }
            }else {
                    $db->logDBError($_SESSION['name'], "Calculator: Error on $SQLMP");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function setCompA($compA) {
        $this->CompA = $compA;
    }
   
    function getCompA() {
        return $this->CompA;
    }
    
    function updateCompAandMultiplier() {
        //Update CompA and Multiplier
        $curSal = $this->getCurSal();
        $midPt = $this->getSalMidPoint();
        if($midPt > 0) {
            $this->setCompA($curSal / $midPt);
            $compA = $this->getCompA();
            if($compA > 0){                
                $atiFinal = $this->getATIFinal();
                $multiplier = ($atiFinal / 3) * (1/$compA);  //Penetration Adjustment
                $this->setMultiplier($multiplier);
                 
                $this->updateTemp1();
            }
        }
    }
    
    function setMultiplier($mult) {
        $this->Multiplier = $mult;
    }
   
    function getMultiplier() {
        return $this->Multiplier;
    }
    
    function setTemp1($temp1) {
        $this->Temp1 = $temp1;
    }
   
    function getTemp1() {
        return $this->Temp1;
    }
    
    function updateTemp1() {
        //Temp1 = Budject available to employee adjusted by range penetration
        if(!class_exists('Budget')) {
            include 'Budget.php';
        }
        $budg = Budget::getInstance();
        
        $availBudget = $budg->getAvailBudget($this->grade);
        $empCnt = $budg->getEmpCnt($this->grade);
        $multiplier = $this->Multiplier;
        if($empCnt > 0) {
            $temp1 = sprintf("%.2f", ($availBudget/$empCnt) * $multiplier);  
            $this->setTemp1($temp1);
        }else {
            echo "Calc Line 232: Cannot update Temp1: Budget->EmpCnt = 0";
        }
        
        $this->updateTemp2();
    }
    
    function setTemp2($temp2) {
        $this->Temp2 = $temp2;
    }
   
    function getTemp2() {
        return $this->Temp2;
    }
    
    function updateTemp2() {
        $budg = Budget::getInstance();
        
        $grade = $this->getGrade();
        $availBudget = $budg->getAvailBudget($grade);
        $compAdjust = $budg->getCompAdjust($grade);
        $salaryMax = $budg->getSalaryMax($grade);
        $curSal = $this->getCurSal();
        $temp1 = $this->getTemp1();
        
        if($compAdjust > 0) {
            if(((($availBudget/$compAdjust)* $temp1) + $curSal) > $salaryMax) {
                if($curSal > $salaryMax) {
                    $temp2 = 0;
                } else {
                    $temp2 = $salaryMax - $curSal;
                }
            } else {
                $temp2 = ($availBudget / $compAdjust * $temp1);
            }
        } else {
            echo "Calc Line 264: Unable to update Temp2. Calc Adjust = 0";
        }
        $temp2 = sprintf("%.2f", $temp2);
        $this->setTemp2($temp2);
        
        $this->updateLimited();
    }
    
    function setLimited($limited) {
        $this->Limited = $limited;
    }
   
    function getLimited() {
        return $this->Limited;
    }
    
    function updateLimited() {
        $budg = Budget::getInstance();
        
        $temp2 = $this->getTemp2();
        $curSal = $this->getCurSal();
        $salaryMax = $budg->getSalaryMax($this->getGrade());
        
        if(($temp2 + $curSal) >= $salaryMax) {
            $limited = 0;
            $this->setLimited($limited);
        } else {
            $limited = 1;
            $this->setLimited($limited);
        }
        
        $budg->updateRedistribution($this->getGrade());
        $this->updateActualInc();
    }
    
    function setActualInc($actualInc) {
        $this->ActualInc = $actualInc;
    }
    
    function getActualInc() {
        return $this->ActualInc;
    }
    
    function updateActualInc() {
        $budg = Budget::getInstance();
        
        $grade = $this->getGrade();
        $temp2 = $this->getTemp2();
        $redistribution = $budg->getRedistribution($grade);
        
        if($budg->getNumLimited($grade)) {
            $actualInc = $temp2 + $redistribution;
        } else {
            $actualInc = $temp2;
        }
        $this->setActualInc(sprintf("%6.2f", $actualInc));
        
        $this->updateNewSalary();
    }
    
    function setNewSalary($newSal) {
        $this->NewSal = $newSal;
    }
   
    function getNewSalary() {
        return $this->NewSal;
    }
    
    function updateNewSalary() {
        $curSal = $this->getCurSal();
        $actInc = $this->getActualInc();
        
        $newSal = $curSal + $actInc;
        $this->setNewSalary($newSal);
                
        $this->updateIncrPercent();
    }
    
    function setIncrPercent($incrPer) {
        $this->IncrPercent = $incrPer;
    }
   
    function getIncrPercent() {
        return $this->IncrPercent;
    }
    
    function updateIncrPercent() {
        $curSal = $this->getCurSal();
        $actInc = $this->getActualInc();
        
        $incrPer = ($actInc/$curSal) * 100;  
        $this->setIncrPercent(sprintf("%2.2f", $incrPer));
        
        $empID = $this->getEmpID();
        $gmAdjust = $this->getGMAdjust();
        $adjustScore = $this->getAdjustScore();
        $atiTalent = $this->getATITalent();
        $atiFinal = $this->getATIFinal();
        $compA = $this->getCompA();
        $multiplier = $this->getMultiplier();
        $temp1 = $this->getTemp1();
        $temp2 = $this->getTemp2();
        $limited = $this->getLimited();
        $actualInc = $this->getActualInc();
        $newSalary = $this->getNewSalary();
        $IPSQL = "UPDATE Employee SET AdjustScore=$adjustScore,
                                      ATITalent=$atiTalent,
                                      ATIFinal=$atiFinal,
                                      CompA=$compA,
                                      GMAdjust=$gmAdjust,
                                      Multiplier=$multiplier,
                                      Temp1=$temp1,
                                      Temp2=$temp2,
                                      Limited=$limited,
                                      ActualInc=$actualInc,
                                      NewSal=$newSalary,
                                      IncrPercent=$incrPer WHERE empID=$empID";
        
        
        //Establish DB Connection
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
            $result = $mysqli->query($IPSQL);
            if(!$result){
                $db->logDBError($_SESSION['name'], "Calculator: Error on $IPSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function setRankTotal($rankTotal) {
        $this->RankTotal = $rankTotal;
    }
   
    function getRankTotal() {
        return $this->RankTotal;
    }
    
    function setResultsScore($resultsScore) {
        $this->ResultsScore = $resultsScore;
    }
    
    function getResultsScore() {
        return $this->ResultsScore;
    }
    
    function updateGoalWts () {
        //Update the appropriate goal weights from the database
        $grade = $this->getGrade();
        $role = $this->getRole();
        
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
            $SQLWT = "SELECT DivWt, TeamWt, PerWt FROM Weight WHERE empGrade='$grade' AND empRole='$role'";
            $result = $mysqli->query($SQLWT);
            if($result) {
                $data = $result->fetch_assoc();
                if($data){
                    $this->setDivWt($data['DivWt']);
                    $this->setTeamWt($data['TeamWt']);
                    $this->setPerWt($data['PerWt']);
                }
            }else {
                $db->logDBError($_SESSION['name'], "Calculator: Error on $SQLWT");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function updateCompWts() {
        //Get the appropriate competency weights for this employee
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
            $COMPSQL = "SELECT comp1Wt, comp2Wt, comp3Wt, comp4Wt, comp5Wt, comp6Wt, comp7Wt, comp8Wt, comp9Wt, DivWt, TeamWt, PerWt FROM Weight WHERE empGrade='$this->grade' AND empRole='$this->role'";
            $result = $mysqli->query($COMPSQL);
            if($result){
                $data = $result->fetch_assoc();
                if($data){
                    $this->setComp1Wt($data['comp1Wt']);
                    $this->setComp2Wt($data['comp2Wt']);
                    $this->setComp3Wt($data['comp3Wt']);
                    $this->setComp4Wt($data['comp4Wt']);
                    $this->setComp5Wt($data['comp5Wt']);
                    $this->setComp6Wt($data['comp6Wt']);
                    $this->setComp7Wt($data['comp7Wt']);
                    $this->setComp8Wt($data['comp8Wt']);
                    $this->setComp9Wt($data['comp9Wt']);
                }
            }else {
                $db->logDBError($_SESSION['name'], "Calculator: Error on $COMPSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function setComp1Wt($comp1Wt) {
        $this->comp1Wt = $comp1Wt;
    }
    
    function getComp1Wt() {
        return $this->comp1Wt;
    }
    
    function setComp2Wt($comp2Wt) {
        $this->comp2Wt = $comp2Wt;
    }
    
    function getComp2Wt() {
        return $this->comp2Wt;
    }
    
    function setComp3Wt($comp3Wt) {
        $this->comp3Wt = $comp3Wt;
    }
    
    function getComp3Wt() {
        return $this->comp3Wt;
    }
   
    function setComp4Wt($comp4Wt) {
        $this->comp4Wt = $comp4Wt;
    }
    
    function getComp4Wt() {
        return $this->comp4Wt;
    }
    
    function setComp5Wt($comp5Wt) {
        $this->comp5Wt = $comp5Wt;
    }
    
    function getComp5Wt() {
        return $this->comp5Wt;
    }
    
    function setComp6Wt($comp6Wt) {
        $this->comp6Wt = $comp6Wt;
    }
    
    function getComp6Wt() {
        return $this->comp6Wt;
    }
    
    function setComp7Wt($comp7Wt) {
        $this->comp7Wt = $comp7Wt;
    }
    
    function getComp7Wt() {
        return $this->comp7Wt;
    }
    
    function setComp8Wt($comp8Wt) {
        $this->comp8Wt = $comp8Wt;
    }
    
    function getComp8Wt() {
        return $this->comp8Wt;
    }
    
    function setComp9Wt($comp9Wt) {
        $this->comp9Wt = $comp9Wt;
    }
    
    function getComp9Wt() {
        return $this->comp9Wt;
    }
    
    function setDivWt($divWt) {
        $this->divWt = $divWt;
    }
    
    function getDivWt() {
        return $this->divWt;
    }
    
    function setTeamWt($teamWt) {
        $this->teamWt = $teamWt;
    }
    
    function getTeamWt() {
        return $this->teamWt;
    }
    
    function setPerWt($perWt) {
        $this->perWt = $perWt;
    }
    
    function getPerWt() {
        return $this->perWt;
    }
    
    function setATITalent($atiTalent) {
        $this->ATITalent = $atiTalent;
    }
    
    function getATITalent() {
        return $this->ATITalent;
    }  
    
    function setATIFinal($atiFinal) {
        $this->ATIFinal = $atiFinal;
    }
    
    function getATIFinal() {
        return $this->ATIFinal;
    }
    
    function setMySQLi($mysqli) {
        $this->mysqli = $mysqli;
    }
    
    function setCompScore($compScore) {
        $this->CompScore = $compScore;
    }
    
    function getCompScore() {
        return $this->CompScore;
    }
    
    function updateCompScore($change, $value,
                             $value1, $value2, $value3,
                             $value4, $value5, $value6,
                             $value7, $value8, $value9) { 
        
        $total = (($value1 * $this->getComp1Wt()) +
                  ($value2 * $this->getComp2Wt()) +
                  ($value3 * $this->getComp3Wt()) +
                  ($value4 * $this->getComp4Wt()) +
                  ($value5 * $this->getComp5Wt()) +
                  ($value6 * $this->getComp6Wt()) +
                  ($value7 * $this->getComp7Wt()) +
                  ($value8 * $this->getComp8Wt()) +
                  ($value9 * $this->getComp9Wt()));

        if($total > 0)
        {
            //Calculate and store the Competency Score
            $this->setCompScore(sprintf("%.2f", $total));
            $compScore = $this->getCompScore();
            
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

                $empID = $this->getEmpID();
                $CSSQL = "UPDATE Employee SET $change=$value, CompScore=$compScore WHERE empID=$empID";
                $result = $mysqli->query($CSSQL);
                if(!$result) {
                    $db->logDBError($_SESSION['name'], "Calculator: Error on $CSSQL");
                }
                mysqli_close($mysqli); 
            }
            unset($db);

            $this->updateAdjustScore();

            return $compScore;
        } else {
            return $this->getCompScore();
        }
    }
 
    function updateResultsScore($change, $value, $divGoal, $teamGoal, $perGoal) {     
        //Calculate the ResultsScore
        $this->updateGoalWts();
        
        $rScore = (($divGoal * $this->getDivWt()) +
                   ($teamGoal * $this->getTeamWt()) +
                   ($perGoal * $this->getPerWt()));                
        
        if($rScore > 0) {
            //Calculate and store the Competency Score
            $this->setResultsScore(sprintf("%.2f", $rScore));
            $resultsScore = $this->getResultsScore($rScore);

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
                $empID = $this->getEmpID();
                $RSSQL = "UPDATE Employee SET $change=$value, ResultsScore=$resultsScore WHERE empID=$empID";
                $result = $mysqli->query($RSSQL);
                if(!$result) {
                    $db->logDBError($_SESSION['name'], "Calculator: Error on $RSSQL");
                }
                mysqli_close($mysqli);
            }
            unset($db);

            $this->updateATIFinal();

            return $resultsScore;
        } else {
            return $this->getResultsScore();
        }
    }
    
    function updateRankTotal($change, $value,
                             $value1, $value2, $value3,
                             $value4, $value5, $value6,
                             $value7, $value8, $value9) {
        $this->updateCompWts();
        $total = (($value1 * $this->getComp1Wt()) +
                  ($value2 * $this->getComp2Wt()) +
                  ($value3 * $this->getComp3Wt()) +
                  ($value4 * $this->getComp4Wt()) +
                  ($value5 * $this->getComp5Wt()) +
                  ($value6 * $this->getComp6Wt()) +
                  ($value7 * $this->getComp7Wt()) +
                  ($value8 * $this->getComp8Wt()) +
                  ($value9 * $this->getComp9Wt()));
        $rankTotal = sprintf("%.2f", $total);
        $this->setRankTotal($rankTotal);
        
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
            $empID = $this->getEmpID();
            $SQL = "UPDATE Employee SET $change=$value, RankTotal=$rankTotal WHERE empID=$empID";
            $result = $mysqli->query($SQL);
            if(!$result) {
                $db->logDBError($_SESSION['name'], "Calculator: Error on $SQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);

        $this->updateAdjustScore();
           
        return $rankTotal;
    }
   
    function updateATITalent() {
        //Calculate ATITalent and ATIFinal
        $compScore = $this->getCompScore();
        $rankTotal = $this->getRankTotal();
        $gmAdjust = $this->getGMAdjust();
        $atiTalent = (($compScore * .5) + ($rankTotal *.5)) * $gmAdjust/100;
        $this->setATITalent($atiTalent);
        
        $this->updateATIFinal();
    }
    
    function updateATIFinal() {
        $atiTalent = $this->getATITalent();
        $resultsScore = $this->getResultsScore();
        $atiFinal = ($atiTalent * .5) + ($resultsScore * .5);
        $this->setATIFinal(sprintf("%1.2f", $atiFinal));
                
        //Update CompA and Multiplier
        $curSal = $this->getCurSal();
        $midPt = $this->getSalMidPoint();
        if($midPt > 0) {
            $this->setCompA($curSal / $midPt);
            $compA = $this->getCompA();
            if($compA > 0){                
                $atiFinal = $this->getATIFinal();
                $multiplier = ($atiFinal / 3) * (1/$compA);  //Penetration Adjustment
                $this->setMultiplier($multiplier);
                 
                //Temp1 = Budject available to employee adjusted by range penetration
                if(!class_exists('Budget')) {
                    include 'Budget.php';
                }
                $budg = Budget::getInstance();
                $availBudget = $budg->getAvailBudget($this->grade);
                
                $empCnt = $budg->getEmpCnt($this->grade);
                if($empCnt > 0) {
                    $temp1 = sprintf("%.2f", ($availBudget/$empCnt) * $multiplier);  
                    $this->setTemp1($temp1);
                }else {
                    echo "Calculator: Cannot update Temp1: Budget->EmpCnt = 0";
                }

                $grade = $this->getGrade();
                $compAdjust = $budg->getCompAdjust($grade);
                $salaryMax = $budg->getSalaryMax($grade);
                $curSal = $this->getCurSal();
                $temp1 = $this->getTemp1();

                if($compAdjust > 0) {
                    if(((($availBudget/$compAdjust)* $temp1) + $curSal) > $salaryMax) {
                        if($curSal > $salaryMax) {
                            $temp2 = 0;
                        } else {
                            $temp2 = $salaryMax - $curSal;
                        }
                    } else {
                        $temp2 = ($availBudget / $compAdjust * $temp1);
                    }
                } else {
                    echo "Calculator: Unable to update Temp2. Calc Adjust = 0";
                }
                $temp2 = sprintf("%.2f", $temp2);
                $this->setTemp2($temp2);

                if(($temp2 + $curSal) >= $salaryMax) {
                    $limited = 0;
                    $this->setLimited($limited);
                } else {
                    $limited = 1;
                    $this->setLimited($limited);
                }
                $budg->updateRedistribution($this->getGrade());
                
                $redistribution = $budg->getRedistribution($grade);

                if($budg->getNumLimited($grade)) {
                    $actualInc = $temp2 + $redistribution;
                } else {
                    $actualInc = $temp2;
                }
                $this->setActualInc(sprintf("%6.2f", $actualInc));

               $actInc = $this->getActualInc();

                $newSal = $curSal + $actInc;
                $this->setNewSalary($newSal);

                $incrPer = ($actInc/$curSal) * 100;  
                $this->setIncrPercent(sprintf("%2.2f", $incrPer));

                $empID = $this->getEmpID();
                $gmAdjust = $this->getGMAdjust();
                $adjustScore = $this->getAdjustScore();
                $atiTalent = $this->getATITalent();
                $compA = $this->getCompA();
                $limited = $this->getLimited();
                $actualInc = $this->getActualInc();
                $newSalary = $this->getNewSalary();
                $IPSQL = "UPDATE Employee SET AdjustScore=$adjustScore,
                                              ATITalent=$atiTalent,
                                              ATIFinal=$atiFinal,
                                              CompA=$compA,
                                              GMAdjust=$gmAdjust,
                                              Multiplier=$multiplier,
                                              Temp1=$temp1,
                                              Temp2=$temp2,
                                              Limited=$limited,
                                              ActualInc=$actualInc,
                                              NewSal=$newSalary,
                                              IncrPercent=$incrPer WHERE empID=$empID";


                //Establish DB Connection
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
                    $result = $mysqli->query($IPSQL);
                    if(!$result){
                        $db->logDBError($_SESSION['name'], "Calculator: Error on $IPSQL");
                    }
                    mysqli_close($mysqli);
                }
                unset($db);
            }
        }
    }
}

gc_disable();
?>
