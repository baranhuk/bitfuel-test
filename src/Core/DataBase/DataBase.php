<?php

namespace src\Core\DataBase;

use ArrayIterator;
use PDO;
use PDOException;
use stdClass;

class DataBase extends Conexao
{


    /**
     * Colunas que contém na Tabela Atual e também as Configurações de Retorno array
     * @access protected
     * @name $tabela
     */
    protected $__columns;

    /**
     * Armazena se Houver atualizar 
     * 0 indica indica que os campos são iguais
     * Se for mais que 0 indica a quantide de linas atulizadas na base de dados 
     * @access protected
     * @name $__rowCount
     */
    protected $__rowCount;

    /**
     * Seta da uma data de update
     * 0 indica que deve seta a data e hora atual para coluna update
     * @access protected
     * @name $__set_updated_at
     */
    protected $__set_updated_at = false;

    /**
     * Seta da uma data de criaçao
     * 0 indica que deve seta a data e hora atual para coluna criaçao
     * @access protected
     * @name $__set_created_at
     */
    protected $__set_created_at = false;

    /**
     * Seta da uma data de criaçao
     * 0 indica que deve seta a data e hora atual para coluna criaçao
     * @access protected
     * @name $__set_created_at
     */
    protected $__class = false;




    private $table;

    /**
     * Função construtora da classe.
     * Realiza a definição da tabela na classe para interação com o Banco de dados.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     *  @param string $table  Tabela que tera uma ação realizada
     *  @param string $array_config  Array de Configurações para interação
     * 
     */
    public function __construct($table = "", $array_config = array())
    {
        $this->table = $table;

        if ($this->table == "") {
            if ($this->table == "") {
                echo 'Informe a tabela de interação !';
                exit;
            }
        }
        if ($array_config) {
            $this->set_config_class($array_config);
        }
    }

    /**
     * @ignore
     */
    public function __destruct() {}

