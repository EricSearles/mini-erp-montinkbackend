<?php

namespace App\Services;

class MailService
{
    public function sendOrderConfirmation(string $email, int $orderId)
    {
        $subject = "Confirmação de Pedido #$orderId";
        $message = "Seu pedido foi realizado com sucesso! Número: #$orderId";
        $headers = "From: loja@exemplo.com\r\n";

        mail($email, $subject, $message, $headers);
    }
}