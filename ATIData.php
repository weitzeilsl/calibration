<!--
 * File:   ATIData
 * Author: Steven Weitzeil
 * Date:   23 May 2013
 * Desc:   Class to handle the backend work for the ATI page
 * Review: 
 -->

<!DOCTYPE html>
<?php

gc_enable();

class ATIData {
    private $employee = array(array());
            
    function __construct() {
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
            //Get the current HR Increase Rate
            if(!isset($_SESSION["hrrate"])){
                $SQL = "SELECT hrrate FROM hrrate WHERE idhrrate = 1";
                $result = $mysqli->query($SQL);
                if($result) {
                    $db_field = mysqli_fetch_assoc($result);
                    $_SESSION['hrrate'] = $db_field['hrrate'];
                    $result->close();
                } else {
                    $db->logDBError($_SESSION['name'], "ATIData - unable to $SQL");
                }
            }

            $SQL = "SELECT * FROM Employee ORDER BY lastName ASC";
            $result = $mysqli->query($SQL);
            if($result) {
                $employeeCnt = 0;
                while ($db_field = mysqli_fetch_assoc($result)){
                    $this->employee[$employeeCnt]['empID'] = $db_field['empID'];
                    $this->employee[$employeeCnt]['lastName'] = $db_field['lastName'];
                    $this->employee[$employeeCnt]['firstName'] = $db_field['firstName'];
                    $this->employee[$employeeCnt]['Grade'] = $db_field['Grade'];
                    $this->employee[$employeeCnt]['MgrFlag'] = $db_field['MgrFlag'];
                    $this->employee[$employeeCnt]['mgrID'] = $db_field['mgrID'];
                    $this->employee[$employeeCnt]['MgrName'] = $db_field['MgrName'];
                    $this->employee[$employeeCnt]['CompScore'] = $db_field['CompScore'];
                    $this->employee[$employeeCnt]['RankTotal'] = $db_field['RankTotal'];
                    $this->employee[$employeeCnt]['GMAdjust'] = $db_field['GMAdjust'];
                    $this->employee[$employeeCnt]['AdjustScore'] = $db_field['AdjustScore'];
                    $this->employee[$employeeCnt]['ResultsScore'] = $db_field['ResultsScore'];
                    $this->employee[$employeeCnt]['ATITalent'] = $db_field['ATITalent'];
                    $this->employee[$employeeCnt]['ATIFinal'] = $db_field['ATIFinal'];
                    $this->employee[$employeeCnt]['CurSal'] = $db_field['CurSal'];
                    $this->employee[$employeeCnt]['ActualInc'] = $db_field['ActualInc'];
                    $this->employee[$employeeCnt]['NewSal'] = $db_field['NewSal'];
                    $this->employee[$employeeCnt]['IncrPercent'] = $db_field['IncrPercent'];
                    $this->employee[$employeeCnt]['Justification'] = $db_field['Justification'];
                    $this->employee[$employeeCnt]['Role'] = $db_field['Role'];
                    $this->employee[$employeeCnt]['Promote'] = $db_field['Promote'];

                    $employeeCnt++;
                }
                $result->close();
            }else {
                    $db->logDBError($_SESSION['name'], "ATIData - unable to $SQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    
    
    function getEmployeesFiltered($mgrID, $grade, $role, $directs, $level, $empDisplay, $filterField, $filterValue) {
        $level++;
        $singleEmp = array();
        
        //If Administrator, make the same as root
        if($mgrID === "000000") {$mgrID = "380034";}
        
        foreach ($this->employee as $singleEmp){
            //Does this person report to the selected manager?
            if($singleEmp['mgrID'] == $mgrID){
                //Are we showing all grades or does the person belong to the selected grade?
                if($grade == 'All' ||$singleEmp['Grade'] == $grade){
                    if($role == 'All' ||$singleEmp['Role'] == $role){
                       //Is this person a direct report and do we want direct reports?
                       if($directs == "2" ||                                                                                   //M+ - With direct managers
                          ($directs == "1" && ($singleEmp["MgrFlag"] == 1)) ||                                                 //M  - Direct managers only
                          ($directs == "3" && $level == 1) ||                                                                  //D  - Show all direct reports
                          ($directs == "0" && (($singleEmp["MgrFlag"] == 0) || ($singleEmp["MgrFlag"] == 1 && $level > 1)))){  //M- - No direct managers  
                            if($singleEmp[$filterField] == $filterValue) {
                                if($empDisplay[0]) {
                                    array_push($empDisplay, $singleEmp);
                                } else {
                                    $empDisplay = array(array());
                                    $empDisplay[0] = $singleEmp;
                                }
                            }
                        }
                    }
                }
                if($directs != 1) {
                    $empDisplay = $this->getEmployeesFiltered($singleEmp['empID'], $grade, $role, $directs, $level, $empDisplay, $filterField, $filterValue);
                }
            }
        }
        return $empDisplay;
    }
    
    function getEmployees($mgrID, $grade, $role, $directs, $level, $empDisplay) {
        $level++;
        $singleEmp = array();
        
        //If Administrator, make the same as root
        if($mgrID === "000000") {$mgrID = "380034";}
        
        foreach ($this->employee as $singleEmp){
            //Does this person report to the selected manager?
            if($singleEmp['mgrID'] == $mgrID){
                //Are we showing all grades or does the person belong to the selected grade?
                if($grade == 'All' ||$singleEmp['Grade'] == $grade){
                    if($role == 'All' ||$singleEmp['Role'] == $role){
                       //Is this person a direct report and do we want direct reports?
                       if($directs == "2" ||                                                                                   //M+ - With direct managers
                          ($directs == "1" && ($singleEmp["MgrFlag"] == 1)) ||                                                 //M  - Direct managers only
                          ($directs == "3" && $level == 1) ||                                                                  //D  - Show all direct reports
                          ($directs == "0" && (($singleEmp["MgrFlag"] == 0) || ($singleEmp["MgrFlag"] == 1 && $level > 1)))){  //M- - No direct managers  
                            if($empDisplay[0]) {
                                array_push($empDisplay, $singleEmp);
                            } else {
                                $empDisplay = array(array());
                                $empDisplay[0] = $singleEmp;
                            }
                        }
                    }
                }
                if($directs != 1) {
                    $empDisplay = $this->getEmployees($singleEmp['empID'], $grade, $role, $directs, $level, $empDisplay);
                }
            }
        }
        return $empDisplay;
    }
    
    function displayEmployees($empResults, $numToDisplay, $reportFlag){
        if(array_count_values($empResults[0])) {
            $count = 0;
            $lastATIFinal = 0;
            foreach($empResults as $singleEmp){
                if(($count > $numToDisplay) && ($lastATIFinal!=$singleEmp["ATIFinal"])) {
                    break;
                }
                $this->displayRow($singleEmp, $count, $reportFlag);
                $lastATIFinal = $singleEmp["ATIFinal"];
                $count++;
            }
            echo"</table>";
            echo"</tbody>";
        }
    }
    
    function displayRow($singleEmp, $count, $reportFlag) {
        echo"<tr>";
        echo"<th headers='empid$count' align='center' width='53'>$singleEmp[empID]</td>";
        echo"<td headers='lname$count' align='left' width='100'>$singleEmp[lastName]</td>";
        echo"<td headers='fname$count' align='left' width='120'>$singleEmp[firstName]</td>";
        echo"<td headers='grade$count' align='center' width='50'>$singleEmp[Grade]</td>";
        echo"<td headers='mgrname$count' align='left' width='110'>$singleEmp[MgrName]</td>";
        echo"<td headers='compScore$count' align='center' width='46'>$singleEmp[CompScore]</td>";
        echo"<td headers='rankTotal$count' align='center' width='46'>$singleEmp[RankTotal]</td>";
        if($reportFlag) {
            echo"<td headers='gmAdjust$count' align='center' width='40'>$singleEmp[GMAdjust]</td>";
        } else {
            echo"<td headers='gmAdjust$count' align='center' class='gmAdjust' width='40'><input type='text' size='1' id='gmAdjust' name='$singleEmp[empID]' value='$singleEmp[GMAdjust]'/></td>";
        }
        echo"<td headers='adjustscore$count' align='center' width='46' class='AdjustScore'>$singleEmp[AdjustScore]</td>";
        $resscore = sprintf("%d", $singleEmp['ResultsScore']);
        echo"<td headers='resultsScore$count' align='center' width='46'>$resscore</td>";
        echo"<td headers='atitalent$count' align='center' width='46'>$singleEmp[ATITalent]</td>";
        echo"<td headers='atifinal$count' align='center' width='46' class='ATIFinal'>$singleEmp[ATIFinal]</td>";
        echo"<td headers='cursal$count' align='center' width='100'>$singleEmp[CurSal]</td>";
        echo"<td headers='actualinc$count' align='center' width='46' class='ActualInc'>$singleEmp[ActualInc]</td>";
        echo"<td headers='newsal$count' align='center' width='46' class='NewSal'>$singleEmp[NewSal]</td>";
        echo"<td headers='incrpercent$count' align='center' width='46' class='IncrPercent'>$singleEmp[IncrPercent]</td>";
        if($reportFlag) {
            if($singleEmp['Promote']) {
                echo"<td headers='Promote' align='center' width='46' class='Promote'><input type='checkbox' disabled='disabled' id='Promote' name='$singleEmp[empID]' value='Promote' checked/></td>";
            } else {
                echo"<td headers='Promote' align='center' width='46' class='Promote'><input type='checkbox' disabled='disabled' id='Promote' name='$singleEmp[empID]' value='Promote' unchecked/></td>";
            }
        } else {
            if($singleEmp['Promote']) {
                echo"<td headers='Promote' align='center' width='46' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' value='Promote' checked/></td>";
            } else {
                echo"<td headers='Promote' align='center' width='46' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' value='Promote' unchecked/></td>";
            }
        }
        if($reportFlag) {
            echo"<td headers='justification$count' align='left' width='300'>$singleEmp[Justification]</td>";
        } else {
            echo"<td headers='justification$count' align='left' class='Justification' width='300'><input type='text' size='50' id='Justification' name='$singleEmp[empID]' value='$singleEmp[Justification]'/></td>";
        }
        echo"</tr>";
    }
    
    function sortEmployees($empResults, $sortBy, $order){
        if(array_count_values($empResults[0])) {
            $name = array();
            foreach($empResults as $emp){
                $name[] = $emp[$sortBy];
            }
            if ($order == SORT_ASC) {
                array_multisort($name, SORT_ASC, SORT_STRING, $empResults);
            } else {
                array_multisort($name, SORT_DESC, SORT_STRING, $empResults);
            }
        }
        return $empResults;
    }
    
    function displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy) {
        $currentMgr = $this->base64url_encode($currentMgr);
        echo "<ul id='menu'>";
            echo"<li><a href='/index.php'>Home</a></li>";
            echo"<li id='manager'><a href='/ManagerSelectionWeb.php?target=ati'>$mgrName - $numEmps</a></li>";
            if($directs == 3){
                echo"<li><a href='/EmployeeATIWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>D*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>D</a></li>";
            }
            if($directs == 1){
                echo"<li><a href='/EmployeeATIWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>M</a></li>";
            }
            if($directs == 0){
                echo"<li><a href='/EmployeeATIWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>-M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>-M</a></li>";
            }
            if($directs == 2){
                echo"<li><a href='/EmployeeATIWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>+M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>+M</a></li>";
            }
            if($currentGrade=='All'){
                echo"<li id='gradefirst'><a href='/EmployeeATIWeb.php?d=$directs&g=All&n=$currentMgr&r=$currentRole&s=$sortBy'>All*</a></li>";
            }else{
                echo"<li id='gradefirst'><a href='/EmployeeATIWeb.php?d=$directs&g=All&n=$currentMgr&r=$currentRole&s=$sortBy'>All</a></li>";
            }
            if($currentGrade==99){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy'>99*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy'>99</a></li>";
            }
            if($currentGrade==98){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy'>98*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy'>98</a></li>";
            }
            if($currentGrade==97){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy'>97*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy'>97</a></li>";
            }
            if($currentGrade==96){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy'>96*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy'>96</a></li>";
            }
            if($currentGrade==95){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy'>95*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy'>95</a></li>";
            }
            if($currentGrade==94){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy'>94*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy'>94</a></li>";
            }
            if($currentGrade==93){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy'>93*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy'>93</a></li>";
            }
            if($currentGrade==92){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy'>92*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy'>92</a></li>";
            }
            if($currentGrade==91){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy'>91*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy'>91</a></li>";
            }
            if($currentGrade==90){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy'>90*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy'>90</a></li>";
            }
            if($currentGrade==89){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy'>89*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy'>89</a></li>";
            }
            if($currentGrade==88){
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy'>88*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeATIWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy'>88</a></li>";
            }
            if($currentRole=='All'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy'>All*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy'>All</a></li>";
            }
            if($currentRole=='SD'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy'>SD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy'>SD</a></li>";
            }
            if($currentRole=='WD'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy'>WD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy'>WD</a></li>";
            }
            if($currentRole=='UX'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy'>UX*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy'>UX</a></li>";
            }
            if($currentRole=='QA'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy'>QA*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy'>QA</a></li>";
            }
            if($currentRole=='AU'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy'>AU*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy'>AU</a></li>";
            }
            if($currentRole=='PG'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy'>PG*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy'>PG</a></li>";
            }
            if($currentRole=='AD'){
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy'>AD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy'>AD</a></li>";
            }
            echo"<li id='gradefirst'><a href='/help/index.html#ATI' target='_blank'>Help</a></li>";
        echo"</ul><br>";
    }
    
     function base64url_encode($str) {
        return strtr(base64_encode($str), '+/', '-_');
    }

    function base64url_decode($base64url) {
        return base64_decode(strtr($base64url, '-_', '+/'));
    }
    
    function displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order, $report){
        $currentMgr = $this->base64url_encode($currentMgr);        
        echo "<form>";
        echo"<table border='0'>";        
        echo"<thead>";
        echo"<tr>";
        if(!$report){
            echo"<th id='empid'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=empID&o=$order&h=1'>ID</a></th>";
            echo"<th id='lname' align=left><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=lastName&o=$order&h=1'>Last Name</a></th>";
            echo"<th id='fname' align=left><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=firstName&o=$order&h=1'>First Name</a></th>";
            echo"<th id='grade' align=center><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Grade&o=$order&h=1'>Grade</a></th>";
            echo"<th id='mgrid' align=left><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=mgrID&o=$order&h=1'>Manager</a></th>";
            echo"<th id='compscore'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=CompScore&o=$order&h=1'>Comp Score</a></th>";
            echo"<th id='rank'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=RankTotal&o=$order&h=1'>Rank</a></th>";
            echo"<th id='adjustment'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=GMAdjust&o=$order&h=1'>Adjustment %</a></th>";
            echo"<th id='adjustedscore'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=AdjustScore&o=$order&h=1'>Adjusted Score</a></th>";
            echo"<th id='resultsscore'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=ResultsScore&o=$order&h=1'>ATI Results</a></th>";
            echo"<th id='atitalent'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=ATITalent&o=$order&h=1'>ATI Talent</a></th>";
            echo"<th id='atifinal'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=ATIFinal&o=$order&h=1'>ATI Final</a></th>";
            echo"<th id='cursal'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=CurSal&o=$order&h=1'>Current Salary</a></th>";
            echo"<th id='actinc'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=ActualInc&o=$order&h=1'>Actual Increase</a></th>";
            echo"<th id='newsal'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=NewSal&o=$order&h=1'>New Salary</a></th>";
            echo"<th id='incPer'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=IncrPercent&o=$order&h=1'>Increase %</a></th>";
            echo"<th id='promote'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Promote&o=$order&h=1'>Promote</a></th>";
            echo"<th id='justification'><a href='/EmployeeATIWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Justification&o=$order&h=1'>Justification</a></th>";
        } else {
            echo"<th id='empid'>ID</th>";
            echo"<th id='lname' align=left>Last Name</th>";
            echo"<th id='fname' align=left>First Name</th>";
            echo"<th id='grade' align=center>Grade</th>";
            echo"<th id='mgrid' align=left>Manager</th>";
            echo"<th id='compscore'>Comp Score</th>";
            echo"<th id='rank'>Rank</th>";
            echo"<th id='adjustment'>Adjustment %</th>";
            echo"<th id='adjustedscore'>Adjusted Score</th>";
            echo"<th id='resultsscore'>ATI Results</th>";
            echo"<th id='atitalent'>ATI Talent</th>";
            echo"<th id='atifinal'>ATI Final</th>";
            echo"<th id='cursal'>Current Salary</th>";
            echo"<th id='actinc'>Actual Increase</th>";
            echo"<th id='newsal'>New Salary</th>";
            echo"<th id='incPer'>Increase %</th>";
            echo"<th id='promote'>Promote</th>";
            echo"<th id='justification'>Justification</th>";
        }
        echo"</tr>";
        echo"</thead>";
    }
    
    function getSortOrder($sortBy){        
        $order = SORT_ASC;
        switch($sortBy){
            case "empID":
                if(isset($_SESSION['empIDOrder'])){
                    if($_SESSION['empIDOrder']){
                        $order = SORT_ASC;
                        $_SESSION['empIDOrder']=0;
                    } else {
                        $_SESSION['empIDOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['empIDOrder']=1;
                }
                break;
            case "lastName":
                if(isset($_SESSION['lastNameOrder'])){
                    if($_SESSION['lastNameOrder']){
                        $order = SORT_ASC;
                        $_SESSION['lastNameOrder']=0;
                    } else {
                        $_SESSION['lastNameOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['lastNameOrder']=1;
                }
                break;
            case "firstName":
                if(isset($_SESSION['firstNameOrder'])){
                    if($_SESSION['firstNameOrder']){
                        $order = SORT_ASC;
                        $_SESSION['firstNameOrder']=0;
                    } else {
                        $_SESSION['firstNameOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['firstNameOrder']=1;
                }
                break;
            case "Grade":
                if(isset($_SESSION['gradeOrder'])){
                    if($_SESSION['gradeOrder']){
                        $order = SORT_ASC;
                        $_SESSION['gradeOrder']=0;
                    } else {
                        $_SESSION['gradeOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['gradeOrder']=1;
                }
                break;
            case "mgrID":
                if(isset($_SESSION['mgrNameOrder'])){
                    if($_SESSION['mgrNameOrder']){
                        $order = SORT_ASC;
                        $_SESSION['mgrNameOrder']=0;
                    } else {
                        $_SESSION['mgrNameOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['mgrNameOrder']=1;
                }
                break;
            case "CompScore":
                if(isset($_SESSION['CompScoreOrder'])){
                    if($_SESSION['CompScoreOrder']){
                        $order = SORT_ASC;
                        $_SESSION['CompScoreOrder']=0;
                    } else {
                        $_SESSION['CompScoreOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['CompScoreOrder']=1;
                }
                break;
            case "RankTotal":
                if(isset($_SESSION['RankTotalOrder'])){
                    if($_SESSION['RankTotalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['RankTotalOrder']=0;
                    } else {
                        $_SESSION['RankTotalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['RankTotalOrder']=1;
                }
                break;
            case "GMAdjust":
                if(isset($_SESSION['GMAdjustOrder'])){
                    if($_SESSION['GMAdjustOrder']){
                        $order = SORT_ASC;
                        $_SESSION['GMAdjustOrder']=0;
                    } else {
                        $_SESSION['GMAdjustOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['GMAdjustOrder']=1;
                }
                break;
            case "AdjustScore":
                if(isset($_SESSION['AdjustScoreOrder'])){
                    if($_SESSION['AdjustScoreOrder']){
                        $order = SORT_ASC;
                        $_SESSION['AdjustScoreOrder']=0;
                    } else {
                        $_SESSION['AdjustScoreOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['AdjustScoreOrder']=1;
                }
                break;
            case "ResultsScore":
                if(isset($_SESSION['ResultsScoreOrder'])){
                    if($_SESSION['ResultsScoreOrder']){
                        $order = SORT_ASC;
                        $_SESSION['ResultsScoreOrder']=0;
                    } else {
                        $_SESSION['ResultsScoreOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['ResultsScoreOrder']=1;
                }
                break;
            case "ATITalent":
                if(isset($_SESSION['ATITalentOrder'])){
                    if($_SESSION['ATITalentOrder']){
                        $order = SORT_ASC;
                        $_SESSION['ATITalentOrder']=0;
                    } else {
                        $_SESSION['ATITalentOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['ATITalentOrder']=1;
                }
                break;
            case "ATIFinal":
                if(isset($_SESSION['ATIFinalOrder'])){
                    if($_SESSION['ATIFinalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['ATIFinalOrder']=0;
                    } else {
                        $_SESSION['ATIFinalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['ATIFinalOrder']=1;
                }
                break;
            case "CurSal":
                if(isset($_SESSION['CurSalOrder'])){
                    if($_SESSION['CurSalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['CurSalOrder']=0;
                    } else {
                        $_SESSION['CurSalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['CurSalOrder']=1;
                }
                break;
            case "ActualInc":
                if(isset($_SESSION['ActualIncOrder'])){
                    if($_SESSION['ActIncOrder']){
                        $order = SORT_ASC;
                        $_SESSION['ActualIncOrder']=0;
                    } else {
                        $_SESSION['ActualIncOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['ActualIncOrder']=1;
                }
                break;
            case "NewSal":
                if(isset($_SESSION['NewSalOrder'])){
                    if($_SESSION['NewSalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['NewSalOrder']=0;
                    } else {
                        $_SESSION['NewSalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['NewSalOrder']=1;
                }
                break;
            case "IncrPercent":
                if(isset($_SESSION['IncrPercentOrder'])){
                    if($_SESSION['IncrPercentOrder']){
                        $order = SORT_ASC;
                        $_SESSION['IncrPercentOrder']=0;
                    } else {
                        $_SESSION['IncrPercentOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['IncrPercentOrder']=1;
                }
                break;
            case "Promote":
                if(isset($_SESSION['PromoteOrder'])){
                    if($_SESSION['PromoteOrder']){
                        $order = SORT_ASC;
                        $_SESSION['PromoteOrder']=0;
                    } else {
                        $_SESSION['PromoteOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['PromoteOrder']=1;
                }
                break;
            case "Justification":
                if(isset($_SESSION['JustificationOrder'])){
                    if($_SESSION['JustificationOrder']){
                        $order = SORT_ASC;
                        $_SESSION['JustificationOrder']=0;
                    } else {
                        $_SESSION['JustificationOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['JustificationOrder']=1;
                }
                break;
        }
        return $order;
    } 
}
gc_disable();
?>
