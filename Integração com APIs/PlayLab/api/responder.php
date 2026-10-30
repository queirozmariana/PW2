<?php
require_once __DIR__ . '/../includes/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');

function saida(array $d, int $c = 200) { http_response_code($c); echo json_encode($d, JSON_UNESCAPED_UNICODE); exit; }

$u = usuario();
if (!$u) saida(['erro' => 'Sessão expirada. Entre novamente.'], 401);
if (!csrf_ok($_SERVER['HTTP_X_CSRF'] ?? null)) saida(['erro' => 'Requisição inválida.'], 403);
$q = $_SESSION['q'] ?? null;
if (!$q || $q['respondida']) saida(['erro' => 'Não há pergunta ativa.'], 409);

$in = json_decode((string)file_get_contents('php://input'), true);
$i = $in['indice'] ?? null;
if (!is_int($i) || !isset($q['opcoes'][$i])) saida(['erro' => 'Resposta inválida.'], 400);

$acertou = $i === $q['correta'];
$streak = (int)($_SESSION['streak'] ?? 0);
$pontos = $acertou ? 10 + min($streak, 5) * 2 : 0;   // base 10 + bônus de sequência (máx. +10)
$_SESSION['streak'] = $acertou ? $streak + 1 : 0;
$_SESSION['q']['respondida'] = true;

db()->prepare('INSERT INTO respostas (user_id, jogo, pergunta, resposta, correta, acertou, pontos) VALUES (?,?,?,?,?,?,?)')
    ->execute([$u['id'], $q['jogo'], $q['pergunta'], $q['opcoes'][$i], $q['opcoes'][$q['correta']], (int)$acertou, $pontos]);

$s = stats((int)$u['id']);
saida(['acertou' => $acertou, 'indiceCorreto' => $q['correta'], 'pontos' => $pontos, 'sequencia' => $_SESSION['streak'],
    'info' => $q['info'], 'xp' => $s['xp'], 'nivel' => $s['nivel']]);
