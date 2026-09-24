<?php
require_once "config/conexao.php";

$busca = trim($_GET['busca'] ?? '');

$where = '';
if ($busca !== '') {
    $where = "WHERE c.nome LIKE ? OR co.produto LIKE ?";
}

$sql = "
    SELECT
        co.id,
        c.id AS cliente_id,
        c.nome AS cliente,
        co.produto,
        co.quantidade,
        co.valor,
        (co.valor * co.quantidade) AS total,
        co.data_compra
    FROM compras co
    INNER JOIN clientes c ON c.id = co.cliente_id
    $where
    ORDER BY co.data_compra DESC, co.id DESC
";

if ($busca !== '') {
    $stmt = $conexao->prepare($sql);
    $termo = "%{$busca}%";
    $stmt->bind_param("ss", $termo, $termo);
    $stmt->execute();
    $historico = $stmt->get_result();
} else {
    $historico = $conexao->query($sql);
}

$resumo = $conexao->query(""
    . "SELECT "
    . "COUNT(*) AS quantidade, "
    . "COALESCE(SUM(valor * quantidade), 0) AS total, "
    . "COALESCE(AVG(valor * quantidade), 0) AS ticket "
    . "FROM compras"
)->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Histórico | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell">
    <?php include "partials_sidebar.php"; ?>
    <main class="main-content">
        <header class="topbar">
            <button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button>
        </header>

        <div class="page-container">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
                <div>
                    <div class="page-kicker">Relacionamento</div>
                    <h1 class="mb-1">Histórico de compras</h1>
                    <p class="text-muted mb-0">Consulte todas as compras registradas no Grão & Co.</p>
                </div>
                <a href="cadastrar_compra.php" class="btn btn-coffee"><i class="bi bi-plus-lg me-2"></i>Nova compra</a>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                        <div class="stat-label">Compras registradas</div>
                        <div class="stat-note">Histórico completo</div>
                        <div class="stat-value"><?= $resumo['quantidade'] ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="stat-label">Total em vendas</div>
                        <div class="stat-note">Valor acumulado</div>
                        <div class="stat-value">R$ <?= number_format($resumo['total'], 2, ',', '.') ?></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-bar-chart"></i></div>
                        <div class="stat-label">Ticket médio</div>
                        <div class="stat-note">Por compra registrada</div>
                        <div class="stat-value">R$ <?= number_format($resumo['ticket'], 2, ',', '.') ?></div>
                    </div>
                </div>
            </div>

            <section class="content-card">
                <div class="content-card-header">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <div>
                            <h5 class="mb-1">Todas as compras</h5>
                            <div class="small text-muted">Cliente, produto, quantidade, valor e data da compra.</div>
                        </div>
                        <form method="GET" class="d-flex gap-2" style="max-width: 360px; width: 100%;">
                            <input type="text" name="busca" class="form-control" placeholder="Buscar cliente ou produto" value="<?= htmlspecialchars($busca) ?>">
                            <button class="btn btn-coffee" type="submit"><i class="bi bi-search"></i></button>
                            <?php if ($busca !== ''): ?>
                                <a href="historico.php" class="btn btn-outline-secondary" title="Limpar busca"><i class="bi bi-x-lg"></i></a>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>

                <?php if ($historico->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Data</th>
                                <th>Cliente</th>
                                <th>Produto</th>
                                <th>Qtd.</th>
                                <th>Valor unit.</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($compra = $historico->fetch_assoc()): ?>
                            <tr>
                                <td><?= date('d/m/Y', strtotime($compra['data_compra'])) ?></td>
                                <td>
                                    <a href="cliente.php?id=<?= $compra['cliente_id'] ?>" class="fw-semibold text-dark text-decoration-none">
                                        <?= htmlspecialchars($compra['cliente']) ?>
                                    </a>
                                </td>
                                <td><?= htmlspecialchars($compra['produto']) ?></td>
                                <td><?= $compra['quantidade'] ?></td>
                                <td>R$ <?= number_format($compra['valor'], 2, ',', '.') ?></td>
                                <td class="fw-semibold">R$ <?= number_format($compra['total'], 2, ',', '.') ?></td>
                                <td>
                                    <a href="editar_compra.php?id=<?= $compra['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Editar compra">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-icon"><i class="bi bi-clock-history"></i></div>
                        <h5><?= $busca !== '' ? 'Nenhuma compra encontrada' : 'Nenhuma compra registrada' ?></h5>
                        <p><?= $busca !== '' ? 'Tente buscar por outro cliente ou produto.' : 'Cadastre uma compra para começar o histórico.' ?></p>
                    </div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>
<script src="js/script.js"></script>
</body>
</html>
