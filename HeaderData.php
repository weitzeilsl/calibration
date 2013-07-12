<?php
/**
 * Description of HeaderData
 *
 * @author weitzeilsl
 */

class HeaderData {
    protected static $_instance;
    private $header = array(
        "PG" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "SD" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "WD" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "QA" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "AU" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "UX" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        ),
        "AD" => array(
            "Comp1"         => 0,
            "Comp2"         => 0,
            "Comp3"         => 0,
            "Comp4"         => 0,
            "Comp5"         => 0,
            "Comp6"         => 0,
            "Comp7"         => 0,
            "Comp8"         => 0,
            "Comp9"         => 0
        )
    );
     
        protected function __construct(){ }	 
        protected function __clone() { }

        public static function getInstance(){
            if( self::$_instance === NULL ) {
              self::$_instance = new self();

            //Establish DB Connection
            if(!class_exists('DBAccess')) {
                include 'DBAccess.php';
            }
            $db = new DBAccess(); 
            $mysqli = new mysqli($db->getServer(), $db->getUser(), $db->getPwd());

            // Check connection
            if ($mysqli->connect_errno){
                $db->logDBError($_SESSION['name'], "Budget::getInstance: Failed to connect to Server");
                echo "Failed to connect to server: " . mysqli_connect_error();
                exit();
            } else {
                $mysqli->select_db($db->getDB());
                $HDSQL = "SELECT * FROM Header";
                $resultHD = $mysqli->query($HDSQL);
                if($resultHD) {
                    while ($db_field = mysqli_fetch_assoc($resultHD)){
                        self::$_instance->header[$db_field['Role']]['Comp1'] = ($db_field['Comp1']);
                        self::$_instance->header[$db_field['Role']]['Comp2'] = ($db_field['Comp2']);
                        self::$_instance->header[$db_field['Role']]['Comp3'] = ($db_field['Comp3']);
                        self::$_instance->header[$db_field['Role']]['Comp4'] = ($db_field['Comp4']);
                        self::$_instance->header[$db_field['Role']]['Comp5'] = ($db_field['Comp5']);
                        self::$_instance->header[$db_field['Role']]['Comp6'] = ($db_field['Comp6']);
                        self::$_instance->header[$db_field['Role']]['Comp7'] = ($db_field['Comp7']);
                        self::$_instance->header[$db_field['Role']]['Comp8'] = ($db_field['Comp8']);
                        self::$_instance->header[$db_field['Role']]['Comp9'] = ($db_field['Comp9']);
                    }
                    $resultHD->close();
                } else {
                    $db->logDBError($_SESSION['name'], "HeaderData: Error on $HDSQL");
                }
            }
        }
        return self::$_instance;
    }
     
    function getComp1($role) {
        return self::$_instance->header[$role]['Comp1'];
    }
     
    function getComp2($role) {
        return self::$_instance->header[$role]['Comp2'];
    }
     
    function getComp3($role) {
        return self::$_instance->header[$role]['Comp3'];
    }
     
    function getComp4($role) {
        return self::$_instance->header[$role]['Comp4'];
    }
     
    function getComp5($role) {
        return self::$_instance->header[$role]['Comp5'];
    }
     
    function getComp6($role) {
        return self::$_instance->header[$role]['Comp6'];
    }
     
    function getComp7($role) {
        return self::$_instance->header[$role]['Comp7'];
    }
     
    function getComp8($role) {
        return self::$_instance->header[$role]['Comp8'];
    }
     
    function getComp9($role) {
        return self::$_instance->header[$role]['Comp9'];
    } 
}

?>
