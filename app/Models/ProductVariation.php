<?php

namespace App\Models;

class ProductVariation
{
    public $id;
    public $product_id;
    public $name;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->product_id = $data['product_id'];
        $this->name = $data['name'];
    }
}