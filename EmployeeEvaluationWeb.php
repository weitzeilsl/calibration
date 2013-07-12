<!--
 * File:   EmployeeEvaluationWeb
 * Author: Steven Weitzeil
 * Date:   6 April 2013
 * Desc:   Display and manage the Employee Evaluation page
 -->
 
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
            $(document).ready(function(){
                $("input").keyup(function(e){
                    //alert(e.keyCode);
                    if(e.keyCode === 9  ||   //Don't calc if: Tab
                       e.keyCode === 37 ||   //Left
                       e.keyCode === 38 ||   //Up
                       e.keyCode === 39 ||   //Right
                       e.keyCode === 40)     //Down
                        return;
                    if(this.value.length > 1) {
                        this.value = this.value.substr(1,1);
                    }
                    if(!this.value.length || isNaN(this.value)){
                        this.value = 3;
                    }else if(this.value > 5) {
                        this.value = 5;
                    } else if (this.value < 1) {
                        this.value = 1;
                    }
                    this.select();
                    var thisRow = $(this).closest('tr');
                    if(this.id.substr(0,4) === "Comp") {
                        var value1, value2, value3, value4, value5, value6, value7, value8, value9;

                        value1 = $(thisRow).find("td.Comp1 input:text").val();
                        value2 = $(thisRow).find("td.Comp2 input:text").val();
                        value3 = $(thisRow).find("td.Comp3 input:text").val();
                        value4 = $(thisRow).find("td.Comp4 input:text").val();
                        value5 = $(thisRow).find("td.Comp5 input:text").val();
                        value6 = $(thisRow).find("td.Comp6 input:text").val();
                        value7 = $(thisRow).find("td.Comp7 input:text").val();
                        value8 = $(thisRow).find("td.Comp8 input:text").val();
                        value9 = $(thisRow).find("td.Comp9 input:text").val();

                        $.post(
                        'UpdateCSTotals.php',
                            { empID: this.name, change: this.id, value: this.value, 
                              value1: value1, value2: value2, value3: value3, 
                              value4: value4, value5: value5, value6: value6, 
                              value7: value7, value8: value8, value9: value9 
                            }, function( total ){
                                $(thisRow).find("td.csTotal").html(total);
                            }
                        );
                    } else {
                        var divGoal, teamGoal, perGoal;

                        divGoal = $(thisRow).find("td.DivGoal input:text").val();
                        teamGoal = $(thisRow).find("td.TeamGoal input:text").val();
                        perGoal = $(thisRow).find("td.PerGoal input:text").val();

                        $.post(
                            'UpdateGoalTotals.php',
                            { empID: this.name, change: this.id, value: this.value, 
                              DivGoal: divGoal, TeamGoal: teamGoal, PerGoal: perGoal 
                            }, function( total ){
                                    $(thisRow).find("td.rsTotal").html(total);
                               }
                        );
                    }
                });            
            });
        </script>
        
        <title>Employee Evaluation</title>
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <?php
            gc_enable();
            
            //Validate that the user is logged in
            if (isset($_SESSION["name"])){
                echo "<h1>Employee Evaluation</h1>";
                
                //Gather the expected parameters
                if(isset($_REQUEST['n'])){$encodedMgr = $_REQUEST['n'];}
                if(isset($_REQUEST['d'])){
                    $directs = $_REQUEST['d'];
                } else {
                    $directs = 2;
                }
                if(isset($_REQUEST['g'])){
                    $currentGrade = $_REQUEST['g'];
                } else {
                    $currentGrade = "All";
                }
                if(isset($_REQUEST['r'])){
                    $currentRole = $_REQUEST['r'];
                } else {
                    $currentRole = "All";
                }
                if(isset($_REQUEST['s'])){$sortBy = $_REQUEST['s'];} else {$sortBy = 'lastName';}  
                
                //Get the current manager information
                if(!class_exists('ManagerData')) {
                    include 'ManagerData.php';
                }
                $managers = new ManagerData();
                $currentMgr = $managers->base64url_decode($encodedMgr);
                $_SESSION['selectedManager'] = $currentMgr;
                $mgrName = $managers->getManagerName($currentMgr);
                $submitted = $managers->isManagerSubmitted($_SESSION['empID'], "Eval");
                
                //Gather the employee data for the table and sort it
                if(!class_exists('EmployeeData')){
                    include 'EmployeeData.php';
                }
                $empDisplay = array(array());
                $employees = new EmployeeData();
                
                $empResults = $employees->getEmployees($currentMgr, $currentGrade, $currentRole, $directs, 0, $empDisplay);
                              
                if(isset($_REQUEST['h'])){$order = $employees->getSortOrder($sortBy);} else {$order = SORT_ASC;}
                
                $_SESSION['lastSort'] = $sortBy;
                $_SESSION['lastGrade'] = $currentGrade;
                $_SESSION['lastRole'] = $currentRole;
                $_SESSION['lastDirects'] = $directs;
                $_SESSION['lastOrder'] = $order;
                                
                $sortedResults = $employees->sortEmployees($empResults, $sortBy, $order);
                $numEmps = count($sortedResults);
                $check = count($sortedResults[0]);
                if(!$check){$numEmps = 0;}
                
                //Display the menus and table
                $employees->displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy, $order); 
                
                //Get the Role of the selected manager so we display the proper header
                if($_SESSION['selectedManager'] == "000000"){
                    $_SESSION['role'] = "SD";
                } else {
                    $oneEmp = $employees->getAnEmployee($_SESSION['selectedManager']);
                    $_SESSION['role'] = $oneEmp['Role'];
                }
                $employees->displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order);
                
                $employees->displayEmployees($sortedResults, $submitted);
                
                if(!$submitted) {
                    //Display the form
                    echo "<form method='post' action='/UpdateSubmit.php?type=Eval'>";
                    echo "<input type='submit' name='button' value='Submit' onclick=\"return confirm('Are you sure you want to submit? Only your manager will be able to make future changes.');\">";
                    echo "</form>";
                }
                
                unset($employees);
                unset($managers);
            } else {
                //Not signed in - go to MustSignInWeb
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
                header('Location: '.$uri.'/MustSignInWeb.php/');
                exit;
            }
            
            gc_disable();
        ?>
    </body>
</html>
