
<?php
    
    if(session_id() == '') {
            session_start();
    }

    if(!class_exists('ManagerUpdate')) {
        include 'ManagerUpdate.php';
    }
    $mgr = new ManagerUpdate();
    $mgr->updateSubmit($_SESSION['selectedManager'], $_REQUEST['type']);

    unset($mgr);

    //Go to Home page
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
       $uri = 'https://';
    } else {
       $uri = 'http://';
    }
    $uri .= $_SERVER['HTTP_HOST'];

    header('Location: '.$uri.'/Index.php');
    gc_disable();
    exit;
?>
