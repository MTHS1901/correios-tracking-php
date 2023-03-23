<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$code = $_GET['code']; // codigo que veio via GET no URL
$url = 'https://proxyapp.correios.com.br/v1/sro-rastro/'.$code;
$token = $_GET['token']; // token que veio via GET no URL

$headers = array(
  'content-type: application/json',
  'user-agent: Dart/2.18 (dart:io)',
  'app-check-token: ' . $token,
);

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Execute the request and store the response
$response = curl_exec($ch);

// Check if cURL request was successful
if(curl_errno($ch)) {
  $error_msg = curl_error($ch);
  curl_close($ch);
  die("cURL error: $error_msg");
}

// Close the cURL session
curl_close($ch);

// Parse the JSON response
$data = json_decode($response, true);

// Check if the 'objetos' key exists in the response
if(isset($data['objetos'])) {
  $objetos = $data['objetos'];
  // Return the first object in the array
  return $objetos[0];
} else {
  // If the 'objetos' key is not present, return an error
  throw new Exception('Invalid response from API: missing "objetos" key');
}
