<!DOCTYPE html>
<html>
<head>
    <title>Pedido Finalizado</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="bg-light">

<div class="container mt-5 mb-5">
    <div class="card shadow-lg">
        <div class="card-body">
            <h1 class="text-success mb-4 text-center">Pedido realizado com sucesso!</h1>

            <?php if (!empty($data) && is_array($data)) :
                $pedido = $data[0];
            ?>
                <div class="mb-4">
                    <h4 class="mb-3">Resumo do Pedido #<?= htmlspecialchars($pedido['id']) ?></h4>
                    <p><strong>Data:</strong> <?= date('d/m/Y H:i', strtotime($pedido['created_at'])) ?></p>
                    <p><strong>E-mail:</strong> <?= htmlspecialchars($pedido['email']) ?></p>
                    <p><strong>Endereço:</strong> <?= htmlspecialchars($pedido['address']) ?></p>
                    <p><strong>Status:</strong> <?= ucfirst($pedido['status']) ?></p>
                </div>

                <div class="mb-4">
                    <h5>Itens do Pedido:</h5>
                    <table class="table table-bordered table-striped">
                        <thead class="table-light">
                        <tr>
                            <th>Produto</th>
                            <th>Descrição</th>
                            <th>Quantidade</th>
                            <th>Preço Unitário</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($data as $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['Produto']) ?></td>
                                <td><?= htmlspecialchars($item['description']) ?></td>
                                <td><?= (int) $item['QTD'] ?></td>
                                <td>R$ <?= number_format($item['unit_price'], 2, ',', '.') ?></td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="mb-4">
                    <h5>Resumo Financeiro:</h5>
                    <p><strong>Frete:</strong> R$ <?= number_format($pedido['shipping'], 2, ',', '.') ?></p>
                    <p><strong>Descontos:</strong> R$ <?= number_format($pedido['discount'], 2, ',', '.') ?></p>
                    <p class="fs-5"><strong>Total do Pedido:</strong> R$ <?= number_format($pedido['total'], 2, ',', '.') ?></p>
                </div>

                <div class="text-center">
                    <a href="/" class="btn btn-primary">Voltar à Loja</a>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    <p>Pedido não encontrado.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
