<!--
File:   AddEmployeeWeb
Author: Steven L. Weitzeil
Date:   14 June 2013
Desc:   This page prompts the user for the information needed to add an
        employee to the Employee table in the Calibration database.
Review: 
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Add</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Change Password</h1>
        
        <?php
            session_start(); 
            gc_enable();
            
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('PasswordWeb');
            
            //If not signed in, return to MustSignIn
            if (!isset($_SESSION["name"])){
                session_unset();
                
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
            
                header('Location: '.$uri.'/MustSignInWeb.php');
            }

            echo "<form method='post' action='/ChangePassword.php'>";
            
            //Old Password
            echo "Current Password: <input type='password' size='30' id='oldPassword' name='oldPassword'/><br><br>";
            echo "New Password: <input type='password' size='30' id='newPassword' name='newPassword'/><br>";
            echo "Retype Password: <input type='password' size='30' id='valPassword' name='valPassword'/><br>";
           
            echo "<input type='submit' name='button' value='Save'>";
            echo "</form>";
            
            unset($admin);
            gc_disable();
        ?>
    </body>
</html>
