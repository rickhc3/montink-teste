<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produtos - Montink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="card shadow">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h1 class="card-title h2 mb-0">Produtos</h1>
                            <div class="d-flex gap-2">
                                <a href="<?= base_url('products/cart') ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-cart3"></i> Carrinho
                                </a>
                                <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Novo Produto
                                </a>
                            </div>
                        </div>

                        <?php if (empty($products)): ?>
                            <div class="text-center py-5">
                                <i class="bi bi-box h1 text-muted"></i>
                                <h3 class="mt-3">Nenhum produto cadastrado</h3>
                                <p class="text-muted">Comece criando seu primeiro produto!</p>
                                <a href="<?= base_url('products/create') ?>" class="btn btn-primary">
                                    <i class="bi bi-plus-circle"></i> Criar Produto
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead class="table-dark">
                                        <tr>
                                            <th>ID</th>
                                            <th>Nome</th>
                                            <th>Preço</th>
                                            <th>Estoque Total</th>
                                            <th>Variações</th>
                                            <th>Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($products as $product): ?>
                                            <tr>
                                                <td><span class="badge bg-secondary"><?= $product->id ?></span></td>
                                                <td>
                                                    <strong><?= htmlspecialchars($product->name) ?></strong>
                                                </td>
                                                <td>
                                                    <span class="text-success fw-bold">R$ <?= number_format($product->price, 2, ',', '.') ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $totalStock = 0;
                                                    foreach ($product->stock as $stock) {
                                                        $totalStock += $stock->quantity;
                                                    }
                                                    ?>
                                                    <span class="badge bg-info"><?= $totalStock ?> unidades</span>
                                                </td>
                                                <td>
                                                    <?php foreach ($product->stock as $stock): ?>
                                                        <span class="badge bg-light text-dark me-1">
                                                            <?= htmlspecialchars($stock->variation) ?>: <?= $stock->quantity ?>
                                                        </span>
                                                    <?php endforeach; ?>
                                                </td>
                                                <td>
                                                    <div class="btn-group" role="group">
                                                        <a href="<?= base_url('products/edit/' . $product->id) ?>" 
                                                           class="btn btn-outline-primary btn-sm" 
                                                           title="Editar/Comprar">
                                                            <i class="bi bi-pencil-square"></i>
                                                        </a>
                                                        <button type="button" 
                                                                class="btn btn-outline-danger btn-sm" 
                                                                onclick="deleteProduct(<?= $product->id ?>)"
                                                                title="Excluir">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteProduct(id) {
            if (confirm('Tem certeza que deseja excluir este produto?')) {
                window.location.href = '<?= base_url('products/delete/') ?>' + id;
            }
        }
    </script>
</body>
</html> 