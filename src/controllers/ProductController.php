<?php

namespace src\controllers;

use src\Core\Response\Response;
use src\Models\Products;
use src\Models\Stock;
use src\Models\StockMovements;

class ProductController
{
    /**
     * Crai um nomvo produto na base de dados    
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param array $prams
     * @access public
     */
    public function store($prams)
    {
        $quantity = 0;
        if (!isset($prams['name']) || !trim(strip_tags($prams['name']))) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'O nome é obrigatório'], 422);
        }
        if (!isset($prams['sku']) || !trim(strip_tags($prams['sku']))) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'O código SKU é obrigatório'], 422);
        }
        if (!isset($prams['cost']) || !$prams['cost']) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'O valor é obrigatório'], 422);
        } else {
            if (!filter_var($prams['cost'], FILTER_VALIDATE_FLOAT) !== false) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'O valor deve ser do tipo double ou int'], 422);
            }
            if (doubleval($prams['cost']) <= 0) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'O valor deve ser maior que 0'], 422);
            }
        }
        if (isset($prams['quantity'])) {
            if (!filter_var($prams['quantity'], FILTER_VALIDATE_FLOAT) !== false) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'A quantidade deve ser do tipo double ou int'], 422);
            }
            $quantity = doubleval($prams['quantity']);
        }

        $product = new Products();
        $exite = $product->executa_query("SELECT id FROM products WHERE sku = '" . $prams['sku'] . "'", 'column');
        if ($exite > 0) {
            Response::responseJson(["message" => "Já existe um produto com esse código SKU"], 409);
        }
        $product->name = trim(strip_tags($prams['name']));
        $product->sku = trim(strip_tags($prams['sku']));
        $product->cost = doubleval($prams['cost']);
        $id = $product->save();
        if ($id) {
            $stock = new Stock();
            $stock->product_id = $id;
            $stock->quantity = 0;
            $id_stock = $stock->save();
            if ($id_stock) {

                if ($quantity) {
                    $stockMovements = new StockMovements();
                    $stockMovements->product_id = $id;
                    $stockMovements->quantity = $quantity;
                    $stockMovements->type = "E";
                    $stockMovements->date = date("Y-m-d H::s");
                    $id_stockMovements = $stockMovements->save();
                    if ($id_stockMovements) {
                        $stock->quantity = $quantity;
                        if (!$stock->update($id_stock)) {
                            $stockMovements->delete($id_stockMovements);
                            $stock->delete($id_stock);
                            $product->delete($id);
                            Response::responseJson(["message" => "Ocorreu um erro ao registrar a entrada do produto. Tente novamente mais tarde."], 500);
                        }
                    } else {
                        $stock->delete($id_stock);
                        $product->delete($id);
                        Response::responseJson(["message" => "Ocorreu um erro ao registrar a entrada do produto. Tente novamente mais tarde."], 500);
                    }
                }

                $product_data = $product->executa_query("SELECT * FROM vw_products WHERE id = " . $id, 'array_one');
                Response::responseJson($product_data, 201);
            } else {
                $product->delete($id);
            }
        }
        Response::responseJson(["message" => "Ocorreu um erro ao salvar o produto. Tente novamente mais tarde."], 500);
    }

    /**
     * Listas todos os produtos     
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>    
     * @access public
     */
    public function index()
    {
        $data = filter_input_array(INPUT_GET, FILTER_DEFAULT);

        $page = (isset($data['page']) ? (intval($data['page']) > 0 ? (intval($data['page']) - 1) : 0) : 0);
        $perPage = (isset($data['perPage']) ? (intval($data['perPage']) > 50 ? 50 : (intval($data['perPage']) < 1 ? 1 : intval($data['perPage']))) : 10);

        $offset = ($page * $perPage);
        $product = new Products();

        $products = $product->executa_query("SELECT * FROM products ORDER BY name ASC LIMIT " . $perPage . ' OFFSET ' . $offset, 'array_all');
        $all_products = $product->executa_query("SELECT * FROM products ", 'count');
        $meta = [
            'current_page' => ($page + 1),
            'per_page' => $perPage,
            'total' => $all_products,
            'last_page' => ceil($all_products / $perPage),
        ];
        $retorno = ['data' => ($products ? $products : []), 'meta' => $meta];

        Response::responseJson($retorno, 200);
    }

    /**
     * Atualiza a quantidade em estoque de um porduto incrementando ou decrementando sua quantidade
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param array $prams
     * @return void
     */
    public function updateStockQuantity($prams)
    {
        $data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        $typs = ['E', 'S'];
        $safeTypes = ['E' => 'E', 'S' => 'S'];


        if (!isset($data['id']) || (intval($data['id']) <= 0)) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'Informe o produto'], 422);
        }
        if (!isset($prams['type']) || !trim(strip_tags($prams['type']))) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'Informe o tipo de movimentação'], 422);
        } else {
            if (!in_array(trim(strip_tags($prams['type'])), $typs)) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'Tipo de movimentação inválido'], 422);
            } else {
            }
        }

        if (!isset($prams['quantity']) || !$prams['quantity']) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'A quantidade é obrigatório'], 422);
        } else {
            if (!filter_var($prams['quantity'], FILTER_VALIDATE_FLOAT) !== false) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'A quantidade deve ser do tipo double ou int'], 422);
            }
            if (doubleval($prams['quantity']) <= 0) {
                Response::responseJson(["message" => "Erro de validação", 'error' => 'A quantidade deve ser maior que 0'], 422);
            }
        }
        $product = new Products(intval($data['id']), 'vw_products');
        if ($product->id > 0) {
            if ($product->id_stock > 0) {
                $stock = new Stock($product->id_stock);
                if ($stock->id && $stock->product_id == $product->id) {
                    $stockMovements = new StockMovements();
                    $stockMovements->product_id = $product->id;
                    $stockMovements->quantity = doubleval($prams['quantity']);
                    $stockMovements->type = $safeTypes[$prams['type']];
                    $stockMovements->date = date("Y-m-d H::s");
                    $id_stockMovements = $stockMovements->save();
                    if ($id_stockMovements) {
                        if ($stockMovements->type == "E") {
                            $stock->quantity = ($stock->quantity + $stockMovements->quantity);
                        } else if ($stockMovements->type == "S") {
                            $stock->quantity = ($stock->quantity - $stockMovements->quantity);
                        } else {
                            $stockMovements->delete($id_stockMovements);
                            Response::responseJson(["message" => "Erro ao atualizar o estoque. Tente novamente mais tarde."], 500);
                        }
                        if (!$stock->update($stock->id)) {
                            $stockMovements->delete($id_stockMovements);
                            Response::responseJson(["message" => "Erro ao atualizar o estoque. Tente novamente mais tarde."], 500);
                        } else {
                            $movement = [
                                "id" => $id_stockMovements,
                                "product_id" => $stockMovements->product_id,
                                "quantity" => $stockMovements->quantity,
                                "type" => $stockMovements->type,
                                "date" => $stockMovements->date
                            ];
                            $estockData = $stock->executa_query("SELECT * FROM stock WHERE id =  " . $stock->id, 'array_one');
                            Response::responseJson([
                                "message" => "Estoque atualizado com sucesso",
                                'data' => [
                                    'stock' => $estockData,
                                    'movement' => $movement
                                ]
                            ], 201);
                        }
                    } else {
                        Response::responseJson(["message" => "Erro ao registrar a movimentação estoque não atualizado"], 500);
                    }
                } else {
                    Response::responseJson(["message" => "Estoque não encontrado para o produto informado"], 404);
                }
            } else {
                Response::responseJson(["message" => "Estoque não encontrado para o produto informado"], 404);
            }
        } else {
            Response::responseJson(["message" => "Produto não encontrado"], 404);
        }
    }

    /**
     * Busca um porduto pelo seu código sku    
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>
     * @param array $prams
     * @access public
     * @return void
     */
    public function findProductBySku()
    {
        $data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        if (!isset($data['code']) || !trim(strip_tags($data['code']))) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'Informe o código SKU'], 422);
        }
        $product = new Products();
        $productData = $product->executa_query("SELECT * FROM vw_products WHERE sku =  '" . trim(strip_tags($data['code'])) . "'", 'array_one');
        if ($productData) {
            Response::responseJson(["data" => $productData], 200);
        }
        Response::responseJson(["message" => "Produto não encontrado"], 404);
    }

    /**
     * Busca a movimentação de estoque de um porduto nos ultimos 30 dias     
     * @author Daniel Baranhuk <danielbaranhuk95@gmail.com>  
     * @access public
     * @return void
     */

    public function getLast30DaysStockMovements()
    {
        $data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
        if (!isset($data['code']) || !trim(strip_tags($data['code']))) {
            Response::responseJson(["message" => "Erro de validação", 'error' => 'Informe o código SKU'], 422);
        }
        $product = new Products();
        $productData = $product->executa_query("SELECT * FROM vw_products WHERE sku =  '" . trim(strip_tags($data['code'])) . "'", 'array_one');
        if ($productData) {

            $movements = $product->executa_query("SELECT * FROM stock_movements WHERE product_id = " . $productData['id']
                . " AND date >= '" . date("Y-m-d 00:00:00", strtotime('-30 days')) . "'" . ' ORDER BY date DESC', 'array_all');
            $productData['movements']  = ($movements ? $movements : []);

            Response::responseJson($productData, 200);
        }
        Response::responseJson(["message" => "Produto não encontrado"], 404);
    }

    public function showProductSearchBySkuView()
    {

        // alguma regra de negocio aqui antes de incluir a view

        include __DIR__ . '/../view/search_by_sku.php';
    }

    public function showLast30DaysStockMovementsView()
    {

        // alguma regra de negocio aqui antes de incluir a view

        include __DIR__ . '/../view/recent_stock_movements.php';
    }
}
