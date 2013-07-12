
<?php
    gc_enable();
    
    if(!class_exists('Budget')) {
        include 'Budget.php';
    }
    $budg = Budget::getInstance();   
    $grd99 = $budg->getEmpCnt("99");
    $grd98 = $budg->getEmpCnt("98");
    $grd97 = $budg->getEmpCnt("97");
    $grd96 = $budg->getEmpCnt("96");
    $grd95 = $budg->getEmpCnt("95");
    $grd94 = $budg->getEmpCnt("94");
    $grd93 = $budg->getEmpCnt("93");
    $grd92 = $budg->getEmpCnt("92");
    $grd91 = $budg->getEmpCnt("91");
    $grd90 = $budg->getEmpCnt("90");
    $grd89 = $budg->getEmpCnt("89");
    $grd88 = $budg->getEmpCnt("88");

    $data = "dummy, $grd99, $grd98, $grd97, $grd96, $grd95, $grd94, $grd93, $grd92, $grd91, $grd90, $grd89, $grd88";

    echo "$data";
    gc_disable();
?>
