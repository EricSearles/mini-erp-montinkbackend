<?php

namespace App\Services;

use App\Models\ProductVariation;
use App\Models\Stock;
use App\Repositories\StockRepository;
use App\Repositories\ProductVariationRepository;


class ProductVariationService {
    protected $variationRepository;
    protected $stockRepository;

    public function __construct(ProductVariationRepository $variationRepository, StockRepository $stockRepository) {
        $this->variationRepository = $variationRepository;
        $this->stockRepository = $stockRepository;
    }

    public function addVariation($productId, $name, $quantity) {
        // Cria a variação
        $variationId = $this->variationRepository->create([
            'product_id' => $productId,
            'name' => $name
        ]);

        // Cria o estoque para a variação
        if ($variationId) {
            $this->stockRepository->create([
                'variation_id' => $variationId,
                'quantity' => $quantity
            ]);
        }

        return $variationId;
    }

    public function getById($id)
    {
        return $this->variationRepository->find($id);
    }

    public function deleteVariation($variationId) {
        // Remove o estoque primeiro
        $this->stockRepository->deleteByVariationId($variationId);
        // Depois remove a variação
        return $this->variationRepository->delete($variationId);
    }

    public function getByProductId($productId) {
        return $this->variationRepository->getByProductId($productId);
    }

    public function updateVariationStock($variationId, $quantity) {
        return $this->stockRepository->updateByVariationId($variationId, $quantity);
    }
}