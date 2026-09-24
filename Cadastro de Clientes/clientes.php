<?php
require_once "config/conexao.php";

$clientes = $conexao->query("
    SELECT c.id, c.nome, c.email, c.telefone, c.cpf, c.data_cadastro,
           COUNT(co.id) AS total_compras,
           COALESCE(SUM(co.valor * co.quantidade), 0) AS total_gasto
    FROM clientes c
    LEFT JOIN compras co ON co.cliente_id = c.id
    GROUP BY c.id
    ORDER BY c.nome ASC
");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Clientes | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell">
<?php include "partials_sidebar.php"; ?>
<main class="main-content">
<header class="topbar d-flex justify-content-between align-items-center">
    <button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button>
    <div class="small text-muted">Clientes / Gestão</div>
</header>
<div class="page-container">
    <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
        <div>
            <div class="page-kicker">Relacionamento</div>
            <h1 class="mb-1">Clientes</h1>
            <p class="text-muted mb-0">Cadastre, consulte e gerencie os clientes da cafeteria.</p>
        </div>
        <a href="cadastrar_cliente.php" class="btn btn-coffee">+ Novo cliente</a>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <div class="row g-2 align-items-center">
                <div class="col-md-7">
                    <input type="search" id="searchClientes" class="form-control" placeholder="Pesquisar por nome, e-mail, telefone ou CPF...">
                </div>
                <div class="col-md-5 text-md-end">
                    <span class="small text-muted"><?= $clientes->num_rows ?> cliente(s) encontrado(s)</span>
                </div>
            </div>
        </div>

        <?php if ($clientes->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table" id="tabelaClientes">
                <thead><tr><th>Cliente</th><th>Contato</th><th>Compras</th><th>Total gasto</th><th>Cadastro</th><th>Ações</th></tr></thead>
                <tbody>
                <?php while ($cliente = $clientes->fetch_assoc()):
                    $search = strtolower($cliente['nome'].' '.$cliente['email'].' '.$cliente['telefone'].' '.$cliente['cpf']);
                ?>
                    <tr data-search="<?= htmlspecialchars($search) ?>">
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="avatar"><?= strtoupper(substr($cliente['nome'], 0, 1)) ?></div>
                                <div class="fw-semibold"><?= htmlspecialchars($cliente['nome']) ?></div>
                            </div>
                        </td>
                        <td>
                            <div><?= htmlspecialchars($cliente['email']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($cliente['telefone']) ?></div>
                        </td>
                        <td><span class="badge-soft"><?= $cliente['total_compras'] ?></span></td>
                        <td>R$ <?= number_format($cliente['total_gasto'], 2, ',', '.') ?></td>
                        <td><?= date('d/m/Y', strtotime($cliente['data_cadastro'])) ?></td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Visualizar"><i class="bi bi-eye"></i></a>
                                <a href="editar_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="bi bi-pencil"></i></a>
                                <a href="excluir_cliente.php?id=<?= $cliente['id'] ?>" class="btn btn-sm btn-outline-danger" data-confirm="Deseja realmente excluir este cliente? Todas as compras relacionadas também serão excluídas." title="Excluir"><i class="bi bi-trash3"></i></a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php else: ?>
            <div class="empty-state"><div class="empty-icon"><i class="bi bi-people"></i></div><h5>Nenhum cliente cadastrado</h5><p>Comece cadastrando o primeiro cliente.</p><a href="cadastrar_cliente.php" class="btn btn-coffee">Cadastrar cliente</a></div>
        <?php endif; ?>
    </div>
</div>
</main>
</div>
<script src="js/script.js"></script>
</body>
</html>
