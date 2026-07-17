<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Respuesta de PayPhone</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: #f4f7ff;
            color: #162043;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 24px;
        }

        .card {
            background: #fff;
            border-radius: 14px;
            box-shadow: 0 10px 25px rgba(24, 42, 92, 0.12);
            padding: 22px;
        }

        h1 {
            margin: 0 0 8px;
        }

        .status {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 999px;
            margin: 8px 0 14px;
            font-weight: 700;
        }

        .ok { background: #e8faef; color: #0b8f4e; }
        .warn { background: #fff4e5; color: #9a6200; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 10px;
            border-bottom: 1px solid #edf0f7;
        }

        td:first-child {
            color: #5d6888;
            width: 220px;
        }

        a {
            display: inline-block;
            margin-top: 14px;
            text-decoration: none;
            color: #0f58c9;
            font-weight: 600;
        }

        .msg {
            color: #5d6888;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <section class="card">
            <h1>Respuesta de PayPhone</h1>
            <p>Datos de la transaccion confirmados con PayPhone.</p>

            <span id="estadoBadge" class="status warn">Pago no confirmado</span>

            <table>
                <tr><td>Estado</td><td id="estado">N/A</td></tr>
                <tr><td>ID de transaccion</td><td id="transactionId">N/A</td></tr>
                <tr><td>Client transaction ID</td><td id="clientTransactionId">N/A</td></tr>
                <tr><td>Monto (USD)</td><td id="amount">N/A</td></tr>
                <tr><td>Codigo de autorizacion</td><td id="authorizationCode">N/A</td></tr>
                <tr><td>Email</td><td id="email">N/A</td></tr>
                <tr><td>Tipo de tarjeta</td><td id="cardType">N/A</td></tr>
                <tr><td>Marca de tarjeta</td><td id="cardBrand">N/A</td></tr>
            </table>

            <p id="apiMsg" class="msg"></p>

            <a href="/index.php">Volver al catalogo</a>
        </section>
    </div>

    <script>
        const params = new URLSearchParams(window.location.search);
        const urlData = Object.fromEntries(params.entries());

        const getField = (obj, keys, fallback = 'N/A') => {
            for (const key of keys) {
                if (obj[key] !== undefined && obj[key] !== null && String(obj[key]).trim() !== '') {
                    return obj[key];
                }
            }
            return fallback;
        };

        const formatAmount = (raw) => {
            const asNumber = Number(raw);
            if (!Number.isFinite(asNumber)) {
                return 'N/A';
            }
            return '$' + (asNumber / 100).toFixed(2);
        };

        const render = (data, msg) => {
            const status = getField(data, ['transactionStatus', 'status', 'Status', 'state'], 'desconocido');
            const transactionId = getField(data, ['transactionId', 'TransactionId', 'id']);
            const clientTransactionId = getField(data, ['clientTransactionId', 'ClientTransactionId', 'clientTxId']);
            const amount = getField(data, ['amount', 'Amount']);
            const authorizationCode = getField(data, ['authorizationCode', 'AuthorizationCode', 'cardAuthorizationCode']);
            const email = getField(data, ['email', 'mail']);
            const cardType = getField(data, ['cardType']);
            const cardBrand = getField(data, ['cardBrand', 'cardBrandCode']);

            document.getElementById('estado').textContent = String(status);
            document.getElementById('transactionId').textContent = String(transactionId);
            document.getElementById('clientTransactionId').textContent = String(clientTransactionId);
            document.getElementById('amount').textContent = formatAmount(amount);
            document.getElementById('authorizationCode').textContent = String(authorizationCode);
            document.getElementById('email').textContent = String(email);
            document.getElementById('cardType').textContent = String(cardType);
            document.getElementById('cardBrand').textContent = String(cardBrand);

            const isOk = /success|approved|paid|pagado/i.test(String(status));
            const badge = document.getElementById('estadoBadge');
            badge.classList.remove('ok', 'warn');
            badge.classList.add(isOk ? 'ok' : 'warn');
            badge.textContent = isOk ? 'Pago aprobado' : 'Pago no confirmado';

            document.getElementById('apiMsg').textContent = msg;
        };

        render(urlData, 'Mostrando datos recibidos en el retorno.');

        const id = urlData.id || urlData.transactionId;
        const clientTransactionId = urlData.clientTransactionId;

        // Limpia la barra para que quede solo /respuestas.
        if (window.location.pathname !== '/respuestas') {
            history.replaceState({}, '', '/respuestas');
        } else {
            history.replaceState({}, '', '/respuestas');
        }

        if (id && clientTransactionId) {
            fetch(`/detalle_pago.php?id=${encodeURIComponent(id)}&clientTransactionId=${encodeURIComponent(clientTransactionId)}`)
                .then(async (res) => {
                    const payload = await res.json();
                    if (payload.ok && payload.data) {
                        const merged = { ...urlData, ...payload.data };
                        render(merged, 'Datos completos confirmados con API de PayPhone.');
                        return;
                    }
                    render(urlData, 'No se pudo obtener detalle completo desde API.');
                })
                .catch(() => {
                    render(urlData, 'No se pudo conectar al endpoint de confirmacion.');
                });
        }
    </script>
</body>
</html>
