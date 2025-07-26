<?php

namespace App\Controllers;

use App\Services\ProductService;
use App\Services\ProductVariationService;

class ProductController
{
    protected $service;
    private $variationService;

    public function __construct(ProductService $service, ProductVariationService $variationService)
    {
        $this->service = $service;
        $this->variationService = $variationService;
    }

    public function index()
    {
        //header('Content-Type: application/json');
       // echo json_encode($this->service->getAll());
        $products = $this->service->getAll();

        require __DIR__ . '/../Views/products/index.php';
    }

    public function create()
    {
        $products = $this->service->getAll();

        require __DIR__ . '/../Views/products/create.php';
    }

    public function show($id)
    {
        header('Content-Type: application/json');

        try {
            $product = $this->service->getById($id);

            if (!$product) {
                throw new \Exception('Produto não encontrado');
            }

            // Adiciona as variações ao produto
            $productData = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float)$product->price,
                'stock' => (int)$product->stock,
                'variations' => $this->variationService->getByProductId($id)
            ];

            echo json_encode($productData);

        } catch (\Exception $e) {
            http_response_code(404);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function store() {
        //  $data = json_decode(file_get_contents('php://input'), true);
        //  $this->service->create($data);
        //   echo json_encode(['message' => 'Produto criado com sucesso']);

        // Primeiro cria o produto
        $productData = [
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'] ?? 0
        ];

        $productId = $this->service->create($productData);

        // Depois cria as variações se existirem
        if (isset($_POST['variation_name']) && is_array($_POST['variation_name'])) {
            foreach ($_POST['variation_name'] as $index => $name) {
                $quantity = $_POST['variation_stock'][$index] ?? 0;
                $this->variationService->addVariation($productId, $name, $quantity);
            }
        }

        header("Location: /products");
        exit;
    }

    public function edit($id)
    {
        $product = $this->service->getById($id);
        $variations = $this->variationService->getByProductId($id);
        // if (!$product) {
        //     http_response_code(404);
        //     echo json_encode(['message' => 'Produto não encontrado']);
        //     return;
        // }       
        require __DIR__ . '/../Views/products/edit.php';
    }

    public function update($id) {
//        $data = json_decode(file_get_contents('php://input'), true);
//        $this->service->update($id, $data);
//        echo json_encode(['message' => 'Produto atualizado com sucesso']);
        $productData = [
           // 'id' => $id,
            'name' => $_POST['name'],
            'price' => $_POST['price'],
            'stock' => $_POST['stock'] ?? 0
        ];

        $this->service->update($id, $productData);

        // Atualizar variações existentes ou adicionar novas
        if (isset($_POST['variation_name'])) {
            foreach ($_POST['variation_name'] as $index => $name) {
                $quantity = $_POST['variation_stock'][$index] ?? 0;

                if (isset($_POST['variation_id'][$index])) {
                    // Atualizar variação existente
                    $this->variationService->updateVariationStock(
                        $_POST['variation_id'][$index],
                        $quantity
                    );
                } else {
                    // Adicionar nova variação
                    $this->variationService->addVariation($id, $name, $quantity);
                }
            }
        }

        header("Location: /products/edit?id=" . $id);
        exit;
    }

    public function delete($id)
    {
        var_dump($id);
        $this->service->delete($id);
        header("Location: /products");
        echo json_encode(['message' => 'Produto removido com sucesso']);
    }
}