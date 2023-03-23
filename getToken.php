<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Token padrão pré-atendimento correios
$token = 'YW5kcm9pZDtici5jb20uY29ycmVpb3MucHJlYXRlbmRpbWVudG87RjMyRTI5OTc2NzA5MzU5ODU5RTBCOTdGNkY4QTQ4M0I5Qjk1MzU3OA==';

// Data que é enviada no request
$data = array(
    'requestToken' => $token
);

// Fields do request em array
$options = array(
    CURLOPT_URL => 'https://proxyapp.correios.com.br/v1/app-validation',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'User-Agent: Dart/2.18 (dart:io)',
    ),
);

$ch = curl_init();
curl_setopt_array($ch, $options);
$response = curl_exec($ch);
curl_close($ch);

// Parseia o JSON
$jsonResponse = json_decode($response, true);

// Verifica se a resposta do json tem a key "token"
if (!isset($jsonResponse['token'])) {
    echo 'Falha ao obter token';
}

//echo $jsonResponse['token'];

$code = $_GET['code']; // via get ex: getToken.php?code=AA123456789BR
$token = $jsonResponse['token'];

// Abaixo não funciona, pois o URL a ser chamado com o token é muito longo.
// Você pode retornar este token para o front end e usar ele como parametro para fazer o request de trackear o codigo. 
// echo file_get_contents('track.php?code='.$code.'&token='.$token);
// abaixo uma alternativa que te redireciona para outro URL com o token inserido.
header("Location: track.php?code=".$code."&token=".$token);
die();
