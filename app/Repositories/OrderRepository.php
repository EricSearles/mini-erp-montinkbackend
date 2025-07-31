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
            INSERT INTO orders (email, address, total, shipping, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        $stmt->execute([
            $order->email,
            $order->address,
            $order->total,
            $order->shipping,
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
                   p.description,
                   oi.quantity as 'QTD', 
                   oi.unit_price,
                   o.discount,
                   o.total
            FROM orders o
            LEFT JOIN order_items oi ON oi.order_id = o.id
            LEFT JOIN products p ON p.id = oi.product_id
            WHERE o.id = ?
        ");
        $stmt->execute([$orderId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $orderId): void
    {
        // Deleta primeiro os itens vinculados
        $stmt = $this->pdo->prepare("DELETE FROM order_items WHERE order_id = ?");
        $stmt->execute([$orderId]);

        // Depois deleta o pedido
        $stmt = $this->pdo->prepare("DELETE FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
    }

    public function updateStatus(int $orderId, string $status): void
    {
        $stmt = $this->pdo->prepare("UPDATE orders SET status = ? WHERE id = ?");
        $stmt->execute([$status, $orderId]);
    }
}