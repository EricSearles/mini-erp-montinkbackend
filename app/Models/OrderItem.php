<?php

namespace App\Models;

class OrderItem
{
    public $product_id;
    public $variation_id;
    public $quantity;
    public $price;

    public function __construct(array $data)
    {
        $this->product_id   = $data['product_id'] ?? null;
        $this->variation_id = $data['variation_id'] ?? null;
        $this->quantity     = $data['quantity'] ?? 0;
        $this->price        = $data['price'] ?? 0;
    }
}