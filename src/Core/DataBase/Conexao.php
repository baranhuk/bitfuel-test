<?php

namespace src\Core\DataBase;

use Exception;
use PDO;
use PDOException;

class Conexao extends PDO
{
    /**
     * Variável statica de Conexão.
     * @access public
     * @name $conecta
     */
    public static $conecta;

    /**
     * Função realiza a conexão em PDO.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @return void
     */
    public function __construct()
    {
        $conexao = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_DATABASE'];
        $pass = $_ENV['DB_PASSWORD'];
        $user = $_ENV['DB_USERNAME'];
        try {
            parent::__construct($conexao, $user, $pass);
        } catch (PDOException $error) {
            if ($_ENV['DEBUG'] == "true") {
                echo 'Erro ao conectar';
                echo $error->getMessage();
            }
            exit();
        }
    }

    /**
     * @ignore
     */
    public function __destruct() {}

    /**
     * Função aramazena a conexão PDO na varável static $conecta.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @return void
     */
    public static function getInstance()
    {
        if (is_null(self::$conecta) === true) {
            try {
                self::$conecta = new Conexao();
                self::$conecta->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                if ($_ENV['DEBUG'] == "true") {
                    echo 'Erro ao conectar favor';
                }
                exit();
            }
        }
        return self::$conecta;
    }
}
