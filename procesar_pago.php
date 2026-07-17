<?php
declare(strict_types=1);

$token = getenv('PAYPHONE_TOKEN') ?: 'xrn3D5MNnxoUnTKdFQ0ypoCTpXkXdJkf00O3SYiqOZBhxEXQZPwMU0UaJgCWdP53FNNAdK18u-IsF2BlCUxtshG9dsyxOjhifHkLin8X8cpI30n5K2jL93RLuwNDppq_EdhsPtpK3Xeaz7KbAsOGjEE3rA_-7IRDzAYhjY1VGcx_Rvg33KZ6V6g73SsPB3CwDT75Kmx_KxVoPZS9EkolXVtNoc9lyesb0Awv8EGtMXjsXIAI4wt3lia390DqZ-vx8MMWZYywVmpLFIV5vLnzHIkClCWiejn9YSKog5iOo_RQACO5fNpxRggFaHSAvA3U8fkD-alNcOINO_Vj6nvrORI53y8';
// Token de API usado para autenticación con PayPhone.

// Verifica que la petición sea POST (formulario de checkout).
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Metodo no permitido.';
    exit;
}

$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
$product = isset($_POST['product']) ? preg_replace('/[^a-z0-9_-]/i', '', (string)$_POST['product']) : 'producto';

// Valida monto recibido desde el formulario.
if ($amount <= 0) {
    http_response_code(400);
    echo 'Monto invalido.';
    exit;
}

$url = 'https://pay.payphonetodoesposible.com/api/button/Prepare';

// Construye la carga para la llamada "Prepare" del API de PayPhone.
$data = [
    'amount' => $amount,
    'amountWithoutTax' => $amount,
    'currency' => 'USD',
    'clientTransactionId' => strtoupper($product) . '_' . time() . '_' . bin2hex(random_bytes(4)),
    'responseUrl' => 'http://localhost:8000/respuestas'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$token}",
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// En algunos entornos locales de Windows el bundle CA puede fallar.
// Se desactiva verificacion SSL solo para pruebas en localhost.
if (in_array($_SERVER['SERVER_NAME'] ?? 'localhost', ['localhost', '127.0.0.1'], true)) {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
}

$response = curl_exec($ch);
// Ejecuta la petición al endpoint de preparación del pago.

if ($response === false) {
    $error = curl_error($ch);
    curl_close($ch);
    http_response_code(500);
    echo 'Error de cURL: ' . htmlspecialchars($error);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

// Si la API devuelve éxito, redirige al usuario a la URL de checkout.
if ($httpCode >= 200 && $httpCode < 300 && is_array($result)) {
    // Segun version del API, la URL puede venir como payWithCard, payWithPayPhone o payButton.
    $checkoutUrl = $result['payWithCard'] ?? $result['payWithPayPhone'] ?? $result['payButton'] ?? null;

    if (is_string($checkoutUrl) && $checkoutUrl !== '') {
        header('Location: ' . $checkoutUrl);
        exit;
    }
}

http_response_code($httpCode > 0 ? $httpCode : 500);
echo '<h1>Error al preparar el pago</h1>';
echo '<p>Codigo HTTP: ' . htmlspecialchars((string)$httpCode) . '</p>';
echo '<pre>' . htmlspecialchars((string)$response) . '</pre>';
?>