<?php
require_once "config/conexao.php";
$clienteId = filter_input(INPUT_GET, 'cliente_id', FILTER_VALIDATE_INT) ?: (filter_input(INPUT_POST, 'cliente_id', FILTER_VALIDATE_INT) ?: 0);
$erro = "";
$clientes = $conexao->query("SELECT id, nome FROM clientes ORDER BY nome");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clienteId = (int)$_POST["cliente_id"];
    $produto = trim($_POST["produto"]);
    $quantidade = (int)$_POST["quantidade"];
    $valor = (float)str_replace(",", ".", $_POST["valor"]);
    $data = $_POST["data_compra"];

    if ($clienteId <= 0 || $produto === "" || $quantidade <= 0 || $valor < 0 || $data === "") {
        $erro = "Preencha corretamente todos os campos.";
    } else {
        $stmt = $conexao->prepare("INSERT INTO compras (cliente_id, produto, quantidade, valor, data_compra) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isids", $clienteId, $produto, $quantidade, $valor, $data);
        if ($stmt->execute()) {
            header("Location: cliente.php?id=".$clienteId);
            exit;
        }
        $erro = "Não foi possível cadastrar a compra.";
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nova compra | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell"><?php include "partials_sidebar.php"; ?><main class="main-content">
<header class="topbar"><button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button></header>
<div class="page-container">
<div class="mb-4"><div class="page-kicker">Compras</div><h1>Nova compra</h1><p class="text-muted">Registre uma compra e vincule-a ao cliente.</p></div>
<?php if ($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="content-card"><form method="POST" class="p-4">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Cliente *</label><select name="cliente_id" class="form-select" required><option value="">Selecione...</option><?php while($c=$clientes->fetch_assoc()): ?><option value="<?= $c['id'] ?>" <?= $clienteId == $c['id'] ? 'selected' : '' ?>><?= htmlspecialchars($c['nome']) ?></option><?php endwhile; ?></select></div>
<div class="col-md-6"><label class="form-label">Produto *</label><input type="text" name="produto" class="form-control" placeholder="Ex.: Cappuccino" required value="<?= htmlspecialchars($_POST['produto'] ?? '') ?>"></div>
<div class="col-md-4"><label class="form-label">Quantidade *</label><input type="number" name="quantidade" min="1" class="form-control" required value="<?= htmlspecialchars($_POST['quantidade'] ?? '1') ?>"></div>
<div class="col-md-4"><label class="form-label">Valor unitário *</label><input type="number" name="valor" min="0" step="0.01" class="form-control" required value="<?= htmlspecialchars($_POST['valor'] ?? '') ?>"></div>
<div class="col-md-4"><label class="form-label">Data da compra *</label><input type="date" name="data_compra" class="form-control" required value="<?= htmlspecialchars($_POST['data_compra'] ?? date('Y-m-d')) ?>"></div>
</div>
<div class="mt-4 d-flex gap-2"><a href="compras.php" class="btn btn-outline-secondary">Cancelar</a><button class="btn btn-coffee">Salvar compra</button></div>
</form></div>
</div>
</main></div>
<script src="js/script.js"></script>
</body>
</html>
