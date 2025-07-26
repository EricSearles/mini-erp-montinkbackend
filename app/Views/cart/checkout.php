<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

// NÃO precisa de session_start() se já está ativo no controller
$cartItems = $_SESSION['cart'] ?? [];
$totals = $cartService->calculateTotals();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Finalizar Compra</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h1>Finalizar Compra</h1>

<?php if (empty($cartItems)): ?>
    <div class="alert alert-warning">Seu carrinho está vazio</div>
    <a href="/products" class="btn btn-primary">Voltar aos produtos</a>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <h3>Itens do Carrinho</h3>
            <table class="table">
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
                <?php foreach ($cartItems as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['product_name'] ?? '') ?></td>
                        <td><?= htmlspecialchars($item['variation_name'] ?? '') ?></td>
                        <td><?= intval($item['quantity'] ?? 0) ?></td>
                        <td>R$ <?= number_format(floatval($item['price'] ?? 0), 2, ',', '.') ?></td>
                        <td>R$ <?= number_format(floatval($item['subtotal'] ?? 0), 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach ?>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h3>Resumo</h3>
            <div class="card">
                <div class="card-body">
                    <h5>Subtotal: R$ <?= number_format(floatval($totals['subtotal'] ?? 0), 2, ',', '.') ?></h5>

                    <?php if (($totals['discount'] ?? 0) > 0): ?>
                        <h5>Desconto: -R$ <?= number_format(floatval($totals['discount'] ?? 0), 2, ',', '.') ?></h5>
                    <?php endif; ?>

                    <h5>Frete: R$ <?= number_format(floatval($totals['shipping'] ?? 0), 2, ',', '.') ?></h5>
                    <hr>
                    <h4>Total: R$ <?= number_format(floatval($totals['total'] ?? 0), 2, ',', '.') ?></h4>

                    <form method="post" action="/cart/finalize" class="mt-3">
                        <div class="mb-3">
                            <label>Endereço Completo</label>
                            <textarea class="form-control" name="address" required></textarea>
                        </div>
                        <button type="submit" class="btn btn-success w-100">Confirmar Pedido</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
</body>
</html>