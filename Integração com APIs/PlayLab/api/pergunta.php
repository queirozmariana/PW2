<?php
// Esta rota SEMPRE precisa devolver JSON puro: avisos/erros do PHP não podem vazar para a saída.
ini_set('display_errors', '0');
error_reporting(E_ALL);
set_time_limit(60);
ob_start();
register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR], true)) {
        while (ob_get_level()) ob_end_clean();
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erro' => 'Erro interno: ' . $e['message']], JSON_UNESCAPED_UNICODE);
    }
});
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../includes/perguntas.php';
header('Content-Type: application/json; charset=utf-8');

function saida(array $d, int $c = 200) { while (ob_get_level()) ob_end_clean(); http_response_code($c); echo json_encode($d, JSON_UNESCAPED_UNICODE); exit; }

if (!usuario()) saida(['erro' => 'Faça login para jogar.'], 401);
$jogo = $_GET['jogo'] ?? '';
if (!isset(JOGOS[$jogo])) saida(['erro' => 'Jogo inexistente.'], 404);

try {
    $q = gerar_pergunta($jogo);
} catch (Throwable $ex) {
    saida(['erro' => 'Não foi possível obter dados da API agora. ' . $ex->getMessage()], 502);
}
// A resposta correta fica só no servidor (sessão).
$_SESSION['q'] = ['jogo' => $jogo, 'pergunta' => $q['pergunta'], 'opcoes' => $q['opcoes'],
    'correta' => $q['correta'], 'info' => $q['info'], 'respondida' => false];
saida(['pergunta' => $q['pergunta'], 'imagem' => $q['imagem'] ?? null, 'estilo' => $q['estilo'] ?? null, 'opcoes' => $q['opcoes']]);
