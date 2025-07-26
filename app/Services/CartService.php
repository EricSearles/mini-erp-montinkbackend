<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Repositories\ProductVariationRepository;
use App\Repositories\StockRepository;
use App\Services\CouponService;

class CartService
{
    protected $productRepository;
    protected $variationRepository;
    protected $stockRepository;
    protected $couponService;

    public function __construct(
        ProductRepository $productRepository,
        ProductVariationRepository $variationRepository,
        StockRepository $stockRepository,
        CouponService $couponService
    ) {
        $this->productRepository = $productRepository;
        $this->variationRepository = $variationRepository;
        $this->stockRepository = $stockRepository;
        $this->couponService = $couponService;
    }

    private function startSessionIfNeeded()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function addItem(array $item)
    {
        $this->startSessionIfNeeded();
        $_SESSION['cart'][] = $item;
    }

    public function checkStock($productId, $variationId, $quantity)
    {
        if ($variationId) {
            $stock = $this->stockRepository->getByVariationId($variationId);
            return $stock['quantity'] >= $quantity;
        } else {
            $product = $this->productRepository->find($productId);
            return $product->stock >= $quantity;
        }
    }

    public function calculateTotals(): array
    {
        $this->startSessionIfNeeded();

        $subtotal = $this->calculateSubtotal();
        $shipping = $this->calculateShipping($subtotal);
        $discount = $_SESSION['cart_discount'] ?? 0;

        return [
            'subtotal' => $subtotal,
            'shipping' => $shipping,
            'discount' => $discount,
            'total' => max(0, $subtotal + $shipping - $discount)
        ];
    }

    private function calculateSubtotal(): float
    {
        $this->startSessionIfNeeded();

        return array_reduce($_SESSION['cart'] ?? [], function($sum, $item) {
            $product = $this->productRepository->find($item['product_id']);
            return $sum + ($product->price * $item['quantity']);
        }, 0);
    }

    private function calculateShipping($subtotal)
    {
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            return 15;
        } elseif ($subtotal > 200) {
            return 0;
        }
        return 20;
    }

    public function applyCoupon(string $couponCode): array
    {
        $this->startSessionIfNeeded();

        $subtotal = $this->calculateSubtotal();
        $coupon = $this->couponService->validateCoupon($couponCode, $subtotal);

        if ($coupon['valid']) {
            $_SESSION['cart_discount'] = $coupon['discount'];
            $_SESSION['cart_coupon'] = $couponCode;
        }

        return $this->calculateTotals();
    }
}
