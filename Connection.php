<?php


class Connection
{
    private  static $connection;

    /**
     * Connection constructor.
     */
    private function __construct()
    {

    }

    public static function getInstance()
    {
        if (is_null(self::$connection)) {
            /*$host = "localhost";
            $dbname = "dbstagmiharisoas188295com";
            $user = "stagmis188295com";
            $password = "CpQYrgg5";*/
            $host = "localhost";
            $dbname = "annuairedb";
            $user = "root";
            $password = "";

            try
            {
                self::$connection = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
                //echo "connection database established";
            }
            catch(Exception $e)
            {
                die('Erreur : '.$e->getMessage());
            }
        }

        return self::$connection;
    }

}