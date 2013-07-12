
<?php
    gc_enable();
    
    if(isset($_REQUEST['empID']) && 
       isset($_REQUEST['change']) &&
       isset($_REQUEST['value'])) {
        
        $empID = $_REQUEST['empID'];
        $change = $_REQUEST['change'];
        $value = $_REQUEST['value'];

        if(!class_exists('Calculator')) {
            include 'Calculator.php';
        }
        $calc = new Calculator($empID);   
        $calc->setGMAdjust($value);
        $calc->updateAdjustScore();
        
        $adjustScore = $calc->getAdjustScore();;
        $atiFinal = $calc->getATIFinal();
        $actualInc = $calc->getActualInc();
        $newSal = $calc->getNewSalary();;
        $incrPercent = $calc->getIncrPercent();
        $data = "$adjustScore, $atiFinal, $actualInc, $newSal, $incrPercent";
        
        unset($calc);
        
        echo "$data";
    }
    gc_disable();
?>
