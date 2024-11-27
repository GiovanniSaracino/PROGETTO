<?php
// Configurazione dell'ambiente
define('API_BASE_URL', 'http://localhost:8080/api');
define('DEBUG_MODE', true);

// Funzione per gestire gli errori in modo consistente
function handleError($message, $code = 500) {
    http_response_code($code);
    echo json_encode(['error' => $message]);
    exit;
}

// Funzione per le chiamate API
function callAPI($method, $endpoint, $data = null) {
    $url = API_BASE_URL . $endpoint;
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    
    if ($data) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($data))
        ]);
    }
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    return [
        'code' => $httpCode,
        'data' => json_decode($response, true)
    ];
}
?> 