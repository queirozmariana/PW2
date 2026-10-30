<?php
require_once __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

function saida(array $d, int $c = 200) { http_response_code($c); echo json_encode($d, JSON_UNESCAPED_UNICODE); exit; }

$u = usuario();
if (!$u) saida(['erro' => 'Faça login para favoritar.'], 401);
if (!csrf_ok($_SERVER['HTTP_X_CSRF'] ?? null)) saida(['erro' => 'Requisição inválida.'], 403);
$in = json_decode((string)file_get_contents('php://input'), true);
$jogo = $in['jogo'] ?? '';
if (!isset(JOGOS[$jogo])) saida(['erro' => 'Jogo inexistente.'], 404);

$st = db()->prepare('SELECT 1 FROM favoritos WHERE user_id = ? AND jogo = ?');
$st->execute([$u['id'], $jogo]);
if ($st->fetch()) {
    db()->prepare('DELETE FROM favoritos WHERE user_id = ? AND jogo = ?')->execute([$u['id'], $jogo]);
    saida(['favorito' => false]);
}
db()->prepare('INSERT INTO favoritos (user_id, jogo) VALUES (?, ?)')->execute([$u['id'], $jogo]);
saida(['favorito' => true]);
