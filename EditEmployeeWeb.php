<!--
File:   EditEmployeeWeb
Author: Steven L. Weitzeil
Date:   4 June 2013
Desc:   Present the page that enables the editing of the base employee data.
Review: 
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Edit</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Edit Employee</h1>
        <?php
            session_start();
            gc_enable();
            
            //Display the Administration menu
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('EditEmployeeWeb');
            
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

            //Gather the list of employees that may be edited
            if(!class_exists('EmployeeData')){
                include 'EmployeeData.php';
            }
            $employees = new EmployeeData();
            $employeeArray = $employees->getEmployeeArray();
                        
            echo "<form method='post' action='/EditEmployeeDataWeb.php'>";
            echo "Select employee to edit: ";
            echo "<select name='employeeName'>";
            foreach ($employeeArray as $singleEmployee) {
                if($singleEmployee[mgrID] == $_SESSION['empID'] || $_SESSION['empID'] == '000000') {
                    echo "<option>$singleEmployee[lastName], $singleEmployee[firstName] ($singleEmployee[empID])</option>";
                }
            }
            echo "</select><br><br>";
            
            //Display the Edit button
            echo "<input type='submit' name='button' value='Edit'>";
            echo "</form>";
            
            unset($admin);
            unset($emloyees);
            
            gc_disable();
        ?>
    </body>
</html>
