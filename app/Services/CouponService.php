<?php

namespace App\Services;

use App\Repositories\CouponRepository;
use App\Models\Coupon;

class CouponService
{
    protected $repository;

    public function __construct(CouponRepository $repository)
    {
        $this->repository = $repository;
    }

    public function validateCoupon(string $code, float $subtotal): array
    {
        $coupon = $this->repository->findByCode($code);

        if (!$coupon) {
            return ['valid' => false, 'message' => 'Cupom inválido'];
        }

        if (!$coupon->isValidForSubtotal($subtotal)) {
            return ['valid' => false, 'message' => $subtotal < $coupon->min_value ?
                'Valor mínimo não atingido' : 'Cupom expirado'];
        }

        return [
            'valid' => true,
            'discount' => $coupon->calculateDiscount($subtotal),
            'coupon' => $coupon
        ];
    }

    // Método para aplicar o cupom no carrinho
    public function applyCouponToCart(array $cart, Coupon $coupon): array
    {
        $subtotal = array_reduce($cart, function($sum, $item) {
            return $sum + ($item['price'] * $item['quantity']);
        }, 0);

        $discount = $coupon->calculateDiscount($subtotal);

        return [
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $subtotal - $discount
        ];
    }
}