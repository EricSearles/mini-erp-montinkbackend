<?php
namespace App;

use App\Controllers\ProductController;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use App\Controllers\ProductVariationController;
use App\Repositories\ProductVariationRepository;
use App\Services\ProductVariationService;
use App\Repositories\StockRepository;

require_once __DIR__ . '/../config/database.php';

$repository = new ProductRepository();
$service = new ProductService($repository);

$stockRepository = new StockRepository();
$variationRepository = new ProductVariationRepository();
$variationService = new ProductVariationService($variationRepository, $stockRepository);
$variationController = new ProductVariationController($variationService);

$controller = new ProductController($service, $variationService);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

//var_dump($uri, $method); // Debugging line to check the URI and method
if ($uri === '/') {
    $controller->index();
}
if ($uri === '/products') {
    $controller->create();
}
if ($uri === '/products/edit' && $method === 'GET') {
    $controller->edit($_GET['id']);
} elseif ($uri === '/products/store' && $method === 'POST') {
    $controller->store();
} elseif ($uri === '/products/edit' && $method === 'GET'  && isset($_GET['id'])) {
    $controller->edit($_GET['id']);
} elseif ($uri === '/products/update' && $method === 'POST' && isset($_GET['id'])) {
    $controller->update($_GET['id']);
} elseif ($uri === '/products/delete' && $method === 'GET' && isset($_GET['id'])) {
    $controller->delete($_GET['id']);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada 1']);
}

if ($uri === '/variations/store' && $method === 'POST') {
    $variationController->store();
} elseif ($uri === '/variations/delete' && $method === 'GET' && isset($_GET['id'], $_GET['product_id'])) {
    $variationController->delete();
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada']);
}
