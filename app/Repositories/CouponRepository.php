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

    public function getAll(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM coupons ORDER BY created_at DESC");
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($row) => new Coupon($row), $data);
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