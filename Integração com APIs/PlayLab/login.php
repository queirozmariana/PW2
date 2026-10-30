<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$erro = ''; $email = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ''));
    if (!csrf_ok($_POST['csrf'] ?? null)) {
        $erro = 'Sessão inválida. Recarregue a página.';
    } else {
        $st = db()->prepare('SELECT id, senha FROM usuarios WHERE email = ?');
        $st->execute([$email]);
        $row = $st->fetch();
        if ($row && password_verify($_POST['senha'] ?? '', $row['senha'])) {
            session_regenerate_id(true);
            $_SESSION['uid'] = (int)$row['id'];
            $destino = $_SESSION['voltar'] ?? 'catalogo.php';
            unset($_SESSION['voltar']);
            if (!preg_match('/^[a-z]+\.php(\?g=[a-z]+)?$/', $destino)) $destino = 'catalogo.php';
            header('Location: ' . $destino); exit;
        }
        $erro = 'E-mail ou senha incorretos.';
    }
}
cabecalho('Entrar');
?>
<section class="form-caixa">
  <h1>Entrar</h1>
  <?php if ($erro): ?><p class="alerta"><?= e($erro) ?></p><?php endif; ?>
  <form method="post">
    <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
    <label>E-mail <input type="email" name="email" value="<?= e($email) ?>" required></label>
    <label>Senha <input type="password" name="senha" required></label>
    <button class="btn" type="submit">Entrar</button>
  </form>
  <p>Ainda não tem conta? <a href="cadastro.php">Cadastre-se</a></p>
</section>
<?php rodape();
