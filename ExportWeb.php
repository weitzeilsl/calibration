<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <title>Export</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Export</h1>
        
        <?php
            session_start();
            gc_enable();
            
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('ExportWeb');
            
            //If not already signed in, signout and return to Index
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
            
            echo "Downloading Confidential Data";
            
            if(!class_exists('EmployeeData')) {
                include 'EmployeeData.php';
            }
            
            $filename = "adminexport";
            
            if(!class_exists('fileIO')) {
                include 'fileIO.php';
            }
            $fileObj = new fileIO();
            $line = "empId, firstName, lastName, Grade, mgrFlag, mgrID, managerName, Salary\r\n";
            $fileObj->fileHeading($filename, $line);
            
            $employees = new EmployeeData();
            $employees->exportEmployees("380034", "All", "All", "2", 0, $filename);
            
            
            $fileObj->download($filename, "FSMT-Export.csv");
            $fileObj->fileHeading($filename, " ");
            
            unset($admin);
            unset($fileObj);
            unset($employees);
                 
            gc_disable();
        ?>
    </body>
</html>
