<?php
    session_start();
    gc_enable();
    
    $goalTotal = 0;
    
    if(isset($_REQUEST['empID']) && 
        isset($_REQUEST['value']) && 
        isset($_REQUEST['change']) &&
        isset($_REQUEST['DivGoal']) &&
        isset($_REQUEST['TeamGoal']) &&
        isset($_REQUEST['PerGoal'])) {
        
        $empID = $_REQUEST['empID'];
        $value = $_REQUEST['value'];
        $change = $_REQUEST['change'];
        $divGoal = $_REQUEST['DivGoal'];
        $teamGoal = $_REQUEST['TeamGoal'];
        $perGoal = $_REQUEST['PerGoal'];
        
        if(!class_exists('Calculator')) {
            include 'Calculator.php';
        }
        $calc = new Calculator($empID);
        $resultsScore = $calc->updateResultsScore($change, $value, $divGoal, $teamGoal, $perGoal);
        unset($calc);
        
        echo $resultsScore;
    }
    gc_disable();
?>
