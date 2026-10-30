<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$erros = []; $nome = $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_ok($_POST['csrf'] ?? null)) $erros[] = 'Sessão inválida. Recarregue a página.';
    $nome = trim($_POST['nome'] ?? ''); $email = strtolower(trim($_POST['email'] ?? ''));
    $senha = $_POST['senha'] ?? ''; $conf = $_POST['confirma'] ?? '';
    if (mb_strlen($nome) < 2 || mb_strlen($nome) > 60) $erros[] = 'Informe um nome entre 2 e 60 caracteres.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $erros[] = 'Informe um e-mail válido.';
    if (strlen($senha) < 6) $erros[] = 'A senha precisa ter pelo menos 6 caracteres.';
    if ($senha !== $conf) $erros[] = 'As senhas não coincidem.';
    if (!$erros) {
        $st = db()->prepare('SELECT 1 FROM usuarios WHERE email = ?');
        $st->execute([$email]);
        if ($st->fetch()) $erros[] = 'Este e-mail já está cadastrado.';
    }
    if (!$erros) {
        db()->prepare('INSERT INTO usuarios (nome, email, senha) VALUES (?,?,?)')
            ->execute([$nome, $email, password_hash($senha, PASSWORD_DEFAULT)]);
        session_regenerate_id(true);
        $_SESSION['uid'] = (int)db()->lastInsertId();
        header('Location: catalogo.php'); exit;
    }
}
cabecalho('Criar conta');
?>
<section class="form-caixa">
  <h1>Criar conta</h1>
  <?php foreach ($erros as $m): ?><p class="alerta"><?= e($m) ?></p><?php endforeach; ?>
  <form method="post" novalidate>
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>Nome <input name="nome" value="<?= e($nome) ?>" required maxlength="60"></label>
    <label>E-mail <input type="email" name="email" value="<?= e($email) ?>" required></label>
    <label>Senha <input type="password" name="senha" required minlength="6"></label>
    <label>Confirmar senha <input type="password" name="confirma" required minlength="6"></label>
    <button class="btn" type="submit">Cadastrar</button>
  </form>
  <p>Já tem conta? <a href="login.php">Entrar</a></p>
</section>
<?php rodape();
