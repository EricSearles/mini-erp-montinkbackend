<?php

namespace App\Models;

class Product
{
    public $id;
    public $name;
    public $description;
    public $price;
    public $stock;

    public function __construct($data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->description = $data['description'] ?? '';
        $this->price = $data['price'] ?? 0.0;
        $this->stock = $data['stock'] ?? 0;
    }
}