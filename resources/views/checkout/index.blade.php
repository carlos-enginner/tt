<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    @vite(['resources/js/app.js'])
    <style>
        body {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            font-family: 'Roboto', sans-serif;
        }
        .checkout-container {
            margin-top: 50px;
            padding: 40px;
            background-color: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        h2 {
            color: #0d47a1;
            font-weight: 700;
        }
        h4 {
            margin-top: 20px;
            color: #424242;
        }
        .form-control {
            border-radius: 10px;
        }
        .form-control:focus {
            border-color: #0d47a1;
            box-shadow: 0 0 5px rgba(13, 71, 161, .5);
        }
        .btn-primary {
            background-color: #0d47a1;
            border-radius: 10px;
        }
        .btn-primary:hover {
            background-color: #0a369a;
        }
        .payment-details {
            border: 1px solid #0d47a1;
            border-radius: 15px;
            padding: 20px;
            margin-top: 15px;
            background-color: #f0f4f8;
        }
        .footer {
            margin-top: 20px;
        }
        .card {
            border-radius: 15px;
            border: none;
        }
        .card-title {
            color: #0d47a1;
        }
        .boleto, .qrcode {
            border-radius: 10px;
            overflow: hidden;
            background-color: #ffffff;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>
<body>

<div class="container checkout-container">
    <h2 class="text-center mb-4">Checkout</h2>

    <form id="formCheckout" action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <h4>Cliente:</h4>
        <div class="mb-3">
            <input type="text" class="form-control" id="name" name="consumer_name" value="Teste do usuário 1" placeholder="Nome do cliente" required>
        </div>

        <div class="mb-3">
            <input type="text" class="form-control" id="cpf" name="consumer_id" placeholder="Cpf/Cnpj" value="12345678909" required>
        </div>

        <h4>Compra:</h4>
        <div class="mb-3">
            <input type="text" class="form-control" id="detailsCompra" name="purchase_description" value="Um bom livro para se ler" placeholder="Descrição do item" required>
        </div>
        <div class="mb-3">
            <input type="text" class="form-control" id="valorCompra" name="purchase_value" value="10" placeholder="Valor do item" required>
        </div>

        <h4>Forma de pagamento:</h4>
        <div class="mb-3">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="pix" value="PIX" checked onclick="togglePaymentDetails()">
                <label class="form-check-label" for="pix">PIX</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="boleto" value="BOLETO" onclick="togglePaymentDetails()">
                <label class="form-check-label" for="boleto">Boleto</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="creditCard" value="CREDIT_CARD" onclick="togglePaymentDetails()">
                <label class="form-check-label" for="creditCard">Cartão de Crédito</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="payment_method" id="dinheiro" value="UNDEFINED" onclick="togglePaymentDetails()">
                <label class="form-check-label" for="dinheiro">Dinheiro (à vista)</label>
            </div>
        </div>

        <div id="creditCardDetails" class="payment-details d-none">
            <h5>Detalhes do Cartão</h5>
            <div class="mb-3">
                <label for="cardName" class="form-label">Nome do cartão</label>
                <input type="text" class="form-control" id="cardName" name="creditcard_holdername" value="Klaus Abt" required>
            </div>
            <div class="mb-3">
                <label for="cardNumber" class="form-label">Número do Cartão</label>
                <input type="text" class="form-control" id="cardNumber" name="creditcard_number" value="4513024912718006" required>
            </div>
            <div class="row">
                <div class="col">
                    <label for="expiryDate" class="form-label">Validade</label>
                    <input type="text" class="form-control" id="expiryDate" name="creditcard_validate" placeholder="MM/AA" value="12/30" required>
                </div>
                <div class="col">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="text" class="form-control" id="cvv" name="creditcard_cvv" value="183" required>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary mt-4">Comprar</button>

        <div class="container mt-5 d-none preview-compra">
            <h1 class="text-center mb-4">Dados para pagamento</h1>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title text-center">Boleto</h5>
                    <div class="boleto text-center">
                        <embed src="https://sandbox.asaas.com/b/pdf/aueda42ke38gdc4c" type="application/pdf" width="100%" height="300"/>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-body">
                    <h5 class="card-title text-center">Escaneie o QR Code</h5>
                    <div class="qrcode text-center">
                        <img src="https://www.researchgate.net/profile/Hafiza-Abas/publication/288303807/figure/fig1/AS:311239419940864@1451216668048/An-example-of-QR-code.png" alt="QR Code" width="300" height="300">
                    </div>
                </div>
            </div>

            <div class="footer text-center mt-4">
                <p class="text-muted">Se você tiver alguma dúvida, entre em contato com o suporte.</p>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function togglePaymentDetails() {
        const creditCardDetails = document.getElementById('creditCardDetails');
        creditCardDetails.classList.toggle('d-none', !document.getElementById('creditCard').checked);
    }

    // Inicializa a exibição correta na carga da página
    document.addEventListener('DOMContentLoaded', togglePaymentDetails);
</script>
</body>
</html>
