<?php
require 'src/config/config.php';

use src\controllers\ProductController;
use src\Core\Router\Router;



Router::get('products/sku', [ProductController::class, 'showProductSearchBySkuView']);
Router::get('products/stock-movements/recent', [ProductController::class, 'showLast30DaysStockMovementsView']);

Router::post('api/products', [ProductController::class, 'store']);
Router::get('api/products', [ProductController::class, 'index']);
Router::get('api/products/sku', [ProductController::class, 'findProductBySku']);
Router::get('api/products/stock-movements', [ProductController::class, 'getLast30DaysStockMovements']);
Router::put('api/products/update-stock', [ProductController::class, 'updateStockQuantity']);


Router::dispatch("bitfuel-test/");
