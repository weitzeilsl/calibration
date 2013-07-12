<!--
Index.html
Steven Weitzeil
4 April 2013
Main web page for the online Calibration tool.
Reviewed: 
-->

<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <title>FS Mgmt Tool</title>
        <link rel="stylesheet" href="css/evalMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>FamilySearch Management Tools</h1>
        
            <?php
            //phpinfo();
            gc_enable();
            
            echo "<ul id='menu'>";
            
            if (isset($_SESSION['name'])) {
                $curUser = $_SESSION['name'];
                echo "<li><a href='/SignInWeb.php'>Sign Out: $curUser</a></li>";
                echo "<li><a href='/help/index.html' target='_blank'>Help</a></li>";
                echo "</ul>";
                echo "<ul id='navigation'>";
                    echo "<li><a href='/ManagerSelectionWeb.php?target=eval'>Evaluate</a></li>";
                    echo "<li><a href='/ManagerSelectionWeb.php?target=rank'>Rank</a></li>";
                    if (isset($_SESSION['admin']) && $_SESSION['admin'] == "1"){            
                        echo "<li><a href='/ManagerSelectionWeb.php?target=ati'>AMI/ATI</a></li>";
                        if($_SESSION['name'] == "Admin") {
                            echo "<li><a href='/ManagerSelectionWeb.php?target=reports'>Reports</a></li>";
                        }
                        echo "<li><a href='/ManagerSelectionWeb.php?target=admin'>Administration</a></li>";
                    }
                echo "</ul>";
            } else {
                echo "<li><a href='/SignInWeb.php?r=1'>Sign In</a></li>";
                echo "</ul>";
            }
            
            gc_disable();
            ?>
    </body>
</html>