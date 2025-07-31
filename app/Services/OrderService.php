<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\OrderRepository;

class OrderService
{
    private $orderRepository;
    private $mailer;

    public function __construct(OrderRepository $orderRepository, MailService $mailer)
    {
        $this->orderRepository = $orderRepository;
        $this->mailer = $mailer;
    }

    public function create(array $userData, string $address, array $cartItems, float $total): int
    {
//        var_dump("USEER: ", $userData);
//        var_dump("ADDRESS: ", $address);
//        var_dump("CART: ", $cartItems);
//        var_dump("TOTAL: ", $total);

        $order = new Order([
           // 'user_id' => $userData['id'],
            'address' => $address,
            'total' => $total,
            'status' => 'pending',
            'created_at' =>"2025-07-28"
        ]);

        foreach ($cartItems as $item) {
            $order->addItem(new OrderItem($item));
        }

        $orderId = $this->orderRepository->save($order);

        $this->mailer->sendOrderConfirmation($userData['email'], $orderId);
        $this->sendWebhook($orderId);

        return $orderId;
    }

    private function sendWebhook(int $orderId)
    {
        $payload = json_encode(['order_id' => $orderId]);

        $ch = curl_init('https://meusite.com/webhook/novo-pedido');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_exec($ch);
        curl_close($ch);
    }

    public function getOrder($orderId)
    {
        $order = $this->orderRepository->getByOrderId($orderId);

        return $order;
    }
}