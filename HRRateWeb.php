<!--
File:   HRRateWeb
Author: Steven L. Weitzeil
Date:   1 June 2013
Desc:   Display and manage the HR Rate page.  The HR rate is used to calculate
        all AMI values for all employees.
Review: Jim Johnson - 24 June 2013
-->

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
            $("input").keyup(function(e){
                //alert(e.keyCode)
                if((e.keyCode >= 48 &&  //0  
                   e.keyCode <= 57) ||  //9
                   e.keyCode === 37 ||  //left arrow
                   e.keyCode === 39 ||  //right arrow
                   e.keyCode === 13 ||  //Enter
                   e.keyCode === 8  ||  //backspace
                   e.keyCode === 46 ||  //delete
                   e.keyCode === 38 ||  //up arrow
                   e.keyCode === 39 ||  //down arrow
                   e.keyCode === 190) { //decimal
                    if(this.value >= 10) {
                        this.value = 9.99;
                    } else if(this.value < 0) {
                        this.value = 0;
                    }

                    $(this).html(this.value);

                    $.post(
                    'UpdateHRRate.php',
                        { value: this.value
                        }, function( data ){}
                    );
                } else {
                    this.value = "";
                    $(this).html(this.value);
                    return;
                }
            });            
        });
        
        </script>
        <title>HR Rate</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: HR Rate</h1>
     
        <?php
            session_start();
            gc_enable();

            //Display the Administrator menu
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('HRRateWeb');
            
            //Access the database
            if(!class_exists('DBAccess')) {
                include 'DBAccess.php';
            }
            $db = new DBAccess();

            $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
            // Check connection
            if ($mysqli->connect_errno){
                $db->logDBError($_SESSION['name'], "HRRate: Unable to connect to " . $db->getServer());
                echo "Failed to connect to server: " . mysqli_connect_error();
                exit();
            } else {
                $mysqli->select_db($db->getDB());
                //Get the current rate from the database 
                $HRSQL = "SELECT hrrate FROM Hrrate WHERE idhrrate=1";
                $result = $mysqli->query($HRSQL);
                if($result){
                    $data = $result->fetch_assoc();
                    if($data){
                        $hrrate = sprintf("%1.2f", $data['hrrate'] * 100);
                    }
                    $result->close();
                }else {
                    $db->logDBError($_SESSION['name'], "HRRateWeb: unable to $HRSQL");
                } 

                //Display the form
                echo "<form method='post' action='/UpdateDatabase.php'>";
                echo "<font color='red'>CAUTION: Changes made to the percentage field are saved immediately!</font><br><br>";
                echo "Specify the HR budget percentage to use for all salary calulations: <input name='hrrate' type='text' size='3' value=$hrrate>%<br><br>";
                echo "<input type='submit' name='button' value='Recalculate Database'>";
                echo "</form>";
                mysqli_close($mysqli);
            } 
            unset($admin);
            unset($db);
            gc_disable();
        ?>
    </body>
</html>
