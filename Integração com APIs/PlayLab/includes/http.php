<?php
class NaoEncontrado extends RuntimeException {}

function http_get(string $url): array {
    if (function_exists('curl_init')) {
        $ch = curl_init($url);
        curl_setopt_array($ch, [CURLOPT_RETURNTRANSFER => true, CURLOPT_TIMEOUT => 12, CURLOPT_CONNECTTIMEOUT => 6, CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_FOLLOWLOCATION => true, CURLOPT_HTTPHEADER => ['Accept: application/json'],
            CURLOPT_USERAGENT => 'PlayLab/1.0 (atividade escolar)']);
        $body = curl_exec($ch);
        // Windows/XAMPP muitas vezes não tem certificado CA configurado (erro SSL 60/77): tenta de novo sem verificar.
        if ($body === false && in_array(curl_errno($ch), [35, 51, 58, 60, 77, 83], true)) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            $body = curl_exec($ch);
        }
        if ($body === false) { throw new RuntimeException('Falha de conexão com a API: ' . curl_error($ch)); }
        $code = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        return [$code, (string)$body];
    }
    $ctx = stream_context_create(['http' => ['timeout' => 12, 'ignore_errors' => true,
        'header' => "Accept: application/json\r\nUser-Agent: PlayLab/1.0\r\n"]]);
    $body = @file_get_contents($url, false, $ctx);
    if ($body === false) throw new RuntimeException('Falha de conexão com a API.');
    $code = 200;
    if (!empty($http_response_header[0]) && preg_match('/\s(\d{3})\s/', $http_response_header[0], $m)) $code = (int)$m[1];
    return [$code, $body];
}

/** Busca JSON de uma API pública, com cache opcional em arquivo (ttl em segundos). */
function http_json(string $url, int $ttl = 0): array {
    $dir = RAIZ . '/data/cache';
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    $f = $dir . '/' . md5($url) . '.json';
    if ($ttl > 0 && is_file($f) && time() - filemtime($f) < $ttl) {
        $d = json_decode((string)file_get_contents($f), true);
        if (is_array($d)) return $d;
    }
    [$code, $body] = http_get($url);
    if ($code === 404) throw new NaoEncontrado('Nada encontrado.');
    if ($code < 200 || $code >= 300) throw new RuntimeException("A API respondeu com erro (HTTP $code).");
    $d = json_decode($body, true);
    if (!is_array($d)) throw new RuntimeException('A API devolveu uma resposta inválida.');
    if ($ttl > 0) @file_put_contents($f, $body);
    return $d;
}
