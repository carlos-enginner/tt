<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview do compra</title>
    <!-- Link do Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css'])
    @vite(['resources/js/app.js'])
    <style>
        .boleto img {
            max-width: 100%;
            border-radius: 8px;
        }
        .qrcode img {
            max-width: 200px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Dados para pagamento</h1>

    @if ($paymentMethod === 'BOLETO')
    <div class="card">
        <div class="card-body">
            <h5 class="card-title text-center">Boleto</h5>
            <div class="boleto text-center">
                <embed src="https://sandbox.asaas.com/b/pdf/aueda42ke38gdc4c" type="application/pdf" width="100%" height="300"/>
            </div>
        </div>
    </div>
    @else
    <div class="card mt-4">
        <div class="card-body">
            <h5 class="card-title text-center">Escaneie o QR Code</h5>
            <div class="qrcode text-center">
                <img src="https://www.researchgate.net/profile/Hafiza-Abas/publication/288303807/figure/fig1/AS:311239419940864@1451216668048/An-example-of-QR-code.png" alt="QR Code">
            </div>
        </div>
    </div>
    @endif

    <div class="footer text-center mt-4">
        <p class="text-muted">Se você tiver alguma dúvida, entre em contato com o suporte.</p>
    </div>

    <div class="container text-center" style="margin-top: 50px;">
        <button type="submit" class="btn btn-primary mt-4">Confirmar pagamento</button>
    </div>
</div>

<!-- Script do Bootstrap JS e dependências -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
