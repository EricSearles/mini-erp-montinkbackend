<?php

namespace App\Controllers;

use App\Services\OrderService;

class OrderController
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index($orderId)
    {
        $data = $this->orderService->getOrderWithItems($orderId);

        $order = $data['order'];
        $items = $data['items'];

        require_once __DIR__ . '/../Views/Order/Index.php';
    }

    public function purchaseFinished($id)
    {
        $data = $this->orderService->getOrder($id);

        require __DIR__ . '/../Views/order/success.php';
    }


}