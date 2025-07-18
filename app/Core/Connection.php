<?php

namespace App\Core;

use PDO;
use PDOException;

class Connection
{
    public static function connect(): PDO
    {
        $config = require __DIR__ . '/../../config/database.php';

        try {
            $pdo = new PDO(
                "mysql:host={$config['db_host']};dbname={$config['db_name']};charset=utf8",
                $config['db_user'],
                $config['db_password']
            );

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;

        } catch (PDOException $e) {
            die("Erro de conexão: " . $e->getMessage());
        }
    }
}
