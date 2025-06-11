<?php

namespace src\Models;

use src\Core\DataBase\DataBase;

class StockMovements extends DataBase
{
    private $id;
    private $product_id;
    private $quantity;
    private $type;
    private $date;

    const COL_ID = "id";
    const COL_PRODUCT_ID = "product_id";
    const COL_TYPE = "type";
    const COL_QUANTITY = "quantity";
    const COL_DATE = "date";
    const TABLE_NAME = "stock_movements";
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
        $configs['__columns'] = [
            self::COL_ID => false,
            self::COL_PRODUCT_ID => true,
            self::COL_TYPE => true,
            self::COL_QUANTITY => true,
            self::COL_DATE  => true,

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
