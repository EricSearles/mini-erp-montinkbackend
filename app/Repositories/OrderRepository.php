<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Core\Connection;
use PDO;

class OrderRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::connect();
    }

    public function save(Order $order): int
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO orders (address, total, status, created_at)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
           // $order->user_id,
            $order->address,
            $order->total,
            $order->status,
            $order->created_at
        ]);

        $orderId = $this->pdo->lastInsertId();

        foreach ($order->items as $item) {
            $this->saveItem($orderId, $item);
        }

        return $orderId;
    }

    private function saveItem(int $orderId, OrderItem $item)
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO order_items (order_id, product_id, variation_id, quantity, unit_price)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $orderId,
            $item->product_id,
            $item->variation_id,
            $item->quantity,
            $item->price
        ]);
    }

    public function getByOrderId($orderId) {
        $stmt = $this->pdo->prepare("
            SELECT o.*, 
                   p.name as 'Produto',
                   oi.quantity as 'QTD', 
                   oi.unit_price
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE o.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}