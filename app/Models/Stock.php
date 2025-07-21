<?php

namespace App\Models;

class Stock {
    public $id;
    public $variation_id;
    public $quantity;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->variation_id = $data['variation_id'] ?? null;
        $this->quantity = $data['quantity'] ?? 0;
    }
}