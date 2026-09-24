<?php
require_once "config/conexao.php";

$clientes = $conexao->query("SELECT COUNT(*) AS total FROM clientes")->fetch_assoc()['total'];
$compras = $conexao->query("SELECT COUNT(*) AS total FROM compras")->fetch_assoc()['total'];
$vendas = $conexao->query("SELECT COALESCE(SUM(valor * quantidade), 0) AS total FROM compras")->fetch_assoc()['total'];
$novos = $conexao->query("SELECT COUNT(*) AS total FROM clientes WHERE MONTH(data_cadastro) = MONTH(CURRENT_DATE()) AND YEAR(data_cadastro) = YEAR(CURRENT_DATE())")->fetch_assoc()['total'];

$ultimos = $conexao->query("
    SELECT c.id, c.nome, c.email, c.data_cadastro,
           COALESCE(SUM(co.valor * co.quantidade), 0) AS total_gasto,
           COUNT(co.id) AS total_compras
    FROM clientes c
    LEFT JOIN compras co ON co.cliente_id = c.id
    GROUP BY c.id
    ORDER BY c.data_cadastro DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Grão & Co.</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell">
    <?php include "partials_sidebar.php"; ?>
    <main class="main-content">
        <header class="topbar d-flex justify-content-between align-items-center">
            <div>
                <button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button>
            </div>
            <div class="topbar-label"><span>CRM</span><i class="bi bi-dot"></i><span>Grão & Co.</span><i class="bi bi-cup-hot ms-1"></i></div>
        </header>

        <div class="page-container">
            <section class="welcome-card mb-4">
                <div class="welcome-copy">
                    <div class="page-kicker">Painel de relacionamento</div>
                    <h1 class="display-title mb-2">Bom dia, Mariana!</h1>
                    <p class="mb-4">Acompanhe os clientes, as compras e o relacionamento da Grão & Co. em um só lugar.</p>
                    <a href="cadastrar_cliente.php" class="btn btn-caramel"><i class="bi bi-person-plus me-2"></i>Cadastrar cliente</a>
                </div>
                <div class="welcome-art" aria-hidden="true">
                    <img src="img/coffee-hero.svg" alt="" class="hero-image">
                    <div class="hero-caption"><span>GRÃO & CO.</span><small>CRM • CAFETERIA</small></div>
                </div>
            </section>

            <div class="row g-3 mb-4">
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-people"></i></div>
                        <div class="stat-label">Clientes cadastrados</div><div class="stat-note">Base ativa</div>
                        <div class="stat-value"><?= $clientes ?></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-receipt"></i></div>
                        <div class="stat-label">Compras registradas</div><div class="stat-note">Histórico completo</div>
                        <div class="stat-value"><?= $compras ?></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-cash-stack"></i></div>
                        <div class="stat-label">Total em vendas</div><div class="stat-note">Valor acumulado</div>
                        <div class="stat-value">R$ <?= number_format($vendas, 2, ',', '.') ?></div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3">
                    <div class="stat-card">
                        <div class="stat-icon"><i class="bi bi-person-plus"></i></div>
                        <div class="stat-label">Novos neste mês</div><div class="stat-note">Novos cadastros</div>
                        <div class="stat-value"><?= $novos ?></div>
                    </div>
                </div>
            </div>

            <section class="content-card">
                <div class="content-card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">Clientes recentes</h5>
                        <div class="small text-muted">Últimos cadastros realizados</div>
                    </div>
                    <a href="clientes.php" class="btn btn-sm btn-coffee">Ver clientes</a>
                </div>

                <?php if ($ultimos->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Cadastro</th>
                                <th>Compras</th>
                                <th>Total gasto</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($cliente = $ultimos->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="avatar"><?= strtoupper(substr($cliente['nome'], 0, 1)) ?></div>
                                        <div>
                                            <div class="fw-semibold"><?= htmlspecialchars($cliente['nome']) ?></div>
                                            <div class="small text-muted"><?= htmlspecialchars($cliente['email']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></td>
                                <td><span class="badge-soft"><?= $cliente['total_compras'] ?></span></td>
                                <td>R$ <?= number_format($cliente['total_gasto'], 2, ',', '.') ?></td>
                                <td><a href="cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-secondary">Ver</a></td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                    <div class="empty-state"><div class="empty-icon"><i class="bi bi-person"></i></div>Nenhum cliente cadastrado ainda.</div>
                <?php endif; ?>
            </section>
        </div>
    </main>
</div>
<script src="js/script.js"></script>
</body>
</html>
