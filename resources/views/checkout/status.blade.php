<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status da Compra</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    @vite(['resources/js/app.js'])
    <style>
               body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .status-container {
            text-align: center;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 400px;
        }
        .status-container h1 {
            margin-bottom: 20px;
        }
        .loading {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .dot {
            height: 15px;
            width: 15px;
            margin: 0 5px;
            border-radius: 50%;
            background-color: #3498db;
            animation: loading 0.6s infinite alternate;
        }
        .dot:nth-child(2) {
            animation-delay: 0.2s;
        }
        .dot:nth-child(3) {
            animation-delay: 0.4s;
        }
        @keyframes loading {
            0% {
                transform: scale(1);
            }
            100% {
                transform: scale(1.5);
            }
        }
        .icon {
            font-size: 50px;
            margin-bottom: 20px;
        }
        .success {
            color: #4CAF50;
        }
        .failure {
            color: #f44336;
        }
        .back-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s;
        }
        .back-button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<div class="status-container" id="status-container">
        <div class="loading">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
        <h1>Aguardando Processamento...</h1>
        <p>Estamos processando sua compra. Por favor, aguarde.</p>
    </div>
</body>
</html>
