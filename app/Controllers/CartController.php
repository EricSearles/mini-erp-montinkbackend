<?php

namespace App\Controllers;

use App\Services\CartService;
use App\Services\ProductService;
use App\Services\CouponService;
use App\Services\ProductVariationService;
use App\Services\OrderService;

class CartController
{
    protected $cartService;
    protected $productService;
    protected $couponService;
    protected $variationService;
    protected $orderService;

    public function __construct(
        CartService $cartService,
        ProductService $productService,
        CouponService $couponService,
        ProductVariationService $variationService,
        OrderService $orderService
    ) {
        $this->cartService = $cartService;
        $this->productService = $productService;
        $this->couponService = $couponService;
        $this->variationService = $variationService;
        $this->orderService = $orderService;
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
            $address = $input['address'] ?? null;
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

            $unitPrice = $variation ? $variation->price : $product->price;

            // Valida cupom
            $couponValidation = ['valid' => false];
            if ($couponCode) {
                $subtotal = $unitPrice * $quantity;
                $couponValidation = $this->couponService->validateCoupon($couponCode, $subtotal);

                if (!$couponValidation['valid']) {
                    throw new \Exception($couponValidation['message']);
                }
            }

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
                'address' => $address,
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

    public function removeItem()
    {
        session_start();

        $index = $_POST['index'] ?? null;

        if ($index !== null && isset($_SESSION['cart'][$index])) {
            unset($_SESSION['cart'][$index]);
            $_SESSION['cart'] = array_values($_SESSION['cart']); // Reindexar
        }

        header("Location: /cart/checkout");
        exit;
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

    public function finalize()
    {
        session_start();

        $cartItems = $_SESSION['cart'] ?? [];
        $totals = $this->cartService->calculateTotals();

        $address = $_POST['address'] ?? '';
        $number = $_POST['number'] ?? '';
        $complement = $_POST['complement'] ?? '';

        $fullAddress = $address;
        if ($number) $fullAddress .= ", Nº $number";
        if ($complement) $fullAddress .= ", $complement";

        $user = [
            'id' => $_SESSION['user_id'] ?? 1,
            'email' => $_SESSION['user_email'] ?? 'cliente@teste.com'
        ];

        $orderId = $this->orderService->create($user, $fullAddress, $cartItems, $totals['total']);
        unset($_SESSION['cart'], $_SESSION['cart_discount'], $_SESSION['cart_coupon']);
        header("Location: /orders/success?id=" . $orderId);
        exit;
    }


}