
<!DOCTYPE html>
<?php
gc_enable();

class EmployeeData {
    private $employee = array(array());

    // Create an instance if necessary and return an instance.
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
            $SQL = "SELECT * FROM Employee ORDER BY lastName ASC";
            $result = $mysqli->query($SQL);
            if($result){
                $employeeCnt = 0;
                while ($db_field = mysqli_fetch_assoc($result)){
                    $this->employee[$employeeCnt]['empID'] = $db_field['empID'];
                    $this->employee[$employeeCnt]['firstName'] = $db_field['firstName'];
                    $this->employee[$employeeCnt]['lastName'] = $db_field['lastName'];
                    $this->employee[$employeeCnt]['Grade'] = $db_field['Grade'];
                    $this->employee[$employeeCnt]['MgrFlag'] = $db_field['MgrFlag'];
                    $this->employee[$employeeCnt]['mgrID'] = $db_field['mgrID'];
                    $this->employee[$employeeCnt]['MgrName'] = $db_field['MgrName'];
                    $this->employee[$employeeCnt]['Comp1'] = $db_field['Comp1'];
                    $this->employee[$employeeCnt]['Comp2'] = $db_field['Comp2'];
                    $this->employee[$employeeCnt]['Comp3'] = $db_field['Comp3'];
                    $this->employee[$employeeCnt]['Comp4'] = $db_field['Comp4'];
                    $this->employee[$employeeCnt]['Comp5'] = $db_field['Comp5'];
                    $this->employee[$employeeCnt]['Comp6'] = $db_field['Comp6'];
                    $this->employee[$employeeCnt]['Comp7'] = $db_field['Comp7'];
                    $this->employee[$employeeCnt]['Comp8'] = $db_field['Comp8'];
                    $this->employee[$employeeCnt]['Comp9'] = $db_field['Comp9'];
                    $this->employee[$employeeCnt]['DivGoal'] = $db_field['DivGoal'];
                    $this->employee[$employeeCnt]['TeamGoal'] = $db_field['TeamGoal'];
                    $this->employee[$employeeCnt]['PerGoal'] = $db_field['PerGoal'];
                    $this->employee[$employeeCnt]['CompScore'] = $db_field['CompScore'];
                    $this->employee[$employeeCnt]['ResultsScore'] = $db_field['ResultsScore'];
                    $this->employee[$employeeCnt]['Role'] = $db_field['Role'];
                    $this->employee[$employeeCnt]['CurSal'] = $db_field['CurSal'];

                    $employeeCnt++;
                }
                $result->close();
            } else {
                echo "EmployeeData: Employee DB read failure";
                $db->logDBError($_SESSION['name'], "EmployeeData: unable to $SQL");
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
                if($grade == 'All' || $singleEmp['Grade'] == $grade){
                    if($role == 'All' || $singleEmp['Role'] == $role){
                        //Is this person a direct report and do we want direct reports?
                        if($directs == "2" ||                                                                                  //M+ - With direct managers
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
    
    function exportEmployees($mgrID, $grade, $role, $directs, $level, $filename) {
        $level++;
        $singleEmp = array();
        
        if(!class_exists('fileIO')) {
            include 'fileIO.php';
        }
        $fileObj = new fileIO();
        
        foreach ($this->employee as $singleEmp){
            //Does this person report to the selected manager?
            if($singleEmp['mgrID'] == $mgrID){
                //Are we showing all grades or does the person belong to the selected grade?
                if($grade == 'All' ||$singleEmp['Grade'] == $grade){
                    if($role == 'All' ||$singleEmp['Role'] == $role){
                        //Is this person a direct report and do we want direct reports?
                        if($directs == "2" ||                                                                                  //M+ - With direct managers
                          ($directs == "1" && ($singleEmp["MgrFlag"] == 1)) ||                                                 //M  - Direct managers only
                          ($directs == "3" && $level == 1) ||                                                                  //D  - Show all direct reports
                          ($directs == "0" && (($singleEmp["MgrFlag"] == 0) || ($singleEmp["MgrFlag"] == 1 && $level > 1)))){  //M- - No direct managers    
                            $line = $singleEmp['empID'] . ', ' .
                                    $singleEmp['firstName'] . ', ' .
                                    $singleEmp['lastName'] . ', ' .
                                    $singleEmp['Grade'] . ', ' .
                                    $singleEmp['MgrFlag'] . ', ' .
                                    $singleEmp['mgrID'] . ', ' .
                                    $singleEmp['MgrName'] . ', ' .
                                    $singleEmp['CurSal'] . "\r\n";
                            $fileObj->export($filename, $line);
                        }
                    }
                }
                if($directs != 1) {
                    $this->exportEmployees($singleEmp['empID'], $grade, $role, $directs, $level, $filename);
                }
            }
        }
        unset($fileObj);
    }
    
    function displayEmployees($empResults, $submitted){
        if(array_count_values($empResults[0])) {
            $count = 0;
            echo"<tbody>";
            foreach($empResults as $singleEmp){
                echo"<tr>";
                echo"<th headers='empid$count' align='center'>$singleEmp[empID]</td>";
                echo"<td headers='lname$count' align='left'>$singleEmp[lastName]</td>";
                echo"<td headers='fname$count' align='left' width='110'>$singleEmp[firstName]</td>";
                echo"<td headers='grade$count' align='center'>$singleEmp[Grade]</td>";
                echo"<td headers='mgrid$count' align='left' width='110'>$singleEmp[MgrName]</td>";
                if($submitted) {
                    echo"<td headers='compA$count' align='center' class='Comp1'>$singleEmp[Comp1]</td>";
                    echo"<td headers='compB$count' align='center' class='Comp2'>$singleEmp[Comp2]</td>";
                    echo"<td headers='compC$count' align='center' class='Comp3'>$singleEmp[Comp3]</td>";
                    echo"<td headers='compD$count' align='center' class='Comp4'>$singleEmp[Comp4]</td>";
                    echo"<td headers='compE$count' align='center' class='Comp5'>$singleEmp[Comp5]</td>";
                    echo"<td headers='compF$count' align='center' class='Comp6'>$singleEmp[Comp6]</td>";
                    echo"<td headers='compG$count' align='center' class='Comp7'>$singleEmp[Comp7]</td>";
                    echo"<td headers='compH$count' align='center' class='Comp8'>$singleEmp[Comp8]</td>";
                    echo"<td headers='compI$count' align='center' class='Comp9'>$singleEmp[Comp9]</td>";
                }else {
                    echo"<td headers='compA$count' align='center' class='Comp1'><input type='text' size='1' id='Comp1' name='$singleEmp[empID]' value='$singleEmp[Comp1]'/></td>";
                    echo"<td headers='compB$count' align='center' class='Comp2'><input type='text' size='1' id='Comp2' name='$singleEmp[empID]' value='$singleEmp[Comp2]'/></td>";
                    echo"<td headers='compC$count' align='center' class='Comp3'><input type='text' size='1' id='Comp3' name='$singleEmp[empID]' value='$singleEmp[Comp3]'/></td>";
                    echo"<td headers='compD$count' align='center' class='Comp4'><input type='text' size='1' id='Comp4' name='$singleEmp[empID]' value='$singleEmp[Comp4]'/></td>";
                    echo"<td headers='compE$count' align='center' class='Comp5'><input type='text' size='1' id='Comp5' name='$singleEmp[empID]' value='$singleEmp[Comp5]'/></td>";
                    echo"<td headers='compF$count' align='center' class='Comp6'><input type='text' size='1' id='Comp6' name='$singleEmp[empID]' value='$singleEmp[Comp6]'/></td>";
                    echo"<td headers='compG$count' align='center' class='Comp7'><input type='text' size='1' id='Comp7' name='$singleEmp[empID]' value='$singleEmp[Comp7]'/></td>";
                    echo"<td headers='compH$count' align='center' class='Comp8'><input type='text' size='1' id='Comp8' name='$singleEmp[empID]' value='$singleEmp[Comp8]'/></td>";
                    echo"<td headers='compI$count' align='center' class='Comp9'><input type='text' size='1' id='Comp9' name='$singleEmp[empID]' value='$singleEmp[Comp9]'/></td>";
                }
                
                printf("<td headers='compScore$count' align='right' class='csTotal'>%1.2f</td>", $singleEmp['CompScore']);
                if($submitted) {
                    echo"<td headers='divgoal$count' align='center' class='DivGoal'>$singleEmp[DivGoal]</td>";
                    echo"<td headers='teamgoal$count' align='center' class='TeamGoal'>$singleEmp[TeamGoal]</td>";
                    echo"<td headers='pergoal$count' align='center' class='PerGoal'>$singleEmp[PerGoal]</td>";
                }else {
                    echo"<td headers='divgoal$count' align='center' class='DivGoal'><input type='text' size='1' id='DivGoal' name='$singleEmp[empID]' value='$singleEmp[DivGoal]'/></td>";
                    echo"<td headers='teamgoal$count' align='center' class='TeamGoal'><input type='text' size='1' id='TeamGoal' name='$singleEmp[empID]' value='$singleEmp[TeamGoal]'/></td>";
                    echo"<td headers='pergoal$count' align='center' class='PerGoal'><input type='text' size='1' id='PerGoal' name='$singleEmp[empID]' value='$singleEmp[PerGoal]'/></td>";
                }
                printf("<td headers='resscore$count' align='right' class='rsTotal'>%1.2f</td>", $singleEmp['ResultsScore']);
                echo"</tr>";
                $count++;
            }
            echo"</tbody>";
            echo "</table>";
            echo "</form>";
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
            echo"<li id='manager'><a href='/ManagerSelectionWeb.php?target=eval'>$mgrName - $numEmps</a></li>";
            if($directs == 3){  //D - Show my directs
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>D*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=3&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>D</a></li>";
            }
            if($directs == 1){  //M - Only show my managers
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=1&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>M</a></li>";
            }
            if($directs == 0){  //-M - Don't include my managers
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>-M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=0&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>-M</a></li>";
            }
            if($directs == 2){  //+M - Include my managers
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>+M*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=2&g=$currentGrade&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>+M</a></li>";
            }
            if($currentGrade=='All'){
                echo"<li id='gradefirst'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=All&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>All*</a></li>";
            }else{
                echo"<li id='gradefirst'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=All&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>All</a></li>";
            }
            if($currentGrade==99){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>99*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=99&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>99</a></li>";
            }
            if($currentGrade==98){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>98*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=98&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>98</a></li>";
            }
            if($currentGrade==97){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>97*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=97&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>97</a></li>";
            }
            if($currentGrade==96){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>96*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=96&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>96</a></li>";
            }
            if($currentGrade==95){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>95*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=95&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>95</a></li>";
            }
            if($currentGrade==94){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>94*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=94&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>94</a></li>";
            }
            if($currentGrade==93){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>93*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=93&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>93</a></li>";
            }
            if($currentGrade==92){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>92*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=92&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>92</a></li>";
            }
            if($currentGrade==91){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>91*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=91&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>91</a></li>";
            }
            if($currentGrade==90){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>90*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=90&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>90</a></li>";
            }
            if($currentGrade==89){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>89*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=89&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>89</a></li>";
            }
            if($currentGrade==88){
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>88*</a></li>";
            }else{
                echo"<li id='gradebk'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=88&n=$currentMgr&r=$currentRole&s=$sortBy&o=$order'>88</a></li>";
            }
            if($currentRole=='All'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy&o=$order'>All*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=All&s=$sortBy&o=$order'>All</a></li>";
            }
            if($currentRole=='SD'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy&o=$order'>SD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=SD&s=$sortBy&o=$order'>SD</a></li>";
            }
            if($currentRole=='WD'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy&o=$order'>WD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=WD&s=$sortBy&o=$order'>WD</a></li>";
            }
            if($currentRole=='UX'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy&o=$order'>UX*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=UX&s=$sortBy&o=$order'>UX</a></li>";
            }
            if($currentRole=='QA'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy&o=$order'>QA*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=QA&s=$sortBy&o=$order'>QA</a></li>";
            }
            if($currentRole=='AU'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy&o=$order'>AU*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AU&s=$sortBy&o=$order'>AU</a></li>";
            }
            if($currentRole=='PG'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy&o=$order'>PG*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=PG&s=$sortBy&o=$order'>PG</a></li>";
            }
            if($currentRole=='AD'){
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy&o=$order'>AD*</a></li>";
            }else{
                echo"<li><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&n=$currentMgr&r=AD&s=$sortBy&o=$order'>AD</a></li>";
            }
            //echo"<li id='gradefirst'><a href='/ExportWeb.php#Evaluation' target='_blank'>Export</a></li>";
            echo"<li id='gradefirst'><a href='/help/evaluate.html' target='_blank'>Help</a></li>";
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
        echo"<table border='0'>";        
        echo"<thead>";
        echo"<tr>";
        echo"<th id='empid'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=empID&o=$order&h=1'>ID</a></th>";
        echo"<th id='lname' align=left><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=lastName&o=$order&h=1'>Last Name</a></th>";
        echo"<th id='fname' align=left><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=firstName&o=$order&h=1'>First Name</a></th>";
        echo"<th id='grade' align=center><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Grade&o=$order&h=1'>Grade</a></th>";
        echo"<th id='mgrid' align=left><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=MgrName&o=$order&h=1'>Manager</a></th>";
        echo"<th id='compA'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp1&o=$order&h=1'>$comp1</a></th>";
        echo"<th id='compB'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp2&o=$order&h=1'>$comp2</a></th>";
        echo"<th id='compC'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp3&o=$order&h=1'>$comp3</a></th>";
        echo"<th id='compD'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp4&o=$order&h=1'>$comp4</a></th>";
        echo"<th id='compE'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp5&o=$order&h=1'>$comp5</a></th>";
        echo"<th id='compF'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp6&o=$order&h=1'>$comp6</a></th>";
        echo"<th id='compG'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp7&o=$order&h=1'>$comp7</a></th>";
        echo"<th id='compH'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp8&o=$order&h=1'>$comp8</a></th>";
        echo"<th id='compI'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=Comp9&o=$order&h=1'>$comp9</a></th>";
        echo"<th id='compscore'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=CompScore&o=$order&h=1'>Comp Score</a></th>";
        echo"<th id='divgoal'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=DivGoal&o=$order&h=1'>Division Goals</a></th>";
        echo"<th id='teamgoal'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=TeamGoal&o=$order&h=1'>Team Goals</a></th>";
        echo"<th id='pergoal'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=PerGoal&o=$order&h=1'>Personal Goals</a></th>";
        echo"<th id='resscore'><a href='/EmployeeEvaluationWeb.php?d=$directs&g=$currentGrade&r=$currentRole&n=$currentMgr&s=ResultsScore&o=$order&h=1'>Result Score</a></th>";
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
            case "MgrName":
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
            case "Comp1":
                if(isset($_SESSION['Comp1Order'])){
                    if($_SESSION['Comp1Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp1Order']=0;
                    } else {
                        $_SESSION['Comp1Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp1Order']=1;
                }
                break;
            case "Comp2":
                if(isset($_SESSION['Comp2Order'])){
                    if($_SESSION['Comp2Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp2Order']=0;
                    } else {
                        $_SESSION['Comp2Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp2Order']=1;
                }
                break;
            case "Comp3":
                if(isset($_SESSION['Comp3Order'])){
                    if($_SESSION['Comp3Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp3Order']=0;
                    } else {
                        $_SESSION['Comp3Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp3Order']=1;
                }
                break;
            case "Comp4":
                if(isset($_SESSION['Comp4Order'])){
                    if($_SESSION['Comp4Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp4Order']=0;
                    } else {
                        $_SESSION['Comp4Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp4Order']=1;
                }
                break;
            case "Comp5":
                if(isset($_SESSION['Comp5Order'])){
                    if($_SESSION['Comp5Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp5Order']=0;
                    } else {
                        $_SESSION['Comp5Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp5Order']=1;
                }
                break;
            case "Comp6":
                if(isset($_SESSION['Comp6Order'])){
                    if($_SESSION['Comp6Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp6Order']=0;
                    } else {
                        $_SESSION['Comp6Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp6Order']=1;
                }
                break;
            case "Comp7":
                if(isset($_SESSION['Comp7Order'])){
                    if($_SESSION['Comp7Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp7Order']=0;
                    } else {
                        $_SESSION['Comp7Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp7Order']=1;
                }
                break;
            case "Comp8":
                if(isset($_SESSION['Comp8Order'])){
                    if($_SESSION['Comp8Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp8Order']=0;
                    } else {
                        $_SESSION['Comp8Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp8Order']=1;
                }
                break;
            case "Comp9":
                if(isset($_SESSION['Comp9Order'])){
                    if($_SESSION['Comp9Order']){
                        $order = SORT_ASC;
                        $_SESSION['Comp9Order']=0;
                    } else {
                        $_SESSION['Comp9Order']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['Comp9Order']=1;
                }
                break;
            case "DivGoal":
                if(isset($_SESSION['divGoalOrder'])){
                    if($_SESSION['divGoalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['divGoalOrder']=0;
                    } else {
                        $_SESSION['divGoalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['divGoalOrder']=1;
                }
                break;
            case "TeamGoal":
                if(isset($_SESSION['teamGoalOrder'])){
                    if($_SESSION['teamGoalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['teamGoalOrder']=0;
                    } else {
                        $_SESSION['teamGoalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['teamGoalOrder']=1;
                }
                break;
            case "PerGoal":
                if(isset($_SESSION['perGoalOrder'])){
                    if($_SESSION['perGoalOrder']){
                        $order = SORT_ASC;
                        $_SESSION['perGoalOrder']=0;
                    } else {
                        $_SESSION['perGoalOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['perGoalOrder']=1;
                }
                break;
            case "CompScore":
                if(isset($_SESSION['compScoreOrder'])){
                    if($_SESSION['compScoreOrder']){
                        $order = SORT_ASC;
                        $_SESSION['compScoreOrder']=0;
                    } else {
                        $_SESSION['compScoreOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['compScoreOrder']=1;
                }
                break;
            case "ResultsScore":
                if(isset($_SESSION['resultsScoreOrder'])){
                    if($_SESSION['resultsScoreOrder']){
                        $order = SORT_ASC;
                        $_SESSION['resultsScoreOrder']=0;
                    } else {
                        $_SESSION['resultsScoreOrder']=1;
                        $order = SORT_DESC;
                    }
                } else {
                    $order = SORT_DESC;
                    $_SESSION['resultsScoreOrder']=1;
                }
                break;
        }
        return $order;
    }
    
    function updateAllData() {
        if(!class_exists('Calculator')) {
            include 'Calculator.php';
        }
            
        foreach ($this->employee as $singleEmp){
            $calc = new Calculator($singleEmp['empID']);
            $calc->updateATITalent();
            unset($calc);
        }
    }
    
    function getEmployeeArray() {
        return $this->employee;
    }
    
    function getComps($empID) {
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
            $CPSQL = "SELECT Comp1, Comp2, Comp3, Comp4, Comp5, Comp6, Comp7, Comp8, Comp9 FROM Employee WHERE empID=$empID";
            $result = $mysqli->query($CPSQL);
            if($result){
                $data = $result->fetch_assoc();
                if($data){
                    $comp['Comp1'] = $data['Comp1'];
                    $comp['Comp2'] = $data['Comp2'];
                    $comp['Comp3'] = $data['Comp3'];
                    $comp['Comp4'] = $data['Comp4'];
                    $comp['Comp5'] = $data['Comp5'];
                    $comp['Comp6'] = $data['Comp6'];
                    $comp['Comp7'] = $data['Comp7'];
                    $comp['Comp8'] = $data['Comp8'];
                    $comp['Comp9'] = $data['Comp9'];
                }
                $result->close();
                return $comp;
            } else {
                $db->logDBError($_SESSION['name'], "EmployeeData: unable to $CPSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function getRanks($empID) {
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();
        
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if (mysqli_connect_errno()){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            $RKSQL = "SELECT Romp1, Romp2, Romp3, Romp4, Romp5, Romp6, Romp7, Romp8, Romp9 FROM Employee WHERE empID=$empID";
            $result = $mysqli->query($RKSQL);
            if($result){
                $data = $result->fetch_assoc();
                if($data){
                    $rank['Romp1'] = $data['Romp1'];
                    $rank['Romp2'] = $data['Romp2'];
                    $rank['Romp3'] = $data['Romp3'];
                    $rank['Romp4'] = $data['Romp4'];
                    $rank['Romp5'] = $data['Romp5'];
                    $rank['Romp6'] = $data['Romp6'];
                    $rank['Romp7'] = $data['Romp7'];
                    $rank['Romp8'] = $data['Romp8'];
                    $rank['Romp9'] = $data['Romp9'];
                }
                $result->close();
                return $rank;
            }else {
                $db->logDBError($_SESSION['name'], "EmployeeData: unable to $RKSQL");
            } 
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function getResults($empID) {
        if(!class_exists('DBAccess')) {
            include 'DBAccess.php';
        }
        $db = new DBAccess();
        
        $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
        // Check connection
        if (mysqli_connect_errno()){
            echo "Failed to connect to server: " . mysqli_connect_error();
            exit();
        } else {
            $mysqli->select_db($db->getDB());
            $RSSQL = "SELECT DivGoal, TeamGoal, PerGoal FROM Employee WHERE empID=$empID";
            $result = $mysqli->query($RSSQL);
            if($result){
                $data = $result->fetch_assoc();
                if($data){
                    $res['DivGoal'] = $data['DivGoal'];
                    $res['TeamGoal'] = $data['TeamGoal'];
                    $res['PerGoal'] = $data['PerGoal'];
                }
                $result->close();
                return $res;
            }else {
                $db->logDBError($_SESSION['name'], "EmployeeData: unable to $RSSQL");
            }
            mysqli_close($mysqli);
        }
        unset($db);
    }
    
    function getAnEmployee($empID) {
        foreach ($this->employee as $value) {
            if($value['empID'] == $empID) {
                $oneEmp['empID'] = $value['empID'];
                $oneEmp['firstName'] = $value['firstName'];
                $oneEmp['lastName'] = $value['lastName'];
                $oneEmp['Role'] = $value['Role'];
                $oneEmp['Grade'] = $value['Grade'];
                $oneEmp['CurSal'] = $value['CurSal'];
                $oneEmp['mgrID'] = $value['mgrID'];
                return $oneEmp;
            }
        }
    }
}

gc_disable();
?>
