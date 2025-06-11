<?php

namespace src\Models;

use src\Core\DataBase\DataBase;

class Products extends DataBase
{

    private $id;
    private $name;
    private $sku;
    private $cost;
    private $created_at;
    private $updated_at;
    private $system_users;
    private $id_stock;
    private $quantity;
    private $stock_updated_at;

    const COL_ID = "id";
    const COL_NAME = "name";
    const COL_SKU = "sku";
    const COL_COST = "cost";
    const COL_UPDATED_AT = "updated_at";
    const COL_CREATE_AT = "created_at";
    const TABLE_NAME = "products";
    const CLASS_NAME = __CLASS__;


    /**
     * Função construtora da classe<br>
     * Realiza a definição da tabela na classe pai Interacao do banco de dados para interação com o Banco de dados.
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @access public
     * @return void
     */
    public function __construct($id_buscar = 0, $view = '')
    {

        $configs = [];
        $configs['__class'] = self::CLASS_NAME;
        $configs['__set_updated_at'] = true;
        $configs['__set_created_at'] = true;
        $configs['__columns'] = [
            self::COL_ID => false,
            self::COL_NAME => true,
            self::COL_SKU => true,
            self::COL_COST => true,
            self::COL_UPDATED_AT  => true,
            self::COL_CREATE_AT  => true
        ];
        parent::__construct(self::TABLE_NAME, $configs);

        if (intval($id_buscar)) {
            if ($busca_dados = $this->listar_e_armazenar_um('', '', "WHERE id = $id_buscar", '', '', $view)) {
                foreach ($this as $name => $value) :
                    $this->$name = $busca_dados->$name;
                endforeach;
            }
        }
    }

    /**
     * @ignore
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * @ignore
     */
    public function __set($key, $value)
    {
        $this->$key = $value;
    }
}
