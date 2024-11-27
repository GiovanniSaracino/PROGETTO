<?php
require_once('../config.php');

// Headers per API e CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type');

// Funzione per normalizzare i dati dei clienti
function normalizeCliente($cliente) {
    return [
        'id' => $cliente['id'] ?? 0,
        'nome' => $cliente['nome'] ?? 'Nome non disponibile',
        'email' => $cliente['email'] ?? 'N/D',
        'partitaIva' => $cliente['partitaIva'] ?? 'N/D',
        'codiceFiscale' => $cliente['codiceFiscale'] ?? 'N/D',
        'indirizzo' => $cliente['indirizzo'] ?? 'N/D',
        'telefono' => $cliente['telefono'] ?? 'N/D'
    ];
}

// Gestione delle richieste
switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        // Simula chiamata API backend
        $response = [
            'code' => 200,
            'data' => [
                [
                    'id' => null,
                    'nome' => 'cliente',
                    'email' => 'cliente@cliente.it',
                    'partitaIva' => '12345678901',
                    'codiceFiscale' => 'Asnr87h3n48d9is',
                    'indirizzo' => 'via cliente c',
                    'telefono' => '12823746184'
                ]
            ]
        ];

        if ($response['code'] !== 200) {
            http_response_code($response['code']);
            echo json_encode(['error' => 'Errore nel recupero dei clienti']);
            exit;
        }

        // Normalizza i dati dei clienti
        $clientiNormalizzati = array_map('normalizeCliente', $response['data']);
        echo json_encode($clientiNormalizzati);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Metodo non permesso']);
        break;
}
?>
