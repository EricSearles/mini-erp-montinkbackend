<!DOCTYPE html>
<html>
<head>
    <title>Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h1>Cadastro de Produtos</h1>
<form method="post" action="/products">
    <input type="hidden" name="id" value="<?= $_GET['edit'] ?? '' ?>">
    <div class="mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" required />
    </div>
    <div class="mb-3">
        <label>Preço</label>
        <input type="number" name="price" step="0.01" class="form-control" required />
    </div>
    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<h2 class="mt-5">Produtos Cadastrados</h2>
<table class="table">
    <thead><tr><th>ID</th><th>Nome</th><th>Preço</th><th>Ações</th></tr></thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['name'] ?></td>
            <td>R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
            <td>
                <a href="/products/edit?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
            </td>
            <td>
                <a href="/products/delete?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Remover</a>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
