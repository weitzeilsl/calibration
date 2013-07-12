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
                    'UpdateWeight.php',
                        { change: this.id, value: this.value 
                        }, function(){
                        }
                );
            });          
        });
        
        </script>
        <title>Weight</title>
        <link rel="stylesheet" href="css/adminMenu.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/evalTable.css" type="text/css" media="screen">
        <link rel="stylesheet" href="css/navigation.css" type="text/css" media="screen">
    </head>
    <body>
        <h1>Administration: Weight</h1>
        
        <?php
            session_start();
            gc_enable();
            
            if(!class_exists('Admin')) {
                include 'Admin.php';
            }
            $admin = new Admin();
            $admin->displayAdminMenu('WeightWeb');
            unset($admin);
            
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
                $RNSQL = "SELECT * FROM Weight ORDER BY empRole ASC, empGrade DESC";
                $result = $mysqli->query($RNSQL);
                if($result){
                    echo "<form method='post' action='/UpdateDatabase.php'>";
                    echo "<font color='red'>CAUTION: Changes made to the weights are saved immediately! </font>";
                    echo "<input type='submit' name='button' value='Recalculate Database'><br><br>";
                    echo "<table border='1'>";        
                    echo "<thead>";
                    echo "<tr>";
                    echo "<th id='grade' align=center>Grade</th>";
                    echo "<th id='role' align=center>Role</th>";
                    echo "<th id='comp1' align=center>Comp1 Wt</th>";
                    echo "<th id='comp2' align=center>Comp2 Wt</th>";
                    echo "<th id='comp3' align=center>Comp3 Wt</th>";
                    echo "<th id='comp4' align=center>Comp4 Wt</th>";
                    echo "<th id='comp5' align=center>Comp5 Wt</th>";
                    echo "<th id='comp6' align=center>Comp6 Wt</th>";
                    echo "<th id='comp7' align=center>Comp7 Wt</th>";
                    echo "<th id='comp8' align=center>Comp8 Wt</th>";
                    echo "<th id='comp9' align=center>Comp9 Wt</th>";
                    echo "<th id='div' align=center>Div Wt</th>";
                    echo "<th id='team' align=center>Team Wt</th>";
                    echo "<th id='per' align=center>Per Wt</th>";
                    echo "</tr>";
                    echo "</thead>";
                    echo"<tbody>";
                    while ($db_field = mysqli_fetch_assoc($result)){
                        echo"<tr>";
                        echo"<th align='center' width='80'>$db_field[empGrade]</td>";
                        echo"<th align='center' width='80'>$db_field[empRole]</td>";
                        echo"<td headers='comp1$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp1Wt' value='$db_field[comp1Wt]'/></td>";
                        echo"<td headers='comp2$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp2Wt' value='$db_field[comp2Wt]'/></td>";
                        echo"<td headers='comp3$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp3Wt' value='$db_field[comp3Wt]'/></td>";
                        echo"<td headers='comp4$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp4Wt' value='$db_field[comp4Wt]'/></td>";
                        echo"<td headers='comp5$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp5Wt' value='$db_field[comp5Wt]'/></td>";
                        echo"<td headers='comp6$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp6Wt' value='$db_field[comp6Wt]'/></td>";
                        echo"<td headers='comp7$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp7Wt' value='$db_field[comp7Wt]'/></td>";
                        echo"<td headers='comp8$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp8Wt' value='$db_field[comp8Wt]'/></td>";
                        echo"<td headers='comp9$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]comp9Wt' value='$db_field[comp9Wt]'/></td>";
                        echo"<td headers='DivWt$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]DivWt' value='$db_field[DivWt]'/></td>";
                        echo"<td headers='TeamWt$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]TeamWt' value='$db_field[TeamWt]'/></td>";
                        echo"<td headers='PerWt$db_field[empRole]$db_field[empGrade]' align='center' class='Min'><input type='text' size='6' id='$db_field[empRole]$db_field[empGrade]PerWt' value='$db_field[PerWt]'/></td>";

                        echo"</tr>";
                    }
                    echo"</tbody>";
                    echo "</table>";
                    echo "</form>";
                    $result->close();
                } else {
                    $db->logDBError($_SESSION['name'], "WeightWeb - unable to $RNSQL");
                }
                mysqli_close($mysqli);
            }       
            unset($db);
            gc_disable();
        ?>
    </body>
</html>
