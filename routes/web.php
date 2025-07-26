<?php
namespace App;

use App\Controllers\ProductController;
use App\Repositories\ProductRepository;
use App\Services\ProductService;
use App\Controllers\ProductVariationController;
use App\Repositories\ProductVariationRepository;
use App\Services\ProductVariationService;
use App\Repositories\StockRepository;
use App\Services\CartService;
use App\Services\CouponService;
use App\Controllers\CartController;
use App\Repositories\CouponRepository;

require_once __DIR__ . '/../config/database.php';

$productRepository = new ProductRepository();
$productService = new ProductService($productRepository);

$stockRepository = new StockRepository();
$variationRepository = new ProductVariationRepository();
$variationService = new ProductVariationService($variationRepository, $stockRepository);
$variationController = new ProductVariationController($variationService);


$couponRepository = new CouponRepository();
$couponService = new CouponService($couponRepository);
$cartService = new CartService($productRepository, $variationRepository, $stockRepository, $couponService);
$cartController = new CartController($cartService, $productService, $couponService, $variationService);

$controller = new ProductController($productService, $variationService);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

//var_dump($uri, $method); // Debugging line to check the URI and method
if ($uri === '/') {
    $controller->index();
} elseif ($uri === '/products' && $method === 'GET') {
    $controller->create();
} elseif ($uri === '/products/edit' && $method === 'GET' && isset($_GET['id'])) {
    $controller->edit($_GET['id']);
} elseif ($uri === '/products/store' && $method === 'POST') {
    $controller->store();
} elseif ($uri === '/products/update' && $method === 'POST' && isset($_GET['id'])) {
    $controller->update($_GET['id']);
} elseif ($uri === '/products/delete' && $method === 'GET' && isset($_GET['id'])) {
    $controller->delete($_GET['id']);
    $variationController->store();
} elseif ($uri === '/variations/delete' && $method === 'GET' && isset($_GET['id'], $_GET['product_id'])) {
    $variationController->delete();
} elseif ($uri === '/cart/add' && $method === 'POST') {
    $cartController->add();
} elseif ($uri === '/cart/checkout' && $method === 'GET') {
    $cartController->checkout();
} elseif (preg_match('/^\/products\/show\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller->show($matches[1]);
} else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada']);
}
