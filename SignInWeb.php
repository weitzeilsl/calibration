<!--
use ManagerData;

File:     SignInWeb
Author:   Steven Weitzeil
Date:     9 April 2013
Reviewed: 26 June 2013
-->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen"> 
        <title>Sign In</title>
    </head>
    <body>
        
        <h1>Sign In</h1>
        <ul id='menu'>
            <li><a href='/index.php'>Home</a></li>
        </ul>
        <?php
            gc_enable();
            //If already signed in, signout and return to Index
            if (isset($_SESSION["name"])){
                if(!class_exists('LogManager')) {
                   include 'LogManager.php';
                }
                $log = new LogManager();
                $log->append($_SESSION['name'] . ": Logged out");
                unset($log);
                
                session_unset();
                
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                   $uri = 'https://';
                } else {
                   $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
            
                header('Location: '.$uri.'/Index.php');
            }

            if(!class_exists('ManagerData')){
                include 'ManagerData.php';
            }
            $managerData = new ManagerData();
            $managerNames = $managerData->getManagerNamesAndEmpId();
            
            echo "<form method='post' action='/ValidateSignInWeb.php'>";
            echo "Select your name: ";
            echo "<select name='userName'>";
            foreach ($managerNames as $singleManager) {
                echo "<option>$singleManager</option>";
            }
            echo "</select><br><br>";
            echo "Password: <input name='password' type='password'>";
            if(isset($_REQUEST['r']) && $_REQUEST['r']==TRUE) {
                echo"<br><br>";
            } else {
                echo" <font color='red'>Incorrect password.</font><br><br>";
            }
            echo "<input type='submit' name='button' value='OK'>";
            echo "<input type='submit' name='button' value='Cancel'>";
            echo "</form>";
            
            unset($managerData);
            gc_disable();
        ?>
    </body>
</html>
