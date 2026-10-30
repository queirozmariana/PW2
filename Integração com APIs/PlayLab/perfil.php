<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$u = exigir_login();
$s = stats((int)$u['id']);
$hist = db()->prepare('SELECT * FROM respostas WHERE user_id = ? ORDER BY id DESC LIMIT 15');
$hist->execute([$u['id']]); $hist = $hist->fetchAll();
$erros = db()->prepare('SELECT * FROM respostas WHERE user_id = ? AND acertou = 0 ORDER BY id DESC LIMIT 10');
$erros->execute([$u['id']]); $erros = $erros->fetchAll();
$porJogo = db()->prepare('SELECT jogo, COUNT(*) total, SUM(acertou) acertos, SUM(pontos) xp FROM respostas WHERE user_id = ? GROUP BY jogo');
$porJogo->execute([$u['id']]); $porJogo = $porJogo->fetchAll();
cabecalho('Perfil', 'perfil');
?>
<h1><?= e($u['nome']) ?></h1>
<p class="meta"><?= e($u['email']) ?> · membro desde <?= e(date('d/m/Y', strtotime($u['criado']))) ?></p>

<div class="stats">
  <div><b>Nível <?= $s['nivel'] ?></b><span><?= $s['xp'] ?> XP</span>
    <div class="barra"><i style="width:<?= $s['progresso'] ?>%"></i></div><small><?= 100 - $s['progresso'] ?> XP para o próximo nível</small></div>
  <div><b><?= $s['total'] ?></b><span>perguntas respondidas</span></div>
  <div><b><?= $s['acertos'] ?></b><span>acertos (<?= $s['taxa'] ?>%)</span></div>
</div>

<h2>Conquistas</h2>
<div class="grade pequena">
<?php foreach (conquistas($s) as [$ic, $nome, $desc, $ok]): ?>
  <div class="cartao conq <?= $ok ? '' : 'bloq' ?>"><div class="icone"><?= $ok ? $ic : '🔒' ?></div><b><?= e($nome) ?></b><small><?= e($desc) ?></small></div>
<?php endforeach; ?>
</div>

<?php if ($porJogo): ?><h2>Desempenho por jogo</h2>
<table class="tabela"><thead><tr><th>Jogo</th><th>Perguntas</th><th>Acertos</th><th>XP</th></tr></thead><tbody>
<?php foreach ($porJogo as $p): ?><tr><td><?= e(JOGOS[$p['jogo']]['nome'] ?? $p['jogo']) ?></td><td><?= (int)$p['total'] ?></td><td><?= (int)$p['acertos'] ?></td><td><?= (int)$p['xp'] ?></td></tr><?php endforeach; ?>
</tbody></table><?php endif; ?>

<h2>Modo de revisão — erros recentes</h2>
<?php if (!$erros): ?><p class="vazio">Nenhum erro registrado. 👏</p><?php endif; ?>
<ul class="revisao">
<?php foreach ($erros as $r): ?>
  <li><b><?= e($r['pergunta']) ?></b><br>Você respondeu <s><?= e($r['resposta']) ?></s> · correta: <strong><?= e($r['correta']) ?></strong></li>
<?php endforeach; ?>
</ul>

<h2>Histórico recente</h2>
<?php if (!$hist): ?><p class="vazio">Você ainda não jogou. <a href="catalogo.php">Escolha um jogo</a>.</p><?php else: ?>
<table class="tabela"><thead><tr><th>Quando</th><th>Jogo</th><th>Pergunta</th><th>Resultado</th><th>XP</th></tr></thead><tbody>
<?php foreach ($hist as $h): ?>
  <tr><td><?= e(date('d/m H:i', strtotime($h['criado']))) ?></td><td><?= e(JOGOS[$h['jogo']]['nome'] ?? $h['jogo']) ?></td>
  <td><?= e($h['pergunta']) ?></td><td><?= $h['acertou'] ? '✅' : '❌' ?></td><td>+<?= (int)$h['pontos'] ?></td></tr>
<?php endforeach; ?></tbody></table><?php endif; ?>
<?php rodape();
