
<?php
//File:   DBAccess.php
//Author: Steven L. Weitzeil
//Date:   13 May 2013
//Desc:   This class handles SAS communication and distribution of the DB Connection
//        information.
//Reviewed: Travis Jones - 24 June 2013
gc_enable();

class DBAccess {
    
    protected static $_instance;
    
    private $user;
    private $password;
    private $database = "Calibration";
    private $server = "127.0.0.1";
    
    // Singleton can't be constructed or cloned
    function __construct() {
        //Allow the running of the application on the developer client
        set_time_limit(270);
        
        if(isset($_SESSION['DBUser']) ||
           isset($_SESSION['DBPW'])) {
                $this->setUser($_SESSION['DBUser']);
                $this->setPwd($_SESSION['DBPW']);
           } else {            
                if(file_exists ("C:\\Users\\weitzeilsl\\security\\1830.txt")){
                    //Copy the credential file to the expected location
                    exec("copy C:\\Users\\weitzeilsl\\security\\1830.txt 1830.txt");
                }else{
                    //Call the SAS client to create the credential file
                    exec("c:\users\weitzeilsl\java\bin\java -cp c:\sas\sas-client-1.32-SNAPSHOT.jar org.familysearch.sas.client.Client Management-Tools-db fchUser fchPassword > 1830.txt");
                }

                //Open the credential file
                $handle = fopen("1830.txt", "r");
                if($handle){
                    //Get the username
                    $userName = fgets($handle);
                    $this->setUser(trim(substr($userName, 9)));

                    //Get the password
                    $password = fgets($handle);
                    $this->setPwd(trim(substr($password, 13)));

                    //Close the file and delete the credential
                    fclose($handle);
                    exec('del 1830.txt');
                }else{
                    //todo: log
                    echo ("DB SAS Info Failure");
                }
           }
    }
    
    function setUser($user) {
        $this->user = $user;
        $_SESSION['DBUser'] = $user;
    }
    
    function setPwd($password) {
        $this->password = $password;
        $_SESSION['DBPW'] = $password;
    }
            
    function getUser(){
        return $this->user;
    }
    
    function getPwd(){
        return $this->password;
    }
    
    function getDB(){
        return $this->database;
    }
    
    function getServer(){
        return $this->server;
    }
    
    function logDBError($userName, $message) {
        if(!class_exists('LogManager')) {
            include 'LogManager.php';
        }
        $log = new LogManager();
        $log->append($userName, $message);
        unset($log);
    }
    
    function __destruct() {
        unset($this->user);
        unset($this->password);
        unset($this->database);
        unset($this->server);
    }
}

gc_disable();
?>