    /**
     * Função seta a configuração de acordo com a classe que herda a classe <code>Interacao</code>
     * 
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $array_config  Array de Configurações para interação
     * @access public
     * @return void
     */
    public function set_config_class($array_config = array())
    {
        if ($array_config && is_array($array_config)) {
            foreach ($array_config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Retorna um array para inserção no banco de dados de uma nova linha ou edição baseado nos valores que a classe tem no presente momento e das
     * configurações que existem no contrutor da Classe que herda as propriedades dessa Classe Interação !
     * 
     */
    public function return_array()
    {
        $array_retorno = [];
        if ($this->__columns && is_array($this->__columns)) {
            foreach ($this->__columns as $colun => $config) {
                if ($config) {
                    if ($this->check_column_exists($colun)) {
                        $array_retorno[$colun] = $this->$colun;
                    }
                }
            }
        }
        return $array_retorno;
    }

    /**
     * Verifica existe !
     * 
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string[] $name_column Nome da coluna a ser verificada
     * @param boolean[] $valid_id Se for True ele válida o ID de forma correta !
     * @access public
     * @return boolean ( TRUE || FALSE )
     */
    public function check_column_exists($name_column, $valid_id = false)
    {
        if ($valid_id) {
            if ($this->$name_column > 0) {
                return true;
            } elseif ($this->$name_column === null) {
                return false;
            } else {
                if ($this->$name_column === 0) {
                    $this->$name_column = null;
                    return true;
                } else {
                    return false;
                }
            }
        } else {
            if ($this->$name_column) {
                return true;
            } elseif (isset($this->$name_column)) {
                return true;
            } elseif ($this->$name_column === '') {
                $this->$name_column = NULL;
                return true;
            } elseif ($this->$name_column === 0) {
                return true;
            } elseif ($this->$name_column === false) {
                return true;
            } else {
                return false;
            }
        }
    }

    /**
     * Função que executa um query no banco de dados
     * 
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $query  Query a ser executada
     * @param string $retorno Tipo de retono <code>boolean</code> | <code>array_all</code> | <code>array_one</code> | <code>column</code> | <code>count</code> | <code>insert</code>
     * @param array $array_bind Chaves da bind se utiliza a função <code>bindValue()</code> do PDO
     * @param string $array_value Valores da bind se utiliza a função <code>bindValue()</code> do PDO
     * @access public
     * @return string|int|bool|array O retorno pode variar de tipo de acordo com tipo de retorno escolhido o valor defaut sempre sera um bool
     */
    public function executa_query($query = "", $retorno = 'boolean', $array_bind = array(), $array_value = array())
    {
        $table = "";
        $bind_log = [];
        try {

            // aceita so select
            if ($query) {
                $words = explode(" ", $query);
                if (mb_strtoupper($words[0]) !== "SELECT") {
                    return false;
                }
                preg_match('/FROM\s+([a-zA-Z_][a-zA-Z0-9_]*)/', $query, $matches);
                $table = (isset($matches[1]) ? $matches[1] : "");
            }


            $query_db = Conexao::$conecta->prepare($query);
            if ($array_bind != '' && $array_value != '') {
                $conta_bind = count($array_bind);
                foreach ($array_bind as $k => $v) {
                    $query_db->bindValue(':' . $v, $array_value[$k], PDO::PARAM_STR);
                    $bind_log[$v] = $array_value[$k];
                }
            }

            $query_db->execute();

            switch ($retorno) {
                case "boolean":
                    $return = true;
                    break;
                case "array_all":
                    $return = $query_db->fetchAll(PDO::FETCH_ASSOC);
                    break;
                case "array_one":
                    $return = $query_db->fetch(PDO::FETCH_ASSOC);
                    break;
                case "column_all":
                    $return = $query_db->fetchAll(PDO::FETCH_COLUMN);
                    break;
                case "column":
                    $return = $query_db->fetch(PDO::FETCH_COLUMN);
                    break;
                case "count":
                    $return = $query_db->rowCount();
                    break;
                default:
                    $retorno = "boolean";
                    $return = true;
                    break;
            }



            return $return;
        } catch (PDOException $error) {
            $sql_erro = $error->getMessage();
            if ($_ENV['DEBUG'] == 'true') {
                if ($_SERVER['HTTP_ACCEPT'] == 'application/json') {
                    echo json_encode(['error' => true, 'message' => $sql_erro]);
                } else {
                    echo    $sql_erro;
                }
            }
            return false;
        }
    }






    /**
     * Constroi um array com base na classe que herda a classe Interação para insersão na nase de dados
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @return int id do registro na base de dados
     */
    public function save(): int
    {
        return $this->insert($this->return_array());
    }

    /**
     * Função que monta a query de insersão do banco de dados apartir de um array e executa a insersão
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param array $dados array que contem os dados para monta a query este de ve ser chave e valor, a chane deve ter o mesmo nome da coluna na base de dados
     * @access public
     * @return int id do registro na base de dados
     */
    public function insert($data = []): int
    {

        if ($data == '') {
            return false;
        } else {
            $sql = 'INSERT INTO ' . $this->table . ' (';
            $f = new ArrayIterator($data);
            while ($f->valid()) {
                $sql .= '`' . $f->key() . '`, ';
                $f->next();
            }

            if ($this->__set_created_at) {
                $sql .= '`created_at`, ';
            }
            if ($this->__set_updated_at) {
                $sql .= '`updated_at`, ';
            }

            $sql = rtrim($sql, ', ') . ') VALUES (';
            $f = new ArrayIterator($data);
            while ($f->valid()) {
                $sql .= ':' . $f->key() . ', ';
                $f->next();
            }
            if ($this->__set_created_at) {
                $sql .= ':created_at, ';
            }
            if ($this->__set_updated_at) {
                $sql .= ':updated_at, ';
            }
            $sql = rtrim($sql, ', ') . ')';

            $bind_log = [];

            try {
                $inserir = Conexao::$conecta->prepare($sql);
                $i = 1;
                foreach ($data as $key => $valor) {
                    $inserir->bindValue(':' . $key, $valor, PDO::PARAM_STR);
                    $i++;
                    $bind_log[$key] = $valor;
                }

                if ($this->__set_created_at) {
                    $data_create = date('Y-m-d H:i:s');
                    $inserir->bindValue(':created_at', $data_create, PDO::PARAM_STR);
                    $bind_log['created_at'] = $data_create;
                }
                if ($this->__set_updated_at) {
                    $data_update = date('Y-m-d H:i:s');
                    $inserir->bindValue(':updated_at', $data_update, PDO::PARAM_STR);
                    $bind_log['updated_at'] = $data_update;
                }


                $inserir->execute();
                return Conexao::$conecta->lastInsertId();
            } catch (PDOexception $error) {
                $sql_erro = $error->getMessage();
                if ($_ENV['DEBUG'] == 'true') {
                    if ($_SERVER['HTTP_ACCEPT'] == 'application/json') {
                        echo json_encode(['error' => true, 'message' => $sql_erro]);
                    } else {
                        echo    $sql_erro;
                    }
                }
                return 0;
            }
        }
    }

    /**
     * Constroi um array com base na classe que herda a classe DataBase para update na nase de dados
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @param string $id  id do registro na base de dados
     * @return bool 
     */
    public function update($id): bool
    {
        if (!$id) {
            return false;
        } else {
            return $this->toUpdate($id, $this->return_array());
        }
    }

    /**
     * Função atualiza os dados na Tabela
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @param string $id  id do registro na base de dados
     * @param array $dados array que contem os dados para monta a query este de ve ser chave e valor, a chane deve ter o mesmo nome da coluna na base de dados
     * @return bool 
     */
    public function toUpdate($id = "", $data = []): bool
    {
        if ($id == '' && $data == '') {
            return false;
        } else {
            $sql = 'UPDATE ' . $this->table . ' SET ';
            $f = new ArrayIterator($data);
            while ($f->valid()) {
                $sql .= '`' . $f->key() . '` = ? ,';
                $f->next();
            }


            $sql2 = rtrim($sql, ',');

            $sql2 .= " WHERE id = ?";
            $bind_log = [];
            try {
                $atualizar = Conexao::$conecta->prepare($sql2);
                $i = 1;
                foreach ($data as $key => $valor) {
                    $atualizar->bindValue($i, $valor);
                    $bind_log[$key] = $valor;
                    $i++;
                }
                $bind_log['id'] = $id;

                $atualizar->bindValue($i, $id);

                $atualizar->execute();
                $this->__rowCount = $atualizar->rowCount();
                if ($this->__rowCount) {
                    $data_update = date('Y-m-d H:i:s');
                    if ($this->__set_updated_at) {
                        $sql_data = 'UPDATE ' . $this->table . ' SET ';
                        $sql_data .= '`updated_at` = ? ';
                        $sql_data .= " WHERE id = ?";
                        $atualizar = Conexao::$conecta->prepare($sql_data);
                        $atualizar->bindValue(1, $data_update);
                        $atualizar->bindValue(2, $id);
                        $atualizar->execute();
                    }
                }

                return true;
            } catch (PDOexception $error_atualizar) {
                $sql_erro = $error_atualizar->getMessage();


                return false;
            }
        }
    }

    /**
     * Função para deletar dados da Tabela
    
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param int[] $id ID da linha que será deletada.
     * @access public
     * @return boolean
     */
    public function delete($id = ""): bool
    {

        if (!$id) {
            return false;
        } else {
            $sql = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            try {
                $query = Conexao::$conecta->prepare($sql);
                $query->bindValue(':id', $id, PDO::PARAM_STR);
                $query->execute();
                $retorno = true;
                return $retorno;
            } catch (PDOexception $error_deleta) {
                $sql_erro = $error_deleta->getMessage();

                echo $sql_erro;
                return false;
            }
        }
    }

    /**
     * Função lista os dados na Tabela
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param string $colunas Caso a consuta deva trazer column especificas Exemplo de uso "id, nome, telenone"
     * @param string $inner_join  strig com o JOIN sql para bucar dados de ralcionamento de tabelas
     * @param string $complemento Especifica as cindições da consulata Deve informa o WHERE exemplo WHERE nome = "teste" AND ....
     * @param array $bindValue Chave da bind 
     * @param array $valor_bv  valores da bind os valores devem esta na mesma posição que o a do array da chave ex array $bindValue ['nome', 'id'], $valor_bv ['teste', 1]
     * @param string $view caso queira usa uma view para consulta
     * 
     * @access public
     * @return array
     */
    public function listar($colunas = "", $inner_join = "", $complemento = "", $bindValue = array(), $valor_bv = array(), $view = "")
    {


        if ($view) {
            $tabela = $view;
        } else {
            $tabela = $this->table;
        }
        if ($colunas == "") {
            $sql = 'SELECT * FROM ' . $tabela;
            if ($complemento) {
                $sql .= ' ' . $complemento;
            }
        } else {
            $sql = 'SELECT ' . $colunas . ' FROM ' . $tabela;
            if ($inner_join) {
                $sql .= ' ' . $inner_join;
            }
            if ($complemento) {
                $sql .= ' ' . $complemento;
            }
        }
        $bind_log = [];

        if ($sql == "") {
            return [];
        } else {
            try {
                $query = Conexao::$conecta->prepare($sql);
                if ($bindValue != '' && $valor_bv != '') {
                    $conta_bind = count($bindValue);
                    foreach ($bindValue as $k => $v) {
                        $query->bindValue(':' . $v, $valor_bv[$k], PDO::PARAM_STR);
                        $bind_log[$v] = $valor_bv[$k];
                    }
                }

                $query->execute();
                $resultado = $query->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOexception $error_verifica_cod) {
                $sql_erro = $error_verifica_cod->getMessage();

                return [];
            }
            if ($resultado) {
                return $resultado;
            } else {
                return [];
            }
        }
    }

    /**
     * 
     * Função faz o mesmo processo do listar porém armazena todos os Resultados em um vetor com os Objetos
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param int[] $id ID da linha que será deletada.
     * @access public
     * @return object
     */
    public function listar_e_armazenar_um($colunas = "", $inner_join = "", $complemento = "", $bindValue = array(), $valor_bv = array(), $view = "", $std_class = false)
    {
        $pega_resultado = $this->listar($colunas, $inner_join, $complemento, $bindValue, $valor_bv, $view);
        if ($pega_resultado && count($pega_resultado) === 1) {
            $retorno = $this->armazena_objeto($pega_resultado, $std_class);
            foreach ($retorno as $obj_return) {
            }
            return $obj_return;
        }
        return null;
    }

    /**
     * Função retorna o array de retorno do banco de dados em um novo Objeto da Classe Instânciada
     * 
     * Ainda a Opção através de um parâmetro Retornar esse Objeto Temporário (standart)
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param array $retorno_bd
     * @param boolean $standar_class 
     * @access public
     * @return array
     */
    public function armazena_objeto($retorno_bd, $standar_class = false)
    {
        if ($retorno_bd != null && is_array($retorno_bd)) {
            foreach ($retorno_bd as $res) {
                if ($standar_class) {
                    $new = new stdClass();
                } elseif ($this->__class != '') {
                    $new = new $this->__class();
                } else {
                    $new = new stdClass();
                }
                foreach ($res as $key => $value) {
                    $new->$key = $value;
                }
                $retorno[] = $new;
            }

            return $retorno;
        }
    }
}
