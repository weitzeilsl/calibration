
<?php
    gc_enable();
    
    if(isset($_REQUEST['empID']) && 
       isset($_REQUEST['value'])) {
        
        $empID = $_REQUEST['empID'];
        $value = $_REQUEST['value'];

        if(!class_exists('EmployeeUpdate')) {
            include 'EmployeeUpdate.php';
        }
        $emp = new EmployeeUpdate();
        $emp->updateJustification($empID, $value);
        
        unset($emp);
    }
    gc_disable();
?>
