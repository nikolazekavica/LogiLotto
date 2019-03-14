<?php
/**
 * This class performs connection on database.
 *
 * @author Nikola Zekavica <nikolazekavica88@yahoo.com>
 *
 * @since 1.0.0
 */
class DBConnection
{
    private static $serverName = "localhost";
    private static $userName   = "root";
    private static $password   = "";
    private static $dbName     = "lotto";
    private static $charset    = "utf8mb4";

    public static $connect;

    public static function connect()
    {
        if(!isset(self::$connect))
        {
            try{
                $dsn="mysql:host=".self::$serverName.";dbname=".self::$dbName.";charset=".self::$charset;
                self::$connect= new PDO($dsn,self::$userName,self::$password);
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