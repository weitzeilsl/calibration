<!--
File:   AdminWeb.php
Author: Steven L. Weitzeil
Date:   15 June 2013
Desc:   Display the Administration Main Menu
Code Review:
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Administration</title>
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration</h1>
        <?php
        session_start();
        
        if(!class_exists('Admin')) {
            include 'Admin.php';
        }
        $admin = new Admin();
        $admin->displayAdminMenu('AdminWeb');
        unset($admin);
        ?>
    </body>
</html>
