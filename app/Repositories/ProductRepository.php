<?php

namespace App\Repositories;

use App\Models\Product;
use App\Core\Connection;
use PDO;

class ProductRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::connect();
    }

    public function all()
    {
        $stmt = $this->pdo->query("SELECT * FROM products");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        return $data ? new Product($data) : null;
    }

    public function create(Product $product)
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, description, price, stock) VALUES (?, ?, ?, ?)");
        return $stmt->execute([
            $product->name,
            $product->description,
            $product->price,
            $product->stock
        ]);
    }

    public function update(Product $product)
    {
        $stmt = $this->pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
        return $stmt->execute([
            $product->name,
            $product->description,
            $product->price,
            $product->stock,
            $product->id
        ]);
    }

    public function delete($id)
    {
        var_dump("AAA", $id);
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
    }
}