<?php

namespace App\Models;

class Coupon
{
    public $id;
    public $code;
    public $discount;
    public $type; // 'percentage' ou 'fixed'
    public $min_value;
    public $expires_at;
    public $created_at;
    public $updated_at;

    public function __construct(array $data = [])
    {
        $this->id = $data['id'] ?? null;
        $this->code = $data['code'] ?? '';
        $this->discount = $data['discount'] ?? 0;
        $this->type = $data['type'] ?? 'fixed';
        $this->min_value = $data['min_value'] ?? 0;
        $this->expires_at = $data['expires_at'] ?? null;
        $this->created_at = $data['created_at'] ?? null;
        $this->updated_at = $data['updated_at'] ?? null;
    }

    public function isValidForSubtotal(float $subtotal): bool
    {
        if ($this->expires_at && strtotime($this->expires_at) < time()) {
            return false;
        }

        return $subtotal >= $this->min_value;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            return $subtotal * ($this->discount / 100);
        }

        return min($this->discount, $subtotal);
    }
}