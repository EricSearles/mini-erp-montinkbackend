<!DOCTYPE html>
<html>
<head>
    <title>Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
<h2>Editar Produto</h2>
<form method="post" action="/products/update?id=<?= $product->id ?>">
    <input type="hidden" name="id" value="<?= $product->id ?>">
    <div class="form-group mb-3">
        <label>Nome</label>
        <input type="text" name="name" class="form-control" value="<?= $product->name ?>" required>
    </div>
    <div class="form-group mb-3">
        <label>Descrição</label>
        <textarea name="description" class="form-control"><?= $product->description ?></textarea>
    </div>
    <div class="form-group mb-3">
        <label>Preço</label>
        <input type="number" step="0.01" name="price" class="form-control" value="<?= $product->price ?>" required>
    </div>
    <div class="form-group mb-3">
        <label>Estoque</label>
        <input type="number" name="stock" class="form-control" value="<?= $product->stock ?>">
    </div>
    <button type="submit" class="btn btn-primary">Atualizar</button>
    <a href="index.php" class="btn btn-secondary">Cancelar</a>
</form>

<h2 class="mt-5">Variações do Produto</h2>

<form method="POST" action="/variations/store" class="mb-4">
    <input type="hidden" name="product_id" value="<?= $product->id ?>">

    <div class="row">
        <div class="col-md-5">
            <div class="form-group mb-3">
                <label class="form-label">Nome da Variação</label>
                <input type="text" name="name" class="form-control" required>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group mb-3">
                <label class="form-label">Quantidade</label>
                <input type="number" name="quantity" class="form-control" value="0" required>
            </div>
        </div>
        <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Adicionar</button>
        </div>
    </div>
</form>

<h3 class="mt-4">Lista de Variações</h3>
<table class="table table-striped table-bordered">
    <thead class="table-dark">
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Quantidade</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($product->variations as $variation): ?>
        <tr>
            <td><?= $variation->id ?></td>
            <td><?= $variation->name ?></td>
            <td><?= $variation->stock->quantity ?? 0 ?></td>
            <td>
                <a href="/variations/delete?id=<?= $variation->id ?>&product_id=<?= $product->id ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Tem certeza que deseja excluir esta variação?')">
                    Excluir
                </a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>