<!--
File:   ReportPromotionsWeb
Author: Steven L. Weitzeil
Date:   9 July 2013
Desc:   Displays the Promotions Report
Review: 
-->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Promotions</title>
        <link rel="stylesheet" href="css/ATIMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ATITable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Reports: Promotions</h1>
        <?php
            gc_enable();
            
            //Verify that the user is signed in
            //if(isset($_SESSION["empID"]) && $_SESSION["empID"] == "000000"){
            if(isset($_SESSION["empID"])){
                $currentGrade = "All";    //All grades
                $currentRole = "All";
                $directs = 2;         //With directs
                if($_SESSION['empID'] == "000000") {
                    $currentMgr = "380034";
                } else {
                    $currentMgr = $_SESSION["empID"];
                }
                $mgrName = $_SESSION['name'];
                $sortBy = 'ATIFinal';
                $order = SORT_ASC;
                
                //Gather and Sort the employee ATI data
                if(!class_exists('ATIData')) {
                    include 'ATIData.php';
                }
                $ati = new ATIData();
                
                $empDisplay = array(array());
                $initialResults = $ati->getEmployeesFiltered($currentMgr, $currentGrade, $currentRole, $directs, 0, $empDisplay, 'Promote', 1);
                $empResults = $ati->sortEmployees($initialResults, $sortBy, $order);
                
                //Display the ATI menus and table
                $numEmps = count($empResults);
                $check = count($empResults[0]);
                if(!$check){$numEmps = 0;}
                //$ati->displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy); 
                if(!class_exists('Reports')){
                    include 'Reports.php';
                }
                $rep = new Reports();
                $rep->displayReportsMenu("ReportsWeb");

                unset($rep);
                $ati->displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order, TRUE);
                $ati->displayEmployees($empResults, $numEmps * .1, TRUE);
                
                unset($ati);
            } else{
                //If the current user is not Admin, go to MustSignIn
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
