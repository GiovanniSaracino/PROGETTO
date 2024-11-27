<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $username = $data['username'] ?? '';
    $password = $data['password'] ?? '';
    
    // URL del microservizio Spring Boot
    $url = 'http://localhost:8080/api/utenti/login';
    
    // Preparazione dei dati per la chiamata
    $postData = json_encode([
        'username' => $username,
        'password' => $password
    ]);
    
    // Inizializzazione cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Content-Length: ' . strlen($postData)
    ]);
    
    // Esecuzione della chiamata
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    if ($httpCode === 200) {
        $userData = json_decode($response, true);
        
        // Verifica esplicita del ruolo amministratore
        if (isset($userData['amministratore'])) {
            // Aggiungiamo un campo più esplicito per il ruolo
            $userData['isAdmin'] = $userData['amministratore'] === true;
            $response = json_encode($userData);
        }
    }
    
    curl_close($ch);
    
    // Inoltro della risposta al client
    http_response_code($httpCode);
    echo $response;
}
?> 