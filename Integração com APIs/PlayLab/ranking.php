<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$linhas = db()->query('SELECT u.nome, SUM(r.pontos) xp, COUNT(*) total, SUM(r.acertou) acertos
    FROM respostas r JOIN usuarios u ON u.id = r.user_id GROUP BY u.id ORDER BY xp DESC, acertos DESC LIMIT 20')->fetchAll();
$eu = usuario();
cabecalho('Ranking', 'ranking');
?>
<h1>Ranking</h1>
<?php if (!$linhas): ?><p class="vazio">Ninguém pontuou ainda. Seja o primeiro!</p><?php else: ?>
<table class="tabela">
  <thead><tr><th>#</th><th>Jogador</th><th>XP</th><th>Nível</th><th>Acertos</th></tr></thead>
  <tbody>
  <?php foreach ($linhas as $i => $l): ?>
    <tr <?= $eu && $eu['nome'] === $l['nome'] ? 'class="eu"' : '' ?>>
      <td><?= $i + 1 ?></td><td><?= e($l['nome']) ?></td><td><?= (int)$l['xp'] ?></td>
      <td><?= intdiv((int)$l['xp'], 100) + 1 ?></td><td><?= (int)$l['acertos'] ?>/<?= (int)$l['total'] ?></td></tr>
  <?php endforeach; ?>
  </tbody>
</table>
<?php endif; rodape();
