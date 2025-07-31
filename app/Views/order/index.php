<?php
/**
 * @var array $order
 * @var array $items
 */
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Pedido #<?= htmlspecialchars($order['id']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
<h1 class="mb-4">Resumo do Pedido #<?= htmlspecialchars($order['id']) ?></h1>

<section class="mb-4">
    <h5>Informações do Pedido</h5>
    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($order['created_at'])) ?></p>
    <p><strong>Endereço:</strong><br> <?= nl2br(htmlspecialchars($order['address'])) ?></p>
    <p><strong>Total:</strong> R$ <?= number_format($order['total'], 2, ',', '.') ?></p>
</section>

<section>
    <h5>Itens do Pedido</h5>
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Produto</th>
            <th>Variação</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Subtotal</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['product_name']) ?></td>
                <td><?= htmlspecialchars($item['variation_name']) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>R$ <?= number_format($item['price'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($item['subtotal'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</section>

<div class="mt-4">
    <a href="/products" class="btn btn-secondary">Voltar à Loja</a>
</div>
</body>
</html>
