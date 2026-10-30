<?php
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
define('RAIZ', dirname(__DIR__));
require_once __DIR__ . '/jogos.php';

function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;
    if (!is_dir(RAIZ . '/data')) mkdir(RAIZ . '/data', 0775, true);
    $pdo = new PDO('sqlite:' . RAIZ . '/data/playlab.sqlite');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS usuarios (
            id INTEGER PRIMARY KEY AUTOINCREMENT, nome TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE, senha TEXT NOT NULL,
            criado TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP);
        CREATE TABLE IF NOT EXISTS respostas (
            id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER NOT NULL, jogo TEXT NOT NULL,
            pergunta TEXT NOT NULL, resposta TEXT NOT NULL, correta TEXT NOT NULL,
            acertou INTEGER NOT NULL, pontos INTEGER NOT NULL,
            criado TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP);
        CREATE TABLE IF NOT EXISTS favoritos (
            user_id INTEGER NOT NULL, jogo TEXT NOT NULL, PRIMARY KEY (user_id, jogo));
    ");
    return $pdo;
}

function e(?string $s): string { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function usuario(): ?array {
    static $u = false;
    if ($u !== false) return $u;
    if (empty($_SESSION['uid'])) return $u = null;
    $st = db()->prepare('SELECT id, nome, email, criado FROM usuarios WHERE id = ?');
    $st->execute([$_SESSION['uid']]);
    return $u = ($st->fetch() ?: null);
}

function exigir_login(): array {
    $u = usuario();
    if (!$u) {
        $_SESSION['voltar'] = basename($_SERVER['REQUEST_URI']);
        header('Location: login.php');
        exit;
    }
    return $u;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_ok(?string $t): bool {
    return $t !== null && !empty($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $t);
}

function stats(int $uid): array {
    $st = db()->prepare('SELECT COALESCE(SUM(pontos),0) xp, COUNT(*) total, COALESCE(SUM(acertou),0) acertos,
        COUNT(DISTINCT jogo) jogos FROM respostas WHERE user_id = ?');
    $st->execute([$uid]);
    $s = array_map('intval', $st->fetch());
    $s['nivel'] = intdiv($s['xp'], 100) + 1;
    $s['progresso'] = $s['xp'] % 100;
    $s['taxa'] = $s['total'] ? (int)round($s['acertos'] / $s['total'] * 100) : 0;
    return $s;
}

function conquistas(array $s): array {
    return [
        ['🌱', 'Primeiros passos', 'Responda a sua primeira pergunta', $s['total'] >= 1],
        ['🎯', 'Mira afiada', 'Acumule 10 acertos', $s['acertos'] >= 10],
        ['🔥', 'Em chamas', 'Acumule 50 acertos', $s['acertos'] >= 50],
        ['🏆', 'Lenda', 'Acumule 100 acertos', $s['acertos'] >= 100],
        ['🧭', 'Explorador', 'Jogue todos os jogos do catálogo', $s['jogos'] >= count(JOGOS)],
        ['⭐', 'Nível 5', 'Alcance o nível 5', $s['nivel'] >= 5],
    ];
}
