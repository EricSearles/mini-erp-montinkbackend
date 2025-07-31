<!DOCTYPE html>
<html>
<head>
    <title>Mini ERP - Cupons</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h1>Gerenciar Cupons</h1>

<form method="post" action="/coupons/store" class="mt-4">
    <div class="row g-3">
        <div class="col-md-3">
            <label class="form-label">Código</label>
            <input type="text" name="code" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Desconto</label>
            <input type="number" name="discount" step="0.01" class="form-control" required>
        </div>

        <div class="col-md-2">
            <label class="form-label">Tipo</label>
            <select name="type" class="form-select">
                <option value="percent">%</option>
                <option value="fixed">R$</option>
            </select>
        </div>

        <div class="col-md-2">
            <label class="form-label">Valor Mínimo</label>
            <input type="number" name="min_value" step="0.01" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Expira em</label>
            <input type="date" name="expires_at" class="form-control">
        </div>
    </div>

    <div class="mt-3">
        <button type="submit" class="btn btn-success">Salvar Cupom</button>
    </div>
</form>

<h2 class="mt-5">Cupons Cadastrados</h2>
<table class="table table-striped">
    <thead>
    <tr>
        <th>Código</th>
        <th>Desconto</th>
        <th>Tipo</th>
        <th>Valor Mínimo</th>
        <th>Expiração</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($coupons as $coupon): ?>
        <tr>
            <td><?= htmlspecialchars($coupon->code) ?></td>
            <td><?= $coupon->discount ?></td>
            <td><?= $coupon->type === 'percent' ? '%' : 'R$' ?></td>
            <td><?= $coupon->min_value ?></td>
            <td><?= $coupon->expires_at ?></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<a href="/" class="btn btn-secondary mt-3">Voltar</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
