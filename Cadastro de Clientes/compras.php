<?php
require_once "config/conexao.php";
$compras = $conexao->query("
    SELECT co.*, c.nome AS cliente
    FROM compras co
    INNER JOIN clientes c ON c.id = co.cliente_id
    ORDER BY co.data_compra DESC, co.id DESC
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Compras | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell"><?php include "partials_sidebar.php"; ?><main class="main-content">
<header class="topbar"><button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button></header>
<div class="page-container">
<div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
<div><div class="page-kicker">Histórico</div><h1 class="mb-1">Compras</h1><p class="text-muted mb-0">Gerencie as compras realizadas pelos clientes.</p></div>
<a href="cadastrar_compra.php" class="btn btn-coffee">+ Nova compra</a>
</div>
<div class="content-card">
<div class="table-responsive">
<table class="table">
<thead><tr><th>Cliente</th><th>Produto</th><th>Qtd.</th><th>Valor unit.</th><th>Total</th><th>Data</th><th>Ações</th></tr></thead>
<tbody>
<?php if ($compras->num_rows > 0): while ($compra = $compras->fetch_assoc()): ?>
<tr>
<td><a href="cliente.php?id=<?= $compra['cliente_id'] ?>" class="fw-semibold text-dark"><?= htmlspecialchars($compra['cliente']) ?></a></td>
<td><?= htmlspecialchars($compra['produto']) ?></td>
<td><?= $compra['quantidade'] ?></td>
<td>R$ <?= number_format($compra['valor'],2,',','.') ?></td>
<td class="fw-semibold">R$ <?= number_format($compra['valor']*$compra['quantidade'],2,',','.') ?></td>
<td><?= date('d/m/Y', strtotime($compra['data_compra'])) ?></td>
<td><a href="editar_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a> <a href="excluir_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Deseja excluir esta compra?"><i class="bi bi-trash3"></i></a></td>
</tr>
<?php endwhile; else: ?>
<tr><td colspan="7"><div class="empty-state"><div class="empty-icon"><i class="bi bi-receipt"></i></div><h5>Nenhuma compra registrada</h5></div></td></tr>
<?php endif; ?>
</tbody>
</table>
</div>
</div>
</div>
</main></div>
<script src="js/script.js"></script>
</body>
</html>
