<?php
require_once __DIR__ . '/http.php';

/** Base local de países (a versão 3.1 da REST Countries foi desativada). Bandeiras vêm do CDN público flagcdn.com. */
function paises(): array {
    $d = json_decode((string)@file_get_contents(RAIZ . '/data/paises.json'), true);
    if (!is_array($d) || count($d) < 10) throw new RuntimeException('Arquivo data/paises.json não encontrado ou inválido.');
    $out = [];
    foreach ($d as $c) {
        $out[] = ['nome' => $c['nome'], 'bandeira' => 'https://flagcdn.com/w320/' . $c['iso'] . '.png',
            'capital' => $c['capital'], 'pop' => (int)$c['pop'], 'area' => (float)$c['area']];
    }
    return $out;
}

/** Sorteia $n itens com valores distintos de $chave. */
function sortear(array $pool, string $chave, int $n = 4): array {
    $pool = array_values($pool);
    shuffle($pool);
    $r = []; $vistos = [];
    foreach ($pool as $p) {
        if (isset($vistos[$p[$chave]])) continue;
        $vistos[$p[$chave]] = true; $r[] = $p;
        if (count($r) === $n) break;
    }
    return $r;
}

function fmt(float $n): string { return number_format($n, 0, ',', '.'); }

function gerar_pergunta(string $jogo): array {
    switch ($jogo) {
        case 'bandeiras':
            $op = sortear(array_filter(paises(), fn($p) => $p['bandeira'] !== ''), 'nome');
            $i = random_int(0, 3); $p = $op[$i];
            return ['pergunta' => 'De qual país é esta bandeira?', 'imagem' => $p['bandeira'], 'estilo' => 'bandeira',
                'opcoes' => array_column($op, 'nome'), 'correta' => $i,
                'info' => $p['nome'] . ($p['capital'] ? ' — capital: ' . $p['capital'] : '') . ' — população: ' . fmt($p['pop'])];
        case 'capitais':
            $op = sortear(array_filter(paises(), fn($p) => $p['capital']), 'capital');
            $i = random_int(0, 3); $p = $op[$i];
            return ['pergunta' => 'Qual é a capital de ' . $p['nome'] . '?', 'opcoes' => array_column($op, 'capital'),
                'correta' => $i, 'info' => $p['capital'] . ' é a capital de ' . $p['nome'] . '.'];
        case 'duelo':
            $pool = array_filter(paises(), fn($p) => $p['pop'] > 0 && $p['area'] > 0);
            $par = sortear($pool, 'nome', 2);
            $campo = random_int(0, 1) ? 'pop' : 'area';
            if ($par[0][$campo] === $par[1][$campo]) return gerar_pergunta($jogo);
            $i = $par[0][$campo] > $par[1][$campo] ? 0 : 1;
            $un = $campo === 'pop' ? 'habitantes' : 'km²';
            return ['pergunta' => $campo === 'pop' ? 'Qual destes países tem MAIOR população?' : 'Qual destes países tem MAIOR área?',
                'opcoes' => [$par[0]['nome'], $par[1]['nome']], 'correta' => $i,
                'info' => $par[0]['nome'] . ': ' . fmt($par[0][$campo]) . " $un · " . $par[1]['nome'] . ': ' . fmt($par[1][$campo]) . " $un"];
        case 'pokemon':
            $lista = http_json('https://pokeapi.co/api/v2/pokemon?limit=386', 86400)['results'] ?? [];
            if (count($lista) < 4) throw new RuntimeException('Lista de Pokémon indisponível.');
            $ids = array_rand($lista, 4); shuffle($ids);
            $i = random_int(0, 3); $id = $ids[$i] + 1;
            $d = http_json("https://pokeapi.co/api/v2/pokemon/$id", 86400);
            $img = $d['sprites']['other']['official-artwork']['front_default'] ?? $d['sprites']['front_default'] ?? '';
            $tipos = implode(', ', array_map(fn($t) => $t['type']['name'], $d['types'] ?? []));
            return ['pergunta' => 'Quem é esse Pokémon?', 'imagem' => $img, 'estilo' => 'silhueta',
                'opcoes' => array_map(fn($k) => ucfirst($lista[$k]['name']), $ids), 'correta' => $i,
                'info' => ucfirst($d['name']) . ' — tipo: ' . $tipos . ' — altura: ' . ($d['height'] / 10) . ' m'];
        case 'clima':
            $cidades = [['São Paulo', -23.55, -46.63], ['Rio de Janeiro', -22.91, -43.17], ['Manaus', -3.12, -60.02],
                ['Porto Alegre', -30.03, -51.23], ['Brasília', -15.79, -47.88], ['Reykjavik', 64.15, -21.94],
                ['Cairo', 30.04, 31.24], ['Tóquio', 35.68, 139.69], ['Nova York', 40.71, -74.01],
                ['Moscou', 55.76, 37.62], ['Sydney', -33.87, 151.21], ['Dubai', 25.20, 55.27],
                ['Cidade do México', 19.43, -99.13], ['Londres', 51.51, -0.13], ['Nairóbi', -1.29, 36.82]];
            shuffle($cidades); [$a, $b] = [$cidades[0], $cidades[1]];
            $url = "https://api.open-meteo.com/v1/forecast?latitude={$a[1]},{$b[1]}&longitude={$a[2]},{$b[2]}&current=temperature_2m";
            $d = http_json($url, 600);
            $ta = $d[0]['current']['temperature_2m'] ?? null; $tb = $d[1]['current']['temperature_2m'] ?? null;
            if ($ta === null || $tb === null) throw new RuntimeException('Dados de clima indisponíveis.');
            if ($ta == $tb) return gerar_pergunta($jogo);
            return ['pergunta' => 'Qual cidade está mais QUENTE agora?', 'opcoes' => [$a[0], $b[0]],
                'correta' => $ta > $tb ? 0 : 1, 'info' => "{$a[0]}: {$ta} °C · {$b[0]}: {$tb} °C (Open-Meteo)"];
    }
    throw new InvalidArgumentException('Jogo inexistente.');
}
