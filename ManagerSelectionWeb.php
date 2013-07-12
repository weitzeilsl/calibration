<!--
Steven Weitzeil
FamilySearch
6 April 2013
-->
<?php 
    session_start(); 
    
    gc_enable();
    //Check for login - if not, redirect
    if (!isset($_SESSION["name"])){
       if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
       } else {
            $uri = 'http://';
       }
       $uri .= $_SERVER['HTTP_HOST'];
       header('Location: '.$uri.'/MustSignInWeb.php');
       exit; 
       
       gc_disable();
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Manager Selection</title>
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body> 
       <h1>Manager Selection</h1>
       <ul id="menu">
           <li><a href="/index.php">Home</a></li>
       </ul>
       <ul id="navigation">
       <?php
            gc_enabled();
            include 'ManagerData.php'; 
            $managerData = new ManagerData();          
            $empID = $managerData->base64url_encode($_SESSION["empID"]);
            
            $currentUser = $_SESSION['name'];
            switch($_REQUEST["target"]){
                case "eval":
                    echo"<li><a href='/EmployeeEvaluationWeb.php?n=$empID&g=All&r=All&d=0&s=lastName'>$currentUser</a></li>";
                    $dest = "/EmployeeEvaluationWeb.php?n=$empID&g=All&r=All&d=0&s=lastName";
                    break;
                case "rank":
                    echo"<li><a href='/EmployeeRankWeb.php?n=$empID&g=All&r=All&d=0&s=lastName'>$currentUser</a></li>";
                    $dest = "/EmployeeRankWeb.php?n=$empID&g=All&r=All&d=0&s=lastName";
                    break;
                case "ati":
                    echo"<li><a href='/EmployeeATIWeb.php?n=$empID&g=All&r=All&d=0&s=lastName'>$currentUser</a></li>";
                    $dest = "/EmployeeATIWeb.php?n=$empID&g=All&r=All&d=0&s=lastName";
                    break;
                case "reports":
                    echo"<li><a href='/ReportsWeb.php'>$currentUser</a></li>";
                    $dest = "/ReportsWeb.php";
                    break;
                case "admin":
                    echo"<li><a href='/AdminWeb.php'>$currentUser</a></li>";
                    $dest = "/AdminWeb.php";
                    break;
            }
            $count = $managerData->displayManagers($_SESSION["empID"], 0, $_REQUEST["target"]);
            unset($managerData);
            
            if(!$count || ($_REQUEST['target'] == "reports" || $_REQUEST['target'] == "admin")) {
                //If only one manager, go straight to destination
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];

                header('Location: '.$uri.$dest);
                exit;
            }
            
            
            gc_disable();
        ?>
       </ul>
        
    </body>
</html>
