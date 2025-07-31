<?php
//error_reporting(E_ALL);
//ini_set('display_errors', 1);

$cartItems = $_SESSION['cart'] ?? [];
$totals = $cartService->calculateTotals();
$last = count($cartItems) - 1;
$lastItem = $cartItems[$last] ?? null;
$preFilledAddress = $lastItem['address'] ?? '';
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
    <a href="/" class="btn btn-primary">Voltar aos produtos</a>
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
                    <?php foreach ($cartItems as $index => $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['product_name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['variation_name'] ?? '') ?></td>
                            <td><?= intval($item['quantity'] ?? 0) ?></td>
                            <td>R$ <?= number_format(floatval($item['price'] ?? 0), 2, ',', '.') ?></td>
                            <td>R$ <?= number_format(floatval($item['subtotal'] ?? 0), 2, ',', '.') ?></td>
                            <td>
                                <form method="post" action="/cart/remove" onsubmit="return confirm('Remover este item?')">
                                    <input type="hidden" name="index" value="<?= $index ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remover</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <tr>
                    <td><a href="/" class="btn btn-primary">Continuar comprando</a></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="col-md-4">
            <h3>Resumo</h3>
            <div class="card">
                <div class="card-body">
                    <h5>Subtotal: R$ <?= number_format(floatval($totals['subtotal'] ?? 0), 2, ',', '.') ?></h5>
                    <h5>Frete: R$ <?= number_format(floatval($totals['shipping'] ?? 0), 2, ',', '.') ?></h5>
                    <?php if (($totals['discount'] ?? 0) > 0): ?>
                        <h5>Desconto: -R$ <?= number_format(floatval($totals['discount'] ?? 0), 2, ',', '.') ?></h5>
                    <?php endif; ?>
                    <?php if (!empty($cartItems[0]['coupon'])): ?>
                        <div class="alert alert-success">
                            Cupom "<?php echo $cartItems[0]['coupon']; ?>" aplicado com sucesso!
                        </div>
                    <?php endif; ?>
                    <hr>
                    <h4>Total: R$ <?= number_format(floatval($totals['total'] ?? 0), 2, ',', '.') ?></h4>

                    <form method="post" action="/cart/finalize" class="mt-3">
                        <h2>Endereço de Entrega</h2>
                        <div class="mb-3">
                            <label>E-mail para confirmação do pedido:</label>
                            <input type="email" class="form-control" name="email" id="email" required>
                        </div>

                        <div class="mb-3">
                            <label>Endereço</label>
                            <input type="text" name="address" class="form-control" value="<?= htmlspecialchars($preFilledAddress) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label>Número</label>
                            <input type="text" name="number" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label>Complemento</label>
                            <input type="text" name="complement" class="form-control">
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