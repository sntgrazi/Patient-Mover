<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sua nova senha</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            background-color: #ffffff;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #dddddd;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #007bff;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            margin: 20px 0;
            padding: 0 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
            margin-bottom: 10px;
        }
        .password {
            background-color: #007bff;
            color: #ffffff;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .instruction {
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Sua nova senha</h1>
        </div>
        <div class="content">
            <p>Olá,</p>
            <p>Sua senha temporária é: <span class="password">{{ $senha }}</span></p>
            <p class="instruction">Ao tentar fazer o primeiro login, será solicitada a alteração da senha. Por favor, faça isso imediatamente após o primeiro acesso. Esta senha temporária só será válida uma vez.</p>
        </div>
    </div>
</body>
</html>
