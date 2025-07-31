<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class WebhookService
{
    private $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    public function handleStatusWebhook()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['order_id']) || !isset($input['status'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Campos obrigatórios ausentes']);
            return;
        }

        $orderId = $input['order_id'];
        $status = strtolower($input['status']);

        if ($status === 'cancelado') {
            $this->orderRepository->delete($orderId);
        } else {
            $this->orderRepository->updateStatus($orderId, $status);
        }

        echo json_encode(['success' => true]);
    }
}
