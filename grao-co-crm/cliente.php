<?php
require_once "config/conexao.php";
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) { header("Location: clientes.php"); exit; }

$stmt = $conexao->prepare("SELECT * FROM clientes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$cliente = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$cliente) { header("Location: clientes.php"); exit; }

$stmt = $conexao->prepare("SELECT * FROM compras WHERE cliente_id = ? ORDER BY data_compra DESC, id DESC");
$stmt->bind_param("i", $id);
$stmt->execute();
$compras = $stmt->get_result();

$stmtTotal = $conexao->prepare("SELECT COUNT(*) AS qtd, COALESCE(SUM(valor * quantidade),0) AS total FROM compras WHERE cliente_id = ?");
$stmtTotal->bind_param("i", $id);
$stmtTotal->execute();
$resumo = $stmtTotal->get_result()->fetch_assoc();
$stmtTotal->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($cliente['nome']) ?> | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell"><?php include "partials_sidebar.php"; ?><main class="main-content">
<header class="topbar"><button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button></header>
<div class="page-container">
<div class="d-flex justify-content-between align-items-center mb-3">
<a href="clientes.php" class="text-muted">← Voltar para clientes</a>
<div class="d-flex gap-2"><a href="editar_cliente.php?id=<?= $id ?>" class="btn btn-outline-secondary">Editar</a><a href="cadastrar_compra.php?cliente_id=<?= $id ?>" class="btn btn-coffee">+ Nova compra</a></div>
</div>

<section class="client-header mb-4">
<div class="row align-items-center">
<div class="col-lg-8">
<div class="d-flex align-items-center gap-3">
<div class="avatar" style="background:#a67c5b;color:#fff;width:58px;height:58px;font-size:22px;"><?= strtoupper(substr($cliente['nome'],0,1)) ?></div>
<div><h2 class="mb-1"><?= htmlspecialchars($cliente['nome']) ?></h2><p>Cliente desde <?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></p></div>
</div>
</div>
<div class="col-lg-4 mt-4 mt-lg-0">
<div class="row">
<div class="col-6"><div class="small" style="color:#cdbfb5">Compras</div><strong><?= $resumo['qtd'] ?></strong></div>
<div class="col-6"><div class="small" style="color:#cdbfb5">Total gasto</div><strong>R$ <?= number_format($resumo['total'],2,',','.') ?></strong></div>
</div>
</div>
</div>
</section>

<div class="row g-4">
<div class="col-lg-4">
<div class="content-card p-4">
<h5 class="mb-4">Dados de contato</h5>
<div class="mb-3"><div class="detail-label">E-mail</div><div class="detail-value"><?= htmlspecialchars($cliente['email']) ?></div></div>
<div class="mb-3"><div class="detail-label">Telefone</div><div class="detail-value"><?= htmlspecialchars($cliente['telefone']) ?></div></div>
<div><div class="detail-label">CPF</div><div class="detail-value"><?= htmlspecialchars($cliente['cpf']) ?></div></div>
</div>
</div>
<div class="col-lg-8">
<div class="content-card">
<div class="content-card-header"><h5 class="mb-1">Histórico de compras</h5><div class="small text-muted">Todas as compras vinculadas a este cliente.</div></div>
<?php if ($compras->num_rows > 0): ?>
<div class="table-responsive"><table class="table">
<thead><tr><th>Produto</th><th>Qtd.</th><th>Valor unit.</th><th>Total</th><th>Data</th><th></th></tr></thead>
<tbody>
<?php while ($compra = $compras->fetch_assoc()): ?>
<tr>
<td class="fw-semibold"><?= htmlspecialchars($compra['produto']) ?></td>
<td><?= $compra['quantidade'] ?></td>
<td>R$ <?= number_format($compra['valor'],2,',','.') ?></td>
<td>R$ <?= number_format($compra['valor']*$compra['quantidade'],2,',','.') ?></td>
<td><?= date('d/m/Y', strtotime($compra['data_compra'])) ?></td>
<td>
<a href="editar_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-outline-secondary"><i class="bi bi-pencil"></i></a>
<a href="excluir_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Deseja excluir esta compra?"><i class="bi bi-trash3"></i></a>
</td>
</tr>
<?php endwhile; ?>
</tbody>
</table></div>
<?php else: ?><div class="empty-state"><div class="empty-icon"><i class="bi bi-receipt"></i></div><h5>Nenhuma compra registrada</h5><p>Cadastre uma compra para começar o histórico.</p></div><?php endif; ?>
</div>
</div>
</div>
</div>
</main></div>
<script src="js/script.js"></script>
</body>
</html>
