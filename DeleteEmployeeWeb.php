<!--
File:   DeleteEmployeeWeb.php
Author: Steven L. Weitzeil
Date:   12 June 2013
Desc:   Produces the Delete employee web page and calls DeleteEmployee.php to
        perform the actual deletion.
Review: 
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Delete</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Delete Employee</h1>
        <?php
            session_start();
            gc_enable();
            
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('DeleteEmployeeWeb');
            
            //If already signed in, signout and return to Index
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

            if(!class_exists('EmployeeData')){
                    include 'EmployeeData.php';
                }
            $employees = new EmployeeData();
            $employeeArray = $employees->getEmployeeArray();
                        
            echo "<form method='post' action='/DeleteEmployee.php'>";
            echo "<font color='red'>CAUTION: This option will remove the employee and all associated data from the database!</font><br><br>";
            echo "Select employee to delete: ";
            echo "<select name='employeeName'>";
            foreach ($employeeArray as $singleEmployee) {
                if($singleEmployee[mgrID] == $_SESSION['empID'] || $_SESSION['empID'] == '000000') {
                    echo "<option>$singleEmployee[lastName], $singleEmployee[firstName] ($singleEmployee[empID])</option>";
                }
            }
            echo "</select><br><br>";
            
            echo "<input type='submit' name='button' value='Delete' onclick=\"return confirm('Are you sure you want to delete the selected employee?');\">";
            echo "</form>";
            
            unset($employees);
            unset($admin);
            
            gc_disable();
        ?>
    </body>
</html>
