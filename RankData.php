<!DOCTYPE html>
<?php
/**
 * RankData
 * Steven Weitzeil
 * FamilySearch
 * 6 April 2013
 */
gc_enable();

class RankData {
    var $dist = array();
    
    private $employee = array(array());
            
    function __construct() {
        $this->dist['romp1'] = array(0, 0, 0, 0, 0);
        $this->dist['romp2'] = array(0, 0, 0, 0, 0);
        $this->dist['romp3'] = array(0, 0, 0, 0, 0);
        $this->dist['romp4'] = array(0, 0, 0, 0, 0);
        $this->dist['romp5'] = array(0, 0, 0, 0, 0);
        $this->dist['romp6'] = array(0, 0, 0, 0, 0);
        $this->dist['romp7'] = array(0, 0, 0, 0, 0);
        $this->dist['romp8'] = array(0, 0, 0, 0, 0);
        $this->dist['romp9'] = array(0, 0, 0, 0, 0);
        
        $db = new DBAccess();
        
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if($mysqli->connect_errno){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            $SQL = "SELECT * FROM Employee ORDER BY lastName ASC";
            $result = $mysqli->query($SQL);
            if($result) {
                $employeeCnt = 0;
                while ($db_field = mysqli_fetch_assoc($result)){
                    $this->employee[$employeeCnt]['empID'] = $db_field['empID'];
                    $this->employee[$employeeCnt]['firstName'] = $db_field['firstName'];
                    $this->employee[$employeeCnt]['lastName'] = $db_field['lastName'];
                    $this->employee[$employeeCnt]['Grade'] = $db_field['Grade'];
                    $this->employee[$employeeCnt]['MgrFlag'] = $db_field['MgrFlag'];
                    $this->employee[$employeeCnt]['mgrID'] = $db_field['mgrID'];
                    $this->employee[$employeeCnt]['MgrName'] = $db_field['MgrName'];
                    $this->employee[$employeeCnt]['Romp1'] = $db_field['Romp1'];
                    $this->employee[$employeeCnt]['Romp2'] = $db_field['Romp2'];
                    $this->employee[$employeeCnt]['Romp3'] = $db_field['Romp3'];
                    $this->employee[$employeeCnt]['Romp4'] = $db_field['Romp4'];
                    $this->employee[$employeeCnt]['Romp5'] = $db_field['Romp5'];
                    $this->employee[$employeeCnt]['Romp6'] = $db_field['Romp6'];
                    $this->employee[$employeeCnt]['Romp7'] = $db_field['Romp7'];
                    $this->employee[$employeeCnt]['Romp8'] = $db_field['Romp8'];
                    $this->employee[$employeeCnt]['Romp9'] = $db_field['Romp9'];
                    $this->employee[$employeeCnt]['RankTotal'] = $db_field['RankTotal'];
                    $this->employee[$employeeCnt]['Role'] = $db_field['Role'];
                    $this->employee[$employeeCnt]['Promote'] = $db_field['Promote'];

                    $employeeCnt++;
                }
            } else {
                $db->logDBError($_SESSION['name'], "RankData:construct - unable to select Employee - $SQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function getEmployees($mgrID, $grade, $role, $directs, $level, $empDisplay)
    {
        $level++;
        $singleEmp = array();
        
        //If Administrator, make the same as root
        if($mgrID === "000000") {$mgrID = "380034";}
        
        foreach ($this->employee as $singleEmp){
            //Does this person report to the selected manager?
            if($singleEmp['mgrID'] == $mgrID){
                //Are we showing all grades or does the person belong to the selected grade?
                if($grade == 0 ||$singleEmp['Grade'] == $grade){                    
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
    
    function displayEmployees($empResults, $submitted){
        if(array_count_values($empResults[0])) {
            $count = 0;
            echo"<tbody>";
            foreach($empResults as $singleEmp){
                echo"<tr>";
                echo"<th headers='empid$count' align='center' width='53'>$singleEmp[empID]</td>";
                echo"<td headers='lname$count' align='left' width='136'>$singleEmp[lastName]</td>";
                echo"<td headers='fname$count' align='left' width='120'>$singleEmp[firstName]</td>";
                echo"<td headers='grade$count' align='center' width='46'>$singleEmp[Grade]</td>";
                echo"<td headers='mgrid$count' align='left' width='103'>$singleEmp[MgrName]</td>";
                if($submitted) {
                    echo"<td headers='rompA$count' align='center' class='Romp1'>$singleEmp[Romp1]</td>";
                    echo"<td headers='rompB$count' align='center' class='Romp2'>$singleEmp[Romp2]</td>";
                    echo"<td headers='rompC$count' align='center' class='Romp3'>$singleEmp[Romp3]</td>";
                    echo"<td headers='rompD$count' align='center' class='Romp4'>$singleEmp[Romp4]</td>";
                    echo"<td headers='rompE$count' align='center' class='Romp5'>$singleEmp[Romp5]</td>";
                    echo"<td headers='rompF$count' align='center' class='Romp6'>$singleEmp[Romp6]</td>";
                    echo"<td headers='rompG$count' align='center' class='Romp7'>$singleEmp[Romp7]</td>";
                    echo"<td headers='rompH$count' align='center' class='Romp8'>$singleEmp[Romp8]</td>";
                    echo"<td headers='rompI$count' align='center' class='Romp9'>$singleEmp[Romp9]</td>";
                } else {
                    echo"<td headers='rompA$count' align='center' class='Romp1'><input type='text' size='1' id='Romp1' name='$singleEmp[empID]' value='$singleEmp[Romp1]'/></td>";
                    echo"<td headers='rompB$count' align='center' class='Romp2'><input type='text' size='1' id='Romp2' name='$singleEmp[empID]' value='$singleEmp[Romp2]'/></td>";
                    echo"<td headers='rompC$count' align='center' class='Romp3'><input type='text' size='1' id='Romp3' name='$singleEmp[empID]' value='$singleEmp[Romp3]'/></td>";
                    echo"<td headers='rompD$count' align='center' class='Romp4'><input type='text' size='1' id='Romp4' name='$singleEmp[empID]' value='$singleEmp[Romp4]'/></td>";
                    echo"<td headers='rompE$count' align='center' class='Romp5'><input type='text' size='1' id='Romp5' name='$singleEmp[empID]' value='$singleEmp[Romp5]'/></td>";
                    echo"<td headers='rompF$count' align='center' class='Romp6'><input type='text' size='1' id='Romp6' name='$singleEmp[empID]' value='$singleEmp[Romp6]'/></td>";
                    echo"<td headers='rompG$count' align='center' class='Romp7'><input type='text' size='1' id='Romp7' name='$singleEmp[empID]' value='$singleEmp[Romp7]'/></td>";
                    echo"<td headers='rompH$count' align='center' class='Romp8'><input type='text' size='1' id='Romp8' name='$singleEmp[empID]' value='$singleEmp[Romp8]'/></td>";
                    echo"<td headers='rompI$count' align='center' class='Romp9'><input type='text' size='1' id='Romp9' name='$singleEmp[empID]' value='$singleEmp[Romp9]'/></td>";
                }
                printf("<td headers='ranktotal$count' align='right' class='RankTotal'>%1.2f</td>", $singleEmp['RankTotal']);
                if($singleEmp['Promote']) {
                    if($submitted) {
                        echo"<td headers='Promote' align='center' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' disabled='disabled' value='Promote' checked/></td>";
                    }else {
                        echo"<td headers='Promote' align='center' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' value='Promote' checked/></td>";
                    }
                } else {
                    if($submitted) {
                        echo"<td headers='Promote' align='center' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' disabled='disabled' value='Promote' unchecked/></td>";
                    }else {
                        echo"<td headers='Promote' align='center' class='Promote'><input type='checkbox' id='Promote' name='$singleEmp[empID]' value='Promote' unchecked/></td>";
                    }
                }
                echo"</tr>";
                $count++;
                
                $this->dist['romp1'][$singleEmp['Romp1']-1]++;
                $this->dist['romp2'][$singleEmp['Romp2']-1]++;
                $this->dist['romp3'][$singleEmp['Romp3']-1]++;
                $this->dist['romp4'][$singleEmp['Romp4']-1]++;
                $this->dist['romp5'][$singleEmp['Romp5']-1]++;
                $this->dist['romp6'][$singleEmp['Romp6']-1]++;
                $this->dist['romp7'][$singleEmp['Romp7']-1]++;
                $this->dist['romp8'][$singleEmp['Romp8']-1]++;
                $this->dist['romp9'][$singleEmp['Romp9']-1]++;
            }
            echo"</table><br>";
            echo"<table width=1262>";
            echo"<tr>";
                echo"<td width=355>Distribution</td>
                     <td width=101 align=center>Target</td>
                     <td width=84 align=center>Leadership</td>
                     <td width=119 align=center>Communication</td>
                     <td width=89 align=center>Technology</td>
                     <td width=63 align=center>Process</td>
                     <td width=98 align=center>Improvement</td>
                     <td width=91 align=center>Productivity</td>
                     <td width=70 align=center>Influence</td>
                     <td width=74 align=center>Judgment</td>
                     <td width=54 align=center>Quality</td>";
            echo"</tr>";
            echo"<tr class=fives>";
                echo"<td align=center>5</td>";
                $numFives = round($count*.1);
                if($count > 2) {
                   $numThrees = round($count*.4); 
                } else {
                    if($count == 2) {
                        $numThrees = 0;
                    } else if($count == 1) {
                        $numThrees = 1;
                    }
                }
                if(($numThrees % 2) && ($count %2) ||      //num of threes is odd and count is odd, or
                   (!($numThrees % 2) && !($count % 2))){  //num of threes ie even and count is even
                    $numOnes = $numFives;
                }else {
                    if($numFives) {
                        $numOnes = $numFives-1;
                    }else {
                        $numOnes = 0;
                    }
                }
                printf("<td align=center>%2.0f</td>", $numFives);
                $FiveCount = $this->dist['romp1'][4];
                echo"<td class='romp1-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp2'][4];
                echo"<td class='romp2-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp3'][4];
                echo"<td class='romp3-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp4'][4];
                echo"<td class='romp4-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp5'][4];
                echo"<td class='romp5-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp6'][4];
                echo"<td class='romp6-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp7'][4];
                echo"<td class='romp7-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp8'][4];
                echo"<td class='romp8-5' align=center>$FiveCount</td>";
                $FiveCount = $this->dist['romp9'][4];
                echo"<td class='romp9-5' align=center>$FiveCount</td>";
            echo"</tr>";
            echo"<tr class=fours>";
                echo"<td align=center>4</td>";
                if($count == 2) {
                    $numTwosandFours = 1;
                } else {
                    $numTwosandFours = $count*.2;
                }
                printf("<td align=center>%2.0f</td>", $numTwosandFours);
                $FourCount = $this->dist['romp1'][3];
                echo"<td class='romp1-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp2'][3];
                echo"<td class='romp2-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp3'][3];
                echo"<td class='romp3-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp4'][3];
                echo"<td class='romp4-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp5'][3];
                echo"<td class='romp5-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp6'][3];
                echo"<td class='romp6-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp7'][3];
                echo"<td class='romp7-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp8'][3];
                echo"<td class='romp8-4' align=center>$FourCount</td>";
                $FourCount = $this->dist['romp9'][3];
                echo"<td class='romp9-4' align=center>$FourCount</td>";
            echo"</tr>";
            echo"<tr class=threes>";
                echo"<td align=center>3</td>";
                printf("<td align=center>%2.0f</td>", $numThrees);
                $ThreeCount = $this->dist['romp1'][2];
                echo"<td class='romp1-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp2'][2];
                echo"<td class='romp2-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp3'][2];
                echo"<td class='romp3-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp4'][2];
                echo"<td class='romp4-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp5'][2];
                echo"<td class='romp5-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp6'][2];
                echo"<td class='romp6-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp7'][2];
                echo"<td class='romp7-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp8'][2];
                echo"<td class='romp8-3' align=center>$ThreeCount</td>";
                $ThreeCount = $this->dist['romp9'][2];
                echo"<td class='romp9-3' align=center>$ThreeCount</td>";
            echo"</tr>";
            echo"<tr class=twos>";
                echo"<td align=center>2</td>";
                printf("<td align=center>%2.0f</td>", $numTwosandFours);
                $TwoCount = $this->dist['romp1'][1];
                echo"<td class='romp1-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp2'][1];
                echo"<td class='romp2-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp3'][1];
                echo"<td class='romp3-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp4'][1];
                echo"<td class='romp4-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp5'][1];
                echo"<td class='romp5-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp6'][1];
                echo"<td class='romp6-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp7'][1];
                echo"<td class='romp7-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp8'][1];
                echo"<td class='romp8-2' align=center>$TwoCount</td>";
                $TwoCount = $this->dist['romp9'][1];
                echo"<td class='romp9-2' align=center>$TwoCount</td>";
            echo"</tr>";
            echo"<tr class=ones>";
                echo"<td align=center>1</td>";
                printf("<td align=center>%2.0f</td>", $numOnes);
                $OneCount = $this->dist['romp1'][0];
                echo"<td class='romp1-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp2'][0];
                echo"<td class='romp2-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp3'][0];
                echo"<td class='romp3-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp4'][0];
                echo"<td class='romp4-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp5'][0];
                echo"<td class='romp5-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp6'][0];
                echo"<td class='romp6-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp7'][0];
                echo"<td class='romp7-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp8'][0];
                echo"<td class='romp8-1' align=center>$OneCount</td>";
                $OneCount = $this->dist['romp9'][0];
                echo"<td class='romp9-1' align=center>$OneCount</td>";
            echo"</tr>";
            echo "</table>";
            echo "</form>";
            echo"</tbody>";
        }
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
    
    function displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy, $order) {
        $currentMgr = $this->base64url_encode($currentMgr);
        echo "<ul id='menu'>";
            echo"<li><a href='/index.php'>Home</a></li>";
            echo"<li id='manager'><a href='/ManagerSelectionWeb.php?target=rank'>$mgrName - $numEmps</a></li>";
            if($directs == 3){
                echo"<li><a href='/EmployeeRankWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>D*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>D</a></li>";
            }
            if($directs == 1){
                echo"<li><a href='/EmployeeRankWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>M</a></li>";
            }
            if($directs == 0){
                echo"<li><a href='/EmployeeRankWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>-M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>-M</a></li>";
            }
            if($directs == 2){
                echo"<li><a href='/EmployeeRankWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>+M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy'>+M</a></li>";
            }
            if($currentGrade==0){
                echo"<li id='gradefirst'><a href='/EmployeeRankWeb.php?d=$directs&g=0&n=$currentMgr&r=$currentRole&s=$sortBy'>All*</a></li>";
            }else{
                echo"<li id='gradefirst'><a href='/EmployeeRankWeb.php?d=$directs&g=0&n=$currentMgr&r=$currentRole&s=$sortBy'>All</a></li>";
            }
            if($currentGrade==99){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy'>99*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy'>99</a></li>";
            }
            if($currentGrade==98){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy'>98*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy'>98</a></li>";
            }
            if($currentGrade==97){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy'>97*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy'>97</a></li>";
            }
            if($currentGrade==96){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy'>96*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy'>96</a></li>";
            }
            if($currentGrade==95){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy'>95*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy'>95</a></li>";
            }
            if($currentGrade==94){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy'>94*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy'>94</a></li>";
            }
            if($currentGrade==93){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy'>93*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy'>93</a></li>";
            }
            if($currentGrade==92){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy'>92*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy'>92</a></li>";
            }
            if($currentGrade==91){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy'>91*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy'>91</a></li>";
            }
            if($currentGrade==90){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy'>90*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy'>90</a></li>";
            }
            if($currentGrade==89){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy'>89*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy'>89</a></li>";
            }
            if($currentGrade==88){
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy'>88*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeRankWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy'>88</a></li>";
            }
            if($currentRole=='All'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy&o=$order'>All*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy&o=$order'>All</a></li>";
            }
            if($currentRole=='SD'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy&o=$order'>SD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy&o=$order'>SD</a></li>";
            }
            if($currentRole=='WD'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy&o=$order'>WD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy&o=$order'>WD</a></li>";
            }
            if($currentRole=='QA'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy&o=$order'>QA*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy&o=$order'>QA</a></li>";
            }
            if($currentRole=='UX'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy&o=$order'>UX*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy&o=$order'>UX</a></li>";
            }
            if($currentRole=='AU'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy&o=$order'>AU*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy&o=$order'>AU</a></li>";
            }
            if($currentRole=='PG'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy&o=$order'>PG*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy&o=$order'>PG</a></li>";
            }
            if($currentRole=='AD'){
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy&o=$order'>AD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy&o=$order'>AD</a></li>";
            }
            echo"<li id='gradefirst'><a href='/help/rank.html' target='_blank'>Help</a></li>";
        echo"</ul><br>";
    }
    
    function base64url_encode($str) {
        return strtr(base64_encode($str), '+/', '-_');
    }

    function base64url_decode($base64url) {
        return base64_decode(strtr($base64url, '-_', '+/'));
    }
    
    function displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order){
        $currentMgr = $this->base64url_encode($currentMgr);
        
        
        
        if(!class_exists('HeaderData')){
            include 'HeaderData.php';
        }
        $header = HeaderData::getInstance();
        $comp1 = $header->getComp1($_SESSION['role']);
        $comp2 = $header->getComp2($_SESSION['role']);
        $comp3 = $header->getComp3($_SESSION['role']);
        $comp4 = $header->getComp4($_SESSION['role']);
        $comp5 = $header->getComp5($_SESSION['role']);
        $comp6 = $header->getComp6($_SESSION['role']);
        $comp7 = $header->getComp7($_SESSION['role']);
        $comp8 = $header->getComp8($_SESSION['role']);
        $comp9 = $header->getComp9($_SESSION['role']);
        
        echo "<form>";
        echo"<table border='0' width=1375>";        
        echo"<thead>";
        echo"<tr>";
        echo"<th id='empid'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=empID&o=$order&h=1'>ID</a></th>";
        echo"<th id='lname' align=left><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=lastName&o=$order&h=1'>Last Name</a></th>";
        echo"<th id='fname' align=left><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=firstName&o=$order&h=1'>First Name</a></th>";
        echo"<th id='grade' align=center><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Grade&o=$order&h=1'>Grade</a></th>";
        echo"<th id='mgrid' align=left><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=mgrID&o=$order&h=1'>Manager</a></th>";
        echo"<th id='compA'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp1&o=$order&h=1'>$comp1</a></th>";
        echo"<th id='compB'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp2&o=$order&h=1'>$comp2</a></th>";
        echo"<th id='compC'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp3&o=$order&h=1'>$comp3</a></th>";
        echo"<th id='compD'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp4&o=$order&h=1'>$comp4</a></th>";
        echo"<th id='compE'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp5&o=$order&h=1'>$comp5</a></th>";
        echo"<th id='compF'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp6&o=$order&h=1'>$comp6</a></th>";
        echo"<th id='compG'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp7&o=$order&h=1'>$comp7</a></th>";
        echo"<th id='compH'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp8&o=$order&h=1'>$comp8</a></th>";
        echo"<th id='compI'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Romp9&o=$order&h=1'>$comp9</a></th>";
        echo"<th id='ranktotal'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=RankTotal&o=$order&h=1'>Rank</a></th>";
        echo"<th id='promote'><a href='/EmployeeRankWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Promote&o=$order&h=1'>Promote</a></th>";
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
            case "Romp1":
                if(isset($_SESSION['Romp1Order'])){
                    if($_SESSION['Romp1Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp1Order']=0;
                    } else {
                        $_SESSION['Romp1Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp1Order']=1;
                }
                break;
            case "Romp2":
                if(isset($_SESSION['Romp2Order'])){
                    if($_SESSION['Romp2Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp2Order']=0;
                    } else {
                        $_SESSION['Romp2Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp2Order']=1;
                }
                break;
            case "Romp3":
                if(isset($_SESSION['Romp3Order'])){
                    if($_SESSION['Romp3Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp3Order']=0;
                    } else {
                        $_SESSION['Romp3Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp3Order']=1;
                }
                break;
            case "Romp4":
                if(isset($_SESSION['Romp4Order'])){
                    if($_SESSION['Romp4Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp4Order']=0;
                    } else {
                        $_SESSION['Romp4Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp4Order']=1;
                }
                break;
            case "Romp5":
                if(isset($_SESSION['Romp5Order'])){
                    if($_SESSION['Romp5Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp5Order']=0;
                    } else {
                        $_SESSION['Romp5Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp5Order']=1;
                }
                break;
            case "Romp6":
                if(isset($_SESSION['Romp6Order'])){
                    if($_SESSION['Romp6Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp6Order']=0;
                    } else {
                        $_SESSION['Romp6Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp6Order']=1;
                }
                break;
            case "Romp7":
                if(isset($_SESSION['Romp7Order'])){
                    if($_SESSION['Romp7Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp7Order']=0;
                    } else {
                        $_SESSION['Romp7Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp7Order']=1;
                }
                break;
            case "Romp8":
                if(isset($_SESSION['Romp8Order'])){
                    if($_SESSION['Romp8Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp8Order']=0;
                    } else {
                        $_SESSION['Romp8Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp8Order']=1;
                }
                break;
            case "Romp9":
                if(isset($_SESSION['Romp9Order'])){
                    if($_SESSION['Romp9Order']){
                        $order = SORT_ASC;
                        $_SESSION['Romp9Order']=0;
                    } else {
                        $_SESSION['Romp9Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Romp9Order']=1;
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
        }
        return $order;
    } 
    
    function getAnEmployee($empID) {
        foreach ($this->employee as $value) {
            if($value['empID'] == $empID) {
                $oneEmp['empID'] = $value['empID'];
                $oneEmp['firstName'] = $value['firstName'];
                $oneEmp['lastName'] = $value['lastName'];
                $oneEmp['Role'] = $value['Role'];
                $oneEmp['Grade'] = $value['Grade'];
                $oneEmp['mgrID'] = $value['mgrID'];
                return $oneEmp;
            }
        }
    }
}

gc_disable();
?>
