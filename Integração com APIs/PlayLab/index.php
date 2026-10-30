<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
cabecalho('Conhecimento em jogo');
?>
<section class="hero">
  <div>
    <p class="selo">Quiz · APIs públicas · dados reais</p>
    <h1>Jogue, aprenda e suba de nível.</h1>
    <p class="lead">O PlayLab transforma APIs públicas em desafios: bandeiras, capitais, Pokémon e até a temperatura de agora em cidades do mundo.</p>
    <div class="acoes">
      <?php if (usuario()): ?>
        <a class="btn" href="catalogo.php">Escolher um jogo</a>
        <a class="btn claro" href="perfil.php">Meu progresso</a>
      <?php else: ?>
        <a class="btn" href="cadastro.php">Criar conta grátis</a>
        <a class="btn claro" href="catalogo.php">Ver os jogos</a>
      <?php endif; ?>
    </div>
  </div>
  <div class="hero-cards" aria-hidden="true">
    <span>🚩</span><span>⚡</span><span>🌡️</span><span>🏛️</span>
  </div>
</section>

<h2>Jogos em destaque</h2>
<div class="grade">
<?php foreach (array_slice(JOGOS, 0, 3, true) as $slug => $j): ?>
  <article class="cartao">
    <div class="icone"><?= $j['icone'] ?></div>
    <h3><?= e($j['nome']) ?></h3>
    <p><?= e($j['descricao']) ?></p>
    <p class="meta"><?= e($j['categoria']) ?> · <?= e($j['dificuldade']) ?></p>
    <a class="btn pequeno" href="jogo.php?g=<?= e($slug) ?>">Jogar</a>
  </article>
<?php endforeach; ?>
</div>

<h2>Como funciona</h2>
<ol class="passos">
  <li><b>Crie sua conta</b> e entre.</li>
  <li><b>Escolha um jogo</b> no catálogo: são 10 perguntas por partida.</li>
  <li><b>Acerte para ganhar XP</b>. Sequências de acertos dão bônus.</li>
  <li><b>Acompanhe</b> conquistas, histórico e ranking no seu perfil.</li>
</ol>
<?php rodape();
