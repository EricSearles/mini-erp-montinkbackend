<?php

namespace App\Models;

class Order
{
    public $id;
    public $user_id;
    public $address;
    public $total;
    public $status;
    public $created_at;

    /** @var OrderItem[] */
    public $items = [];

    public function __construct(array $data = [])
    {
        $this->id         = $data['id'] ?? null;
        $this->user_id    = $data['user_id'] ?? null;
        $this->address    = $data['address'] ?? '';
        $this->total      = $data['total'] ?? 0;
        $this->status     = $data['status'] ?? 'pending';
        $this->created_at = $data['created_at'] ?? date('Y-m-d H:i:s');
    }

    public function addItem(OrderItem $item)
    {
        $this->items[] = $item;
    }
}