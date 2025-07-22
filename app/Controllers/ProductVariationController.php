<?php

namespace App\Controllers;

use App\Services\ProductVariationService;

class ProductVariationController {
    protected $service;

    public function __construct(ProductVariationService $variationService) {
        $this->service = $variationService;
    }

    public function store() {
        $productId = $_POST['product_id'] ?? null;
        $name = $_POST['name'] ?? '';
        $quantity = $_POST['quantity'] ?? 0;

        if (!$productId || !$name) {
            echo "Nome da variação e produto são obrigatórios.";
            return;
        }

        $this->service->addVariation($productId, $name, $quantity);
        header("Location: /products/edit?id=" . $productId);
        exit;
    }

    public function delete() {
        $variationId = $_GET['id'] ?? null;
        $productId = $_GET['product_id'] ?? null;

        if ($variationId && $productId) {
            $this->service->deleteVariation($variationId);
            header("Location: /products/edit?id=" . $productId);
            exit;
        }

        echo "Variação ou Produto não informados.";
    }
}