<?php include '../templates/header.php'; ?>

<div class="container mt-5">
    <h2>Novo Produto</h2>
    <form action="store.php" method="POST">
        <div class="form-group mb-3">
            <label>Nome</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Descrição</label>
            <textarea name="description" class="form-control"></textarea>
        </div>
        <div class="form-group mb-3">
            <label>Preço</label>
            <input type="number" step="0.01" name="price" class="form-control" required>
        </div>
        <div class="form-group mb-3">
            <label>Estoque</label>
            <input type="number" name="stock" class="form-control">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include '../templates/footer.php'; ?>
