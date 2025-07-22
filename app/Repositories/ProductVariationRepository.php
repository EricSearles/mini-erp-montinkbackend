<?php

namespace App\Repositories;

use App\Core\Connection;
use App\Models\ProductVariation;
use PDO;

class ProductVariationRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::connect();
    }

    public function create(array $data) {
        $stmt = $this->pdo->prepare("INSERT INTO product_variations (product_id, name) VALUES (?, ?)");
        $stmt->execute([$data['product_id'], $data['name']]);
        return $this->pdo->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM product_variations WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByProductId($productId) {
        $stmt = $this->pdo->prepare("
            SELECT v.*, s.quantity 
            FROM product_variations v
            LEFT JOIN stocks s ON s.variation_id = v.id
            WHERE v.product_id = ?
        ");
        $stmt->execute([$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}