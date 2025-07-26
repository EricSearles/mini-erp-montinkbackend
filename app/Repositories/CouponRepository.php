<?php

namespace App\Repositories;

use App\Core\Connection;
use App\Models\Coupon;
use PDO;

class CouponRepository
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = Connection::connect();
    }

    public function findByCode(string $code): ?Coupon
    {
        $stmt = $this->pdo->prepare("SELECT * FROM coupons WHERE code = ?");
        $stmt->execute([$code]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        return $data ? new Coupon($data) : null;
    }

    public function create(Coupon $coupon): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO coupons (code, discount, type, min_value, expires_at)
            VALUES (?, ?, ?, ?, ?)
        ");

        return $stmt->execute([
            $coupon->code,
            $coupon->discount,
            $coupon->type,
            $coupon->min_value,
            $coupon->expires_at
        ]);
    }
}