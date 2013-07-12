<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>
        $(document).ready(function(){
            $("input").keyup(function(e){
                if(e.keyCode === 9  ||   //Don't calc if: Tab
                   e.keyCode === 37 ||   //Left
                   e.keyCode === 38 ||   //Up
                   e.keyCode === 39 ||   //Right
                   e.keyCode === 40)     //Down
                    return;
                
                if(this.value >= 200000) {
                    this.value = 199999;
                } else if(this.value < 0) {
                    this.value = 0;
                }
                
                var thisRow = $(this).closest('tr');
                $.post(
                    'UpdateRange.php',
                        { change: this.id, value: this.value 
                        }, function(){
                        }
                );
            });          
        });
        
        </script>
        <title>Range</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Range</h1>
        
        <?php
            session_start();
            gc_enable();
            
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('RangeWeb');
            
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
            
            include 'DBAccess.php';
            $db = new DBAccess();
            
            $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());
            // Check connection
            if ($mysqli->connect_errno){
                echo "Failed to connect to server: " . mysqli_connect_error();
                exit();
            } else {
                $mysqli->select_db($db->getDB());
                $RNSQL = "SELECT * FROM Salary ORDER BY Grade DESC";
                $result = $mysqli->query($RNSQL);
                if($result){
                    echo "<form method='post' action='/UpdateDatabase.php'>";
                    echo "<font color='red'>CAUTION: Changes made to salaries are saved immediately!<br><br></font>";
                    echo "<table border='1'>";        
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th id='grade' align=center>Grade</th>";
                    echo "<th id='min' align=center>Min</th>";
                    echo "<th id='mid' align=center>Mid</th>";
                    echo "<th id='max' align=center>Max</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo"<tbody>";
                    while ($db_field = mysqli_fetch_assoc($result)){
                        echo"<tr>";
                        echo"<th align='center' width='80'>$db_field[Grade]</td>";
                        echo"<td headers='min$db_field[Grade]' align='center' class='Min'><input type='text' size='6' id='Min$db_field[Grade]' name='Min$db_field[Grade]' value='$db_field[Min]'/></td>";
                        echo"<td headers='mid$db_field[Grade]' align='center' class='Mid'><input type='text' size='6' id='Mid$db_field[Grade]' name='Mid$db_field[Grade]' value='$db_field[Mid]'/></td>";
                        echo"<td headers='max$db_field[Grade]' align='center' class='Max'><input type='text' size='6' id='Max$db_field[Grade]' name='Max$db_field[Grade]' value='$db_field[Max]'/></td>";

                        echo"</tr>";
                    }
                    echo"</tbody>";
                    echo "</table>";
                    echo "<br><input type='submit' name='button' value='Recalculate Database'>";
                    echo "</form>";
                    $result->close();
                }else {
                    $db->logDBError($_SESSION['name'], "RangeWeb: unable to $RNSQL");
                }
                mysqli_close($mysqli);
            } 
            unset($admin);
            unset($db);
            gc_disable();
        ?>
    </body>
</html>
