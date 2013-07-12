<!--
* File:   Reports
* Author: Steven L. Weitzeil
* Date:   25 June 2013
* Desc:   Reports main page
* Review: 
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
        <h1>Reports</h1>
        
        <?php
        if(!class_exists('Reports')){
            include 'Reports.php';
        }
        $rep = new Reports();
        $rep->displayReportsMenu("ReportsWeb");
        
        unset($rep);
        ?>
    </body>
</html>
