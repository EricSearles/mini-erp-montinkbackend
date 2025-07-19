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
</div>

<?php include '../templates/footer.php'; ?>