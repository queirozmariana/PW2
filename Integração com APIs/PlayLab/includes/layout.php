<?php
function cabecalho(string $titulo, string $pagina = ''): void {
    $u = usuario(); ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf" content="<?= e(csrf_token()) ?>">
<title><?= e($titulo) ?> · PlayLab</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@600;800&family=Figtree:wght@400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<header class="topo">
  <a class="logo" href="index.php">Play<span>Lab</span></a>
  <nav>
    <a href="catalogo.php" class="<?= $pagina === 'catalogo' ? 'ativo' : '' ?>">Jogos</a>
    <a href="explorar.php" class="<?= $pagina === 'explorar' ? 'ativo' : '' ?>">Explorar Pokémon</a>
    <a href="ranking.php" class="<?= $pagina === 'ranking' ? 'ativo' : '' ?>">Ranking</a>
    <?php if ($u): ?>
      <a href="perfil.php" class="<?= $pagina === 'perfil' ? 'ativo' : '' ?>">Perfil (<?= e(explode(' ', $u['nome'])[0]) ?>)</a>
      <a href="logout.php" class="btn pequeno">Sair</a>
    <?php else: ?>
      <a href="login.php">Entrar</a>
      <a href="cadastro.php" class="btn pequeno">Criar conta</a>
    <?php endif; ?>
  </nav>
</header>
<main>
<?php }

function rodape(): void { ?>
</main>
<footer class="rodape">PlayLab · projeto de integração com APIs públicas (PokeAPI, Open-Meteo e base de países) · PHP</footer>
</body>
</html>
<?php }
