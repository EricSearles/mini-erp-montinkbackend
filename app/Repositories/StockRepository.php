<?php

namespace App\Repositories;

use App\Core\Connection;
use PDO;

class StockRepository {
    private $pdo;

    public function __construct() {
        $this->pdo = Connection::connect();
    }

    public function create(array $data) {
        $stmt = $this->pdo->prepare("INSERT INTO stocks (variation_id, quantity) VALUES (?, ?)");
        return $stmt->execute([$data['variation_id'], $data['quantity']]);
    }

    public function deleteByVariationId($variationId) {
        $stmt = $this->pdo->prepare("DELETE FROM stocks WHERE variation_id = ?");
        return $stmt->execute([$variationId]);
    }

    public function updateByVariationId($variationId, $quantity) {
        $stmt = $this->pdo->prepare("UPDATE stocks SET quantity = ? WHERE variation_id = ?");
        return $stmt->execute([$quantity, $variationId]);
    }

    public function getByVariationId($variationId) {
        $stmt = $this->pdo->prepare("SELECT * FROM stocks WHERE variation_id = ?");
        $stmt->execute([$variationId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}