
<?php
    gc_enable();
    $rankTotal = 0;
    
    if(isset($_REQUEST['empID']) && 
       isset($_REQUEST['value']) && 
       isset($_REQUEST['change']) &&
       isset($_REQUEST['value1']) &&
       isset($_REQUEST['value2']) &&
       isset($_REQUEST['value3']) &&
       isset($_REQUEST['value4']) &&
       isset($_REQUEST['value5']) &&
       isset($_REQUEST['value6']) &&
       isset($_REQUEST['value7']) &&
       isset($_REQUEST['value8']) &&
       isset($_REQUEST['value9'])) {
        
        $empID = $_REQUEST['empID'];
        $value = $_REQUEST['value'];
        $change = $_REQUEST['change'];
        $value1 = $_REQUEST['value1'];
        $value2 = $_REQUEST['value2'];
        $value3 = $_REQUEST['value3'];
        $value4 = $_REQUEST['value4'];
        $value5 = $_REQUEST['value5'];
        $value6 = $_REQUEST['value6'];
        $value7 = $_REQUEST['value7'];
        $value8 = $_REQUEST['value8'];
        $value9 = $_REQUEST['value9'];

        if(!class_exists('Calculator')) {
            include 'Calculator.php';
        }
        $calc = new Calculator($empID);            
        $rankTotal = $calc->updateRankTotal($change, $value,
                                            $value1, $value2, $value3,
                                            $value4, $value5, $value6,
                                            $value7, $value8, $value9);
        unset($calc);

        echo "$rankTotal";  
    }
    gc_disable();
?>
