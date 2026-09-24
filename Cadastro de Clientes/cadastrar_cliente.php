<?php
require_once "config/conexao.php";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = trim($_POST["nome"]);
    $email = trim($_POST["email"]);
    $telefone = trim($_POST["telefone"]);
    $cpf = trim($_POST["cpf"]);

    if ($nome === "" || $email === "" || $telefone === "" || $cpf === "") {
        $erro = "Preencha todos os campos obrigatórios.";
    } else {
        $stmt = $conexao->prepare("INSERT INTO clientes (nome, email, telefone, cpf) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $email, $telefone, $cpf);

        if ($stmt->execute()) {
            header("Location: clientes.php?sucesso=cliente");
            exit;
        } else {
            $erro = $stmt->errno === 1062 ? "Já existe um cliente cadastrado com este CPF." : "Não foi possível cadastrar o cliente.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Novo cliente | Grão & Co.</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="app-shell"><?php include "partials_sidebar.php"; ?><main class="main-content">
<header class="topbar"><button class="btn btn-sm btn-outline-secondary mobile-toggle" id="mobileToggle"><i class="bi bi-list"></i></button></header>
<div class="page-container">
<div class="mb-4"><div class="page-kicker">Clientes</div><h1>Novo cliente</h1><p class="text-muted">Cadastre os dados de contato do cliente.</p></div>
<?php if ($erro): ?><div class="alert alert-danger"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="content-card">
<div class="content-card-header"><h5 class="mb-0">Dados do cliente</h5></div>
<form method="POST" class="p-4">
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome completo *</label><input type="text" name="nome" class="form-control" required value="<?= htmlspecialchars($_POST['nome'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label">E-mail *</label><input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label">Telefone *</label><input type="text" name="telefone" class="form-control" required value="<?= htmlspecialchars($_POST['telefone'] ?? '') ?>"></div>
<div class="col-md-6"><label class="form-label">CPF *</label><input type="text" name="cpf" class="form-control" required maxlength="14" value="<?= htmlspecialchars($_POST['cpf'] ?? '') ?>"></div>
</div>
<div class="mt-4 d-flex gap-2"><a href="clientes.php" class="btn btn-outline-secondary">Cancelar</a><button type="submit" class="btn btn-coffee">Salvar cliente</button></div>
</form>
</div>
</div>
</main></div>
<script src="js/script.js"></script>
</body>
</html>
