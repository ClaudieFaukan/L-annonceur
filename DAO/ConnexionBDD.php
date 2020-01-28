<?php

/** Classe ConnexionBdd */
class ConnexionBdd
{
    private static $cnx;

    // Singleton to connect db.
    private static $instance = null;

    // The db connection is established in the private constructor.
    private function __construct()
    {
        $host = '127.0.0.1';
        $db = 'annonce';
        $user = 'root';
        $pass = '';
        $charset = 'utf8';

        $dsn = "mysql:host={$host};dbname={$db};charset={$charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        try {
            self::$cnx = new PDO($dsn, $user, $pass, $options);
        } catch (PDOException $e) {
            //throw new PDOException($e->getMessage(), (int)$e->getCode());
            echo 'erreur connexion';
        }
    }

    public static function getConnexion()
    {
        if (!self::$instance) {
            self::$instance = new ConnexionBdd();
        }

        return self::$cnx;
    }
}
