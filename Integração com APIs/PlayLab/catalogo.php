<?php
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
$u = usuario();
$q = trim($_GET['q'] ?? ''); $cat = $_GET['categoria'] ?? ''; $dif = $_GET['dificuldade'] ?? '';
$soFav = isset($_GET['favoritos']) && $u;
$favs = [];
if ($u) {
    $st = db()->prepare('SELECT jogo FROM favoritos WHERE user_id = ?');
    $st->execute([$u['id']]);
    $favs = array_column($st->fetchAll(), 'jogo');
}
$lista = array_filter(JOGOS, function ($j, $slug) use ($q, $cat, $dif, $soFav, $favs) {
    if ($q !== '' && mb_stripos($j['nome'] . ' ' . $j['descricao'], $q) === false) return false;
    if ($cat !== '' && $j['categoria'] !== $cat) return false;
    if ($dif !== '' && $j['dificuldade'] !== $dif) return false;
    if ($soFav && !in_array($slug, $favs, true)) return false;
    return true;
}, ARRAY_FILTER_USE_BOTH);
$cats = array_unique(array_column(JOGOS, 'categoria'));
cabecalho('Catálogo', 'catalogo');
?>
<h1>Catálogo de jogos</h1>
<form class="filtros" method="get">
  <input type="search" name="q" placeholder="Pesquisar jogo…" value="<?= e($q) ?>">
  <select name="categoria"><option value="">Todas as categorias</option>
    <?php foreach ($cats as $c): ?><option <?= $cat === $c ? 'selected' : '' ?>><?= e($c) ?></option><?php endforeach; ?></select>
  <select name="dificuldade"><option value="">Qualquer dificuldade</option>
    <?php foreach (['Fácil', 'Médio', 'Difícil'] as $d): ?><option <?= $dif === $d ? 'selected' : '' ?>><?= $d ?></option><?php endforeach; ?></select>
  <?php if ($u): ?><label class="chk"><input type="checkbox" name="favoritos" value="1" <?= $soFav ? 'checked' : '' ?>> Só favoritos</label><?php endif; ?>
  <button class="btn pequeno">Filtrar</button>
  <a class="btn claro pequeno" href="catalogo.php">Limpar</a>
</form>

<?php if (!$lista): ?><p class="vazio">Nenhum jogo encontrado com esses filtros.</p><?php endif; ?>
<div class="grade">
<?php foreach ($lista as $slug => $j): $fav = in_array($slug, $favs, true); ?>
  <article class="cartao">
    <button class="fav <?= $fav ? 'on' : '' ?>" data-jogo="<?= e($slug) ?>" title="Favoritar" aria-label="Favoritar <?= e($j['nome']) ?>"><?= $fav ? '★' : '☆' ?></button>
    <div class="icone"><?= $j['icone'] ?></div>
    <h3><?= e($j['nome']) ?></h3>
    <p><?= e($j['descricao']) ?></p>
    <p class="meta"><?= e($j['categoria']) ?> · <?= e($j['dificuldade']) ?> · API: <?= e($j['api']) ?></p>
    <a class="btn pequeno" href="jogo.php?g=<?= e($slug) ?>">Jogar</a>
  </article>
<?php endforeach; ?>
</div>
<script>
document.querySelectorAll('.fav').forEach(b => b.addEventListener('click', async () => {
  const r = await fetch('api/favorito.php', {method: 'POST',
    headers: {'Content-Type': 'application/json', 'X-CSRF': document.querySelector('meta[name=csrf]').content},
    body: JSON.stringify({jogo: b.dataset.jogo})});
  if (r.status === 401) { location.href = 'login.php'; return; }
  const d = await r.json();
  if (d.erro) { alert(d.erro); return; }
  b.classList.toggle('on', d.favorito); b.textContent = d.favorito ? '★' : '☆';
}));
</script>
<?php rodape();
