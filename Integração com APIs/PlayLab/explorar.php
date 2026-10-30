<?php
// Consulta livre: o usuário digita um Pokémon e o PHP consulta a PokeAPI (API pública, sem chave).
require_once __DIR__ . '/includes/bootstrap.php';
require_once __DIR__ . '/includes/layout.php';
require_once __DIR__ . '/includes/http.php';
$busca = trim($_GET['pokemon'] ?? '');
$p = null; $erro = '';
if ($busca !== '') {
    $slug = strtolower(str_replace(' ', '-', $busca));
    try {
        $p = http_json('https://pokeapi.co/api/v2/pokemon/' . rawurlencode($slug), 3600);
    } catch (NaoEncontrado $x) {
        $erro = 'Nenhum Pokémon encontrado para “' . $busca . '”. Tente o nome em inglês (ex.: pikachu) ou o número.';
    } catch (Throwable $x) {
        $erro = 'Não foi possível consultar a API agora: ' . $x->getMessage();
    }
}
cabecalho('Explorar Pokémon', 'explorar');
?>
<h1>Explorar Pokémon</h1>
<p>Digite o nome (em inglês) ou o número de um Pokémon. O PHP consulta a API PokeAPI, decodifica o JSON e mostra os dados.</p>
<form class="filtros" method="get">
  <input type="search" name="pokemon" placeholder="Ex.: pikachu, charizard, 25…" value="<?= e($busca) ?>" required>
  <button class="btn pequeno">Consultar</button>
</form>
<?php if ($erro): ?><p class="alerta"><?= e($erro) ?></p><?php endif; ?>
<?php if ($p):
    $img = $p['sprites']['other']['official-artwork']['front_default'] ?? $p['sprites']['front_default'] ?? '';
    $tipos = implode(', ', array_map(fn($t) => $t['type']['name'], $p['types'] ?? []));
    $hab = implode(', ', array_map(fn($a) => $a['ability']['name'], $p['abilities'] ?? [])); ?>
<div class="grade">
  <article class="cartao">
    <?php if ($img): ?><img class="bandeira-peq" src="<?= e($img) ?>" alt="<?= e($p['name']) ?>"><?php endif; ?>
    <h3>#<?= (int)$p['id'] ?> <?= e(ucfirst($p['name'])) ?></h3>
    <ul class="dados">
      <li><b>Tipo:</b> <?= e($tipos ?: '—') ?></li>
      <li><b>Altura:</b> <?= e((string)(($p['height'] ?? 0) / 10)) ?> m</li>
      <li><b>Peso:</b> <?= e((string)(($p['weight'] ?? 0) / 10)) ?> kg</li>
      <li><b>Habilidades:</b> <?= e($hab ?: '—') ?></li>
      <?php foreach ($p['stats'] ?? [] as $s): ?>
        <li><b><?= e($s['stat']['name']) ?>:</b> <?= (int)$s['base_stat'] ?></li>
      <?php endforeach; ?>
    </ul>
  </article>
</div>
<?php endif; ?>
<?php rodape();
