<!DOCTYPE html>
<html>
<head>
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h1>Finalizar Compra</h1>

<h1>Pedido Realizado com Sucesso!</h1>

<?php if (!empty($data) && is_array($data)) :
    // Pegando os dados fixos (endereço, total, etc.) da primeira posição
    $pedido = $data[0];
    ?>
    <h2>Resumo do Pedido #<?= htmlspecialchars($pedido['id']) ?></h2>
    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>
    <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['address']) ?></p>
    <p><strong>Status:</strong> <?= ucfirst($pedido['status']) ?></p>

    <h3>Itens do Pedido:</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
        <tr>
            <th>Produto</th>
            <th>Quantidade</th>
            <th>Preço Unitário</th>
            <th>Total</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['Produto']) ?></td>
                <td><?= (int) $item['QTD'] ?></td>
                <td>R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                <td>R$ <?= number_format($item['unit_price'] * $item['QTD'], 2, ',', '.') ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <h3>Resumo Financeiro:</h3>
    <p><strong>Descontos:</strong> R$ <?= number_format($pedido['discount'], 2, ',', '.') ?></p>
    <p><strong>Total do Pedido:</strong> <strong>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></strong></p>

    <p><a href="/">Voltar à loja</a></p>
<?php else: ?>
    <p>Pedido não encontrado.</p>
<?php endif; ?>

</body>
</html>
