<!--
Validate
Steven Weitzeil
FamilySearch
9 April 2013
Reviewed: 28 June 2013 - Travis Jones
-->
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

   if($_REQUEST['button'] == "Cancel"){
        //Yes - Go back home
        header('Location: '.$uri.'/Index.php');
        exit;
    }

    if($_REQUEST['button'] == "Get Password"){
        //Yes - Go to GetPasswordWeb
        //$_SESSION['name'] = $Request['userName'];
        header('Location: '.$uri.'/GetPasswordWeb.php');
        exit;
    }

    if(!class_exists('ManagerData')) {
        include 'ManagerData.php';
    }
    $managerData = new ManagerData();

    //Get the selected username and password from the SignInWeb page.
    foreach($_REQUEST as $key => $value){
        if($key == 'userName') {
            $userName = $value;
        }
        if ($key == 'password') {
            $password = $value;
        }
    }

    //Was the password correct?
    if($managerData->isCorrectPassword($userName, $password)) {
        //Yes - Go back home
        header('Location: '.$uri.'/Index.php');
        exit;
    }else {
        //No - Go back to SignInWe     
        if(!class_exists('LogManager')) {
           include 'LogManager.php';
       }
       $log = new LogManager();
       $log->append($userName, "Incorrect Password");
       unset($log);
       
       header('Location: '.$uri.'/SignInWeb.php?r=0');
       exit;
    }
    
    unset($managerData);
    gc_disable();
?>
