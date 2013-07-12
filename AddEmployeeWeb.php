<!--
File:   AddEmployeeWeb.php
Author: Steven L. Weitzeil
Date:   14 June 2013
Desc:   Prompt the user for the information needed to add an
        employee to the Employee table in the Calibration database.
Code Reviewed:
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
        <h1>Administration: Add Employee</h1>
        
        <?php
            session_start(); 
            gc_enable();
            
            //Display the Administration Menu
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('AddEmployeeWeb');
            unset($admin);
            
            //If not signed in, go to Must Sign-In Page
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
                        
            echo "<form method='post' action='/AddEmployee.php'>";
            
            //Manager
            if(!class_exists('ManagerData')){
                include 'ManagerData.php';
            }
            $managerData = new ManagerData();
            $managerNames = $managerData->getManagerNamesAndEmpId();
            
            echo "Manager: ";
            echo "<select name='mgrName'>";
            foreach ($managerNames as $singleManager) {
                echo "<option>$singleManager</option>";
            }
            unset($managerData);
            
            echo "</select><br><br>";
            
            //Employee Info
            echo "<b><i>New employee information:</i></b><br>";
            
            echo "Employee ID: <input type='text' size='6' id='empID' name='empID'/><br>";
            echo "First Name: <input type='text' size='20' id='firstName' name='firstName'/><br>";
            echo "Last Name: <input type='text' size='20' id='lastName' name='lastName'/><br>";
            
            //Role
            echo "Role: ";
            echo "<select name='role'>";
            echo "     <option>Administration (AM)</option>";
            echo "     <option>Authorities (AU)</option>";
            echo "     <option>Quality AssuranceQA</option>";
            echo "     <option>Program Management (PG)</option>";
            echo "     <option>Software Development (SD)</option>";
            echo "     <option>User Design (UX)</option>";
            echo "     <option>Web Development (WD)</option>";
            echo "</select><br>";
            
            //Grade
            echo "Grade: ";
            echo "<select name='grade'>";
            echo "     <option>99</option>";
            echo "     <option>98</option>";
            echo "     <option>97</option>";
            echo "     <option>96</option>";
            echo "     <option>95</option>";
            echo "     <option>94</option>";
            echo "     <option>93</option>";
            echo "     <option>92</option>";
            echo "     <option>91</option>";
            echo "     <option>90</option>";
            echo "     <option>89</option>";
            echo "     <option>88</option>";
            echo "     <option>86</option>";
            echo "</select><br>";
            
            //Salary
            echo "Current Salary: $<input type='text' size='9' id='salary' name='salary'/><br><br>";
            
            //Managerial Data
            echo "<b><i>If the new employee is a manager, enter the following:</i></b><br>";
            echo "Email: <input type='text' size='20' id='email' name='email'/>@familysearch.org<br>";
            echo "FSMT Access Password: <input type='password' size='20' id='password' name='password'/><br><br>";
            echo "<input type='submit' name='button' value='Save'>";
            echo "</form>";
            
            gc_disable();
        ?>
    </body>
</html>
