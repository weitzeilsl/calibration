<!--
* File:   Reports
* Author: Steven L. Weitzeil
* Date:   25 June 2013
* Desc:   Supporting code for FSMT reports
* Review: 
-->
<!DOCTYPE html>
<?php

class Reports {
    function displayReportsMenu ($page) {
         echo "<ul id='menu'>";
            echo "<li><a href='/index.php'>Home</a></li>";
            if($page == "ReportManagerCompWeb") {
                echo "<li><a href='/ReportManagerCompWeb.php'>Manager Comparison*</a></li>";
            } else {
                echo "<li><a href='/ReportManagerCompWeb.php'>Manager Comparison</a></li>";
            }
            
            if($page == "ReportHighPerfWeb") {
                echo "<li><a href='/ReportHighPerfWeb.php'>High Performers*</a></li>";
            } else {
                echo "<li><a href='/ReportHighPerfWeb.php'>High Performers</a></li>";
            }
            if($page == "ReportLowPerfWeb") {
                echo "<li><a href='/ReportLowPerfWeb.php'>Low Performers*</a></li>";
            } else {
                echo "<li><a href='/ReportLowPerfWeb.php'>Low Performers</a></li>";
            }
            
            if($page == "ReportPromotionsWeb") {    
                echo "<li><a href='/ReportPromotionsWeb.php'>Promotions*</a></li>";
            } else {
                echo "<li><a href='/ReportPromotionsWeb.php'>Promotions</a></li>";
            }
            
            if($page == "ReportOrgShapeWeb") {
                echo "<li><a href='/ReportOrgShapeWeb.php'>Organizational Shape*</a></li>";
            } else {
                echo "<li><a href='/ReportOrgShapeWeb.php'>Organizational Shape</a></li>";
            }  
        echo "</ul>";
    }
}

?>
