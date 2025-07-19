<?php
require '../../config/connection.php';
require '../../app/Repositories/ProductRepository.php';

use App\Repositories\ProductRepository;

$id = $_GET['id'] ?? null;
if ($id) {
    $repo = new ProductRepository($pdo);
    $repo->delete($id);
}
header('Location: index.php');
exit;
