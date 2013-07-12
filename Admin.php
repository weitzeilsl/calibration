<?php

/*
 * File:   Admin.php
 * Author: Steven L. Weitzeil
 * Date:   25 June 2013
 * Desc:   Provides common methods fo addressing Administration needs
 * Review: 
 */
class Admin {
    
    function displayAdminMenu($page) {
        //Display the Adminisration Menu
        echo "<ul id='menu'>";
            echo"<li><a href='/index.php'>Home</a></li>";
            if(isset($_SESSION['empID']) && $_SESSION['empID'] == '000000') {
                if($page == 'HRRateWeb') {
                    echo "<li><a href='/HRRateWeb.php'>Rate*</a></li>";
                } else {
                    echo "<li><a href='/HRRateWeb.php'>Rate</a></li>";
                }
                
                if($page == 'RangeWeb') {
                    echo "<li><a href='/RangeWeb.php'>Range*</a></li>";
                } else {
                    echo "<li><a href='/RangeWeb.php'>Range</a></li>";
                }
                
                if($page == 'WeightWeb') {
                    echo "<li><a href='/WeightWeb.php'>Weights*</a></li>";
                } else {
                    echo "<li><a href='/WeightWeb.php'>Weights</a></li>";
                }
            
                if($page == "ExportWeb") {
                    echo "<li><a href='/ExportWeb.php'>Export*</a></li>";
                } else {
                    echo "<li><a href='/ExportWeb.php'>Export</a></li>";
                }  
            
                if($page == 'AddEmployeeWeb') {
                    echo "<li><a href='/AddEmployeeWeb.php'>Add*</a></li>";
                } else {
                    echo "<li><a href='/AddEmployeeWeb.php'>Add</a></li>";
                }

                if($page == 'EditEmployeeWeb') {
                    echo "<li><a href='/EditEmployeeWeb.php'>Edit*</a></li>";
                } else {
                    echo "<li><a href='/EditEmployeeWeb.php'>Edit</a></li>";
                }

                if($page == 'DeleteEmployeeWeb') {
                    echo "<li><a href='/DeleteEmployeeWeb.php'>Delete*</a></li>";
                } else {
                    echo "<li><a href='/DeleteEmployeeWeb.php'>Delete</a></li>";
                }  
            }
            
            if ($page == 'PasswordWeb') {
                echo "<li><a href='/PasswordWeb.php'>Password*</a></li>";
            } else {
                echo "<li><a href='/PasswordWeb.php'>Password</a></li>";
            }
        echo "</ul>";
    }
}

?>
