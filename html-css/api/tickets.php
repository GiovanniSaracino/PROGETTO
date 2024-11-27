<?php
require_once('../config.php');

header('Content-Type: application/json');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $clienteId = $_GET['clienteId'] ?? null;
        $endpoint = $clienteId ? "/api/ticket?clienteId=$clienteId" : '/api/ticket/all';
        
        $response = callAPI('GET', $endpoint);
        http_response_code($response['code']);
        echo json_encode($response['data']);
        break;

    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        
        if (!isset($data['clienteId']) || !isset($data['descrizione'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Dati mancanti']);
            exit;
        }

        $response = callAPI('POST', '/api/ticket', $data);
        http_response_code($response['code']);
        echo json_encode($response['data']);
        break;

    case 'PUT':
        $data = json_decode(file_get_contents('php://input'), true);
        $ticketId = $data['id'] ?? null;
        
        if (!$ticketId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID ticket mancante']);
            exit;
        }

        $response = callAPI('PUT', "/api/ticket/$ticketId", [
            'stato' => $data['stato'],
            'note' => $data['note'] ?? ''
        ]);

        http_response_code($response['code']);
        echo json_encode($response['data']);
        break;

    case 'DELETE':
        $ticketId = $_GET['id'] ?? null;
        
        if (!$ticketId) {
            http_response_code(400);
            echo json_encode(['error' => 'ID ticket mancante']);
            exit;
        }

        $response = callAPI('DELETE', "/api/ticket/$ticketId");
        http_response_code($response['code']);
        echo json_encode($response['data']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Metodo non permesso']);
        break;
}
?>