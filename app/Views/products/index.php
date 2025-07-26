<!DOCTYPE html>
<html>
<head>
    <title>Mini ERP</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

    <!-- Seção de Variações -->
    <div class="mb-3">
        <label>Variações</label>
        <div id="variations-container">
            <!-- Variações serão adicionadas aqui dinamicamente -->
        </div>
        <button type="button" id="add-variation" class="btn btn-secondary btn-sm mt-2">+ Adicionar Variação</button>
    </div>

    <!-- Controle de Estoque -->
    <div class="mb-3">
        <label>Estoque Geral</label>
        <input type="number" name="stock" class="form-control" min="0" required />
    </div>

    <button type="submit" class="btn btn-success">Salvar</button>
</form>

<h2 class="mt-5">Produtos Cadastrados</h2>
<table class="table">
    <thead>
    <tr>
        <th>ID</th>
        <th>Nome</th>
        <th>Preço</th>
        <th>Estoque</th>
        <th>Ações</th>
    </tr>
    </thead>
    <tbody>
    <?php foreach ($products as $p): ?>
        <tr>
            <td><?= $p['id'] ?></td>
            <td><?= $p['name'] ?></td>
            <td>R$ <?= number_format($p['price'], 2, ',', '.') ?></td>
            <td><?= $p['stock'] ?? '0' ?></td>
            <td>
                <a href="/products/edit?id=<?= $p['id'] ?>" class="btn btn-primary btn-sm">Editar</a>
                <a href="/products/delete?id=<?= $p['id'] ?>" class="btn btn-danger btn-sm">Remover</a>
                <button class="btn btn-info btn-sm buy-btn" data-product-id="<?= $p['id'] ?>">Comprar</button>
            </td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>

<!-- Modal de Compra -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Finalizar Compra</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="cart-items">
                    <!-- Itens do carrinho serão adicionados aqui -->
                </div>

                <div class="mb-3">
                    <label>CEP</label>
                    <input type="text" id="cep" class="form-control" placeholder="00000-000">
                    <small class="text-muted" id="address-info"></small>
                </div>

                <div class="mb-3">
                    <label>Cupom de Desconto</label>
                    <input type="text" id="coupon" class="form-control">
                </div>

                <div class="row">
                    <div class="col">
                        <h6>Subtotal: <span id="subtotal">R$ 0,00</span></h6>
                        <h6>Frete: <span id="shipping">R$ 0,00</span></h6>
                        <h5>Total: <span id="total">R$ 0,00</span></h5>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar Comprando</button>
                <button type="button" class="btn btn-success" id="finalize-purchase">Finalizar Compra</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function() {
        // Botão comprar
        $('.buy-btn').click(function(e) {
            e.preventDefault();
            const productId = $(this).data('product-id');

            // Abre o modal de compra
            const purchaseModal = new bootstrap.Modal(document.getElementById('purchaseModal'));
            purchaseModal.show();

            // Carrega os dados do produto
            $.get('/products/show/' + productId, function(response) {
                const product = typeof response === 'string' ? JSON.parse(response) : response;

                $('#cart-items').html(`
                <div class="cart-item mb-3" data-product-id="${product.id}" data-price="${product.price}">
                    <h5>${product.name}</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <label>Variação</label>
                            <select class="form-select variation-select">
                                <option value="0">Padrão</option>
                                ${product.variations && product.variations.length ?
                    product.variations.map(v =>
                        `<option value="${v.id}">${v.name} (${v.quantity} disponíveis)</option>`
                    ).join('') : ''}
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label>Quantidade</label>
                            <input type="number" class="form-control quantity" value="1" min="1" max="${product.stock}">
                        </div>
                    </div>
                    <div class="mt-2">
                        <strong>Preço:</strong> R$ ${parseFloat(product.price).toFixed(2)}
                    </div>
                </div>
            `);

                // Calcula o subtotal inicial
                updateCartTotals(product.price, 1);
            }).fail(function() {
                console.error('Erro ao carregar produto');
            });
        });

        // Atualiza quantidades
        $(document).on('change', '.quantity, .variation-select', function() {
            const price = parseFloat($('#cart-items').find('.cart-item').data('price'));
            const quantity = parseInt($(this).closest('.cart-item').find('.quantity').val());
            updateCartTotals(price, quantity);
        });

        function updateCartTotals(price, quantity) {
            const subtotal = price * quantity;
            const shipping = calculateShipping(subtotal);
            const total = subtotal + shipping;

            $('#subtotal').text('R$ ' + subtotal.toFixed(2));
            $('#shipping').text('R$ ' + shipping.toFixed(2));
            $('#total').text('R$ ' + total.toFixed(2));
        }

        function calculateShipping(subtotal) {
            if (subtotal >= 52 && subtotal <= 166.59) {
                return 15;
            } else if (subtotal > 200) {
                return 0;
            }
            return 20;
        }

        // Consulta CEP
        $('#cep').on('blur', function() {
            const cep = $(this).val().replace(/\D/g, '');
            if (cep.length === 8) {
                $.getJSON(`https://viacep.com.br/ws/${cep}/json/`, function(data) {
                    if (!data.erro) {
                        $('#address-info').text(`${data.logradouro}, ${data.bairro}, ${data.localidade}/${data.uf}`).removeClass('text-danger');
                    } else {
                        $('#address-info').text('CEP não encontrado').addClass('text-danger');
                    }
                });
            }
        });

        // Finalizar compra
        $('#finalize-purchase').click(function() {
            const productId = $('.cart-item').data('product-id');
            const variationId = $('.variation-select').val();
            const quantity = $('.quantity').val();
            const cep = $('#cep').val();
            const coupon = $('#coupon').val();

            $.ajax({
                url: '/cart/add',
                method: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    product_id: productId,
                    variation_id: variationId !== '0' ? variationId : null,
                    quantity: quantity,
                    cep: cep,
                    coupon: coupon
                }),
                success: function(response) {
                    if (response.success) {
                        window.location.href = '/cart/checkout';
                    } else {
                        alert('Erro: ' + response.message);
                    }
                },
                error: function() {
                    alert('Erro ao processar a compra');
                }
            });
        });
    });
</script>

<?php include __DIR__ . '/../templates/footer.php'; ?>