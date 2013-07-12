<!--
File:   GetPasswordWeb
Author: Steven Weitzeil
Date:   11 April 2013
Desc:   Display the page to request a password be emailed to the current user.
Review: 
-->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
        <title>Get Password</title>
    </head>
    <body>
        <h1>Get Password</h1>
        <ul id='menu'>
            <li><a href='/index.php'>Home</a></li>
        </ul>
        
        <?php
            echo "<form method='post' action='/MailSentWeb.php'>";
            echo "If you click OK, user's password will be emailed to: email <br><br>";
            echo "<input type='submit' name='button' value='OK'>";
            echo "<input type='submit' name='button' value='Cancel'>";
            echo "</form>";
        ?>
    </body>
</html>
