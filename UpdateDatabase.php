<?php

    session_start(); 
    gc_enable();
    
    //Build the uri for the destination page
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
        $uri = 'https://';
    } else {
        $uri = 'http://';
    }
    $uri .= $_SERVER['HTTP_HOST'];

    include 'EmployeeData.php';
    $empDisplay = array(array());
    $employees = new EmployeeData();
    $employees->updateAllData();     
    
    unset($employees);

    header('Location: '.$uri.'/AdminWeb.php');
    gc_disable();
    exit;
?>
