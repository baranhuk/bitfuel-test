<?php
require str_replace('/src/config', '', __DIR__) . '/vendor/autoload.php';



use Dotenv\Dotenv;
use src\Core\DataBase\Conexao;
// inicia a variaveis de ambiente
$dotenv = Dotenv::createImmutable(str_replace('/src/config', '', __DIR__));
$dotenv->load();



// inicia o banco de dados
$conecta = new Conexao();
$conecta->getInstance();
