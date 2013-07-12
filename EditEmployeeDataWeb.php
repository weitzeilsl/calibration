<!--
AddEmployeeWeb
Steven L. Weitzeil
14 June 2013
Description: This page prompts the user for the information needed to add an
             employee to the Employee table in the Calibration database.
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
            
            //Display the Administration Menu
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('EditEmployeeWeb');
            
            //If not signed in, signout and return to MustSignInWeb
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
            
            //Get empID of employee selected
            if(!isset($_REQUEST['employeeName'])) {
                if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
            
                header('Location: '.$uri.'/EditEmployeeWeb.php');
            } else {
                $begin = strrpos($_REQUEST['employeeName'], '(') + 1;
                $end = strrpos($_REQUEST['employeeName'], ')');
                $empID = substr($_REQUEST['employeeName'], $begin, $end-$begin);
                $_SESSION['oldEmpID'] = $empID;   // Save in case it is changed
            }

            echo "<form method='post' action='/EditEmployee.php'>";
            
            //Manager
            if(!class_exists('ManagerData')){
                include 'ManagerData.php';
            }
            $managerData = new ManagerData();
            $managerNames = $managerData->getManagerNamesAndEmpId();
            
            if(!class_exists('EmployeeData')) {
                include 'EmployeeData.php';
            }
            $employee = new EmployeeData();
            $oneEmp = $employee->getAnEmployee($empID);
            $mgrParenStr = $managerData->getManagerEmpIDinParen($oneEmp['mgrID']);
            
            echo "Manager: ";
            echo "<select name='mgrName'>";
            foreach ($managerNames as $singleManager) {
                if($mgrParenStr == $singleManager) {
                    echo "<option selected='selected'>$singleManager</option>";
                } else {
                    echo "<option>$singleManager</option>";
                }
            }
            echo "</select><br><br>";
            
            //Employee Info
            echo "<b><i>Employee information:</i></b><br>";
            echo "Employee ID: <input type='text' size='6' id='empID' name='empID' value=$oneEmp[empID]><br>";
            echo "First Name: <input type='text' size='20' id='firstName' name='firstName' value=$oneEmp[firstName]><br>";
            echo "Last Name: <input type='text' size='20' id='lastName' name='lastName' value=$oneEmp[lastName]><br>";
            
            //Role
            echo "Role: ";
            echo "<select name='role'>";
            if($oneEmp[Role] == 'AM') {
                echo "<option selected='selected'>Administration (AM)</option>";
            } else {
                echo "<option>Administration (AM)</option>";
            }
            
            if($oneEmp[Role] == 'AU') {
                echo "<option selected='selected'>Authorities (AU)</option>";
            } else {
                echo "<option>Authorities (AU)</option>";
            }
            
            if($oneEmp[Role] == 'QA') {
                echo "<option selected='selected'>Quality Assurance (QA)</option>";
            } else {
                echo "Quality Assurance (QA)</option>";
            }
            
            if($oneEmp[Role] == 'PG') {
                echo "<option selected='selected'>Program Management (PG)</option>";
            } else {
                echo "<option>Program Management (PG)</option>";
            }
            
            if($oneEmp[Role] == 'SD') {
                echo "<option selected='selected'>Software Development (SD)</option>";
            } else {
                echo "<option>Software Development (SD)</option>";
            }
            
            if($oneEmp[Role] == 'UX') {
                echo "<option selected='selected'>User Design (UX)</option>";
            } else {
                echo "<option>User Design (UX)</option>";
            }
            
            if($oneEmp[Role] == 'WD') {
                echo "<option selected='selected'>Web Development (WD)</option>";
            } else {
                echo "<option>Web Development (WD)</option>";
            }
            echo "</select><br>";
            
            //Grade
            echo "Grade: ";
            echo "<select name='grade'>";
            if($oneEmp[Grade] == '99') {
                echo "     <option selected='selected'>99</option>";
            } else {
                echo "     <option>99</option>";
            }
            if($oneEmp[Grade] == '98') {
                echo "     <option selected='selected'>98</option>";
            } else {
                echo "     <option>98</option>";
            }
            if($oneEmp[Grade] == '97') {
                echo "     <option selected='selected'>97</option>";
            } else {
                echo "     <option>97</option>";
            }
            if($oneEmp[Grade] == '96') {
                echo "     <option selected='selected'>96</option>";
            } else {
                echo "     <option>96</option>";
            }
            if($oneEmp[Grade] == '95') {
                echo "     <option selected='selected'>95</option>";
            } else {
                echo "     <option>95</option>";
            }
            if($oneEmp[Grade] == '94') {
                echo "     <option selected='selected'>94</option>";
            } else {
                echo "     <option>94</option>";
            }
            if($oneEmp[Grade] == '93') {
                echo "     <option selected='selected'>93</option>";
            } else {
                echo "     <option>93</option>";
            }
            if($oneEmp[Grade] == '92') {
                echo "     <option selected='selected'>92</option>";
            } else {
                echo "     <option>92</option>";
            }
            if($oneEmp[Grade] == '91') {
                echo "     <option selected='selected'>91</option>";
            } else {
                echo "     <option>91</option>";
            }
            if($oneEmp[Grade] == '90') {
                echo "     <option selected='selected'>90</option>";
            } else {
                echo "     <option>90</option>";
            }
            if($oneEmp[Grade] == '89') {
                echo "     <option selected='selected'>89</option>";
            } else {
                echo "     <option>89</option>";
            }
            if($oneEmp[Grade] == '88') {
                echo "     <option selected='selected'>88</option>";
            } else {
                echo "     <option>88</option>";
            }
            if($oneEmp[Grade] == '87') {
                echo "     <option selected='selected'>87</option>";
            } else {
                echo "     <option>87</option>";
            }
            echo "</select><br>";
            
            //Salary
            echo "Current Salary: $<input type='text' size='9' id='salary' name='salary' value=$oneEmp[CurSal]><br><br>";
            
            //Managerial Data
            echo "<b><i>If this person is a manager, enter the following:</i></b><br>";
            echo "Email: <input type='text' size='20' id='email' name='email'/>@familysearch.org<br>";
            echo "FSMT Access Password: <input type='password' size='20' id='password' name='password'><br><br>";
            echo "<input type='submit' name='button' value='Save'> ";
            echo "<input type='submit' name='button' value='Cancel'>";
            echo "</form>";
            
            unset($admin);
            unset($managerData);
            unset($employee);
            gc_disable();
        ?>
    </body>
</html>
