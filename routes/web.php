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
use App\Controllers\OrderController;
use App\Repositories\OrderRepository;
use App\Models\Order;
use App\Services\OrderService;
use App\Services\MailService;
use App\Services\WebhookService;


require_once __DIR__ . '/../config/database.php';

$productRepository = new ProductRepository();
$productService = new ProductService($productRepository);

$stockRepository = new StockRepository();
$variationRepository = new ProductVariationRepository();
$variationService = new ProductVariationService($variationRepository, $stockRepository);
$variationController = new ProductVariationController($variationService);

// Order
$orderRepository = new OrderRepository();
$mailService = new MailService();
$webhookService = new WebhookService();
$orderService = new OrderService($orderRepository, $mailService, $webhookService);
$orderController = new OrderController($orderService);

$couponRepository = new CouponRepository();
$couponService = new CouponService($couponRepository);
$cartService = new CartService($productRepository, $variationRepository, $stockRepository, $couponService);
$cartController = new CartController($cartService, $productService, $couponService, $variationService, $orderService);
$controller = new ProductController($productService, $variationService);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

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
} elseif ($uri === '/cart/remove' && $method === 'POST') {
    $cartController->removeItem();
} elseif (preg_match('/^\/products\/show\/(\d+)$/', $uri, $matches) && $method === 'GET') {
    $controller->show($matches[1]);
} elseif ($uri === '/cart/finalize' && $method === 'POST') {
    $cartController->finalize();
} elseif ($uri === '/orders' && $method === 'GET') {
    $orderController->index();
} elseif ($uri === '/orders/store' && $method === 'POST') {
    $orderController->store();
} elseif ($uri === '/orders/success' && $method === 'GET' && isset($_GET['id'])) {
    $orderController->purchaseFinished($_GET['id']);
}else {
    http_response_code(404);
    echo json_encode(['message' => 'Rota não encontrada']);
}
