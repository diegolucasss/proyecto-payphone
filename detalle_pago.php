<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=utf-8');

// Lee y valida parámetros recibidos en la consulta (id y clientTransactionId).
$idRaw = $_GET['id'] ?? '';
$clientTransactionId = trim((string)($_GET['clientTransactionId'] ?? ''));
$id = is_numeric($idRaw) ? (int)$idRaw : 0;

// Si faltan parámetros, devuelve 400.
if ($id <= 0 || $clientTransactionId === '') {
    http_response_code(400);
    echo json_encode([
        'ok' => false,
        'error' => 'Faltan parametros id o clientTransactionId.',
    ]);
    exit;
}

$token = getenv('P¿YPHONE_TOKEN') ?: 'xrn3D5MNnxoUnTKdFQ0ypoCTpXkXdJkf00O3SYiqOZBhxEXQZPwMU0UaJgCWdP53FNNAdK18u-IsF2BlCUxtshG9dsyxOjhifHkLin8X8cpI30n5K2jL93RLuwNDppq_EdhsPtpK3Xeaz7KbAsOGjEE3rA_-7IRDzAYhjY1VGcx_Rvg33KZ6V6g73SsPB3CwDT75Kmx_KxVoPZS9EkolXVtNoc9lyesb0Awv8EGtMXjsXIAI4wt3lia390DqZ-vx8MMWZYywVmpLFIV5vLnzHIkClCWiejn9YSKog5iOo_RQACO5fNpxRggFaHSAvA3U8fkD-alNcOINO_Vj6nvrORI53y8';

// Intenta confirmar la transacción usando distintos endpoints/variantes.
$attempts = [
    [
        'url' => 'https://pay.payphonetodoesposible.com/api/button/V2/Confirm',
        'payload' => ['id' => $id, 'clientTxId' => $clientTransactionId],
    ],
    [
        'url' => 'https://pay.payphonetodoesposible.com/api/button/V2/Confirm',
        'payload' => ['id' => $id, 'clientTransactionId' => $clientTransactionId],
    ],
    [
        'url' => 'https://pay.payphonetodoesposible.com/api/button/Confirm',
        'payload' => ['id' => $id, 'clientTxId' => $clientTransactionId],
    ],
    [
        'url' => 'https://pay.payphonetodoesposible.com/api/button/Confirm',
        'payload' => ['id' => $id, 'clientTransactionId' => $clientTransactionId],
    ],
];

$errors = [];

foreach ($attempts as $attempt) {
    $ch = curl_init($attempt['url']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
    ]);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($attempt['payload']));

    if (in_array($_SERVER['SERVER_NAME'] ?? 'localhost', ['localhost', '127.0.0.1'], true)) {
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    }

    $response = curl_exec($ch);
    $curlError = curl_error($ch);
    $httpCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($response === false) {
        $errors[] = ['url' => $attempt['url'], 'error' => $curlError];
        continue;
    }

    $decoded = json_decode($response, true);
    // Si la confirmación es exitosa, devuelve el resultado al cliente.
    if ($httpCode >= 200 && $httpCode < 300 && is_array($decoded)) {
        echo json_encode([
            'ok' => true,
            'source' => $attempt['url'],
            'data' => $decoded,
        ], JSON_UNESCAPED_SLASHES);
        exit;
    }

    $errors[] = [
        'url' => $attempt['url'],
        'httpCode' => $httpCode,
        'response' => $response,
    ];
}

http_response_code(502);
echo json_encode([
    'ok' => false,
    'error' => 'No se pudo confirmar la transaccion con PayPhone.',
    'attempts' => $errors,
], JSON_UNESCAPED_SLASHES);
