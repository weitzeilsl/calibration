<!--
To change this template, choose Tools | Templates
and open the template in the editor.
-->
<!DOCTYPE html>
<?php session_start(); ?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Get Password</title>
    </head>
    <body>
        <h1>Get Password</h1>
        <h2><br<br>Your email was sent.<2/h1>
            
        <?php
            gc_enable();
            
            if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
                    $uri = 'https://';
                } else {
                    $uri = 'http://';
                }
                $uri .= $_SERVER['HTTP_HOST'];
                
            if($_REQUEST['button'] == "Cancel"){
                //Yes - Go back home
                header('Location: '.$uri.'/Index.php/');
                exit;
            } else {
                //mail('weitzeilsl@familysearch.org', 'Calibration Information', 'mypass');
                header('Location: '.$uri.'/SignInWeb.php/');
            }
            
            gc_disable();
        ?>
    </body>
</html>
