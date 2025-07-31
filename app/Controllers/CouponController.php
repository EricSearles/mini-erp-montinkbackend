<?php

namespace App\Controllers;

use App\Services\CouponService;
use App\Models\Coupon;

class CouponController
{
    protected $couponService;

    public function __construct(CouponService $couponService)
    {
        $this->couponService = $couponService;
    }

    public function index()
    {
        session_start();
        $coupons = $this->couponService->getAll();
        require __DIR__ . '/../Views/coupons/index.php';
    }

    public function store()
    {
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'code' => $_POST['code'] ?? '',
                'discount' => floatval($_POST['discount'] ?? 0),
                'type' => $_POST['type'] ?? 'percent',
                'min_value' => floatval($_POST['min_value'] ?? 0),
                'expires_at' => $_POST['expires_at'] ?? null,
            ];

            $coupon = new Coupon($data);
            $this->couponService->create($coupon);

            header('Location: /coupons');
            exit;
        }
    }
}