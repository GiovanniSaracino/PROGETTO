<?php
require_once('../config.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = callAPI('GET', '/utenti/all');
    http_response_code($response['code']);
    echo json_encode($response['data']);
}
?> 