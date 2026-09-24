<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<aside class="sidebar" id="sidebar">
    <a href="index.php" class="brand">
        <div class="brand-icon"><i class="bi bi-cup-hot-fill"></i></div>
        <div>
            <div class="brand-title">Grão & Co.</div>
            <div class="brand-subtitle">CRM Cafeteria</div>
        </div>
    </a>

    <div class="nav-section">Principal</div>
    <a class="nav-link <?= $current === 'index.php' ? 'active' : '' ?>" href="index.php"><i class="bi bi-grid-1x2"></i><span>Dashboard</span></a>
    <a class="nav-link <?= in_array($current, ['clientes.php','cadastrar_cliente.php','editar_cliente.php','cliente.php','excluir_cliente.php']) ? 'active' : '' ?>" href="clientes.php"><i class="bi bi-people"></i><span>Clientes</span></a>
    <a class="nav-link <?= in_array($current, ['compras.php','cadastrar_compra.php','editar_compra.php','excluir_compra.php']) ? 'active' : '' ?>" href="compras.php"><i class="bi bi-receipt"></i><span>Compras</span></a>

    <div class="nav-section">Sistema</div>
    <a class="nav-link <?= $current === 'historico.php' ? 'active' : '' ?>" href="historico.php"><i class="bi bi-clock-history"></i><span>Histórico</span></a>

    <div class="sidebar-footer">
        <strong>Grão & Co.</strong><br>
        <span>Relacionamento que começa no café.</span>
    </div>
</aside>
