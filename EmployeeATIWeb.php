<!--
File:   EmployeeATIWeb
Author: Steven L. Weitzeil
Date:   4 June 2013
Desc:   Displays the ATI Table
Review: 
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
                    if(this.id === "gmAdjust") {
                        if(e.keyCode < 48 || e.keyCode > 57)
                            return;
                        
                        var thisRow = $(this).closest('tr');

                        $.post(
                        'UpdateATITotals.php',
                            { empID: this.name, change: this.id, value: this.value
                            }, function( data ){
                                    //Parse the data variable
                                    var output = data.split(',');
                                    $(thisRow).find("td.AdjustScore").html(output[0]);
                                    $(thisRow).find("td.ATIFinal").html(output[1]);
                                    $(thisRow).find("td.ActualInc").html(output[2]);
                                    $(thisRow).find("td.NewSal").html(output[3]);
                                    $(thisRow).find("td.IncrPercent").html(output[4]);
                               }
                        );
                    } else if(this.id == "Justification") {
                        $.post(
                        'UpdateJustification.php',
                            { empID: this.name, value: this.value 
                            }, function(){
                            }
                        );
                    }
                });            
            });  

            $(document).ready(function(){
                $('td input:checkbox').mousedown(function() {
                    if (!$(this).is(':checked')) {
                        $.post(
                        'UpdatePromotion.php',
                            { empID: this.name, value: 1 
                            }, function(){
                                $(this).prop('checked', true); // will check the checkbox
                            }
                        );
                    }  else {
                        $.post(
                        'UpdatePromotion.php',
                            { empID: this.name, value: 0 
                            }, function(){
                                $(this).prop('checked', false); // will uncheck the checkbox
                            }
                        );
                    }
                });
            });
        </script>
        
        <title>Employee AMI/ATI</title>
        <link rel="stylesheet" href="css/ATIMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/ATITable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Employee AMI/ATI</h1>
        <?php
            gc_enable();
            
            //Verify that the user is signed in
            if(isset($_SESSION["name"])){            
                //Gather the necessary parameter values
                if(isset($_REQUEST['n'])){$encodedMgr = $_REQUEST['n'];}
                if(isset($_REQUEST['d'])){$directs = $_REQUEST['d'];}
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
                
                //Gather the manage empID (base64encoded) and name
                if(!class_exists('ManagerData')){
                    include 'ManagerData.php';
                }
                $managers = new ManagerData();
                
                $currentMgr = $managers->base64url_decode($encodedMgr);
                $mgrName = $managers->getManagerName($currentMgr);
                
                //Gather and Sort the employee ATI data
                include 'ATIData.php';
                $empDisplay = array(array());
                $employees = new ATIData();
                $empResults = $employees->getEmployees($currentMgr, $currentGrade, $currentRole, $directs, 0, $empDisplay);
                              
                if(isset($_REQUEST['h'])){$order = $employees->getSortOrder($sortBy);} else {$order = SORT_ASC;}
                
                $_SESSION['lastSort'] = $sortBy;
                $_SESSION['lastGrade'] = $currentGrade;
                $_SESSION['lastDirects'] = $directs;
                $_SESSION['lastOrder'] = $order;
                $sortResults = $employees->sortEmployees($empResults, $sortBy, $order);
                
                //Display the ATI menus and table
                $numEmps = count($sortResults);
                $check = count($sortResults[0]);
                if(!$check){$numEmps = 0;}
                $employees->displayMenu($mgrName, $numEmps, $directs, $currentGrade, $currentMgr, $currentRole, $sortBy);             
                $employees->displayTableHeader($directs, $currentGrade, $currentRole, $currentMgr, $order, FALSE);
                $employees->displayEmployees($sortResults, 10000, FALSE);
                
                unset($managers);
                unset($employees);
            } else{
                //If the current user is not signed in, go to MustSignIn
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
