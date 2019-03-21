<?php
/**
 * This class performs connection on database.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
include_once './api.lotto.com/config/DBConfig.php';

class DBConnection
{
    public static $connect;

    public static function connect()
    {
        if(!isset(self::$connect))
        {
            try{
                $dsn="mysql:host=".SERVERNAME.";dbname=".DBNAME.";charset=".CHARSET;
                self::$connect= new PDO($dsn,USERNAME,PASSWORD);
                self::$connect->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

                return self::$connect;
            }catch (PDOException $exception)
            {
                echo "Connection failed: ".$exception->getMessage();
            }
        }
        else{
            return self::$connect;
        }
    }
}