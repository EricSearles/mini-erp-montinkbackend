<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\ProductService;
use App\Services\CouponService;
use App\Services\ProductVariationService;

class CartController
{
    protected $cartService;
    protected $productService;
    protected $couponService;
    protected $variationService;

    public function __construct(
        CartService $cartService,
        ProductService $productService,
        CouponService $couponService,
        ProductVariationService $variationService
    ) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->couponService = $couponService;
        $this->variationService = $variationService;
    }

    public function add()
    {
        session_start();
        header('Content-Type: application/json');

        try {
            $input = json_decode(file_get_contents('php://input'), true);

            $productId = $input['product_id'] ?? null;
            $variationId = $input['variation_id'] ?? null;
            $quantity = $input['quantity'] ?? 1;
            $cep = $input['cep'] ?? null;
            $couponCode = $input['coupon'] ?? null;

            if (!$productId) {
                throw new \Exception('Produto não especificado');
            }

            // Verifica estoque
            if (!$this->cartService->checkStock($productId, $variationId, $quantity)) {
                throw new \Exception('Estoque insuficiente');
            }

            $product = $this->productService->getById($productId);
            $variation = $variationId ? $this->variationService->getById($variationId) : null;

            if (!$product) throw new \Exception('Produto não encontrado');

           // $unitPrice = $variation ? $variation['price'] : $product['price'];
            $unitPrice = $variation ? $variation->price : $product->price;

            // Valida cupom se fornecido
            $couponValidation = ['valid' => false];
            if ($couponCode) {
                $subtotal = $unitPrice * $quantity;
                $couponValidation = $this->couponService->validateCoupon($couponCode, $subtotal);

                if (!$couponValidation['valid']) {
                    throw new \Exception($couponValidation['message']);
                }
            }

        //    var_dump($product, $variation, $productId );

         //   die;

            // Adiciona ao carrinho na sessão
            $this->cartService->addItem([
                'product_id' => $productId,
                'product_name' => $product->name,
                'variation_id' => $variationId,
                'variation_name' => $variation->name ?? 'Padrão',
                'quantity' => $quantity,
                'price' => $unitPrice,
                'subtotal' => $unitPrice * $quantity,
                'cep' => $cep,
                'coupon' => $couponValidation['valid'] ? $couponCode : null,
                'discount' => $couponValidation['valid'] ? $couponValidation['discount'] : 0
            ]);

            echo json_encode([
                'success' => true,
                'totals' => $this->cartService->calculateTotals()
            ]);

        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function checkout() {
        session_start();

        $cartItems = $_SESSION['cart'] ?? [];
        $totals = $this->cartService->calculateTotals();

        $cartService = $this->cartService;
        $productService = $this->productService;
        $variationService = $this->variationService;

        require __DIR__ . '/../Views/cart/checkout.php';
    }
}