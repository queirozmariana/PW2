<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$slug = $_GET['g'] ?? '';
if (!isset(JOGOS[$slug])) { http_response_code(404); header('Location: catalogo.php'); exit; }
exigir_login();
$j = JOGOS[$slug];
$_SESSION['streak'] = 0; unset($_SESSION['q']);
cabecalho($j['nome']);
?>
<p><a class="btn claro pequeno" href="catalogo.php">← Voltar ao catálogo</a></p>
<h1><?= $j['icone'] ?> <?= e($j['nome']) ?></h1>
<p class="meta"><?= e($j['descricao']) ?> · API: <?= e($j['api']) ?></p>
<div id="game" data-jogo="<?= e($slug) ?>"><p>Carregando…</p></div>
<script src="js/game.js"></script>
<?php rodape();
