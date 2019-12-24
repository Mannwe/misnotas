<?php

class Connection{

    private static $connection;

    public static function openConnection(){
        include_once 'config.inc.php';

        if(!isset(self::$connection)){
            try{
                self::$connection = new PDO('mysql:host=' .  HOST_NAME . '; dbname=' . DATABASE, USERNAME, PASSWORD);
                self::$connection -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection -> exec("SET CHARACTER SET utf8");
                if(self::$connection){
                    //print 'Conexión realizada.';
                }
            }catch(PDOException $ex){
				print 'ERROR: ' . $ex -> getMessage() . '<br>';
				die();
			}
        }
    }

    public static function closeConnection(){
        if(isset(self::$connection)){
            self::$connection = null;
        }
    }

    public static function getConnection(){
        return self::$connection;
    }

}

?>